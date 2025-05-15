<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user()->user;
        return view('backend.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user()->user;
        
        $request->validate([
            'Hoten' => 'required|string|max:255',
            'Ngaysinh' => 'nullable|date',
            'SDT' => 'nullable|string|max:20',
            'Email' => 'required|email|max:255',
            'Gioitinh' => 'nullable|string|in:Nam,Nữ',
        ]);

        $user->update([
            'Hoten' => $request->Hoten,
            'Ngaysinh' => $request->Ngaysinh,
            'SDT' => $request->SDT,
            'Email' => $request->Email,
            'Gioitinh' => $request->Gioitinh,
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Thông tin cá nhân đã được cập nhật thành công!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $account = Auth::user();

        if (!Hash::check($request->current_password, $account->Matkhau)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác']);
        }

        $account->update([
            'Matkhau' => Hash::make($request->password)
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Mật khẩu đã được thay đổi thành công!');
    }

    public function changeEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $user = Auth::user()->user;
        $user->update([
            'Email' => $request->email
        ]);

        return redirect()->route('admin.profile.index')->with('success', 'Email đã được thay đổi thành công!');
    }
} 