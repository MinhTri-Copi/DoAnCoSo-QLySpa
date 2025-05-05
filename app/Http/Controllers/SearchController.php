<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DatLich;
use App\Models\DichVu;
use App\Models\Phong;
use App\Models\HoaDon;
use App\Models\DanhGia;
use App\Models\Advertisement;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchByFunction(Request $request)
    {
        $query = strtolower(trim($request->input('query')));

        if (!$query) {
            return redirect()->back()->with('error', 'Vui lòng nhập từ khóa tìm kiếm!');
        }

        // Ánh xạ từ khóa với các route chức năng - thêm nhiều từ khóa tương đương cho mỗi chức năng
        $functionRoutes = [
            // Đặt lịch
            'đặt lịch' => 'admin.datlich.index',
            'lịch hẹn' => 'admin.datlich.index',
            'đặt hẹn' => 'admin.datlich.index',
            'booking' => 'admin.datlich.index',
            'lịch' => 'admin.datlich.index',
            'hẹn' => 'admin.datlich.index',
            'dat lich' => 'admin.datlich.index',
            'lich hen' => 'admin.datlich.index',
            'đat lich' => 'admin.datlich.index',
            'đat lịch' => 'admin.datlich.index',
            'dặt lịch' => 'admin.datlich.index',
            
            // Khách hàng
            'khách hàng' => 'admin.customers.index',
            'khách' => 'admin.customers.index',
            'người dùng' => 'admin.customers.index',
            'user' => 'admin.customers.index',
            'customer' => 'admin.customers.index',
            'khach hang' => 'admin.customers.index',
            'khach' => 'admin.customers.index',
            'nguoi dung' => 'admin.customers.index',
            'nguời dùng' => 'admin.customers.index',
            'khach hang' => 'admin.customers.index',
            
            // Dịch vụ
            'dịch vụ' => 'admin.dichvu.index',
            'service' => 'admin.dichvu.index',
            'dich vu' => 'admin.dichvu.index',
            'dich vụ' => 'admin.dichvu.index',
            'dịch vu' => 'admin.dichvu.index',
            
            // Phòng
            'phòng' => 'admin.phong.index',
            'room' => 'admin.phong.index',
            'phong' => 'admin.phong.index',
            
            // Trạng thái phòng
            'trạng thái phòng' => 'admin.trangthaiphong.index',
            'trạng thái' => 'admin.trangthaiphong.index',
            'tình trạng phòng' => 'admin.trangthaiphong.index',
            'trang thai phong' => 'admin.trangthaiphong.index',
            'trang thai' => 'admin.trangthaiphong.index',
            'trạng thai phòng' => 'admin.trangthaiphong.index',
            'trangthaiphong' => 'admin.trangthaiphong.index',
            
            // Hóa đơn
            'hóa đơn' => 'admin.hoadonvathanhtoan.index',
            'thanh toán' => 'admin.hoadonvathanhtoan.index',
            'bill' => 'admin.hoadonvathanhtoan.index',
            'invoice' => 'admin.hoadonvathanhtoan.index',
            'payment' => 'admin.hoadonvathanhtoan.index',
            'hoa don' => 'admin.hoadonvathanhtoan.index',
            'thanh toan' => 'admin.hoadonvathanhtoan.index',
            'hoadon' => 'admin.hoadonvathanhtoan.index',
            'hóa don' => 'admin.hoadonvathanhtoan.index',
            'hoa đơn' => 'admin.hoadonvathanhtoan.index',
            
            // Đánh giá
            'đánh giá' => 'admin.danhgia.index',
            'review' => 'admin.danhgia.index',
            'feedback' => 'admin.danhgia.index',
            'phản hồi' => 'admin.danhgia.index',
            'danh gia' => 'admin.danhgia.index',
            'phan hoi' => 'admin.danhgia.index',
            'đanh gia' => 'admin.danhgia.index',
            'danhgia' => 'admin.danhgia.index',
            
            // Quảng cáo
            'quảng cáo' => 'admin.advertisements.index',
            'banner' => 'admin.advertisements.index',
            'advertisement' => 'admin.advertisements.index',
            'ad' => 'admin.advertisements.index',
            'quang cao' => 'admin.advertisements.index',
            'quẩng cáo' => 'admin.advertisements.index',
            'quảng cao' => 'admin.advertisements.index',
            
            // Tài khoản
            'tài khoản' => 'admin.accounts.index',
            'account' => 'admin.accounts.index',
            'tai khoan' => 'admin.accounts.index',
            'tài khoan' => 'admin.accounts.index',
            'tai khoản' => 'admin.accounts.index',
            
            // Vai trò
            'vai trò' => 'admin.roles.index',
            'role' => 'admin.roles.index',
            'quyền' => 'admin.roles.index',
            'permission' => 'admin.roles.index',
            'vai tro' => 'admin.roles.index',
            'quyen' => 'admin.roles.index',
            
            // Hạng thành viên
            'hạng thành viên' => 'admin.membership_ranks.index',
            'member' => 'admin.membership_ranks.index',
            'hạng' => 'admin.membership_ranks.index',
            'membership' => 'admin.membership_ranks.index',
            'rank' => 'admin.membership_ranks.index',
            'hang thanh vien' => 'admin.membership_ranks.index',
            'hang' => 'admin.membership_ranks.index',
            'hạng thanh vien' => 'admin.membership_ranks.index',
            'hang thành viên' => 'admin.membership_ranks.index',
            
            // Hỗ trợ
            'hỗ trợ' => 'admin.phieuhotro.index',
            'phiếu hỗ trợ' => 'admin.phieuhotro.index',
            'support' => 'admin.phieuhotro.index',
            'ticket' => 'admin.phieuhotro.index',
            'ho tro' => 'admin.phieuhotro.index',
            'phieu ho tro' => 'admin.phieuhotro.index',
            'hổ trợ' => 'admin.phieuhotro.index',
            'phiếu ho trợ' => 'admin.phieuhotro.index',
            'phieu hỗ trợ' => 'admin.phieuhotro.index',
            'phieuhotro' => 'admin.phieuhotro.index',
            
            // Phương thức hỗ trợ
            'phương thức hỗ trợ' => 'admin.pthotro.index',
            'pt hỗ trợ' => 'admin.pthotro.index',
            'phương thức' => 'admin.pthotro.index',
            'phuong thuc ho tro' => 'admin.pthotro.index',
            'phuong thuc' => 'admin.pthotro.index',
            'pt ho tro' => 'admin.pthotro.index',
            'pthotro' => 'admin.pthotro.index',
            
            // Điểm thưởng
            'điểm thưởng' => 'admin.lsdiemthuong.index',
            'lịch sử điểm' => 'admin.lsdiemthuong.index',
            'point' => 'admin.lsdiemthuong.index',
            'loyalty' => 'admin.lsdiemthuong.index',
            'diem thuong' => 'admin.lsdiemthuong.index',
            'lich su diem' => 'admin.lsdiemthuong.index',
            'điẻm thưởng' => 'admin.lsdiemthuong.index',
            'điểm thuong' => 'admin.lsdiemthuong.index',
            
            // Phương thức thanh toán
            'phương thức thanh toán' => 'admin.phuongthuc.index',
            'pt thanh toán' => 'admin.phuongthuc.index',
            'payment method' => 'admin.phuongthuc.index',
            'phuong thuc thanh toan' => 'admin.phuongthuc.index',
            'pt thanh toan' => 'admin.phuongthuc.index',
            'phương thức thanh toan' => 'admin.phuongthuc.index',
            'phuong thức thanh toán' => 'admin.phuongthuc.index',
            'phuongthuc' => 'admin.phuongthuc.index',
        ];

        // Tìm kiếm từ khóa chính xác
        if (isset($functionRoutes[$query])) {
            return redirect()->route($functionRoutes[$query]);
        }

        // Tìm kiếm từ khóa trong chuỗi
        foreach ($functionRoutes as $function => $route) {
            if (str_contains($query, $function)) {
                return redirect()->route($route);
            }
        }

        // Tìm kiếm tương đối (nếu từ khóa là một phần của key trong mảng)
        foreach ($functionRoutes as $function => $route) {
            if (str_contains($function, $query)) {
                return redirect()->route($route);
            }
        }

        // Xử lý lỗi chính tả bằng khoảng cách Levenshtein
        $closestMatch = null;
        $minDistance = PHP_INT_MAX;
        $threshold = 2; // Ngưỡng cho phép lỗi chính tả (càng thấp càng nghiêm ngặt)
        
        foreach (array_keys($functionRoutes) as $function) {
            // Tính khoảng cách Levenshtein
            $distance = levenshtein($query, $function);
            
            // Tìm từ khóa gần nhất
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $closestMatch = $function;
            }
        }
        
        // Nếu có từ khóa gần giống và khoảng cách không quá xa
        if ($closestMatch && $minDistance <= $threshold) {
            // Thêm thông báo gợi ý từ khóa gần đúng
            $route = $functionRoutes[$closestMatch];
            return redirect()->route($route)->with('suggestion', "Đã tìm kiếm gần đúng cho: '{$query}' → '{$closestMatch}'");
        }
        
        // Nếu từ khóa dài, thử phân tích và tìm kiếm phần tương đối
        if (strlen($query) > 8) {
            foreach (array_keys($functionRoutes) as $function) {
                $similarityPercentage = similar_text($query, $function, $percent);
                if ($percent > 70) { // Độ tương đồng trên 70%
                    $route = $functionRoutes[$function];
                    return redirect()->route($route)->with('suggestion', "Đã tìm kiếm gần đúng cho: '{$query}' → '{$function}'");
                }
            }
        }

        // Nếu không tìm thấy kết quả phù hợp, gợi ý các chức năng phổ biến
        // Danh sách chức năng phổ biến để gợi ý
        $popularFunctions = [
            'đặt lịch',
            'khách hàng',
            'dịch vụ',
            'phòng',
            'hóa đơn',
            'đánh giá',
            'hỗ trợ',
            'tài khoản'
        ];
        
        // Tìm 3 chức năng gần với từ khóa nhất
        $suggestions = [];
        foreach ($popularFunctions as $function) {
            $distance = levenshtein($query, $function);
            $suggestions[$function] = $distance;
        }
        
        // Sắp xếp theo khoảng cách tăng dần (gần nhất lên đầu)
        asort($suggestions);
        
        // Lấy 3 gợi ý đầu tiên
        $topSuggestions = array_slice(array_keys($suggestions), 0, 3);
        
        return redirect()->back()->with([
            'error' => 'Không tìm thấy chức năng phù hợp với từ khóa: ' . $query,
            'functionSuggestions' => $topSuggestions
        ]);
    }

    private function performDetailedSearch($query)
    {
        $results = [];

        // Tìm kiếm khách hàng
        $customers = User::where('Hoten', 'like', "%{$query}%")
            ->orWhere('Email', 'like', "%{$query}%")
            ->orWhere('SDT', 'like', "%{$query}%")
            ->orWhere('DiaChi', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        if ($customers->count() > 0) {
            $results['customers'] = $customers;
        }

        // Tìm kiếm đặt lịch
        $appointments = DatLich::where('TenKH', 'like', "%{$query}%")
            ->orWhere('SDT', 'like', "%{$query}%")
            ->orWhere('Email', 'like', "%{$query}%")
            ->orWhere('GhiChu', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        if ($appointments->count() > 0) {
            $results['appointments'] = $appointments;
        }

        // Tìm kiếm dịch vụ
        $services = DichVu::where('TenDV', 'like', "%{$query}%")
            ->orWhere('MoTa', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        if ($services->count() > 0) {
            $results['services'] = $services;
        }

        // Tìm kiếm phòng
        $rooms = Phong::where('TenPhong', 'like', "%{$query}%")
            ->orWhere('MoTa', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        if ($rooms->count() > 0) {
            $results['rooms'] = $rooms;
        }

        // Tìm kiếm hóa đơn
        $invoices = HoaDon::where('MaHD', 'like', "%{$query}%")
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('Hoten', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        if ($invoices->count() > 0) {
            $results['invoices'] = $invoices;
        }

        // Tìm kiếm đánh giá
        $reviews = DanhGia::where('NoiDung', 'like', "%{$query}%")
            ->orWhereHas('user', function($q) use ($query) {
                $q->where('Hoten', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();

        if ($reviews->count() > 0) {
            $results['reviews'] = $reviews;
        }

        return $results;
    }
}