<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdStatusController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\MembershipRankController;
use App\Http\Controllers\PointHistoryController;
use App\Http\Controllers\TrangThaiController;
use App\Http\Controllers\PthotroController;
use App\Http\Controllers\PhieuHotroController;
use App\Http\Controllers\DichVuController;
use App\Http\Controllers\DatLichController;
use App\Http\Controllers\TrangThaiPhongController;
use App\Http\Controllers\PhongController;
use App\Http\Controllers\PhuongThucController;
use App\Http\Controllers\HoaDonVaThanhToanController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DatLichDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\HangThanhVien;





Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Thay đổi route dashboard để sử dụng controller
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
// API Routes cho Dashboard
Route::get('/api/dashboard/revenue', [DashboardController::class, 'getRevenueData'])->middleware('auth');
Route::get('/api/dashboard/services', [DashboardController::class, 'getServiceStats'])->middleware('auth');

// Thêm route cho admin dashboard
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/revenue-data', [DashboardController::class, 'getRevenueData'])->name('admin.dashboard.revenue-data');
    Route::get('/dashboard/service-stats', [DashboardController::class, 'getServiceStats'])->name('admin.dashboard.service-stats');
    Route::get('/dashboard/filtered-stats', [DashboardController::class, 'getFilteredStats'])->name('admin.dashboard.filtered-stats');
});

Route::get('/customer/home', function () {
    return view('customer.home');
})->middleware('auth')->name('customer.home');

// Customer routes
Route::prefix('customer')->middleware(['auth'])->name('customer.')->group(function () {
    // Home and Dashboard
    Route::get('/home', [App\Http\Controllers\Customer\HomeController::class, 'index'])->name('home');
    Route::get('/profile', [App\Http\Controllers\Customer\HomeController::class, 'profile'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Customer\HomeController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password', [App\Http\Controllers\Customer\HomeController::class, 'updatePassword'])->name('password.update');

    // Services
    Route::get('/dich-vu', [App\Http\Controllers\Customer\DichVuController::class, 'index'])->name('dichvu.index');
    Route::get('/dich-vu/{id}', [App\Http\Controllers\Customer\DichVuController::class, 'show'])->name('dichvu.show');
    Route::get('/dich-vu/api/featured', [App\Http\Controllers\Customer\DichVuController::class, 'getFeatured'])->name('dichvu.featured');
    Route::get('/dich-vu/api/search', [App\Http\Controllers\Customer\DichVuController::class, 'search'])->name('dichvu.search');
    Route::get('/dich-vu/api/check-availability', [App\Http\Controllers\Customer\DichVuController::class, 'checkAvailability'])->name('dichvu.availability');

    // Bookings
    Route::get('/dat-lich', [App\Http\Controllers\Customer\DatLichController::class, 'create'])->name('datlich.create');
    Route::post('/dat-lich', [App\Http\Controllers\Customer\DatLichController::class, 'store'])->name('datlich.store');
    Route::get('/check-availability', [App\Http\Controllers\Customer\DatLichController::class, 'checkAvailability'])->name('datlich.checkAvailability');
    
    // Booking History
    Route::get('/lich-su-dat-lich', [App\Http\Controllers\Customer\LichSuDatLichController::class, 'index'])->name('lichsudatlich.index');
    Route::get('/lich-su-dat-lich/{id}', [App\Http\Controllers\Customer\LichSuDatLichController::class, 'show'])->name('lichsudatlich.show');
    Route::post('/lich-su-dat-lich/{id}/cancel', [App\Http\Controllers\Customer\LichSuDatLichController::class, 'cancel'])->name('lichsudatlich.cancel');
    Route::post('/lich-su-dat-lich/{id}/reschedule', [App\Http\Controllers\Customer\LichSuDatLichController::class, 'reschedule'])->name('lichsudatlich.reschedule');
    
    // Reviews
    Route::get('/danh-gia', [App\Http\Controllers\Customer\DanhGiaController::class, 'index'])->name('danhgia.index');
    Route::get('/danh-gia/create', [App\Http\Controllers\Customer\DanhGiaController::class, 'create'])->name('danhgia.create');
    Route::post('/danh-gia', [App\Http\Controllers\Customer\DanhGiaController::class, 'store'])->name('danhgia.store');
    Route::get('/danh-gia/{id}', [App\Http\Controllers\Customer\DanhGiaController::class, 'show'])->name('danhgia.show');
    Route::get('/danh-gia/{id}/edit', [App\Http\Controllers\Customer\DanhGiaController::class, 'edit'])->name('danhgia.edit');
    Route::put('/danh-gia/{id}', [App\Http\Controllers\Customer\DanhGiaController::class, 'update'])->name('danhgia.update');
    
    // Membership
    Route::get('/hang-thanh-vien', [App\Http\Controllers\Customer\HangThanhVienController::class, 'index'])->name('thanhvien.index');
    Route::get('/hang-thanh-vien/lich-su-diem', [App\Http\Controllers\Customer\HangThanhVienController::class, 'pointHistory'])->name('thanhvien.pointHistory');
    Route::get('/hang-thanh-vien/cac-hang', [App\Http\Controllers\Customer\HangThanhVienController::class, 'allRanks'])->name('thanhvien.allRanks');
    
    // Invoices
    Route::get('/hoa-don', [App\Http\Controllers\Customer\HoaDonController::class, 'index'])->name('hoadon.index');
    Route::get('/hoa-don/{id}', [App\Http\Controllers\Customer\HoaDonController::class, 'show'])->name('hoadon.show');
    Route::get('/hoa-don/{id}/pdf', [App\Http\Controllers\Customer\HoaDonController::class, 'downloadPdf'])->name('hoadon.pdf');
    Route::get('/hoa-don/{id}/thanh-toan', [App\Http\Controllers\Customer\HoaDonController::class, 'showPayment'])->name('hoadon.showPayment');
    Route::post('/hoa-don/{id}/thanh-toan', [App\Http\Controllers\Customer\HoaDonController::class, 'processPayment'])->name('hoadon.processPayment');
    
    // Advertisements
    Route::get('/quang-cao', [App\Http\Controllers\Customer\QuangCaoController::class, 'index'])->name('quangcao.index');
    Route::get('/quang-cao/{id}', [App\Http\Controllers\Customer\QuangCaoController::class, 'show'])->name('quangcao.show');
    Route::get('/quang-cao-noi-bat', [App\Http\Controllers\Customer\QuangCaoController::class, 'getFeaturedAds'])->name('quangcao.featured');
    Route::get('/khuyen-mai', [App\Http\Controllers\Customer\QuangCaoController::class, 'getPromotionAds'])->name('quangcao.promotions');
    Route::get('/dich-vu-moi', [App\Http\Controllers\Customer\QuangCaoController::class, 'getNewServiceAds'])->name('quangcao.newservices');
    Route::get('/su-kien', [App\Http\Controllers\Customer\QuangCaoController::class, 'getEventAds'])->name('quangcao.events');
    Route::get('/lien-he', [App\Http\Controllers\Customer\LienHeController::class, 'index'])->name('lienhe');

    // Phiếu hỗ trợ
    Route::get('/phieu-ho-tro', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'index'])->name('phieuhotro.index');
    Route::get('/phieu-ho-tro/create', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'create'])->name('phieuhotro.create');
    Route::post('/phieu-ho-tro', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'store'])->name('phieuhotro.store');
    Route::get('/phieu-ho-tro/{id}', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'show'])->name('phieuhotro.show');
    Route::get('/phieu-ho-tro/{id}/edit', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'edit'])->name('phieuhotro.edit');
    Route::put('/phieu-ho-tro/{id}', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'update'])->name('phieuhotro.update');
    Route::post('/phieu-ho-tro/{id}/cancel', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'cancel'])->name('phieuhotro.cancel');
    Route::post('/phieu-ho-tro/{id}/feedback', [App\Http\Controllers\Customer\PhieuHoTroController::class, 'sendFeedback'])->name('phieuhotro.feedback');
});

Route::prefix('admin')->middleware(['auth', 'role:1'])->group(function () {
    // Routes cho quản lý trạng thái quảng cáo
    Route::resource('ad-statuses', AdStatusController::class, ['names' => 'admin.ad-statuses']);
    Route::get('ad-statuses/{id}/confirm-destroy', [AdStatusController::class, 'confirmDestroy'])->name('admin.ad-statuses.confirm-destroy');
    Route::get('ad-statuses-statistics', [AdStatusController::class, 'statistics'])->name('admin.ad-statuses.statistics');

    Route::resource('customers', CustomerController::class, ['names' => 'admin.customers']);
    Route::get('customers/{id}/confirm-destroy', [CustomerController::class, 'confirmDestroy'])->name('admin.customers.confirmDestroy');
    Route::post('customers/{id}/add-points', [CustomerController::class, 'addPoints'])->name('admin.customers.addPoints');

    // Routes cho quản lý quảng cáo
    Route::resource('advertisements', AdvertisementController::class, ['names' => 'admin.advertisements']);
    Route::get('advertisements/{id}/confirm-destroy', [AdvertisementController::class, 'confirmDestroy'])->name('admin.advertisements.confirm-destroy');
    Route::resource('roles', RoleController::class, ['names' => 'admin.roles']);
    Route::get('roles/{id}/confirm-destroy', [RoleController::class, 'confirmDestroy'])->name('admin.roles.confirmDestroy');
    Route::post('roles/bulk-action', [RoleController::class, 'bulkAction'])->name('admin.roles.bulkAction');
    
    // API Routes for role statistics
    Route::get('roles/{id}/stats/department', [RoleController::class, 'getDepartmentDistribution'])->name('admin.roles.stats.department');
    Route::get('roles/{id}/stats/permissions/{period?}', [RoleController::class, 'getPermissionsComparison'])->name('admin.roles.stats.permissions');
    Route::get('roles/{id}/stats/performance/{period?}', [RoleController::class, 'getPerformanceMetrics'])->name('admin.roles.stats.performance');
    Route::get('roles/{id}/stats/topstaff/{period?}', [RoleController::class, 'getTopStaff'])->name('admin.roles.stats.topstaff');

    // Routes cho quản lý tài khoản
    Route::resource('accounts', AccountController::class, ['names' => 'admin.accounts']);
    Route::get('accounts/{id}/confirm-destroy', [AccountController::class, 'confirmDestroy'])->name('admin.accounts.confirm-destroy');

    // Routes cho quản lý hạng thành viên
    Route::resource('membership_ranks', MembershipRankController::class, ['names' => 'admin.membership_ranks']);
    Route::get('/membership_ranks/{id}/confirm-destroy', [MembershipRankController::class, 'confirmDestroy'])->name('admin.membership_ranks.confirm-destroy');
    Route::get('/membership_ranks/update-all/ranks', [MembershipRankController::class, 'updateAllRanks'])->name('admin.membership_ranks.update-all');
    Route::get('/membership_ranks/cleanup/duplicates', [MembershipRankController::class, 'cleanupDuplicates'])->name('admin.membership_ranks.cleanup-duplicates');

    //Routes cho quản lý lịch sử điểm thưởng
    Route::post('/point_histories', [PointHistoryController::class, 'store'])->name('admin.point_histories.store');

    //Routes cho quản lý trạng thái
    Route::resource('trangthai', TrangThaiController::class, ['names' => 'admin.trangthai']);
    Route::get('trangthai/{id}/confirm-destroy', [TrangThaiController::class, 'confirmDestroy'])->name('admin.trangthai.confirm-destroy');

   // Route cho quản lý phiếu hỗ trợ (PHIEUHOTRO)
   Route::resource('phieuhotro', PhieuHoTroController::class, ['names' => 'admin.phieuhotro']);
   Route::get('phieuhotro/{id}/confirm-destroy', [PhieuHoTroController::class, 'confirmDestroy'])->name('admin.phieuhotro.confirm-destroy');

   // Route cho quản lý phương thức hỗ trợ (PTHOTRO)
   Route::resource('pthotro', PthotroController::class, ['names' => 'admin.pthotro']);
   Route::get('pthotro/{id}/confirm-destroy', [PthotroController::class, 'confirmDestroy'])->name('admin.pthotro.confirm-destroy');

   // Route cho quản lý dịch vụ (DICHVU)
   Route::resource('dichvu', DichVuController::class, ['names' => 'admin.dichvu']);
   Route::get('dichvu/{id}/confirm-destroy', [DichVuController::class, 'confirmDestroy'])->name('admin.dichvu.confirm-destroy');
   Route::put('dichvu/{id}/update-status', [DichVuController::class, 'updateStatus'])->name('admin.dichvu.update-status');
   Route::get('dichvu/analytics/data', [DichVuController::class, 'analytics'])->name('admin.dichvu.analytics');
   Route::get('dichvu/export/csv', [DichVuController::class, 'export'])->name('admin.dichvu.export');
   Route::post('dichvu/{id}/toggle-featured', [DichVuController::class, 'toggleFeatured'])->name('admin.dichvu.toggle-featured');
   Route::get('api/services', [DichVuController::class, 'apiServices'])->name('api.services');

   // Route cho quản lý đặt lịch (DATLICH)
Route::resource('datlich', DatLichController::class, ['names' => 'admin.datlich']);
Route::get('datlich/{id}/confirmDestroy', [DatLichController::class, 'confirmDestroy'])->name('admin.datlich.confirmDestroy');
Route::get('datlich-statistics', [DatLichController::class, 'statistics'])->name('admin.datlich.statistics');
Route::get('datlich/check-availability', [DatLichController::class, 'checkAvailability'])->name('admin.datlich.checkAvailability');
Route::post('datlich/{id}/update-status', [DatLichController::class, 'updateStatus'])->name('admin.datlich.updateStatus');
Route::get('datlich-dashboard', [DatLichDashboardController::class, 'index'])->name('admin.datlich.dashboard');

    // Route cho quản lý trạng thái phòng (TRANGTHAIPHONG)
    Route::resource('trangthaiphong', TrangThaiPhongController::class, ['names' => 'admin.trangthaiphong']);
    Route::get('trangthaiphong/{id}/confirm-destroy', [TrangThaiPhongController::class, 'confirmDestroy'])->name('admin.trangthaiphong.confirm-destroy');

    // Route cho quản lý phòng (PHONG)
    Route::resource('phong', PhongController::class, ['names' => 'admin.phong']);
    Route::get('phong/{id}/confirm-destroy', [PhongController::class, 'confirmDestroy'])->name('admin.phong.confirm-destroy');

    // Route cho quản lý phương thức (PHUONGTHUC)
    Route::resource('phuongthuc', PhuongThucController::class, ['names' => 'admin.phuongthuc']);
    Route::get('phuongthuc/{id}/confirm-destroy', [PhuongThucController::class, 'confirmDestroy'])->name('admin.phuongthuc.confirmDestroy');

    // Routes cho quản lý lịch sử điểm thưởng (LSDIEMTHUONG)
    Route::resource('lsdiemthuong', PointHistoryController::class, ['names' => 'admin.lsdiemthuong']);
    Route::get('lsdiemthuong/{id}/confirm-destroy', [PointHistoryController::class, 'confirmDestroy'])->name('admin.lsdiemthuong.confirmDestroy');
    Route::get('lsdiemthuong-statistics', [PointHistoryController::class, 'statistics'])->name('admin.lsdiemthuong.statistics');
    Route::get('lsdiemthuong-export-excel', [PointHistoryController::class, 'exportExcel'])->name('admin.lsdiemthuong.exportExcel');
    Route::get('lsdiemthuong/get-user-details/{id}', [PointHistoryController::class, 'getUserDetails']);
    Route::get('lsdiemthuong/get-invoice-details/{id}', [PointHistoryController::class, 'getInvoiceDetails']);

// Route cho quản lý hóa đơn và thanh toán
Route::resource('hoadonvathanhtoan', HoaDonVaThanhToanController::class, ['names' => 'admin.hoadonvathanhtoan']);
Route::get('hoadonvathanhtoan/{id}/confirmDestroy', [HoaDonVaThanhToanController::class, 'confirmDestroy'])->name('admin.hoadonvathanhtoan.confirmDestroy');
Route::get('hoadonvathanhtoan/{id}/print', [HoaDonVaThanhToanController::class, 'print'])->name('admin.hoadonvathanhtoan.print');
Route::get('hoadonvathanhtoan-statistics', [HoaDonVaThanhToanController::class, 'statistics'])->name('admin.hoadonvathanhtoan.statistics');
Route::get('hoadonvathanhtoan-export-excel', [HoaDonVaThanhToanController::class, 'exportExcel'])->name('admin.hoadonvathanhtoan.exportExcel');
Route::get('hoadonvathanhtoan/{id}/create-danhgia', [HoaDonVaThanhToanController::class, 'createDanhGia'])->name('admin.hoadonvathanhtoan.createDanhGia');
Route::post('hoadonvathanhtoan/{id}/store-danhgia', [HoaDonVaThanhToanController::class, 'storeDanhGia'])->name('admin.hoadonvathanhtoan.storeDanhGia');
Route::post('hoadonvathanhtoan/update-status', [HoaDonVaThanhToanController::class, 'updateStatus'])->name('admin.hoadonvathanhtoan.updateStatus');
    
    // Routes cho đánh giá hóa đơn
    Route::get('hoadonvathanhtoan/{id}/danhgia/create', [HoaDonVaThanhToanController::class, 'createDanhGia'])->name('admin.hoadonvathanhtoan.danhgia.create');
    Route::post('hoadonvathanhtoan/{id}/danhgia', [HoaDonVaThanhToanController::class, 'storeDanhGia'])->name('admin.hoadonvathanhtoan.danhgia.store');

    // Danh sách đánh giá
    Route::get('/danhgia', [DanhGiaController::class, 'index'])->name('admin.danhgia.index');
    
    // Tạo đánh giá mới
    Route::get('/danhgia/create', [DanhGiaController::class, 'create'])->name('admin.danhgia.create');
    Route::post('/danhgia', [DanhGiaController::class, 'store'])->name('admin.danhgia.store');
    
    // Xem chi tiết đánh giá
    Route::get('/danhgia/{id}', [DanhGiaController::class, 'show'])->name('admin.danhgia.show');
    
    // Chỉnh sửa đánh giá
    Route::get('/danhgia/{id}/edit', [DanhGiaController::class, 'edit'])->name('admin.danhgia.edit');
    Route::put('/danhgia/{id}', [DanhGiaController::class, 'update'])->name('admin.danhgia.update');
    
    // Xóa đánh giá
    Route::get('/danhgia/{id}/destroy', [DanhGiaController::class, 'confirmDestroy'])->name('admin.danhgia.confirmDestroy');
    Route::delete('/danhgia/{id}', [DanhGiaController::class, 'destroy'])->name('admin.danhgia.destroy');
    
    // Phản hồi đánh giá
    Route::post('/danhgia/{id}/reply', [DanhGiaController::class, 'reply'])->name('admin.danhgia.reply');
    
    // Xuất dữ liệu đánh giá
    Route::get('/danhgia-export', [DanhGiaController::class, 'export'])->name('admin.danhgia.export');
    
    // API lấy thống kê
    Route::get('/danhgia-statistics', [DanhGiaController::class, 'getStatistics'])->name('admin.danhgia.statistics');
    
    // API lấy hóa đơn theo người dùng
    Route::get('/danhgia/get-user-invoices/{userId}', [DanhGiaController::class, 'getUserInvoices'])->name('admin.danhgia.getUserInvoices');
});

// API để lấy thông tin đặt lịch cho hóa đơn
Route::get('/admin/api/datlich/{id}', [App\Http\Controllers\HoaDonVaThanhToanController::class, 'getBookingDetails']);

// Routes cho profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('admin.profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('admin.profile.change-password');
    Route::post('/profile/change-email', [ProfileController::class, 'changeEmail'])->name('admin.profile.change-email');
});

// Routes cho tìm kiếm
Route::get('/search', [SearchController::class, 'searchByFunction'])->name('admin.search');

// Thêm route để hiển thị hình ảnh quảng cáo
Route::get('/ad-images/{filename}', [AdvertisementController::class, 'showImage'])->name('advertisement.image');

// Route để hiển thị bất kỳ hình ảnh nào từ storage
Route::get('/storage-image/{path}', [AdvertisementController::class, 'showAnyImage'])->name('storage.image')->where('path', '.*');

// Route để debug hình ảnh trong storage
Route::get('/debug-images', function() {
    $disk = \Illuminate\Support\Facades\Storage::disk('public');
    $files = $disk->allFiles();
    $images = [];
    
    foreach ($files as $file) {
        if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif'])) {
            $images[] = [
                'path' => $file,
                'url' => route('storage.image', ['path' => $file]),
                'size' => $disk->size($file),
                'last_modified' => date('Y-m-d H:i:s', $disk->lastModified($file))
            ];
        }
    }
    
    return view('debug.images', ['images' => $images]);
});

