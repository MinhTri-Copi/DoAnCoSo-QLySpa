<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\DichVu;
use App\Models\HangThanhVien;
use App\Models\QuangCao;

class ChatController extends Controller
{
    /**
     * Hiển thị giao diện chat
     */
    public function show()
    {
        return view('chat.form');
    }

    /**
     * Xử lý tin nhắn và gửi câu trả lời từ OpenAI
     */
    public function send(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->message;
        
        try {
            // Thử gọi OpenAI API
            $dbContext = $this->getRelevantDataFromDB($userMessage);
            $systemPrompt = "Bạn là trợ lý thông minh của Rosa Spa. Bạn có thể cung cấp thông tin về các dịch vụ spa, đặt lịch, giá cả và tư vấn khách hàng. Hãy trả lời một cách thân thiện và chuyên nghiệp. Dưới đây là một số thông tin từ hệ thống của chúng tôi:\n\n" . $dbContext;
            
            $apiKey = config('services.openai.key');
            Log::info('OpenAI API Key: ' . substr($apiKey, 0, 5) . '...' . substr($apiKey, -4));
            
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.chatanywhere.org/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $systemPrompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $userMessage
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['choices'][0]['message']['content'];
                Log::info('OpenAI response successful', ['reply' => $reply]);
            } else {
                $errorData = $response->json();
                Log::warning('OpenAI API error', ['error' => $errorData]);
                // Fallback message when API fails
                $reply = "Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ trực tiếp với Rosa Spa qua số điện thoại (028) 1234 5678.";
            }
            
            // Lưu vào session để hiển thị lại khi load trang
            session()->flash('user', $userMessage);
            session()->flash('reply', $reply);
            
            return redirect()->back();
            
        } catch (\Exception $e) {
            Log::error('OpenAI API Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            // Fallback message when exception occurs
            $reply = "Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ trực tiếp với Rosa Spa qua số điện thoại (028) 1234 5678.";
            
            // Trả về thông báo lỗi
            session()->flash('user', $userMessage);
            session()->flash('reply', $reply);
            
            return redirect()->back();
        }
    }
    
    /**
     * Lấy dữ liệu liên quan từ DB dựa trên tin nhắn của người dùng
     */
    private function getRelevantDataFromDB($userMessage)
    {
        try {
            $context = "";
            
            // Tìm từ khóa trong tin nhắn
            $keywords = $this->extractKeywords($userMessage);
            
            // Lấy thông tin dịch vụ
            if ($this->containsAny($userMessage, ['dịch vụ', 'giá', 'massage', 'spa', 'làm đẹp', 'chăm sóc'])) {
                $services = DichVu::where('TrangThai', 1)
                            ->orderBy('Gia', 'asc')
                            ->take(5)
                            ->get();
                            
                if ($services->count() > 0) {
                    $context .= "DỊCH VỤ CỦA CHÚNG TÔI:\n";
                    foreach ($services as $service) {
                        $context .= "- {$service->Tendichvu}: " . number_format($service->Gia, 0, ',', '.') . " VND\n";
                        if (isset($service->MoTa)) {
                            $context .= "  Mô tả: " . substr($service->MoTa, 0, 100) . "...\n\n";
                        }
                    }
                }
            }
            
            // Lấy thông tin khuyến mãi
            if ($this->containsAny($userMessage, ['khuyến mãi', 'ưu đãi', 'giảm giá', 'quảng cáo'])) {
                $promotions = QuangCao::where('TrangThai', 1)
                            ->where('Ngayketthuc', '>=', now())
                            ->take(3)
                            ->get();
                            
                if ($promotions->count() > 0) {
                    $context .= "KHUYẾN MÃI HIỆN TẠI:\n";
                    foreach ($promotions as $promo) {
                        $context .= "- {$promo->Tieude}\n";
                        $context .= "  Thời gian: " . date('d/m/Y', strtotime($promo->Ngaybatdau)) . " - " . date('d/m/Y', strtotime($promo->Ngayketthuc)) . "\n";
                        if (isset($promo->Noidung)) {
                            $context .= "  Nội dung: " . substr($promo->Noidung, 0, 100) . "...\n\n";
                        }
                    }
                }
            }
            
            // Lấy thông tin hạng thành viên
            if ($this->containsAny($userMessage, ['thành viên', 'hạng', 'điểm', 'tích lũy'])) {
                $ranks = HangThanhVien::orderBy('Diemtoithieu', 'asc')->get();
                
                if ($ranks->count() > 0) {
                    $context .= "THÔNG TIN HẠNG THÀNH VIÊN:\n";
                    foreach ($ranks as $rank) {
                        $context .= "- {$rank->Tenhangtv}: Yêu cầu {$rank->Diemtoithieu} điểm\n";
                        $context .= "  Ưu đãi: Giảm " . ($rank->Uudai * 100) . "% cho mỗi đơn hàng\n\n";
                    }
                }
            }
            
            // Nếu không tìm thấy dữ liệu phù hợp
            if (empty($context)) {
                $context = "Chào mừng bạn đến với Rosa Spa. Chúng tôi cung cấp nhiều dịch vụ spa và làm đẹp cao cấp với đội ngũ nhân viên chuyên nghiệp. Vui lòng cho biết bạn cần tìm hiểu thông tin gì?";
            }
            
            return $context;
        } catch (\Exception $e) {
            Log::error('Error getting data from DB: ' . $e->getMessage());
            return "Thông tin dịch vụ Rosa Spa";
        }
    }
    
    /**
     * Trích xuất từ khóa từ tin nhắn của người dùng
     */
    private function extractKeywords($message)
    {
        // Loại bỏ dấu câu và chuyển thành chữ thường
        $message = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', mb_strtolower($message, 'UTF-8'));
        
        // Tách thành các từ
        $words = preg_split('/\s+/', $message);
        
        // Loại bỏ stopwords
        $stopwords = ['là', 'và', 'các', 'của', 'có', 'những', 'cho', 'trong', 'với', 'đến', 'tôi', 'muốn', 'cần', 'hỏi', 'về'];
        $keywords = array_diff($words, $stopwords);
        
        // Chỉ giữ lại từ có từ 2 ký tự trở lên
        $keywords = array_filter($keywords, function($word) {
            return mb_strlen($word, 'UTF-8') >= 2;
        });
        
        return array_values($keywords);
    }
    
    /**
     * Kiểm tra xem tin nhắn có chứa bất kỳ từ khóa nào trong danh sách
     */
    private function containsAny($message, $keywords)
    {
        $message = mb_strtolower($message, 'UTF-8');
        foreach ($keywords as $keyword) {
            if (mb_strpos($message, mb_strtolower($keyword, 'UTF-8')) !== false) {
                return true;
            }
        }
        return false;
    }
}
