<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use App\Models\HangThanhVien; // Thêm import cho HangThanhVien
use App\Models\PendingAccount;
use App\Mail\AdminRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('Tendangnhap', 'Matkhau');

        $account = Account::where('Tendangnhap', $credentials['Tendangnhap'])->first();

        if (!$account) {
            // Tài khoản không tồn tại
            return redirect()->back()
                ->withInput($request->only('Tendangnhap'))
                ->with('error', 'Tài khoản không tồn tại. Vui lòng kiểm tra lại hoặc đăng ký mới.');
        }

        if ($account && !Hash::check($credentials['Matkhau'], $account->Matkhau)) {
            // Mật khẩu không đúng
            return redirect()->back()
                ->withInput($request->only('Tendangnhap'))
                ->with('error', 'Mật khẩu không chính xác. Vui lòng thử lại.');
        }

        if ($account && Hash::check($credentials['Matkhau'], $account->Matkhau)) {
            Auth::login($account);

            // Log successful login
            Log::info('User logged in successfully: ' . $account->Tendangnhap . ' with RoleID: ' . $account->RoleID);
            
            // Thông báo đăng nhập thành công
            session()->flash('success', 'Đăng nhập thành công!');

            if ($account->RoleID == 1) {
                // Admin - Chuyển hướng đến dashboard
                return redirect('/admin/dashboard');
            } else {
                // Khách hàng - Chuyển hướng đến trang chủ khách hàng
                return redirect()->route('customer.home');
            }
        }

        // Trường hợp khác không xác định
        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi đăng nhập. Vui lòng thử lại sau.');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        Log::info('Register request received: ' . json_encode($request->all()));

        // Validate dữ liệu đầu vào với tên trường từ form
        $request->validate([
            'tendangnhap' => 'required|unique:ACCOUNT,Tendangnhap|min:5',
            'hoten' => 'required',
            'sdt' => 'required|min:10|max:15',
            'email' => 'required|email|unique:USER,Email',
            'diachi' => 'required',
            'ngaysinh' => 'required|date',
            'gioitinh' => 'required',
            'matkhau' => 'required|min:6|confirmed',
        ], [
            'tendangnhap.required' => 'Vui lòng nhập tên đăng nhập',
            'tendangnhap.unique' => 'Tên đăng nhập đã tồn tại',
            'tendangnhap.min' => 'Tên đăng nhập phải có ít nhất 5 ký tự',
            'hoten.required' => 'Vui lòng nhập họ tên',
            'sdt.required' => 'Vui lòng nhập số điện thoại',
            'sdt.min' => 'Số điện thoại phải có ít nhất 10 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng',
            'diachi.required' => 'Vui lòng nhập địa chỉ',
            'ngaysinh.required' => 'Vui lòng chọn ngày sinh',
            'ngaysinh.date' => 'Ngày sinh không hợp lệ',
            'gioitinh.required' => 'Vui lòng chọn giới tính',
            'matkhau.required' => 'Vui lòng nhập mật khẩu',
            'matkhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'matkhau.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        try {
            DB::beginTransaction();
            Log::info('Starting transaction for user registration');

            // Tạo tài khoản mới
            $maxMaTK = DB::table('ACCOUNT')->max('MaTK') ?? 0;
            $matk = $maxMaTK + 1;
            Log::info('Generated new MaTK: ' . $matk);

            // Tạo account mới
            $account = new Account();
            $account->MaTK = $matk;
            $account->Tendangnhap = $request->tendangnhap;
            $account->Matkhau = Hash::make($request->matkhau);
            $account->RoleID = 2; // Role mặc định là khách hàng (2)
            $account->save();
            Log::info('Account created with MaTK: ' . $matk);

            // Tạo user mới
            $maxMaUser = DB::table('USER')->max('Manguoidung') ?? 0;
            $manguoidung = $maxMaUser + 1;
            Log::info('Generated new Manguoidung: ' . $manguoidung);

            $user = new User();
            $user->Manguoidung = $manguoidung;
            $user->MaTK = $matk;
            $user->Hoten = $request->hoten;
            $user->SDT = $request->sdt;
            $user->DiaChi = $request->diachi;
            $user->Email = $request->email;
            $user->Ngaysinh = $request->ngaysinh;
            $user->Gioitinh = $request->gioitinh;
            $user->save();
            Log::info('User created with Manguoidung: ' . $manguoidung);

            // Tạo hạng thành viên mới
            $maxMaHang = DB::table('HANGTHANHVIEN')->max('Mahang') ?? 0;
            $mahang = $maxMaHang + 1;
            Log::info('Generated new Mahang: ' . $mahang);

            $hangThanhVien = new HangThanhVien();
            $hangThanhVien->Mahang = $mahang;
            $hangThanhVien->Tenhang = 'Thành viên bạc';
            $hangThanhVien->Mota = 'Hạng thành viên mặc định khi đăng ký';
            $hangThanhVien->Manguoidung = $manguoidung;
            $hangThanhVien->save();
            Log::info('Membership rank created with Mahang: ' . $mahang);

            DB::commit();
            Log::info('Transaction committed successfully');

            // Kiểm tra các lịch đặt cũ của khách vãng lai theo số điện thoại
            $guestBookings = \App\Models\DatLich::whereNull('Manguoidung')
                ->where('SDT_khach', $request->sdt)
                ->get();
                
            // Nếu tìm thấy lịch đặt trùng số điện thoại
            if ($guestBookings->count() > 0) {
                // Lưu thông tin để hiển thị sau khi đăng nhập
                session(['found_guest_bookings' => true, 'guest_bookings_count' => $guestBookings->count()]);
                Log::info('Found ' . $guestBookings->count() . ' guest bookings for phone: ' . $request->sdt);
            }

            // Đăng nhập người dùng sau khi đăng ký
            Auth::login($account);
            Log::info('User logged in after registration: ' . $account->Tendangnhap);

            // Thông báo đăng ký thành công
            toastr()->success('Đăng ký tài khoản thành công!', ['timeOut' => 3000], 'Chào mừng đến với Rosa Spa');

            // Kiểm tra nếu có lịch đặt cần liên kết, chuyển hướng đến trang xác nhận
            if (session('found_guest_bookings')) {
                return redirect()->route('customer.link-guest-bookings');
            }

            return redirect()->route('customer.home');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in registration: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Thông báo lỗi khi đăng ký
            toastr()->error('Đã xảy ra lỗi: ' . $e->getMessage(), ['timeOut' => 5000], 'Đăng ký không thành công');
            
            return redirect()->back()->withInput();
        }
    }

    public function confirmAdmin($token)
    {
        $pendingAccount = PendingAccount::where('token', $token)->first();

        if (!$pendingAccount) {
            toastr()->error('Liên kết xác nhận không hợp lệ hoặc đã hết hạn.', ['timeOut' => 3000], 'Lỗi xác nhận');
            return redirect()->route('login');
        }

        $lastAccount = Account::orderBy('MaTK', 'desc')->first();
        $newMaTK = $lastAccount ? $lastAccount->MaTK + 1 : 1;
        $newManguoidung = $newMaTK;

        $account = Account::create([
            'MaTK' => $newMaTK,
            'RoleID' => $pendingAccount->RoleID,
            'Tendangnhap' => $pendingAccount->Tendangnhap,
            'Matkhau' => $pendingAccount->Matkhau,
        ]);

        User::create([
            'Manguoidung' => $newManguoidung,
            'MaTK' => $newMaTK,
            'Hoten' => $pendingAccount->Hoten,
            'SDT' => $pendingAccount->SDT,
            'DiaChi' => $pendingAccount->DiaChi,
            'Email' => $pendingAccount->Email,
            'Ngaysinh' => $pendingAccount->Ngaysinh,
            'Gioitinh' => $pendingAccount->Gioitinh,
        ]);

        $pendingAccount->delete();

        toastr()->success('Tài khoản Admin đã được xác nhận và tạo thành công.', ['timeOut' => 3000], 'Xác nhận thành công');
        return redirect()->route('login');
    }

    public function logout()
    {
        // Store the user's role before logging out
        $wasAdmin = Auth::user() && Auth::user()->RoleID == 1;
        
        Auth::logout();
        
        // Thông báo đăng xuất thành công - sửa lại cú pháp đúng
        session()->flash('info', 'Đã đăng xuất thành công!');
        
        // Redirect all users to the welcome page
        return redirect()->route('welcome');
    }
}