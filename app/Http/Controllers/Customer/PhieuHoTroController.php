<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PhieuHoTro;
use App\Models\PTHoTro;
use App\Models\TrangThai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PhieuHoTroController extends Controller
{
    /**
     * Hiển thị danh sách phiếu hỗ trợ của khách hàng hiện tại.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = [];
        if ($customer) {
            $phieuHoTro = \App\Models\PhieuHoTro::where('Manguoidung', $customer->Manguoidung)
                ->with(['trangThai', 'ptHoTro'])
                ->orderBy('MaphieuHT', 'desc')
                ->paginate(10);
        }
        return view('customer.phieuhotro.index', compact('phieuHoTro'));
    }

    /**
     * Hiển thị form tạo phiếu hỗ trợ mới.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $phuongThucHoTro = PTHoTro::all();
        return view('customer.phieuhotro.create', compact('phuongThucHoTro'));
    }

    /**
     * Lưu phiếu hỗ trợ mới vào database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'Noidungyeucau' => 'required|string',
            'MaPTHT' => 'required|exists:PTHOTRO,MaPTHT',
        ], [
            'Noidungyeucau.required' => 'Vui lòng nhập nội dung yêu cầu',
            'MaPTHT.required' => 'Vui lòng chọn phương thức hỗ trợ',
            'MaPTHT.exists' => 'Phương thức hỗ trợ không tồn tại',
        ]);

        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        if (!$customer) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin khách hàng.');
        }
        
        // Tạo mã phiếu hỗ trợ mới
        $maxMaphieuHT = PhieuHoTro::max('MaphieuHT') ?? 0;
        $newMaphieuHT = $maxMaphieuHT + 1;

        // Lấy mã trạng thái mặc định (Đang xử lý)
        $trangThai = TrangThai::where('Tentrangthai', 'Đang xử lý')->first();

        $phieuHoTro = new PhieuHoTro();
        $phieuHoTro->MaphieuHT = $newMaphieuHT;
        $phieuHoTro->Noidungyeucau = $request->Noidungyeucau;
        $phieuHoTro->MaPTHT = $request->MaPTHT;
        $phieuHoTro->Manguoidung = $customer->Manguoidung;
        $phieuHoTro->Matrangthai = $trangThai->Matrangthai;
        // Nếu có cột Ngaygui thì giữ lại, nếu không thì bỏ dòng này
        if (\Schema::hasColumn('PHIEUHOTRO', 'Ngaygui')) {
            $phieuHoTro->Ngaygui = \Carbon\Carbon::now();
        }

        $phieuHoTro->save();

        return redirect()->route('customer.phieuhotro.index')
            ->with('success', 'Phiếu hỗ trợ đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết phiếu hỗ trợ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $customer->Manguoidung)
            ->with(['trangThai', 'ptHoTro'])
            ->firstOrFail();
        return view('customer.phieuhotro.show', compact('phieuHoTro'));
    }

    /**
     * Hiển thị form cập nhật phiếu hỗ trợ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $customer->Manguoidung)
            ->with(['trangThai', 'ptHoTro'])
            ->firstOrFail();
        if ($phieuHoTro->trangThai->Tentrangthai !== 'Đang xử lý') {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Bạn không thể chỉnh sửa phiếu hỗ trợ này vì nó đang được xử lý hoặc đã hoàn thành.');
        }
        $phuongThucHoTro = PTHoTro::all();
        return view('customer.phieuhotro.edit', compact('phieuHoTro', 'phuongThucHoTro'));
    }

    /**
     * Cập nhật phiếu hỗ trợ trong database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Noidungyeucau' => 'required|string',
            'MaPTHT' => 'required|exists:PTHOTRO,MaPTHT',
        ]);
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $customer->Manguoidung)
            ->with(['trangThai', 'ptHoTro'])
            ->firstOrFail();
        if ($phieuHoTro->trangThai->Tentrangthai !== 'Đang xử lý') {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Bạn không thể chỉnh sửa phiếu hỗ trợ này vì nó đang được xử lý hoặc đã hoàn thành.');
        }
        $phieuHoTro->Noidungyeucau = $request->Noidungyeucau;
        $phieuHoTro->MaPTHT = $request->MaPTHT;
        $phieuHoTro->save();
        return redirect()->route('customer.phieuhotro.show', $id)
            ->with('success', 'Phiếu hỗ trợ đã được cập nhật thành công.');
    }

    /**
     * Hủy phiếu hỗ trợ (không xóa khỏi database).
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $customer->Manguoidung)
            ->with(['trangThai', 'ptHoTro'])
            ->firstOrFail();
        if ($phieuHoTro->trangThai->Tentrangthai !== 'Đang xử lý') {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Bạn không thể hủy phiếu hỗ trợ này vì nó đang được xử lý hoặc đã hoàn thành.');
        }
        $trangThai = TrangThai::where('Tentrangthai', 'Đã hủy')->first();
        $phieuHoTro->Matrangthai = $trangThai->Matrangthai;
        $phieuHoTro->save();
        return redirect()->route('customer.phieuhotro.index')
            ->with('success', 'Phiếu hỗ trợ đã được hủy thành công.');
    }
    
    /**
     * Gửi phản hồi bổ sung cho phiếu hỗ trợ.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sendFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string',
        ]);

        $user = Auth::user();
        $phieuHoTro = PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $user->Manguoidung)
            ->firstOrFail();
            
        // Không cho phép gửi phản hồi nếu phiếu đã hoàn thành hoặc đã hủy
        if (in_array($phieuHoTro->trangThai->Tentrangthai, ['Đã hoàn thành', 'Đã hủy'])) {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Không thể gửi phản hồi cho phiếu hỗ trợ đã hoàn thành hoặc đã hủy.');
        }

        // Thêm phản hồi vào nội dung hiện tại
        $currentContent = $phieuHoTro->Noidungyeucau;
        $feedback = "\n\n--- Phản hồi khách hàng (" . Carbon::now()->format('d/m/Y H:i:s') . ") ---\n";
        $feedback .= $request->feedback;
        
        $phieuHoTro->Noidungyeucau = $currentContent . $feedback;
        $phieuHoTro->save();

        return redirect()->route('customer.phieuhotro.show', $id)
            ->with('success', 'Phản hồi đã được gửi thành công.');
    }

    public function confirmDestroy($id)
    {
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = \App\Models\PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $customer->Manguoidung)
            ->with(['trangThai', 'ptHoTro'])
            ->firstOrFail();
        return view('customer.phieuhotro.confirm-destroy', compact('phieuHoTro'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $customer = \App\Models\User::where('MaTK', $user->MaTK)->first();
        $phieuHoTro = \App\Models\PhieuHoTro::where('MaphieuHT', $id)
            ->where('Manguoidung', $customer->Manguoidung)
            ->first();
        if (!$phieuHoTro) {
            return redirect()->route('customer.phieuhotro.index')->with('error', 'Không tìm thấy phiếu hỗ trợ.');
        }
        if ($phieuHoTro->trangThai && $phieuHoTro->trangThai->Tentrangthai !== 'Đang xử lý') {
            return redirect()->route('customer.phieuhotro.index')->with('error', 'Chỉ có thể xoá phiếu hỗ trợ khi đang ở trạng thái Đang xử lý.');
        }
        $phieuHoTro->delete();
        return redirect()->route('customer.phieuhotro.index')->with('success', 'Xoá phiếu hỗ trợ thành công.');
    }
} 