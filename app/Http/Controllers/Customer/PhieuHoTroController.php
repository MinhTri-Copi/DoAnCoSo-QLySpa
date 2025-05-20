<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\PhieuHoTro;
use App\Models\PTHoTro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
        $phieuHoTro = PhieuHoTro::where('Manguoidung', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
            'Tieude' => 'required|string|max:255',
            'Noidung' => 'required|string',
            'MaPTHT' => 'required|exists:PTHOTRO,MaPTHT',
        ], [
            'Tieude.required' => 'Vui lòng nhập tiêu đề phiếu hỗ trợ',
            'Noidung.required' => 'Vui lòng nhập nội dung phiếu hỗ trợ',
            'MaPTHT.required' => 'Vui lòng chọn phương thức hỗ trợ',
            'MaPTHT.exists' => 'Phương thức hỗ trợ không tồn tại',
        ]);

        $user = Auth::user();

        $phieuHoTro = new PhieuHoTro();
        $phieuHoTro->Tieude = $request->Tieude;
        $phieuHoTro->Noidung = $request->Noidung;
        $phieuHoTro->MaPTHT = $request->MaPTHT;
        $phieuHoTro->Manguoidung = $user->id;
        $phieuHoTro->Trangthai = 'Đang xử lý'; // Trạng thái mặc định khi tạo mới
        $phieuHoTro->Ngaygui = Carbon::now();

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
        $phieuHoTro = PhieuHoTro::where('MaPhieu', $id)
            ->where('Manguoidung', $user->id)
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
        $phieuHoTro = PhieuHoTro::where('MaPhieu', $id)
            ->where('Manguoidung', $user->id)
            ->firstOrFail();
            
        // Chỉ cho phép chỉnh sửa nếu phiếu đang ở trạng thái "Đang xử lý"
        if ($phieuHoTro->Trangthai !== 'Đang xử lý') {
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
            'Tieude' => 'required|string|max:255',
            'Noidung' => 'required|string',
            'MaPTHT' => 'required|exists:PTHOTRO,MaPTHT',
        ]);

        $user = Auth::user();
        $phieuHoTro = PhieuHoTro::where('MaPhieu', $id)
            ->where('Manguoidung', $user->id)
            ->firstOrFail();
            
        // Chỉ cho phép chỉnh sửa nếu phiếu đang ở trạng thái "Đang xử lý"
        if ($phieuHoTro->Trangthai !== 'Đang xử lý') {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Bạn không thể chỉnh sửa phiếu hỗ trợ này vì nó đang được xử lý hoặc đã hoàn thành.');
        }

        $phieuHoTro->Tieude = $request->Tieude;
        $phieuHoTro->Noidung = $request->Noidung;
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
        $phieuHoTro = PhieuHoTro::where('MaPhieu', $id)
            ->where('Manguoidung', $user->id)
            ->firstOrFail();
            
        // Chỉ cho phép hủy nếu phiếu đang ở trạng thái "Đang xử lý"
        if ($phieuHoTro->Trangthai !== 'Đang xử lý') {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Bạn không thể hủy phiếu hỗ trợ này vì nó đang được xử lý hoặc đã hoàn thành.');
        }

        $phieuHoTro->Trangthai = 'Đã hủy';
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
        $phieuHoTro = PhieuHoTro::where('MaPhieu', $id)
            ->where('Manguoidung', $user->id)
            ->firstOrFail();
            
        // Không cho phép gửi phản hồi nếu phiếu đã hoàn thành hoặc đã hủy
        if (in_array($phieuHoTro->Trangthai, ['Đã hoàn thành', 'Đã hủy'])) {
            return redirect()->route('customer.phieuhotro.show', $id)
                ->with('error', 'Không thể gửi phản hồi cho phiếu hỗ trợ đã hoàn thành hoặc đã hủy.');
        }

        // Thêm phản hồi vào nội dung hiện tại
        $currentContent = $phieuHoTro->Noidung;
        $feedback = "\n\n--- Phản hồi khách hàng (" . Carbon::now()->format('d/m/Y H:i:s') . ") ---\n";
        $feedback .= $request->feedback;
        
        $phieuHoTro->Noidung = $currentContent . $feedback;
        $phieuHoTro->save();

        return redirect()->route('customer.phieuhotro.show', $id)
            ->with('success', 'Phản hồi đã được gửi thành công.');
    }
} 