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
use App\Models\HangThanhVien;




Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
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
    Route::get('lsdiemthuong/{id}/confirm-destroy', [PointHistoryController::class, 'confirmDestroy'])->name('admin.lsdiemthuong.confirm-destroy');

// Route cho quản lý hóa đơn và thanh toán
Route::resource('hoadonvathanhtoan', HoaDonVaThanhToanController::class, ['names' => 'admin.hoadonvathanhtoan']);
Route::get('hoadonvathanhtoan/{id}/confirmDestroy', [HoaDonVaThanhToanController::class, 'confirmDestroy'])->name('admin.hoadonvathanhtoan.confirmDestroy');
Route::get('hoadonvathanhtoan/{id}/print', [HoaDonVaThanhToanController::class, 'print'])->name('admin.hoadonvathanhtoan.print');
Route::get('hoadonvathanhtoan-statistics', [HoaDonVaThanhToanController::class, 'statistics'])->name('admin.hoadonvathanhtoan.statistics');
Route::get('hoadonvathanhtoan-export-excel', [HoaDonVaThanhToanController::class, 'exportExcel'])->name('admin.hoadonvathanhtoan.exportExcel');
Route::get('hoadonvathanhtoan/{id}/create-danhgia', [HoaDonVaThanhToanController::class, 'createDanhGia'])->name('admin.hoadonvathanhtoan.createDanhGia');
Route::post('hoadonvathanhtoan/{id}/store-danhgia', [HoaDonVaThanhToanController::class, 'storeDanhGia'])->name('admin.hoadonvathanhtoan.storeDanhGia');
    
    // Routes cho đánh giá hóa đơn
    Route::get('hoadonvathanhtoan/{id}/danhgia/create', [HoaDonVaThanhToanController::class, 'createDanhGia'])->name('admin.hoadonvathanhtoan.danhgia.create');
    Route::post('hoadonvathanhtoan/{id}/danhgia', [HoaDonVaThanhToanController::class, 'storeDanhGia'])->name('admin.hoadonvathanhtoan.danhgia.store');


    // Routes cho quản lý đánh giá (DANHGIA)
    Route::resource('danhgia', DanhGiaController::class, ['names' => 'admin.danhgia', 'except' => ['create', 'store']]);
    Route::get('danhgia/{id}/confirm-destroy', [DanhGiaController::class, 'confirmDestroy'])->name('admin.danhgia.confirm-destroy');

    // Routes cho tìm kiếm
    Route::get('/search', [SearchController::class, 'searchByFunction'])->name('admin.search');
    
});

