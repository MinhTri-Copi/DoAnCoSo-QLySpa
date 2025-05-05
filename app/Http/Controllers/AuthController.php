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

        if ($account && Hash::check($credentials['Matkhau'], $account->Matkhau)) {
            Auth::login($account);

            if ($account->RoleID == 1) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('customer.home');
            }
        }

        return redirect()->back()->with('error', 'Tên đăng nhập hoặc mật khẩu không đúng.');
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
        \Log::info('Register request received: ' . json_encode($request->all()));

        $request->validate([
            'Tendangnhap' => 'required|string|max:100|unique:ACCOUNT,Tendangnhap',
            'Matkhau' => 'required|string|max:100',
            'Hoten' => 'required|string|max:100',
            'SDT' => 'nullable|string|max:15',
            'DiaChi' => 'nullable|string|max:200',
            'Email' => 'required|email|max:100|unique:USER,Email',
            'Ngaysinh' => 'nullable|date',
            'Gioitinh' => 'nullable|string|max:10',
            'RoleID' => 'required|in:1,2',
            'admin_code' => 'required_if:RoleID,1',
        ]);

        // Kiểm tra mật mã Admin nếu RoleID = 1
        if ($request->RoleID == 1) {
            $correctAdminCode = env('ADMIN_CODE', 'admin123');
            if ($request->admin_code !== $correctAdminCode) {
                \Log::error('Admin code incorrect: ' . $request->admin_code);
                return redirect()->back()->withErrors(['admin_code' => 'Mật mã Admin không đúng.'])->withInput();
            }

            try {
                $pendingAccount = PendingAccount::create([
                    'Tendangnhap' => $request->Tendangnhap,
                    'Matkhau' => Hash::make($request->Matkhau),
                    'RoleID' => $request->RoleID,
                    'Hoten' => $request->Hoten,
                    'SDT' => $request->SDT,
                    'DiaChi' => $request->DiaChi,
                    'Email' => $request->Email,
                    'Ngaysinh' => $request->Ngaysinh,
                    'Gioitinh' => $request->Gioitinh,
                    'token' => Str::random(60),
                ]);

                \Log::info('Pending account created: ' . $pendingAccount->Tendangnhap);
                return redirect()->route('login')->with('success', 'Tài khoản Admin đã được gửi để duyệt. Vui lòng chờ xác nhận.');
            } catch (\Exception $e) {
                \Log::error('Error creating pending account: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Đăng ký Admin thất bại: ' . $e->getMessage())->withInput();
            }
        }

        // Nếu không phải Admin, tạo tài khoản ngay
        \DB::beginTransaction();
        try {
            $lastAccount = Account::orderBy('MaTK', 'desc')->first();
            $newMaTK = $lastAccount ? $lastAccount->MaTK + 1 : 1;
            $newManguoidung = $newMaTK;

            $account = Account::create([
                'MaTK' => $newMaTK,
                'RoleID' => $request->RoleID,
                'Tendangnhap' => $request->Tendangnhap,
                'Matkhau' => Hash::make($request->Matkhau),
            ]);

            \Log::info('Account created: MaTK ' . $account->MaTK);

            $user = User::create([
                'Manguoidung' => $newManguoidung,
                'MaTK' => $newMaTK,
                'Hoten' => $request->Hoten,
                'SDT' => $request->SDT,
                'DiaChi' => $request->DiaChi,
                'Email' => $request->Email,
                'Ngaysinh' => $request->Ngaysinh,
                'Gioitinh' => $request->Gioitinh,
            ]);

            \Log::info('User created: Manguoidung ' . $user->Manguoidung . ', MaTK: ' . $user->MaTK);

            // Tạo hạng thành viên
            try {
                $maxMahang = HangThanhVien::max('Mahang') ?? 0;
                $newMahang = $maxMahang + 1;

                \Log::info('Attempting to create membership rank with Mahang: ' . $newMahang . ' for user: ' . $user->Manguoidung);

                $membershipRank = HangThanhVien::create([
                    'Mahang' => $newMahang,
                    'Tenhang' => 'Thành viên Bạc',
                    'Mota' => 'Hạng thành viên dành cho người mới',
                    'Manguoidung' => $user->Manguoidung,
                ]);

                \Log::info('Membership rank created: Mahang ' . $membershipRank->Mahang . ' for user: ' . $user->Manguoidung);
            } catch (\Exception $e) {
                \Log::error('Error creating membership rank: ' . $e->getMessage());
                \Log::error('Stack trace: ' . $e->getTraceAsString());
                // Không rollback transaction, chỉ ghi log lỗi
            }

            \DB::commit();
            return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error creating account or user: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Đăng ký thất bại: ' . $e->getMessage())->withInput();
        }
    }

    public function confirmAdmin($token)
    {
        $pendingAccount = PendingAccount::where('token', $token)->first();

        if (!$pendingAccount) {
            return redirect()->route('login')->with('error', 'Liên kết xác nhận không hợp lệ hoặc đã hết hạn.');
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

        return redirect()->route('login')->with('success', 'Tài khoản Admin đã được xác nhận và tạo thành công.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}