<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DichVu;
use App\Models\HangThanhVien;
use App\Models\QuangCao;
use App\Models\User;
use App\Models\DatLich;
use Carbon\Carbon;

class ChatController extends Controller
{
    // Cấu hình API
    private $apiUrl = 'https://api.chatanywhere.org';
    
    /**
     * Hiển thị giao diện chat
     */
    public function show()
    {
        return view('chat.form');
    }

    /**
     * Xử lý tin nhắn và gửi câu trả lời
     */
    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $userMessage = trim($request->message);

        try {
            $isLoggedIn = auth()->check();
            $user = $isLoggedIn ? User::where('MaTK', auth()->user()->MaTK)->first() : null;

            // Chuẩn bị system prompt và context
            $systemPrompt = $this->getSystemPrompt($isLoggedIn, $user);
            
            // Lấy tin nhắn cũ từ session
            $chatHistory = session()->get('chat_history', []);
            
            $reply = '';
            $aiResponse = true; // Mặc định sử dụng AI trả lời
            
            // Kiểm tra xem có thể là yêu cầu đặt lịch không
            $isBookingRequest = $this->isBookingRequest($userMessage);
            
            // Kiểm tra xem có phải là yêu cầu xác nhận đặt lịch không
            $isConfirmationRequest = $this->isConfirmationRequest($userMessage);
            
            // Lấy thông tin đặt lịch từ session nếu có
            $partialBookingInfo = session()->get('partial_booking_info');
            
            // Kiểm tra tin nhắn trước đó có liên quan đến đặt lịch không
            $lastMessage = end($chatHistory);
            $hasPreviousBookingContext = false;
            $isWaitingForConfirmation = false;
            
            if ($lastMessage) {
                $hasPreviousBookingContext = (
                    strpos($lastMessage['reply'], 'xác nhận') !== false || 
                    strpos($lastMessage['reply'], 'đặt lịch') !== false ||
                    strpos($lastMessage['reply'], 'lịch hẹn') !== false ||
                    strpos($lastMessage['reply'], 'ngày') !== false && strpos($lastMessage['reply'], 'giờ') !== false
                );
                
                // Kiểm tra xem có đang chờ xác nhận không - đơn giản hóa cách kiểm tra
                $isWaitingForConfirmation = (
                    strpos($lastMessage['reply'], 'XÁC NHẬN') !== false ||
                    strpos($lastMessage['reply'], 'xác nhận') !== false ||
                    strpos($lastMessage['reply'], 'THÔNG TIN ĐẶT LỊCH') !== false
                );
                
                Log::info('Kiểm tra trạng thái xác nhận', [
                    'lastReply' => mb_substr($lastMessage['reply'], 0, 50) . '...',
                    'isWaitingForConfirmation' => $isWaitingForConfirmation,
                    'hasPreviousBookingContext' => $hasPreviousBookingContext
                ]);
            }
            
            Log::info('Xử lý tin nhắn mới', [
                'message' => $userMessage,
                'isBookingRequest' => $isBookingRequest,
                'isConfirmationRequest' => $isConfirmationRequest,
                'hasPreviousBookingContext' => $hasPreviousBookingContext,
                'isWaitingForConfirmation' => $isWaitingForConfirmation,
                'hasPartialBookingInfo' => !empty($partialBookingInfo),
            ]);
            
            // Nếu người dùng đang xác nhận sau khi xem thông tin đặt lịch
            if ($isConfirmationRequest && $isWaitingForConfirmation && $partialBookingInfo) {
                Log::info('Người dùng xác nhận đặt lịch sau khi xem thông tin', [
                    'booking_info' => [
                        'service' => $partialBookingInfo['service'] ? $partialBookingInfo['service']->Tendichvu : 'Không có',
                        'date' => $partialBookingInfo['date'] ?? 'Không có',
                        'time' => $partialBookingInfo['time'] ?? 'Không có'
                    ]
                ]);
                
                try {
                    // Người dùng đã xác nhận, tiến hành đặt lịch
                    $bookingResult = $this->autoCreateBooking($partialBookingInfo, $user);
                    
                    if ($bookingResult['success']) {
                        $reply = $this->generateBookingSuccessResponse($bookingResult, $partialBookingInfo, $user);
                        $aiResponse = false;
                        
                        // Lưu thông tin đặt lịch thành công và xóa thông tin tạm
                        session()->forget('partial_booking_info');
                        session()->put('last_successful_booking', [
                            'booking_id' => $bookingResult['booking_id'],
                            'service' => $partialBookingInfo['service']->Tendichvu,
                            'date' => $partialBookingInfo['date'],
                            'time' => $partialBookingInfo['time'],
                            'timestamp' => now()->timestamp
                        ]);
                        
                        Log::info('Đặt lịch thành công', [
                            'booking_id' => $bookingResult['booking_id'],
                        ]);
                    } else {
                        $reply = $this->generateBookingFailureResponse($bookingResult, $partialBookingInfo);
                        $aiResponse = false;
                        
                        Log::warning('Đặt lịch thất bại', [
                            'reason' => $bookingResult['message'],
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi nghiêm trọng khi đặt lịch', [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    $reply = "Xin lỗi, đã xảy ra lỗi khi đặt lịch: " . $e->getMessage() . ". Vui lòng thử lại sau hoặc đặt lịch qua hotline (028) 1234 5678.";
                    $aiResponse = false;
                }
            }
            // Nếu người dùng xác nhận đặt lịch nhưng chưa có đủ thông tin
            else if ($isConfirmationRequest && $hasPreviousBookingContext) {
                Log::info('Người dùng muốn xác nhận nhưng có thể chưa đủ thông tin');
                
                // Nếu đã có đủ thông tin một phần, hiển thị để xác nhận
                if ($partialBookingInfo && $this->hasMinimumBookingInfo($partialBookingInfo)) {
                    $reply = $this->generateBookingConfirmation($partialBookingInfo, $user);
                    $aiResponse = false;
                    
                    Log::info('Hiển thị thông tin xác nhận đặt lịch');
                } else {
                    // Chưa có đủ thông tin, yêu cầu cung cấp
                    $reply = $this->generateBookingInformationRequest();
                    $aiResponse = false;
                    
                    Log::info('Yêu cầu thêm thông tin đặt lịch');
                }
            } 
            else if ($isBookingRequest) {
                Log::info('Phát hiện yêu cầu đặt lịch');
                
                // Trích xuất thông tin đặt lịch từ tin nhắn
                $bookingInfo = $this->extractBookingInfo($userMessage);
                
                // Thử bổ sung thông tin đặt lịch từ lịch sử chat
                if (!empty($chatHistory)) {
                    $bookingInfo = $this->enhanceBookingInfoFromHistory($bookingInfo, $chatHistory);
                }
                
                // Bổ sung thông tin từ đặt lịch một phần nếu có
                if ($partialBookingInfo) {
                    foreach ($partialBookingInfo as $key => $value) {
                        if (empty($bookingInfo[$key]) && !empty($value)) {
                            $bookingInfo[$key] = $value;
                        }
                    }
                    
                    Log::info('Đã bổ sung thông tin từ đặt lịch một phần');
                }
                
                // Kiểm tra thông tin đặt lịch
                $missingInfo = $this->checkMissingBookingInfo($bookingInfo, $isLoggedIn);
                
                // Nếu đã đủ thông tin, hiển thị xác nhận trước khi đặt lịch
                if (empty($missingInfo) && $this->canAutoBook($bookingInfo, $isLoggedIn)) {
                    Log::info('Đã đủ thông tin, hiển thị xác nhận trước khi đặt lịch');
                    
                    $reply = $this->generateBookingConfirmation($bookingInfo, $user);
                    $aiResponse = false;
                    
                    // Lưu thông tin để sử dụng khi xác nhận
                    session()->put('partial_booking_info', $bookingInfo);
                } else {
                    // Chưa đủ thông tin, tiếp tục hỏi
                    Log::info('Chưa đủ thông tin đặt lịch, còn thiếu: ' . implode(', ', $missingInfo));
                    
                    $bookingContext = $this->prepareBookingContext($bookingInfo, $missingInfo, $isLoggedIn, $user);
                    $reply = $this->generateReplyWithOpenAI($userMessage, $systemPrompt, $bookingContext);
                    
                    // Lưu thông tin đặt lịch không đầy đủ
                    session()->put('partial_booking_info', $bookingInfo);
                }
            } else {
                // Kiểm tra nếu tin nhắn liên quan đến đặt lịch trước đó
                $lastSuccessfulBooking = session()->get('last_successful_booking');
                
                if ($lastSuccessfulBooking && 
                    now()->timestamp - $lastSuccessfulBooking['timestamp'] < 300 && // Trong vòng 5 phút
                    $this->isRelatedToLastBooking($userMessage, $lastSuccessfulBooking)) {
                    
                    Log::info('Tin nhắn liên quan đến lịch hẹn đã đặt trước đó');
                    
                    // Cung cấp thông tin bổ sung về lịch hẹn đã đặt
                    $additionalContext = $this->prepareFollowUpBookingContext($lastSuccessfulBooking);
                    $reply = $this->generateReplyWithOpenAI($userMessage, $systemPrompt, $additionalContext);
                } 
                elseif ($partialBookingInfo && $this->canEnhancePartialBooking($userMessage, $partialBookingInfo)) {
                    Log::info('Có thể bổ sung thông tin đặt lịch từ tin nhắn mới');
                    
                    // Cập nhật thông tin đặt lịch từ tin nhắn mới
                    $enhancedBookingInfo = $this->enhancePartialBookingInfo($userMessage, $partialBookingInfo);
                    session()->put('partial_booking_info', $enhancedBookingInfo);
                    
                    // Kiểm tra lại xem đã đủ thông tin chưa
                    $missingInfo = $this->checkMissingBookingInfo($enhancedBookingInfo, $isLoggedIn);
                    
                    if (empty($missingInfo) && $this->canAutoBook($enhancedBookingInfo, $isLoggedIn)) {
                        Log::info('Đã đủ thông tin sau khi cập nhật, hiển thị xác nhận');
                        
                        // Đủ thông tin, hiển thị xác nhận trước khi đặt lịch
                        $reply = $this->generateBookingConfirmation($enhancedBookingInfo, $user);
                        $aiResponse = false;
                    } else {
                        Log::info('Vẫn thiếu thông tin sau khi cập nhật: ' . implode(', ', $missingInfo));
                        
                        // Vẫn còn thiếu thông tin, tiếp tục hỏi
                        $bookingContext = $this->prepareBookingContext($enhancedBookingInfo, $missingInfo, $isLoggedIn, $user);
                        $reply = $this->generateReplyWithOpenAI($userMessage, $systemPrompt, $bookingContext);
                    }
                } else {
                    Log::info('Tin nhắn thông thường, sử dụng AI trả lời');
                    
                    // Tạo câu trả lời bằng OpenAI cho các yêu cầu thông thường
                    $reply = $this->generateReplyWithOpenAI($userMessage, $systemPrompt);
                }
            }

            // Thêm tin nhắn mới vào cuối mảng
            $chatHistory[] = [
                'user' => $userMessage,
                'reply' => $reply,
                'time' => now()->format('H:i')
            ];
            
            // Giới hạn lịch sử chat để không quá lớn (giữ 10 tin nhắn gần nhất)
            if (count($chatHistory) > 10) {
                $chatHistory = array_slice($chatHistory, -10);
            }
            
            // Lưu lại vào session
            session()->put('chat_history', $chatHistory);
            
            return response()->json([
                'success' => true,
                'reply' => $reply,
                'time' => now()->format('H:i')
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing message', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $reply = "Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ Rosa Spa qua (028) 1234 5678.";
            
            return response()->json([
                'success' => false,
                'reply' => $reply,
                'time' => now()->format('H:i')
            ]);
        }
    }

    /**
     * Tạo system prompt cho AI
     */
    private function getSystemPrompt($isLoggedIn, $user = null)
    {
        $bookUrl = route('customer.datlich.create');
        $baseUrl = config('app.url');
        
        $systemPrompt = <<<EOT
Bạn là trợ lý AI của Rosa Spa, chuyên hỗ trợ khách hàng đặt lịch dịch vụ. 
Tên của bạn là "Rosa Assistant" và bạn phải trả lời thân thiện, lịch sự và chuyên nghiệp.

TẬP TRUNG VÀO CHỨC NĂNG ĐẶT LỊCH:
1. Hỗ trợ khách hàng tìm hiểu quy trình đặt lịch
2. Giới thiệu các dịch vụ có thể đặt lịch
3. Hướng dẫn đặt lịch online hoặc qua hotline
4. Hỗ trợ đặt lịch trực tiếp qua chat khi khách hàng cung cấp đủ thông tin
5. Giúp tra cứu thông tin lịch đã đặt (nếu khách đã đăng nhập)
6. Giải đáp các câu hỏi liên quan đến đặt lịch

ĐẶT LỊCH TỰ ĐỘNG QUA CHAT:
Bạn có khả năng giúp khách hàng đặt lịch trực tiếp qua chat. Khi khách hàng muốn đặt lịch, hãy:
- Hỏi tên dịch vụ khách hàng muốn đặt
- Hỏi ngày và giờ muốn đặt lịch
- Thu thập thông tin cá nhân (nếu là khách vãng lai)
- Xác nhận lại thông tin trước khi đặt lịch
- Tôi sẽ tự động xử lý yêu cầu đặt lịch khi đủ thông tin

BASE URL API: {$baseUrl}
URL ĐẶT LỊCH: {$bookUrl}

QUY TRÌNH ĐẶT LỊCH:
1. Chọn dịch vụ mong muốn
2. Chọn ngày đặt lịch (trong vòng 30 ngày tới)
3. Chọn giờ (giờ hoạt động từ 8:00 - 17:30)
4. Cung cấp thông tin cá nhân nếu là khách vãng lai
5. Nhận xác nhận đặt lịch

THÔNG TIN QUAN TRỌNG:
- Mỗi khung giờ chỉ nhận tối đa 2 lịch đặt
- Dịch vụ có thời gian khác nhau từ 30 phút đến 2 giờ
- Đặt lịch trước ít nhất 1 giờ
- Hotline đặt lịch: (028) 1234 5678
- Địa chỉ: 123 Nguyễn Văn Linh, Q.7, TP.HCM

TRẠNG THÁI KHÁCH HÀNG:
EOT;
        
        // Bổ sung thông tin về trạng thái đăng nhập
        if ($isLoggedIn && $user) {
            $systemPrompt .= <<<EOT

Khách hàng đã đăng nhập với thông tin:
- Họ tên: {$user->Hoten}
- Số điện thoại: {$user->SDT}
- Email: {$user->Email}

Khách hàng là khách quen và có thể truy cập các chức năng:
- Xem lịch sử đặt lịch: {$baseUrl}/customer/lichsudatlich
- Xem điểm tích lũy: {$baseUrl}/customer/diemthuong
- Đặt lịch mà không cần nhập lại thông tin cá nhân
EOT;
        } else {
            $systemPrompt .= <<<EOT

Khách hàng chưa đăng nhập và được xem là khách vãng lai.
Khách vãng lai cần nhập đầy đủ thông tin cá nhân khi đặt lịch:
- Họ tên
- Số điện thoại

Ưu điểm khi đăng nhập/đăng ký tài khoản:
- Lưu lịch sử đặt lịch
- Tích điểm thành viên
- Nhận ưu đãi đặc biệt
- Không cần nhập lại thông tin cá nhân mỗi lần đặt lịch
EOT;
        }

        // Thêm thông tin về dịch vụ
        $services = $this->getServiceData();
        $systemPrompt .= <<<EOT

DANH SÁCH DỊCH VỤ:
{$services}

HƯỚNG DẪN PHẢN HỒI:
- Tích cực hỗ trợ khách hàng đặt lịch trực tiếp qua chat
- Khi khách hàng muốn đặt lịch, chủ động hỏi để lấy đủ thông tin cần thiết
- Luôn xác nhận lại thông tin đặt lịch trước khi hoàn tất
- Nếu có thông tin còn thiếu, hãy hỏi khách hàng để hoàn thiện
- Đối với thông tin không rõ ràng, hãy đề xuất các lựa chọn phù hợp
- Nếu thời gian đặt lịch không khả dụng, đề xuất các khung giờ thay thế
EOT;

        return $systemPrompt;
    }

    /**
     * Gọi API OpenAI để tạo phản hồi
     */
    private function generateReplyWithOpenAI($userMessage, $systemPrompt, $bookingContext = null)
    {
        // Sử dụng API key trực tiếp thay vì lấy từ config
        $apiKey = 'sk-ocIpQZGleBhWCnzmFJSESm7mOL6eGK0bgmEQggF8NOCrcmyW'; // Thay thế bằng API key thật của bạn
        
        try {
            // Nếu có thông tin đặt lịch, thêm vào messages
            $messages = [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ];
            
            // Thêm ngữ cảnh đặt lịch nếu có
            if ($bookingContext) {
                array_splice($messages, 1, 0, [
                    [
                        'role' => 'system',
                        'content' => $bookingContext,
                    ],
                ]);
            }
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.chatanywhere.org/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => $messages,
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (isset($result['choices'][0]['message']['content'])) {
                    return $result['choices'][0]['message']['content'];
                }
            }
            
            Log::error("ChatAnywhere API Error", [
                'status' => $response->status(),
                'response' => $response->json()
            ]);
            
            return "Xin lỗi, tôi không thể tạo câu trả lời lúc này. Vui lòng gọi đến hotline (028) 1234 5678 để được hỗ trợ đặt lịch.";
        } catch (\Exception $e) {
            Log::error("ChatAnywhere API Exception", [
                'message' => $e->getMessage()
            ]);
            
            return "Xin lỗi, tôi đang gặp sự cố kết nối. Vui lòng thử lại sau hoặc liên hệ hotline (028) 1234 5678.";
        }
    }
    
    /**
     * Kiểm tra xem tin nhắn có phải là yêu cầu đặt lịch không
     */
    private function isBookingRequest($message)
    {
        $message = mb_strtolower($message, 'UTF-8');
        
        // Danh sách từ khóa chính xác biểu thị ý định đặt lịch
        $exactPatterns = [
            'đặt lịch',
            'book',
            'đặt hẹn',
            'lịch hẹn',
            'đặt chỗ',
            'reserve',
            'reservation',
            'appointment',
        ];
        
        // Danh sách từ khóa mạnh biểu thị ý định đặt lịch
        $strongPatterns = [
            'muốn đặt',
            'cần đặt',
            'đặt dịch vụ',
            'muốn đặt lịch',
            'cần đặt lịch',
            'làm lịch',
            'hẹn lịch',
            'xin đặt',
        ];
        
        // Danh sách từ khóa yếu biểu thị có thể là ý định đặt lịch khi kết hợp
        $weakPatterns = [
            'lịch',
            'book',
            'hẹn',
            'đặt',
            'dịch vụ',
            'ngày',
            'giờ',
        ];
        
        // Kiểm tra các từ khóa chính xác
        foreach ($exactPatterns as $pattern) {
            if (mb_strpos($message, $pattern) !== false) {
                return true;
            }
        }
        
        // Kiểm tra các từ khóa mạnh
        foreach ($strongPatterns as $pattern) {
            if (mb_strpos($message, $pattern) !== false) {
                return true;
            }
        }
        
        // Kiểm tra các từ khóa yếu (cần ít nhất 2 từ khóa yếu để xác định)
        $weakMatches = 0;
        foreach ($weakPatterns as $pattern) {
            if (mb_strpos($message, $pattern) !== false) {
                $weakMatches++;
            }
        }
        
        // Nếu có từ 2 từ khóa yếu trở lên
        if ($weakMatches >= 2) {
            return true;
        }
        
        // Kiểm tra nếu tin nhắn chứa tên dịch vụ cụ thể và từ "muốn"
        $services = DichVu::all();
        foreach ($services as $service) {
            $serviceName = mb_strtolower($service->Tendichvu, 'UTF-8');
            if (mb_strpos($message, $serviceName) !== false && 
                (mb_strpos($message, 'muốn') !== false || mb_strpos($message, 'đặt') !== false)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Kiểm tra thông tin đặt lịch còn thiếu
     */
    private function checkMissingBookingInfo($bookingInfo, $isLoggedIn)
    {
        $missingInfo = [];
        
        if (empty($bookingInfo['service'])) {
            $missingInfo[] = 'dịch vụ';
        }
        
        if (empty($bookingInfo['date'])) {
            $missingInfo[] = 'ngày đặt lịch';
        }
        
        if (empty($bookingInfo['time'])) {
            $missingInfo[] = 'giờ đặt lịch';
        }
        
        // Nếu là khách vãng lai, kiểm tra thông tin cá nhân
        if (!$isLoggedIn) {
            if (empty($bookingInfo['name'])) {
                $missingInfo[] = 'họ tên';
            }
            
            if (empty($bookingInfo['phone'])) {
                $missingInfo[] = 'số điện thoại';
            }
        }
        
        // Log thông tin kiểm tra
        Log::info('Kiểm tra thông tin đặt lịch còn thiếu', [
            'isLoggedIn' => $isLoggedIn,
            'service' => !empty($bookingInfo['service']),
            'date' => !empty($bookingInfo['date']),
            'time' => !empty($bookingInfo['time']),
            'name' => !empty($bookingInfo['name']),
            'phone' => !empty($bookingInfo['phone']),
            'missingInfo' => $missingInfo
        ]);
        
        return $missingInfo;
    }
    
    /**
     * Kiểm tra xem có thể tự động đặt lịch không
     */
    private function canAutoBook($bookingInfo, $isLoggedIn)
    {
        $canAutoBook = $bookingInfo['service'] && $bookingInfo['date'] && $bookingInfo['time'];
        
        // Nếu là khách vãng lai, cần thêm tên và số điện thoại
        if (!$isLoggedIn) {
            $canAutoBook = $canAutoBook && $bookingInfo['name'] && $bookingInfo['phone'];
        }
        
        return $canAutoBook;
    }
    
    /**
     * Chuẩn bị ngữ cảnh đặt lịch cho AI
     */
    private function prepareBookingContext($bookingInfo, $missingInfo, $isLoggedIn, $user = null)
    {
        $context = "THÔNG TIN ĐẶT LỊCH ĐÃ TRÍCH XUẤT:\n";
        
        // Thông tin dịch vụ
        if ($bookingInfo['service']) {
            $context .= "- Dịch vụ: {$bookingInfo['service']->Tendichvu}\n";
            $context .= "- Giá: " . number_format($bookingInfo['service']->Gia, 0, ',', '.') . " VND\n";
            $context .= "- Thời gian dịch vụ: {$bookingInfo['service']->Thoigian} phút\n";
        } else {
            $context .= "- Dịch vụ: Chưa xác định\n";
        }
        
        // Thông tin ngày, giờ
        if ($bookingInfo['date']) {
            $formattedDate = date('d/m/Y', strtotime($bookingInfo['date']));
            $context .= "- Ngày: {$formattedDate}\n";
        } else {
            $context .= "- Ngày: Chưa xác định\n";
        }
        
        if ($bookingInfo['time']) {
            $context .= "- Giờ: {$bookingInfo['time']}\n";
        } else {
            $context .= "- Giờ: Chưa xác định\n";
        }
        
        // Thông tin khách hàng
        if ($isLoggedIn && $user) {
            $context .= "\nTHÔNG TIN KHÁCH HÀNG (ĐÃ ĐĂNG NHẬP):\n";
            $context .= "- Họ tên: {$user->Hoten}\n";
            $context .= "- Số điện thoại: {$user->SDT}\n";
        } else {
            $context .= "\nTHÔNG TIN KHÁCH HÀNG (VÃNG LAI):\n";
            if ($bookingInfo['name']) {
                $context .= "- Họ tên: {$bookingInfo['name']}\n";
            } else {
                $context .= "- Họ tên: Chưa cung cấp\n";
            }
            
            if ($bookingInfo['phone']) {
                $context .= "- Số điện thoại: {$bookingInfo['phone']}\n";
            } else {
                $context .= "- Số điện thoại: Chưa cung cấp\n";
            }
        }
        
        // Thông tin còn thiếu
        if (!empty($missingInfo)) {
            $context .= "\nTHÔNG TIN CẦN HỎI THÊM: " . implode(", ", $missingInfo) . "\n";
            $context .= "Hãy hỏi người dùng để lấy đủ những thông tin còn thiếu.\n";
        }
        
        // Kiểm tra tính khả dụng của thời gian nếu đã có đủ thông tin
        if ($bookingInfo['service'] && $bookingInfo['date'] && $bookingInfo['time']) {
            $availabilityInfo = $this->checkTimeAvailability(
                $bookingInfo['service']->MaDV,
                $bookingInfo['date'],
                $bookingInfo['time']
            );
            
            if ($availabilityInfo['available']) {
                $context .= "\nKhung giờ này còn trống và có thể đặt lịch.\n";
            } else {
                $context .= "\nKhung giờ này {$availabilityInfo['message']}.\n";
                
                // Đề xuất các khung giờ thay thế
                if (!empty($availabilityInfo['alternative_times'])) {
                    $context .= "\nCÁC KHUNG GIỜ THAY THẾ CÓ THỂ ĐẶT:\n";
                    foreach ($availabilityInfo['alternative_times'] as $time) {
                        $context .= "- {$time}\n";
                    }
                    $context .= "Hãy đề xuất những khung giờ thay thế này cho người dùng.\n";
                }
            }
        }
        
        $context .= "\nHƯỚNG DẪN TRẢ LỜI:\n";
        if (!empty($missingInfo)) {
            $context .= "- Hỏi thêm thông tin còn thiếu để có thể đặt lịch\n";
            $context .= "- Hỏi từng thông tin một cách rõ ràng và lịch sự\n";
        } else {
            $context .= "- Xác nhận lại thông tin đặt lịch với người dùng\n";
            $context .= "- Đề xuất người dùng xác nhận nếu muốn đặt lịch ngay\n";
        }
        
        return $context;
    }
    
    /**
     * Tạo phản hồi khi đặt lịch thành công
     */
    private function generateBookingSuccessResponse($bookingResult, $bookingInfo, $user = null)
    {
        $formattedDate = date('d/m/Y', strtotime($bookingInfo['date']));
        $serviceName = $bookingInfo['service']->Tendichvu;
        $serviceTime = $bookingInfo['service']->Thoigian;
        $servicePrice = number_format($bookingInfo['service']->Gia, 0, ',', '.') . ' VND';
        
        // Tính thời gian kết thúc dự kiến
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $bookingInfo['time']);
        $endTime = (clone $startTime)->addMinutes($serviceTime);
        
        $response = "✅ ĐẶT LỊCH THÀNH CÔNG!\n\n";
        $response .= "📋 THÔNG TIN LỊCH HẸN\n";
        $response .= "- Mã đặt lịch: {$bookingResult['booking_id']}\n";
        $response .= "- Dịch vụ: {$serviceName}\n";
        $response .= "- Giá dịch vụ: {$servicePrice}\n";
        $response .= "- Thời gian dịch vụ: " . $this->formatDuration($serviceTime) . "\n";
        $response .= "- Ngày: {$formattedDate}\n";
        $response .= "- Giờ bắt đầu: {$bookingInfo['time']}\n";
        $response .= "- Giờ kết thúc (dự kiến): " . $endTime->format('H:i') . "\n";
        $response .= "- Trạng thái: Chờ xác nhận\n\n";
        
        // Thông tin người đặt
        $response .= "👤 THÔNG TIN NGƯỜI ĐẶT\n";
        if ($user) {
            $response .= "- Họ tên: {$user->Hoten}\n";
            $response .= "- Số điện thoại: {$user->SDT}\n\n";
        } else {
            $response .= "- Họ tên: {$bookingInfo['name']}\n";
            $response .= "- Số điện thoại: {$bookingInfo['phone']}\n\n";
        }
        
        // Thông tin cho người dùng đã đăng nhập
        if ($user) {
            $viewUrl = route('customer.lichsudatlich.show', $bookingResult['booking_id']);
            $response .= "🔍 QUẢN LÝ LỊCH HẸN\n";
            $response .= "Bạn có thể xem chi tiết hoặc thay đổi lịch hẹn tại: {$viewUrl}\n\n";
            
            // Thông tin điểm thưởng
            $response .= "💎 ĐIỂM THƯỞNG\n";
            $response .= "Khi hoàn thành dịch vụ và thanh toán, bạn sẽ nhận được khoảng " . 
                      floor($bookingInfo['service']->Gia / 10000) . " điểm thưởng.\n\n";
        }
        
        // Thông tin địa điểm và liên hệ
        $response .= "📍 ĐỊA ĐIỂM VÀ LIÊN HỆ\n";
        $response .= "- Địa chỉ: 123 Nguyễn Văn Linh, Q.7, TP.HCM\n";
        $response .= "- Điện thoại: (028) 1234 5678\n";
        $response .= "- Email: contact@rosa-spa.com\n\n";
        
        // Hướng dẫn
        $response .= "🔔 LƯU Ý\n"; 
        $response .= "- Vui lòng đến trước giờ hẹn 15 phút để làm thủ tục\n";
        $response .= "- Mang theo giấy tờ tùy thân có ảnh\n";
        $response .= "- Nếu cần hủy lịch, vui lòng báo trước ít nhất 2 giờ\n";
        $response .= "- Nhân viên sẽ liên hệ xác nhận lịch hẹn trong thời gian sớm nhất\n\n";
        
        $response .= "Cảm ơn bạn đã sử dụng dịch vụ của Rosa Spa!";
        
        return $response;
    }
    
    /**
     * Format thời gian dịch vụ
     */
    private function formatDuration($minutes) {
        if ($minutes < 60) {
            return "$minutes phút";
        } else {
            $hours = floor($minutes / 60);
            $mins = $minutes % 60;
            
            if ($mins == 0) {
                return "$hours giờ";
            } else {
                return "$hours giờ $mins phút";
            }
        }
    }
    
    /**
     * Tạo phản hồi khi đặt lịch thất bại
     */
    private function generateBookingFailureResponse($bookingResult, $bookingInfo)
    {
        $formattedDate = $bookingInfo['date'] ? date('d/m/Y', strtotime($bookingInfo['date'])) : "Không xác định";
        $serviceName = $bookingInfo['service'] ? $bookingInfo['service']->Tendichvu : "Không xác định";
        
        $response = "❌ KHÔNG THỂ ĐẶT LỊCH\n\n";
        
        $response .= "Thông tin đặt lịch của bạn:\n";
        $response .= "- Dịch vụ: {$serviceName}\n";
        $response .= "- Ngày: {$formattedDate}\n";
        $response .= "- Giờ: " . ($bookingInfo['time'] ? $bookingInfo['time'] : 'Không xác định') . "\n\n";
        
        // Hiển thị lý do lỗi
        $response .= "⚠️ Lý do không đặt được lịch: \n{$bookingResult['message']}\n\n";
        
        // Nếu có các khung giờ thay thế, hiển thị chúng
        if (isset($bookingResult['alternative_times']) && !empty($bookingResult['alternative_times'])) {
            $response .= "🕒 CÁC KHUNG GIỜ KHÁC CÓ THỂ ĐẶT VÀO NGÀY {$formattedDate}:\n";
            foreach ($bookingResult['alternative_times'] as $time) {
                $response .= "- {$time}\n";
            }
            $response .= "\nBạn có thể chọn một trong những khung giờ trên.\n\n";
        }
        
        // Đề xuất các giải pháp dựa trên loại lỗi
        $response .= "💡 ĐỀ XUẤT:\n";
        
        if (strpos($bookingResult['message'], 'đã đạt giới hạn') !== false || 
            strpos($bookingResult['message'], 'không khả dụng') !== false) {
            $response .= "1. Chọn một khung giờ khác trong danh sách đề xuất\n";
            $response .= "2. Thử đặt lịch vào ngày khác\n";
            
            // Đề xuất các ngày gần đó
            $currentDate = \Carbon\Carbon::parse($bookingInfo['date']);
            $response .= "3. Bạn có thể thử các ngày khác như:\n";
            for ($i = 1; $i <= 3; $i++) {
                $nextDay = $currentDate->copy()->addDays($i);
                $response .= "   - " . $nextDay->format('d/m/Y') . " (" . $this->getDayOfWeekInVietnamese($nextDay) . ")\n";
            }
        } elseif (strpos($bookingResult['message'], 'đã qua') !== false) {
            $response .= "1. Đặt lịch với thời gian trong tương lai (ít nhất 1 giờ kể từ bây giờ)\n";
            $response .= "2. Đặt lịch với giờ bắt đầu sau " . date('H:i', strtotime('+1 hour')) . " hôm nay\n";
        } elseif (strpos($bookingResult['message'], 'thiếu thông tin') !== false) {
            $response .= "1. Vui lòng cung cấp đầy đủ thông tin cá nhân (họ tên, số điện thoại)\n";
            $response .= "2. Đăng nhập để sử dụng thông tin đã lưu trong tài khoản\n";
        } else {
            // Đề xuất chung
            $response .= "1. Thử lại sau vài phút\n";
            $response .= "2. Đặt lịch thủ công qua trang đặt lịch\n";
            $response .= "3. Liên hệ trực tiếp với nhân viên tư vấn\n";
        }
        $response .= "\n";
        
        // Đường link đặt lịch thủ công
        $bookingParams = [];
        if ($bookingInfo['service']) {
            $bookingParams['service_id'] = $bookingInfo['service']->MaDV;
        }
        if ($bookingInfo['date']) {
            $bookingParams['date'] = $bookingInfo['date'];
        }
        if ($bookingInfo['time']) {
            $bookingParams['booking_time'] = $bookingInfo['time'];
        }
        
        $bookUrl = route('customer.datlich.create', $bookingParams);
        $response .= "🔗 ĐẶT LỊCH THỦ CÔNG: {$bookUrl}\n\n";
        $response .= "📞 HOTLINE HỖ TRỢ: (028) 1234 5678\n";
        $response .= "🕙 Giờ làm việc: 8:00 - 17:30, từ thứ 2 đến chủ nhật";
        
        return $response;
    }
    
    /**
     * Lấy tên thứ trong tuần tiếng Việt
     */
    private function getDayOfWeekInVietnamese(\Carbon\Carbon $date) {
        $dayOfWeek = $date->dayOfWeek;
        $days = [
            0 => 'Chủ Nhật',
            1 => 'Thứ Hai',
            2 => 'Thứ Ba',
            3 => 'Thứ Tư',
            4 => 'Thứ Năm',
            5 => 'Thứ Sáu',
            6 => 'Thứ Bảy',
        ];
        return $days[$dayOfWeek];
    }

    /**
     * Lấy thông tin dịch vụ từ database
     */
    private function getServiceData()
    {
        try {
            $services = DichVu::all();
            if ($services->isEmpty()) {
                return "Không tìm thấy dịch vụ trong hệ thống.";
            }

            $serviceText = "";
            foreach ($services as $service) {
                $duration = $service->Thoigian;
                $durationText = $duration < 60 ? "$duration phút" : (($duration == 60) ? "1 giờ" : (int)($duration/60) . " giờ " . ($duration % 60 > 0 ? ($duration % 60) . " phút" : ""));
                
                $price = number_format($service->Gia, 0, ',', '.') . ' VND';
                $serviceText .= "- {$service->Tendichvu}: {$price} (Thời gian: {$durationText})\n";
            }
            
            return $serviceText;
        } catch (\Exception $e) {
            Log::error("Error getting service data", [
                'message' => $e->getMessage()
            ]);
            
            return "Không thể lấy thông tin dịch vụ lúc này.";
        }
    }

    /**
     * Trích xuất thông tin đặt lịch từ tin nhắn
     */
    private function extractBookingInfo($message)
    {
        Log::info('Bắt đầu trích xuất thông tin đặt lịch từ: ' . $message);
        
        $result = [
            'service' => null,
            'date' => null,
            'time' => null,
            'name' => null,
            'phone' => null
        ];
        
        // Trích xuất tên dịch vụ bằng fuzzy matching
        $allServices = DichVu::all();
        $bestMatch = null;
        $highestScore = 0;
        
        foreach ($allServices as $service) {
            $serviceName = mb_strtolower($service->Tendichvu, 'UTF-8');
            $messageLower = mb_strtolower($message, 'UTF-8');
            
            // Nếu tìm thấy chính xác tên dịch vụ
            if (mb_strpos($messageLower, $serviceName) !== false) {
                $result['service'] = $service;
                Log::info('Tìm thấy dịch vụ chính xác: ' . $service->Tendichvu);
                break;
            }
            
            // Tìm theo từng từ trong tên dịch vụ (cho các dịch vụ có nhiều từ)
            $serviceWords = explode(' ', $serviceName);
            $foundWords = 0;
            foreach ($serviceWords as $word) {
                if (mb_strlen($word) >= 3 && mb_strpos($messageLower, $word) !== false) {
                    $foundWords++;
                }
            }
            
            $score = $foundWords / count($serviceWords);
            if ($score > 0.6 && $score > $highestScore) {
                $highestScore = $score;
                $bestMatch = $service;
                Log::info('Tìm thấy dịch vụ gần đúng: ' . $service->Tendichvu . ' (điểm: ' . $score . ')');
            }
        }
        
        if ($result['service'] === null && $bestMatch !== null) {
            $result['service'] = $bestMatch;
            Log::info('Sử dụng dịch vụ gần đúng nhất: ' . $bestMatch->Tendichvu);
        }
        
        // Trích xuất ngày với nhiều định dạng phổ biến
        // 1. Định dạng dd/mm/yyyy hoặc dd-mm-yyyy hoặc dd.mm.yyyy
        if (preg_match('/(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})/', $message, $dateMatches)) {
            $day = str_pad($dateMatches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($dateMatches[2], 2, '0', STR_PAD_LEFT);
            $year = $dateMatches[3];
            $result['date'] = "{$year}-{$month}-{$day}";
            Log::info('Trích xuất ngày theo định dạng dd/mm/yyyy: ' . $result['date']);
        }
        // 2. Định dạng "ngày dd tháng mm"
        elseif (preg_match('/ngày\s+(\d{1,2})\s+tháng\s+(\d{1,2})(?:\s+năm\s+(\d{4}))?/', $message, $dateMatches)) {
            $day = str_pad($dateMatches[1], 2, '0', STR_PAD_LEFT);
            $month = str_pad($dateMatches[2], 2, '0', STR_PAD_LEFT);
            $year = isset($dateMatches[3]) ? $dateMatches[3] : date('Y');
            $result['date'] = "{$year}-{$month}-{$day}";
            Log::info('Trích xuất ngày theo định dạng "ngày dd tháng mm": ' . $result['date']);
        }
        // 3. Từ khóa tương đối
        elseif (preg_match('/(hôm nay|today|ngày hôm nay)/', mb_strtolower($message, 'UTF-8'))) {
            $result['date'] = date('Y-m-d');
            Log::info('Trích xuất ngày là hôm nay: ' . $result['date']);
        }
        elseif (preg_match('/(ngày mai|tomorrow|mai|next day)/', mb_strtolower($message, 'UTF-8'))) {
            $result['date'] = date('Y-m-d', strtotime('+1 day'));
            Log::info('Trích xuất ngày là ngày mai: ' . $result['date']);
        }
        elseif (preg_match('/(ngày kia|day after tomorrow|kia|ngày mốt|mốt)/', mb_strtolower($message, 'UTF-8'))) {
            $result['date'] = date('Y-m-d', strtotime('+2 days'));
            Log::info('Trích xuất ngày là ngày kia: ' . $result['date']);
        }
        elseif (preg_match('/(thứ|chủ nhật|monday|tuesday|wednesday|thursday|friday|saturday|sunday)/', mb_strtolower($message, 'UTF-8'))) {
            // Xử lý theo tên thứ trong tuần
            $weekdayMap = [
                'chủ nhật' => 'Sunday',
                'cn' => 'Sunday',
                'thứ 2' => 'Monday',
                'thứ hai' => 'Monday',
                'monday' => 'Monday',
                'thứ 3' => 'Tuesday',
                'thứ ba' => 'Tuesday',
                'tuesday' => 'Tuesday',
                'thứ 4' => 'Wednesday',
                'thứ tư' => 'Wednesday',
                'wednesday' => 'Wednesday',
                'thứ 5' => 'Thursday',
                'thứ năm' => 'Thursday',
                'thursday' => 'Thursday',
                'thứ 6' => 'Friday',
                'thứ sáu' => 'Friday',
                'friday' => 'Friday',
                'thứ 7' => 'Saturday',
                'thứ bảy' => 'Saturday',
                'saturday' => 'Saturday',
                'sunday' => 'Sunday'
            ];
            
            $messageLower = mb_strtolower($message, 'UTF-8');
            foreach ($weekdayMap as $vietnamese => $english) {
                if (mb_strpos($messageLower, $vietnamese) !== false) {
                    $today = strtolower(date('l'));
                    if ($today == strtolower($english)) {
                        $result['date'] = date('Y-m-d');
                    } else {
                        $date = new \DateTime();
                        $date->modify('next ' . $english);
                        $result['date'] = $date->format('Y-m-d');
                    }
                    Log::info('Trích xuất ngày theo tên thứ: ' . $vietnamese . ' => ' . $result['date']);
                    break;
                }
            }
        }
        
        // Trích xuất giờ (nhiều định dạng)
        // 1. Định dạng HH:MM hoặc HHhMM
        if (preg_match('/(\d{1,2})[h\:](\d{2})/', $message, $timeMatches)) {
            $hour = str_pad($timeMatches[1], 2, '0', STR_PAD_LEFT);
            $minute = $timeMatches[2];
            $result['time'] = "{$hour}:{$minute}";
            Log::info('Trích xuất giờ theo định dạng HH:MM hoặc HHhMM: ' . $result['time']);
        }
        // 2. Định dạng chữ "X giờ" hoặc "X h"
        elseif (preg_match('/(\d{1,2})\s*(giờ|h|hour)(?:\s*(?:rưỡi|ruoi|rưởi|ruởi|30))?/', mb_strtolower($message, 'UTF-8'), $hourMatches)) {
            $hour = str_pad($hourMatches[1], 2, '0', STR_PAD_LEFT);
            
            // Kiểm tra xem có "rưỡi" (30 phút) không
            if (preg_match('/(rưỡi|ruoi|rưởi|ruởi|30)/', mb_strtolower($message, 'UTF-8'))) {
                $result['time'] = "{$hour}:30";
                Log::info('Trích xuất giờ theo định dạng "X giờ rưỡi": ' . $result['time']);
            } else {
                $result['time'] = "{$hour}:00";
                Log::info('Trích xuất giờ theo định dạng "X giờ": ' . $result['time']);
            }
        }
        // 3. Xử lý đặc biệt cho buổi sáng/chiều/tối
        elseif (!$result['time']) {
            // Sáng: 8-11h, Trưa: 11-13h, Chiều: 13-17h, Tối: 17-20h
            if (preg_match('/(buổi sáng|sáng|buoi sang|morning)/', mb_strtolower($message, 'UTF-8'))) {
                $result['time'] = '09:00';
                Log::info('Trích xuất giờ theo buổi sáng: ' . $result['time']);
            } elseif (preg_match('/(buổi trưa|trưa|buoi trua|noon)/', mb_strtolower($message, 'UTF-8'))) {
                $result['time'] = '12:00';
                Log::info('Trích xuất giờ theo buổi trưa: ' . $result['time']);
            } elseif (preg_match('/(buổi chiều|chiều|buoi chieu|afternoon)/', mb_strtolower($message, 'UTF-8'))) {
                $result['time'] = '15:00';
                Log::info('Trích xuất giờ theo buổi chiều: ' . $result['time']);
            } elseif (preg_match('/(buổi tối|tối|buoi toi|evening)/', mb_strtolower($message, 'UTF-8'))) {
                $result['time'] = '18:00';
                Log::info('Trích xuất giờ theo buổi tối: ' . $result['time']);
            }
        }
        
        // Trích xuất tên (nhiều mẫu câu)
        $namePatterns = [
            '/(tên|tên tôi là|tôi là|tên của tôi|toi la|tên tôi|tôi tên)[:]?\s+([^\d.,]+?)(?=\s*\d|\s*[.,]|$)/',
            '/[tên|là]\s+([^\d.,]+?)(?:\s+\d|\s*[.,]|$)/',
            '/(?:là|người tên)[:]?\s+([^\d.,]+?)(?:\s+\d|\s*[.,]|$)/',
            '/(?:tôi|mình|t)[:]?\s+([^\d.,]+?)(?=\s*\d|\s*[.,]|$)/', // Thêm các mẫu mới
            '/(?:của|toi|minh)[:]?\s+([^\d.,]+?)(?=\s*\d|\s*[.,]|$)/', // Thêm các mẫu mới
        ];
        
        foreach ($namePatterns as $pattern) {
            if (preg_match($pattern, mb_strtolower($message, 'UTF-8'), $nameMatches) && !$result['name']) {
                $result['name'] = trim($nameMatches[count($nameMatches) - 1]);
                Log::info('Trích xuất tên: ' . $result['name']);
                break;
            }
        }
        
        // Nếu vẫn chưa có tên, thử tìm các từ thông dụng trong họ tên
        if (empty($result['name'])) {
            $commonNames = ['nguyễn', 'trần', 'lê', 'phạm', 'hoàng', 'huỳnh', 'phan', 'vũ', 'võ', 'đặng', 'bùi', 'đỗ', 'hồ', 'ngô', 'dương', 'lý'];
            foreach ($commonNames as $lastName) {
                $pattern = '/\b' . $lastName . '\s+([^\d.,]{2,})/iu';
                if (preg_match($pattern, $message, $matches)) {
                    $result['name'] = ucfirst(trim($matches[0]));
                    Log::info('Trích xuất tên từ họ phổ biến: ' . $result['name']);
                    break;
                }
            }
        }
        
        // Trích xuất số điện thoại (nhiều định dạng)
        if (preg_match('/(?:số điện thoại|sđt|điện thoại|phone|tel|số)[:]?\s*(\d{9,11})/', $message, $phoneMatches)) {
            $result['phone'] = $phoneMatches[1];
            Log::info('Trích xuất số điện thoại từ từ khóa: ' . $result['phone']);
        } elseif (preg_match('/(\d{10,11})/', $message, $phoneMatches)) {
            // Chỉ lấy số điện thoại nếu là chuỗi độc lập 10-11 số
            if (preg_match('/\b\d{10,11}\b/', $phoneMatches[0])) {
                $result['phone'] = $phoneMatches[1];
                Log::info('Trích xuất số điện thoại từ số: ' . $result['phone']);
            }
        }
        
        // Nếu có số điện thoại nhưng không có tên, đặt tên mặc định
        if (empty($result['name']) && !empty($result['phone'])) {
            $result['name'] = "Khách " . substr($result['phone'], -4);
            Log::info('Đặt tên mặc định từ SĐT: ' . $result['name']);
        }
        
        // Nếu có tên nhưng không có số điện thoại, kiểm tra xem có số trong tin nhắn không
        if (!empty($result['name']) && empty($result['phone'])) {
            // Tìm số điện thoại có ít nhất 9-10 chữ số
            if (preg_match('/\b\d{9,10}\b/', $message, $matches)) {
                $result['phone'] = $matches[0];
                Log::info('Trích xuất số điện thoại đơn giản: ' . $result['phone']);
            }
        }
        
        Log::info('Kết quả trích xuất thông tin đặt lịch', [
            'service' => $result['service'] ? $result['service']->Tendichvu : null,
            'date' => $result['date'],
            'time' => $result['time'],
            'name' => $result['name'],
            'phone' => $result['phone']
        ]);
        
        return $result;
    }
    
    /**
     * Kiểm tra tính khả dụng của khung giờ đặt lịch
     */
    private function checkTimeAvailability($serviceId, $date, $time)
    {
        try {
            $result = [
                'available' => false,
                'message' => 'không khả dụng',
                'alternative_times' => []
            ];
            
            // Kiểm tra giới hạn số lượng đặt lịch trong ngày
            $bookingsCountInDay = \App\Models\DatLich::whereDate('Thoigiandatlich', $date)
                ->where('Trangthai_', '!=', 'Đã hủy')
                ->count();
                
            if ($bookingsCountInDay >= 30) {
                $result['message'] = 'đã đạt giới hạn 30 lịch đặt trong ngày này';
                return $result;
            }
            
            // Lấy thông tin dịch vụ
            $dichVu = DichVu::findOrFail($serviceId);
            
            // Kiểm tra dịch vụ có hoạt động trong ngày đã chọn không
            $dayOfWeek = \Carbon\Carbon::parse($date)->format('l');
            if (!$dichVu->isAvailableOn($dayOfWeek)) {
                $result['message'] = "không khả dụng vì dịch vụ này không hoạt động vào {$dayOfWeek}";
                return $result;
            }
            
            // Lấy thời gian dịch vụ (phút)
            $serviceTime = $dichVu->Thoigian;
            
            // Lấy các khung giờ đã đặt cho dịch vụ này trong ngày
            $bookedSlots = $this->getBookedTimeSlots($serviceId, $date);
            
            // Kiểm tra xem khung giờ này đã đạt giới hạn chưa
            $bookingDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
            $serviceEndTime = (clone $bookingDateTime)->addMinutes($serviceTime);
            
            $overlappingBookings = 0;
            foreach ($bookedSlots as $slot) {
                $slotStart = \Carbon\Carbon::parse($date . ' ' . $slot['start']);
                $slotEnd = \Carbon\Carbon::parse($date . ' ' . $slot['end']);
                
                if (($bookingDateTime >= $slotStart && $bookingDateTime < $slotEnd) ||
                    ($serviceEndTime > $slotStart && $serviceEndTime <= $slotEnd) ||
                    ($bookingDateTime <= $slotStart && $serviceEndTime >= $slotEnd)
                ) {
                    $overlappingBookings++;
                }
            }
            
            // Giới hạn tối đa 2 lịch đặt trùng giờ
            $maxConcurrentBookings = 2;
            if ($overlappingBookings >= $maxConcurrentBookings) {
                $result['message'] = 'đã đạt giới hạn đặt lịch';
                
                // Đề xuất các khung giờ thay thế
                $availableSlots = $this->getAvailableTimeSlots($serviceId, $date);
                $result['alternative_times'] = array_slice($availableSlots, 0, 3);
                
                return $result;
            }
            
            // Kiểm tra thời gian hiện tại
            $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
            if ($date == $now->format('Y-m-d') && $bookingDateTime <= $now) {
                $result['message'] = 'đã qua thời gian hiện tại';
                
                // Đề xuất các khung giờ thay thế
                $availableSlots = $this->getAvailableTimeSlots($serviceId, $date);
                $result['alternative_times'] = array_slice($availableSlots, 0, 3);
                
                return $result;
            }
            
            $result['available'] = true;
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('Lỗi khi kiểm tra tính khả dụng: ' . $e->getMessage());
            return [
                'available' => false,
                'message' => 'có lỗi xảy ra khi kiểm tra',
                'alternative_times' => []
            ];
        }
    }

    /**
     * Lấy các khung giờ đã đặt cho dịch vụ
     */
    private function getBookedTimeSlots($serviceId, $date)
    {
        // Lấy thông tin dịch vụ
        $dichVu = DichVu::findOrFail($serviceId);
        $serviceTime = $dichVu->Thoigian;
        
        // Lấy các khung giờ đã đặt trong ngày
        $bookings = \App\Models\DatLich::whereDate('Thoigiandatlich', $date)
            ->where('Trangthai_', '!=', 'Đã hủy')
            ->get();
            
        $slots = $bookings->map(function($booking) {
            // Lấy thời gian dịch vụ từ dịch vụ của booking
            $bookingServiceTime = $booking->dichVu ? $booking->dichVu->Thoigian : 60;
            
            $time = \Carbon\Carbon::parse($booking->Thoigiandatlich);
            $endTime = (clone $time)->addMinutes($bookingServiceTime);
            
            return [
                'start' => $time->format('H:i'),
                'end' => $endTime->format('H:i'),
                'booking_id' => $booking->MaDL,
                'service_id' => $booking->MaDV,
            ];
        });
        
        return $slots;
    }
    
    /**
     * Lấy các khung giờ còn trống cho dịch vụ và ngày
     */
    private function getAvailableTimeSlots($serviceId, $date)
    {
        try {
            // Lấy thông tin dịch vụ
            $dichVu = DichVu::findOrFail($serviceId);
            $serviceTime = $dichVu->Thoigian;
            
            // Lấy các khung giờ đã đặt cho dịch vụ này trong ngày
            $bookedSlots = $this->getBookedTimeSlots($serviceId, $date);
            
            // Tạo danh sách các khung giờ có sẵn (ví dụ: từ 8:00 đến 18:00, mỗi 30 phút)
            $availableTimeSlots = [];
            $startHour = 8;
            $endHour = 17;
            $interval = 30; // phút
            
            $currentTime = \Carbon\Carbon::parse($date)->setHour($startHour)->setMinute(0)->setSecond(0);
            $endTime = \Carbon\Carbon::parse($date)->setHour($endHour)->setMinute(30)->setSecond(0);
            
            // Thiết lập múi giờ cho Việt Nam/TP.HCM
            $now = \Carbon\Carbon::now('Asia/Ho_Chi_Minh');
            
            // Nếu ngày đặt lịch là hôm nay, bỏ qua các khung giờ đã qua
            if ($date == $now->format('Y-m-d')) {
                $currentTime = max($currentTime, $now->ceil('30 minutes'));
            }
            
            $result = [];
            
            while ($currentTime < $endTime) {
                $timeSlot = $currentTime->format('H:i');
                
                // Kiểm tra xem khung giờ này đã đạt giới hạn chưa
                $overlappingBookings = 0;
                $currentTimeEnd = (clone $currentTime)->addMinutes($serviceTime);
                
                foreach ($bookedSlots as $bookedSlot) {
                    $bookedStart = \Carbon\Carbon::parse($date . ' ' . $bookedSlot['start']);
                    $bookedEnd = \Carbon\Carbon::parse($date . ' ' . $bookedSlot['end']);
                    
                    if (($currentTime >= $bookedStart && $currentTime < $bookedEnd) ||
                        ($currentTimeEnd > $bookedStart && $currentTimeEnd <= $bookedEnd) ||
                        ($currentTime <= $bookedStart && $currentTimeEnd >= $bookedEnd)
                    ) {
                        $overlappingBookings++;
                    }
                }
                
                // Giới hạn tối đa 2 lịch đặt trùng giờ
                $maxConcurrentBookings = 2;
                if ($overlappingBookings < $maxConcurrentBookings) {
                    $result[] = $timeSlot;
                }
                
                $currentTime->addMinutes($interval);
            }
            
            return $result;
            
        } catch (\Exception $e) {
            \Log::error('Lỗi khi lấy khung giờ trống: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lấy dữ liệu hạng thành viên
     */
    private function getMembershipData()
    {
        try {
            $ranks = HangThanhVien::orderBy('Diemtoithieu', 'asc')->get();
            if ($ranks->isEmpty()) {
                return "Hiện tại chưa có thông tin về hạng thành viên.";
            }
            
            $result = "";
            
            foreach ($ranks as $rank) {
                $discount = $rank->Uudai * 100;
                $result .= "## Hạng {$rank->Tenhang}\n";
                $result .= "- Điểm tích lũy tối thiểu: {$rank->Diemtoithieu} điểm\n";
                $result .= "- Ưu đãi: Giảm {$discount}% cho mỗi lần sử dụng dịch vụ\n";
                if (!empty($rank->Mota)) {
                    $result .= "- Chi tiết: {$rank->Mota}\n";
                }
                $result .= "\n";
            }
            
            $result .= "Cách tích điểm:\n";
            $result .= "- Mỗi 10,000 VND = 1 điểm thưởng\n";
            $result .= "- Điểm tích lũy có giá trị trong vòng 12 tháng\n";
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting membership data: ' . $e->getMessage());
            return "Không thể lấy thông tin hạng thành viên.";
        }
    }
    
    /**
     * Lấy dữ liệu hóa đơn
     */
    private function getInvoiceData($user)
    {
        try {
            $invoices = DB::table('hoadon')
                ->join('chitiet_hoadon', 'hoadon.MaHD', '=', 'chitiet_hoadon.MaHD')
                ->join('dichvu', 'chitiet_hoadon.MaDV', '=', 'dichvu.MaDV')
                ->where('hoadon.MaNguoiDung', $user->Manguoidung)
                ->orderBy('hoadon.NgayLap', 'desc')
                ->select('hoadon.*', 'dichvu.Tendichvu')
                ->limit(5)
                ->get();
                
            if ($invoices->isEmpty()) {
                return "Chưa có hóa đơn nào.";
            }
            
            $result = "";
            
            foreach ($invoices as $invoice) {
                $date = date('d/m/Y', strtotime($invoice->NgayLap));
                $total = number_format($invoice->TongTien, 0, ',', '.') . ' VND';
                
                $result .= "## Hóa đơn #{$invoice->MaHD}\n";
                $result .= "- Ngày: {$date}\n";
                $result .= "- Dịch vụ: {$invoice->Tendichvu}\n";
                $result .= "- Tổng tiền: {$total}\n";
                $result .= "- Trạng thái: " . ($invoice->TrangThai == 1 ? "Đã thanh toán" : "Chưa thanh toán") . "\n\n";
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting invoice data: ' . $e->getMessage());
            return "Không thể lấy thông tin hóa đơn.";
        }
    }
    
    /**
     * Lấy dữ liệu điểm thưởng
     */
    private function getRewardPointData($user)
    {
        try {
            $points = $user->DiemTL ?? 0;
            $result = "- Tổng điểm hiện có: {$points} điểm\n";
            
            // Tìm hạng thành viên hiện tại
            $currentRank = HangThanhVien::where('Diemtoithieu', '<=', $points)
                ->orderBy('Diemtoithieu', 'desc')
                ->first();
            
            if ($currentRank) {
                $discount = $currentRank->Uudai * 100;
                $result .= "- Hạng thành viên hiện tại: {$currentRank->Tenhang} (Giảm {$discount}%)\n";
                
                // Tìm hạng thành viên tiếp theo
                $nextRank = HangThanhVien::where('Diemtoithieu', '>', $points)
                    ->orderBy('Diemtoithieu', 'asc')
                    ->first();
                
                if ($nextRank) {
                    $pointsNeeded = $nextRank->Diemtoithieu - $points;
                    $nextDiscount = $nextRank->Uudai * 100;
                    $result .= "- Cần thêm {$pointsNeeded} điểm để lên hạng {$nextRank->Tenhang} (Giảm {$nextDiscount}%)\n";
                } else {
                    $result .= "- Đã đạt hạng thành viên cao nhất\n";
                }
            } else {
                $lowestRank = HangThanhVien::orderBy('Diemtoithieu', 'asc')->first();
                if ($lowestRank) {
                    $pointsNeeded = $lowestRank->Diemtoithieu - $points;
                    $result .= "- Cần thêm {$pointsNeeded} điểm để đạt hạng {$lowestRank->Tenhang}\n";
                }
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error getting reward point data: ' . $e->getMessage());
            return "Không thể lấy thông tin điểm thưởng.";
        }
    }

    /**
     * Tự động tạo đặt lịch từ thông tin đã trích xuất
     */
    private function autoCreateBooking($bookingInfo, $user = null)
    {
        try {
            Log::info('Bắt đầu đặt lịch tự động', [
                'booking_info' => [
                    'service' => $bookingInfo['service'] ? $bookingInfo['service']->Tendichvu : null,
                    'date' => $bookingInfo['date'] ?? null,
                    'time' => $bookingInfo['time'] ?? null,
                    'name' => $bookingInfo['name'] ?? ($user ? $user->Hoten : null),
                    'phone' => $bookingInfo['phone'] ?? ($user ? $user->SDT : null),
                ],
                'user_id' => $user ? $user->MaTK : 'guest'
            ]);
            
            // Kiểm tra dữ liệu đầu vào
            if (!$bookingInfo['service']) {
                Log::warning('Thiếu thông tin dịch vụ khi đặt lịch');
                return [
                    'success' => false,
                    'message' => 'Không xác định được dịch vụ'
                ];
            }
            
            if (!$bookingInfo['date']) {
                Log::warning('Thiếu thông tin ngày đặt lịch');
                return [
                    'success' => false,
                    'message' => 'Không xác định được ngày đặt lịch'
                ];
            }
            
            if (!$bookingInfo['time']) {
                Log::warning('Thiếu thông tin giờ đặt lịch');
                return [
                    'success' => false,
                    'message' => 'Không xác định được giờ đặt lịch'
                ];
            }
            
            try {
                $bookingDateTime = \Carbon\Carbon::createFromFormat(
                    'Y-m-d H:i', 
                    $bookingInfo['date'] . ' ' . $bookingInfo['time']
                );
                Log::info('Thời gian đặt lịch: ' . $bookingDateTime->format('Y-m-d H:i:s'));
            } catch (\Exception $e) {
                Log::error('Lỗi khi tạo đối tượng DateTime: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Thời gian đặt lịch không hợp lệ'
                ];
            }
            
            // Tạo mã đặt lịch mới
            try {
                // Tìm mã đặt lịch lớn nhất trong CSDL
                $lastMaDL = DB::table('datlich')->max('MaDL') ?? 0;
                $newMaDL = $lastMaDL + 1;  // Tăng lên 1 để tạo mã mới
                
                // Log thông tin
                Log::info('Tạo mã đặt lịch mới (chỉ số):', [
                    'lastMaDL' => $lastMaDL,
                    'newMaDL' => $newMaDL
                ]);
            } catch (\Exception $e) {
                Log::error('Lỗi khi tạo mã đặt lịch: ' . $e->getMessage());
                // Fallback nếu không thể lấy mã cuối cùng
                $newMaDL = (int)time() % 1000000;  // Dùng timestamp nhưng giới hạn độ dài
                Log::info('Sử dụng mã dự phòng: ' . $newMaDL);
            }
            
            // Lưu đặt lịch trực tiếp vào database bằng DB facade
            try {
                // Chuẩn bị dữ liệu
                $insertData = [
                    'MaDL' => $newMaDL,
                    'MaDV' => $bookingInfo['service']->MaDV,
                    'Thoigiandatlich' => $bookingDateTime,
                    'Trangthai_' => 'Chờ xác nhận',
                    'GhiChu' => 'Đặt lịch qua chatbot'
                ];
                
                // Thêm thông tin người dùng
                if ($user) {
                    $insertData['Manguoidung'] = $user->Manguoidung;
                    $insertData['Hoten_khach'] = $user->Hoten;
                    $insertData['SDT_khach'] = $user->SDT;
                    Log::info('Đặt lịch cho user đã đăng nhập: ' . $user->Hoten);
                } else {
                    // Khách vãng lai
                    Log::info('Thông tin khách vãng lai: ', [
                        'name' => $bookingInfo['name'] ?? 'không có',
                        'phone' => $bookingInfo['phone'] ?? 'không có'
                    ]);
                    
                    if (empty($bookingInfo['name']) || empty($bookingInfo['phone'])) {
                        Log::warning('Thiếu thông tin khách hàng vãng lai', [
                            'has_name' => !empty($bookingInfo['name']),
                            'has_phone' => !empty($bookingInfo['phone']),
                        ]);
                        return [
                            'success' => false,
                            'message' => 'Thiếu thông tin khách hàng (tên hoặc số điện thoại)'
                        ];
                    }
                    
                    $insertData['Manguoidung'] = null;
                    $insertData['Hoten_khach'] = $bookingInfo['name'];
                    $insertData['SDT_khach'] = $bookingInfo['phone'];
                    Log::info('Đặt lịch cho khách vãng lai: ' . $bookingInfo['name'] . ' - SĐT: ' . $bookingInfo['phone']);
                }
                
                // Log thông tin trước khi lưu
                Log::info('Thông tin đặt lịch trước khi lưu vào DB', $insertData);
                
                // Thử các cách khác nhau để xác định đúng tên bảng
                try {
                    // Lấy thông tin cấu trúc bảng thực tế
                    Log::info('Cấu trúc bảng theo Model', [
                        'table' => (new \App\Models\DatLich())->getTable(),
                        'connection' => \App\Models\DatLich::resolveConnection()->getName()
                    ]);
                
                    // Cách 1: Dùng query builder với tên bảng từ model
                    $table = (new \App\Models\DatLich())->getTable();
                    DB::table($table)->insert($insertData);
                    Log::info('Đặt lịch thành công (cách 1 - qua table): ' . $newMaDL);
                } catch (\Exception $e1) {
                    Log::warning('Lỗi khi dùng tên bảng từ model: ' . $e1->getMessage());
                    
                    try {
                        // Cách 2: Dùng prepared statement để đảm bảo các tham số được định dạng đúng
                        $sql = "INSERT INTO datlich (MaDL, MaDV, Thoigiandatlich, Trangthai_, Manguoidung, Hoten_khach, SDT_khach, GhiChu) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        
                        DB::statement($sql, [
                            $insertData['MaDL'],
                            $insertData['MaDV'],
                            $insertData['Thoigiandatlich'],
                            $insertData['Trangthai_'],
                            $insertData['Manguoidung'],
                            $insertData['Hoten_khach'],
                            $insertData['SDT_khach'],
                            $insertData['GhiChu']
                        ]);
                        Log::info('Đặt lịch thành công (cách 2 - qua statement): ' . $newMaDL);
                    } catch (\Exception $e2) {
                        Log::warning('Lỗi khi dùng DB::statement: ' . $e2->getMessage());
                        
                        try {
                            // Cách 3: Dùng model trực tiếp
                            $datLich = new \App\Models\DatLich();
                            $datLich->MaDL = $insertData['MaDL']; 
                            $datLich->MaDV = $insertData['MaDV'];
                            $datLich->Thoigiandatlich = $insertData['Thoigiandatlich'];
                            $datLich->Trangthai_ = $insertData['Trangthai_'];
                            $datLich->Manguoidung = $insertData['Manguoidung'];
                            $datLich->Hoten_khach = $insertData['Hoten_khach'];
                            $datLich->SDT_khach = $insertData['SDT_khach']; 
                            $datLich->GhiChu = $insertData['GhiChu'];
                            $datLich->save();
                            Log::info('Đặt lịch thành công (cách 3 - qua model): ' . $newMaDL);
                        } catch (\Exception $e3) {
                            Log::error('Tất cả các cách đều thất bại', [
                                'model_error' => $e1->getMessage(),
                                'statement_error' => $e2->getMessage(), 
                                'direct_model_error' => $e3->getMessage(),
                                'trace' => $e3->getTraceAsString()
                            ]);
                                
                            throw new \Exception('Không thể lưu đặt lịch: ' . $e3->getMessage());
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Lỗi khi lưu đặt lịch vào database: ' . $e->getMessage(), [
                    'exception' => $e,
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]);
                return [
                    'success' => false,
                    'message' => 'Lỗi khi lưu đặt lịch: ' . $e->getMessage()
                ];
            }
            
            return [
                'success' => true,
                'booking_id' => $newMaDL,
                'message' => 'Đặt lịch thành công',
                'booking_time' => $bookingDateTime->format('H:i d/m/Y')
            ];
            
        } catch (\Exception $e) {
            Log::error('Lỗi tổng quát khi tự động đặt lịch: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đặt lịch: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Lấy dữ liệu liên quan từ DB
     */
    private function getRelevantDataFromDB($userMessage)
    {
        try {
            $context = "";

            $services = DichVu::all()->take(5);
            if ($services->count() > 0) {
                $context .= "DỊCH VỤ CỦA CHÚNG TÔI:\n";
                foreach ($services as $service) {
                    $context .= "- {$service->Tendichvu}: " . number_format($service->Gia, 0, ',', '.') . " VND\n";
                    if ($service->MoTa) $context .= "  Mô tả: " . substr($service->MoTa, 0, 100) . "...\n\n";
                }
            }

            $promotions = QuangCao::where('Trangthai_', 1)->where('Ngayketthuc', '>=', now())->take(3)->get();
            if ($promotions->count() > 0) {
                $context .= "KHUYẾN MÃI HIỆN TẠI:\n";
                foreach ($promotions as $promo) {
                    $context .= "- {$promo->Tieude}\n  Thời gian: " . date('d/m/Y', strtotime($promo->Ngaybatdau)) . " - " . date('d/m/Y', strtotime($promo->Ngayketthuc)) . "\n";
                    if ($promo->Noidung) $context .= "  Nội dung: " . substr($promo->Noidung, 0, 100) . "...\n\n";
                }
            }

            $ranks = HangThanhVien::orderBy('Diemtoithieu', 'asc')->get();
            if ($ranks->count() > 0) {
                $context .= "THÔNG TIN HẠNG THÀNH VIÊN:\n";
                foreach ($ranks as $rank) {
                    $context .= "- {$rank->Tenhang}: Yêu cầu {$rank->Diemtoithieu} điểm, giảm " . ($rank->Uudai * 100) . "%\n\n";
                }
            }

            return $context ?: "Chào mừng bạn đến với Rosa Spa. Vui lòng cho biết bạn cần thông tin gì?";
        } catch (\Exception $e) {
            Log::error('Error getting DB data: ' . $e->getMessage());
            return "Thông tin dịch vụ Rosa Spa.";
        }
    }

    /**
     * Kiểm tra xem tin nhắn hiện tại có liên quan đến đặt lịch trước đó không
     */
    private function isRelatedToLastBooking($userMessage, $lastBooking)
    {
        $message = mb_strtolower($userMessage, 'UTF-8');
        $relevantTerms = ['lịch', 'hẹn', 'đặt', 'booking', 'xem lại', 'thông tin', 'chi tiết', 'hủy'];
        
        foreach ($relevantTerms as $term) {
            if (mb_strpos($message, $term) !== false) {
                return true;
            }
        }
        
        // Kiểm tra nếu tin nhắn có chứa mã đặt lịch
        if (mb_strpos($message, $lastBooking['booking_id']) !== false) {
            return true;
        }
        
        return false;
    }

    /**
     * Kiểm tra xem có thể bổ sung thông tin đặt lịch từ tin nhắn mới không
     */
    private function canEnhancePartialBooking($userMessage, $partialBookingInfo)
    {
        // Nếu đã có đầy đủ thông tin, không cần bổ sung
        if ($this->isCompleteBookingInfo($partialBookingInfo)) {
            return false;
        }
        
        // Kiểm tra xem tin nhắn có chứa thông tin bổ sung không
        $newBookingInfo = $this->extractBookingInfo($userMessage);
        
        foreach ($newBookingInfo as $key => $value) {
            if (!empty($value) && (empty($partialBookingInfo[$key]) || $partialBookingInfo[$key] === null)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Kiểm tra xem thông tin đặt lịch đã đầy đủ chưa
     */
    private function isCompleteBookingInfo($bookingInfo)
    {
        return !empty($bookingInfo['service']) && 
               !empty($bookingInfo['date']) && 
               !empty($bookingInfo['time']) && 
               (!empty($bookingInfo['name']) || auth()->check()) && 
               (!empty($bookingInfo['phone']) || auth()->check());
    }

    /**
     * Bổ sung thông tin đặt lịch từ tin nhắn mới
     */
    private function enhancePartialBookingInfo($userMessage, $partialBookingInfo)
    {
        $newInfo = $this->extractBookingInfo($userMessage);
        
        // Cập nhật thông tin từ tin nhắn mới nếu có
        foreach ($newInfo as $key => $value) {
            if (!empty($value)) {
                $partialBookingInfo[$key] = $value;
            }
        }
        
        return $partialBookingInfo;
    }

    /**
     * Bổ sung thông tin đặt lịch từ lịch sử chat
     */
    private function enhanceBookingInfoFromHistory($bookingInfo, $chatHistory)
    {
        // Chỉ xem xét 5 tin nhắn gần nhất
        $recentMessages = array_slice($chatHistory, -5);
        
        foreach ($recentMessages as $chat) {
            $messageInfo = $this->extractBookingInfo($chat['user']);
            
            // Bổ sung thông tin còn thiếu từ lịch sử
            foreach ($messageInfo as $key => $value) {
                if (empty($bookingInfo[$key]) && !empty($value)) {
                    $bookingInfo[$key] = $value;
                }
            }
        }
        
        return $bookingInfo;
    }

    /**
     * Chuẩn bị ngữ cảnh theo dõi sau khi đặt lịch
     */
    private function prepareFollowUpBookingContext($lastBooking)
    {
        $bookingId = $lastBooking['booking_id'];
        $serviceName = $lastBooking['service'];
        $formattedDate = date('d/m/Y', strtotime($lastBooking['date']));
        $time = $lastBooking['time'];
        
        $context = "THÔNG TIN LỊCH HẸN HIỆN TẠI:\n";
        $context .= "- Mã đặt lịch: {$bookingId}\n";
        $context .= "- Dịch vụ: {$serviceName}\n";
        $context .= "- Ngày: {$formattedDate}\n";
        $context .= "- Giờ: {$time}\n";
        $context .= "- Trạng thái: Chờ xác nhận\n\n";
        
        $context .= "Người dùng có vẻ đang hỏi về lịch hẹn họ vừa đặt. ";
        $context .= "Hãy cung cấp thông tin hữu ích về lịch hẹn này hoặc hướng dẫn họ cách quản lý lịch hẹn.\n\n";
        
        $context .= "CÁC THAO TÁC CÓ THỂ:\n";
        $context .= "- Xem chi tiết lịch hẹn\n";
        $context .= "- Hủy lịch hẹn\n";
        $context .= "- Đổi lịch hẹn\n";
        $context .= "- Các dịch vụ bổ sung liên quan\n";
        
        return $context;
    }

    /**
     * Kiểm tra xem tin nhắn có phải là yêu cầu xác nhận đặt lịch không
     */
    private function isConfirmationRequest($message)
    {
        // Chuyển về chữ thường và bỏ dấu
        $message = mb_strtolower($message, 'UTF-8');
        $message = trim($message);
        
        // Gỡ bỏ dấu câu
        $message = str_replace(['!', '?', '.', ',', ';', ':', '"', "'"], '', $message);
        
        Log::info('Kiểm tra xác nhận từ tin nhắn: "' . $message . '"');
        
        // Danh sách từ khóa xác nhận chính xác (chấp nhận nếu tin nhắn chỉ chứa từ này)
        $exactConfirmations = ['ok', 'yes', 'đúng', 'xác nhận', 'đặt', 'đồng ý', 'được', 'ừ', 'uh', 'vâng', 'đúng rồi'];
        
        // Kiểm tra tin nhắn đơn giản (chỉ 1 từ)
        if (in_array($message, $exactConfirmations)) {
            Log::info('Phát hiện xác nhận chính xác: ' . $message);
            return true;
        }
        
        // Danh sách từ khóa xác nhận
        $confirmationPatterns = [
            'xác nhận',
            'đồng ý', 
            'ok',
            'được',
            'tôi xác nhận',
            'đặt ngay',
            'chắc chắn',
            'xác nhận đặt lịch',
            'đặt lịch luôn',
            'confirm',
            'yes',
            'chính xác',
            'chốt',
            'đúng rồi',
            'đúng vậy',
            'vâng',
            'vâng nhé',
            'đồng ý đặt',
            'đặt giúp mình',
            'tôi muốn đặt',
            'tôi đồng ý',
            'đặt đi',
            'đặt giúp',
            'đặt luôn',
            'đặt hẹn',
            'tôi muốn đặt hẹn'
        ];
        
        foreach ($confirmationPatterns as $pattern) {
            // Kiểm tra từng từ khóa trong tin nhắn
            if (mb_strpos($message, $pattern) !== false) {
                Log::info('Phát hiện xác nhận từ mẫu: ' . $pattern);
                return true;
            }
        }
        
        // Từ chối các phủ định
        $negativePatterns = ['không', 'chưa', 'đừng', 'hủy', 'cancel'];
        foreach ($negativePatterns as $negative) {
            if (mb_strpos($message, $negative) !== false) {
                Log::info('Phát hiện phủ định: ' . $negative);
                return false;
            }
        }
        
        // Nếu tin nhắn chỉ có 1-2 từ và không phải phủ định, coi như xác nhận
        $wordCount = count(explode(' ', trim($message)));
        if ($wordCount <= 2) {
            Log::info('Xác nhận ngắn gọn: ' . $message);
            return true;
        }
        
        return false;
    }
    
    /**
     * Tạo phản hồi yêu cầu thông tin đặt lịch
     */
    private function generateBookingInformationRequest()
    {
        $response = "⚠️ THÔNG TIN KHÔNG ĐỦ\n\n";
        $response .= "Để giúp bạn đặt lịch, tôi cần thêm thông tin cá nhân của bạn. Vui lòng cung cấp:\n\n";
        $response .= "1. 👤 Họ tên của bạn\n";
        $response .= "2. 📱 Số điện thoại của bạn (bắt đầu bằng số 0, có 10 số)\n\n";
        $response .= "Ví dụ: \"Tôi là Nguyễn Văn A, số điện thoại 0912345678\"\n\n";
        $response .= "Hoặc đăng nhập để sử dụng thông tin tài khoản của bạn.";
        
        return $response;
    }

    /**
     * Tạo tin nhắn tổng hợp thông tin đặt lịch và yêu cầu xác nhận
     */
    private function generateBookingConfirmation($bookingInfo, $user = null)
    {
        $serviceName = $bookingInfo['service'] ? $bookingInfo['service']->Tendichvu : "Chưa xác định";
        $formattedDate = $bookingInfo['date'] ? date('d/m/Y', strtotime($bookingInfo['date'])) : "Chưa xác định";
        $time = $bookingInfo['time'] ? $bookingInfo['time'] : "Chưa xác định";
        
        // Lấy thêm thông tin về dịch vụ
        $servicePrice = $bookingInfo['service'] ? number_format($bookingInfo['service']->Gia, 0, ',', '.') . ' VND' : "Chưa xác định";
        $serviceDuration = $bookingInfo['service'] ? $this->formatDuration($bookingInfo['service']->Thoigian) : "Chưa xác định";
        
        // Lấy tên và số điện thoại từ thông tin đặt lịch hoặc user đã đăng nhập
        $name = $user ? $user->Hoten : ($bookingInfo['name'] ?? "Chưa xác định");
        $phone = $user ? $user->SDT : ($bookingInfo['phone'] ?? "Chưa xác định");
        
        // Tính thời gian kết thúc dự kiến
        $endTime = "Chưa xác định";
        if ($bookingInfo['time'] && $bookingInfo['service'] && isset($bookingInfo['service']->Thoigian)) {
            $serviceMinutes = (int)$bookingInfo['service']->Thoigian;
            
            Log::info('Tính toán giờ kết thúc dịch vụ', [
                'giờ_bắt_đầu' => $bookingInfo['time'],
                'thời_gian_dịch_vụ' => $serviceMinutes . ' phút',
                'tên_dịch_vụ' => $bookingInfo['service']->Tendichvu
            ]);
            
            if ($serviceMinutes > 0) {
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $bookingInfo['time']);
                $endTime = (clone $startTime)->addMinutes($serviceMinutes)->format('H:i');
                
                Log::info('Kết quả tính toán', [
                    'giờ_bắt_đầu' => $startTime->format('H:i'),
                    'giờ_kết_thúc' => $endTime,
                    'thời_gian_dịch_vụ' => $serviceMinutes
                ]);
            } else {
                // Trường hợp không có thời gian dịch vụ, dùng mặc định 60 phút
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $bookingInfo['time']);
                $endTime = (clone $startTime)->addMinutes(60)->format('H:i');
                
                Log::warning('Thời gian dịch vụ bằng 0, sử dụng mặc định 60 phút', [
                    'dịch_vụ' => $bookingInfo['service']->Tendichvu,
                    'giờ_bắt_đầu' => $bookingInfo['time'],
                    'giờ_kết_thúc' => $endTime
                ]);
            }
        }
        
        // Hiển thị thông tin ngày với ngày trong tuần
        $dayOfWeek = "";
        if ($bookingInfo['date']) {
            $dayOfWeek = " (" . $this->getDayOfWeekInVietnamese(\Carbon\Carbon::parse($bookingInfo['date'])) . ")";
        }
        
        Log::info('Tạo thông báo xác nhận đặt lịch', [
            'service' => $serviceName,
            'date' => $formattedDate,
            'time' => $time,
            'name' => $name,
            'phone' => $phone
        ]);
        
        $response = "📋 THÔNG TIN ĐẶT LỊCH\n\n";
        
        // Kiểm tra nếu thiếu thông tin khách hàng
        $isMissingCustomerInfo = ($name == "Chưa xác định" || $phone == "Chưa xác định") && !$user;
        
        if ($isMissingCustomerInfo) {
            $response .= "⚠️ VUI LÒNG CUNG CẤP THÔNG TIN CÁ NHÂN\n\n";
            $response .= "Tôi cần thông tin cá nhân của bạn để hoàn tất đặt lịch. Vui lòng cho biết:\n";
            
            if ($name == "Chưa xác định") {
                $response .= "- Họ tên của bạn\n";
            }
            
            if ($phone == "Chưa xác định") {
                $response .= "- Số điện thoại của bạn\n";
            }
            
            $response .= "\nVí dụ: \"Tôi là Nguyễn Văn A, số điện thoại 0912345678\"\n\n";
            $response .= "----------------------------------------------\n\n";
        }
        
        $response .= "Dạ, vui lòng xác nhận thông tin đặt lịch của " . ($name != "Chưa xác định" ? $name : "bạn") . ":\n\n";
        $response .= "- Dịch vụ: {$serviceName}\n";
        $response .= "- Giá dịch vụ: {$servicePrice}\n";
        $response .= "- Thời gian dịch vụ: {$serviceDuration}\n";
        $response .= "- Ngày đặt: {$formattedDate}{$dayOfWeek}\n";
        $response .= "- Giờ bắt đầu: {$time}\n";
        $response .= "- Giờ kết thúc (dự kiến): {$endTime}\n";
        $response .= "- Họ tên: {$name}\n";
        $response .= "- Số điện thoại: {$phone}\n\n";
        
        if ($isMissingCustomerInfo) {
            $response .= "❗ Bạn cần cung cấp đủ thông tin cá nhân mới có thể đặt lịch.\n\n";
        } else {
            $response .= "💡 Để XÁC NHẬN đặt lịch, vui lòng trả lời \"xác nhận\" hoặc \"đồng ý\".\n";
            $response .= "❓ Để THAY ĐỔI thông tin, vui lòng nhập lại thông tin cần thay đổi.\n";
        }
        
        return $response;
    }

    /**
     * Kiểm tra xem đã có thông tin tối thiểu để hiển thị xác nhận chưa
     */
    private function hasMinimumBookingInfo($bookingInfo)
    {
        $basicInfo = !empty($bookingInfo['service']) && 
               (!empty($bookingInfo['date']) || !empty($bookingInfo['time']));
               
        // Log thông tin kiểm tra
        Log::info('Kiểm tra thông tin đặt lịch tối thiểu', [
            'service' => !empty($bookingInfo['service']),
            'date' => !empty($bookingInfo['date']),
            'time' => !empty($bookingInfo['time']),
            'name' => !empty($bookingInfo['name']),
            'phone' => !empty($bookingInfo['phone']),
            'basicInfo' => $basicInfo
        ]);
        
        return $basicInfo;
    }

    /**
     * Xóa lịch sử chat và thông tin đặt lịch từ session
     */
    public function reset()
    {
        try {
            // Xóa lịch sử chat từ session
            session()->forget('chat_history');
            
            // Xóa thông tin đặt lịch một phần
            session()->forget('partial_booking_info');
            
            // Xóa thông tin đặt lịch thành công gần đây
            session()->forget('last_successful_booking');
            
            return response()->json([
                'success' => true,
                'message' => 'Đã làm mới cuộc trò chuyện'
            ]);
        } catch (\Exception $e) {
            Log::error('Lỗi khi làm mới cuộc trò chuyện', [
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể khởi tạo cuộc trò chuyện mới'
            ], 500);
        }
    }
}