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
            
            // Kiểm tra nếu đây là yêu cầu đặt lịch
            $isBookingRequest = $this->isBookingRequest($userMessage);
            
            if ($isBookingRequest) {
                // Trích xuất thông tin đặt lịch từ tin nhắn
                $bookingInfo = $this->extractBookingInfo($userMessage);
                
                // Kiểm tra thông tin đặt lịch
                $missingInfo = $this->checkMissingBookingInfo($bookingInfo, $isLoggedIn);
                
                // Nếu đã đủ thông tin, thực hiện đặt lịch tự động
                if (empty($missingInfo) && $this->canAutoBook($bookingInfo, $isLoggedIn)) {
                    $bookingResult = $this->autoCreateBooking($bookingInfo, $user);
                    
                    if ($bookingResult['success']) {
                        $reply = $this->generateBookingSuccessResponse($bookingResult, $bookingInfo, $user);
                    } else {
                        $reply = $this->generateBookingFailureResponse($bookingResult, $bookingInfo);
                    }
                } else {
                    // Tạo câu trả lời bằng OpenAI với thông tin đặt lịch đã trích xuất
                    $bookingContext = $this->prepareBookingContext($bookingInfo, $missingInfo, $isLoggedIn, $user);
                    $reply = $this->generateReplyWithOpenAI($userMessage, $systemPrompt, $bookingContext);
                }
            } else {
                // Tạo câu trả lời bằng OpenAI cho các yêu cầu thông thường
                $reply = $this->generateReplyWithOpenAI($userMessage, $systemPrompt);
            }

            // Lưu vào session
            session()->flash('user', $userMessage);
            session()->flash('reply', $reply);

            return redirect()->back()->with('success', 'Tin nhắn đã được xử lý.');
        } catch (\Exception $e) {
            Log::error('Error processing message', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            $reply = "Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ Rosa Spa qua (028) 1234 5678.";
            session()->flash('user', $userMessage);
            session()->flash('reply', $reply);

            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý tin nhắn.');
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
        $message = strtolower($message);
        $bookingPatterns = [
            'đặt lịch',
            'book',
            'đặt hẹn',
            'lịch hẹn',
            'muốn đặt',
            'schedule',
            'appointment',
            'đặt chỗ',
            'đặt dịch vụ',
            'reserve',
            'reservation'
        ];
        
        foreach ($bookingPatterns as $pattern) {
            if (strpos($message, $pattern) !== false) {
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
        
        if (!$bookingInfo['service']) {
            $missingInfo[] = 'dịch vụ';
        }
        
        if (!$bookingInfo['date']) {
            $missingInfo[] = 'ngày đặt lịch';
        }
        
        if (!$bookingInfo['time']) {
            $missingInfo[] = 'giờ đặt lịch';
        }
        
        // Nếu là khách vãng lai, kiểm tra thông tin cá nhân
        if (!$isLoggedIn) {
            if (!$bookingInfo['name']) {
                $missingInfo[] = 'họ tên';
            }
            
            if (!$bookingInfo['phone']) {
                $missingInfo[] = 'số điện thoại';
            }
        }
        
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
        
        $response = "✅ ĐẶT LỊCH THÀNH CÔNG!\n\n";
        $response .= "Thông tin đặt lịch của bạn:\n";
        $response .= "- Mã đặt lịch: {$bookingResult['booking_id']}\n";
        $response .= "- Dịch vụ: {$serviceName}\n";
        $response .= "- Ngày: {$formattedDate}\n";
        $response .= "- Giờ: {$bookingInfo['time']}\n";
        $response .= "- Trạng thái: Chờ xác nhận\n\n";
        
        // Thông tin cho người dùng đã đăng nhập
        if ($user) {
            $viewUrl = route('customer.lichsudatlich.show', $bookingResult['booking_id']);
            $response .= "Bạn có thể xem chi tiết đặt lịch tại: {$viewUrl}\n\n";
        }
        
        $response .= "Chúng tôi sẽ liên hệ xác nhận lịch hẹn của bạn trong thời gian sớm nhất.\n";
        $response .= "Vui lòng đến trước giờ hẹn 15 phút để chuẩn bị.\n\n";
        $response .= "Cảm ơn bạn đã sử dụng dịch vụ của Rosa Spa!";
        
        return $response;
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
        
        $response .= "Lý do: {$bookingResult['message']}\n\n";
        
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
        $response .= "Bạn có thể thử đặt lịch thủ công tại đây: {$bookUrl}\n\n";
        $response .= "Hoặc liên hệ hotline (028) 1234 5678 để được hỗ trợ.";
        
        return $response;
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
        $result = [
            'service' => null,
            'date' => null,
            'time' => null,
            'name' => null,
            'phone' => null
        ];
        
        // Trích xuất tên dịch vụ
        $allServices = DichVu::all();
        foreach ($allServices as $service) {
            if (stripos($message, strtolower($service->Tendichvu)) !== false) {
                $result['service'] = $service;
                break;
            }
        }
        
        // Trích xuất ngày (dd/mm/yyyy, dd-mm-yyyy, dd.mm.yyyy)
        if (preg_match('/(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})/', $message, $dateMatches)) {
            $day = $dateMatches[1];
            $month = $dateMatches[2];
            $year = $dateMatches[3];
            $result['date'] = "{$year}-{$month}-{$day}";
        } elseif (preg_match('/(hôm nay|today)/', strtolower($message))) {
            $result['date'] = date('Y-m-d');
        } elseif (preg_match('/(ngày mai|tomorrow)/', strtolower($message))) {
            $result['date'] = date('Y-m-d', strtotime('+1 day'));
        } elseif (preg_match('/(ngày kia|day after tomorrow)/', strtolower($message))) {
            $result['date'] = date('Y-m-d', strtotime('+2 days'));
        }
        
        // Trích xuất giờ (HH:MM hoặc HHhMM)
        if (preg_match('/(\d{1,2})[h\:](\d{2})/', $message, $timeMatches)) {
            $hour = str_pad($timeMatches[1], 2, '0', STR_PAD_LEFT);
            $minute = $timeMatches[2];
            $result['time'] = "{$hour}:{$minute}";
        } elseif (preg_match('/(\d{1,2})\s*(giờ|h|hour)/', strtolower($message), $hourMatches)) {
            $hour = str_pad($hourMatches[1], 2, '0', STR_PAD_LEFT);
            $result['time'] = "{$hour}:00";
        }
        
        // Trích xuất tên (thường sau từ "tên", "tên tôi là", "tôi là", "tên của tôi")
        if (preg_match('/(tên|tên tôi là|tôi là|tên của tôi)[:]?\s+([^\d.,]+?)(?=\s*\d|\s*[.,]|$)/', strtolower($message), $nameMatches)) {
            $result['name'] = trim($nameMatches[2]);
        }
        
        // Trích xuất số điện thoại
        if (preg_match('/(?:số điện thoại|sđt|điện thoại|phone|tel|số)[:]?\s*(\d{10,11})/', $message, $phoneMatches)) {
            $result['phone'] = $phoneMatches[1];
        } elseif (preg_match('/(\d{10,11})/', $message, $phoneMatches)) {
            $result['phone'] = $phoneMatches[1];
        }
        
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
            // Kiểm tra dữ liệu đầu vào
            if (!$bookingInfo['service']) {
                return [
                    'success' => false,
                    'message' => 'Không xác định được dịch vụ'
                ];
            }
            
            if (!$bookingInfo['date']) {
                return [
                    'success' => false,
                    'message' => 'Không xác định được ngày đặt lịch'
                ];
            }
            
            if (!$bookingInfo['time']) {
                return [
                    'success' => false,
                    'message' => 'Không xác định được giờ đặt lịch'
                ];
            }
            
            // Kiểm tra tính khả dụng của thời gian
            $availabilityInfo = $this->checkTimeAvailability(
                $bookingInfo['service']->MaDV,
                $bookingInfo['date'],
                $bookingInfo['time']
            );
            
            if (!$availabilityInfo['available']) {
                return [
                    'success' => false,
                    'message' => 'Khung giờ này ' . $availabilityInfo['message']
                ];
            }
            
            // Tạo đối tượng đặt lịch
            $bookingDateTime = \Carbon\Carbon::createFromFormat(
                'Y-m-d H:i', 
                $bookingInfo['date'] . ' ' . $bookingInfo['time']
            );
            
            // Tạo mã đặt lịch mới
            $maxMaDL = \App\Models\DatLich::max('MaDL');
            $newMaDL = $maxMaDL ? (is_numeric($maxMaDL) ? $maxMaDL + 1 : 'DL1') : 'DL1';
            
            $datLich = new \App\Models\DatLich();
            $datLich->MaDL = $newMaDL;
            $datLich->MaDV = $bookingInfo['service']->MaDV;
            $datLich->Thoigiandatlich = $bookingDateTime;
            $datLich->Trangthai_ = 'Chờ xác nhận';
            
            // Xử lý thông tin người dùng
            if ($user) {
                // Người dùng đã đăng nhập
                $datLich->Manguoidung = $user->Manguoidung;
                $datLich->Hoten_khach = $user->Hoten;
                $datLich->SDT_khach = $user->SDT;
            } else {
                // Khách vãng lai
                if (!$bookingInfo['name'] || !$bookingInfo['phone']) {
                    return [
                        'success' => false,
                        'message' => 'Thiếu thông tin khách hàng (tên hoặc số điện thoại)'
                    ];
                }
                
                $datLich->Manguoidung = null;
                $datLich->Hoten_khach = $bookingInfo['name'];
                $datLich->SDT_khach = $bookingInfo['phone'];
            }
            
            // Lưu đặt lịch vào database
            $datLich->save();
            
            return [
                'success' => true,
                'booking_id' => $datLich->MaDL,
                'message' => 'Đặt lịch thành công'
            ];
            
        } catch (\Exception $e) {
            \Log::error('Lỗi khi tự động đặt lịch: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi: ' . $e->getMessage()
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

            $services = DichVu::where('TrangThai', 1)->orderBy('Gia', 'asc')->take(5)->get();
            if ($services->count() > 0) {
                $context .= "DỊCH VỤ CỦA CHÚNG TÔI:\n";
                foreach ($services as $service) {
                    $context .= "- {$service->Tendichvu}: " . number_format($service->Gia, 0, ',', '.') . " VND\n";
                    if ($service->MoTa) $context .= "  Mô tả: " . substr($service->MoTa, 0, 100) . "...\n\n";
                }
            }

            $promotions = QuangCao::where('TrangThai', 1)->where('Ngayketthuc', '>=', now())->take(3)->get();
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
}