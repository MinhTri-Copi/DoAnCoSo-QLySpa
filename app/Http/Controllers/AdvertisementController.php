<?php

namespace App\Http\Controllers;

use App\Models\QuangCao;
use App\Models\TrangThaiQC;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = QuangCao::with(['trangThaiQC', 'user'])->get();
        
        // Ensure all advertisements have a type (for backward compatibility)
        foreach ($advertisements as $ad) {
            if (empty($ad->Loaiquangcao)) {
                $ad->Loaiquangcao = null; // Explicitly set to null if empty
            }
        }
        
        return view('backend.advertisements.index', compact('advertisements'));
    }

    public function create()
    {
        $maxMaQC = QuangCao::max('MaQC') ?? 0;
        $suggestedMaQC = $maxMaQC + 1;

        $statuses = TrangThaiQC::all();
        $users = User::all();
        $adTypes = ['Khuyến mãi', 'Sự kiện', 'Thông báo']; // Sử dụng tiếng Việt có dấu
        return view('backend.advertisements.create', compact('suggestedMaQC', 'statuses', 'users', 'adTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaQC' => 'required|integer|unique:QUANGCAO,MaQC',
            'Tieude' => 'required|string|max:255',
            'Noidung' => 'required|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Loaiquangcao' => 'required|string|in:Khuyenmai,Sukien,Thongbao',
            'MaTTQC' => 'required|exists:TRANGTHAIQC,MaTTQC',
            'Ngaybatdau' => 'required|date',
            'Ngayketthuc' => 'required|date|after_or_equal:Ngaybatdau',
        ]);

        $imagePath = null;
        if ($request->hasFile('Image')) {
            $imagePath = $request->file('Image')->store('advertisements', 'public');
        }

        QuangCao::create([
            'MaQC' => $request->MaQC,
            'Tieude' => $request->Tieude,
            'Noidung' => $request->Noidung,
            'Image' => $imagePath,
            'Manguoidung' => $request->Manguoidung,
            'Loaiquangcao' => $request->Loaiquangcao,
            'MaTTQC' => $request->MaTTQC,
            'Ngaybatdau' => $request->Ngaybatdau,
            'Ngayketthuc' => $request->Ngayketthuc,
        ]);

        return redirect()->route('admin.advertisements.index')->with('success', 'Thêm quảng cáo thành công!');
    }

    public function show($id)
    {
        $advertisement = QuangCao::with(['trangThaiQC', 'user'])->findOrFail($id);
        return view('backend.advertisements.show', compact('advertisement'));
    }

    public function edit($id)
    {
        $advertisement = QuangCao::findOrFail($id);
        $statuses = TrangThaiQC::all();
        $users = User::all();
        $adTypes = ['Khuyenmai', 'Sukien', 'Thongbao'];
        return view('backend.advertisements.edit', compact('advertisement', 'statuses', 'users', 'adTypes'));
    }

    public function update(Request $request, $id)
    {
        $advertisement = QuangCao::findOrFail($id);

        $request->validate([
            'Tieude' => 'required|string|max:255',
            'Noidung' => 'required|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'Manguoidung' => 'required|exists:USER,Manguoidung',
            'Loaiquangcao' => 'required|string|in:Khuyenmai,Sukien,Thongbao',
            'MaTTQC' => 'required|exists:TRANGTHAIQC,MaTTQC',
            'Ngaybatdau' => 'required|date',
            'Ngayketthuc' => 'required|date|after_or_equal:Ngaybatdau',
        ]);

        $imagePath = $advertisement->Image;
        if ($request->hasFile('Image')) {
            if ($imagePath && \Storage::disk('public')->exists($imagePath)) {
                \Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('Image')->store('advertisements', 'public');
        }

        $advertisement->update([
            'Tieude' => $request->Tieude,
            'Noidung' => $request->Noidung,
            'Image' => $imagePath,
            'Manguoidung' => $request->Manguoidung,
            'Loaiquangcao' => $request->Loaiquangcao,
            'MaTTQC' => $request->MaTTQC,
            'Ngaybatdau' => $request->Ngaybatdau,
            'Ngayketthuc' => $request->Ngayketthuc,
        ]);

        return redirect()->route('admin.advertisements.index')->with('success', 'Cập nhật quảng cáo thành công!');
    }

    public function confirmDestroy($id)
    {
        $advertisement = QuangCao::findOrFail($id);
        return view('backend.advertisements.destroy', compact('advertisement'));
    }

    public function destroy($id)
    {
        $advertisement = QuangCao::findOrFail($id);

        if ($advertisement->Image && \Storage::disk('public')->exists($advertisement->Image)) {
            \Storage::disk('public')->delete($advertisement->Image);
        }

        $advertisement->delete();

        return redirect()->route('admin.advertisements.index')->with('success', 'Xóa quảng cáo thành công!');
    }

    public function showImage($filename)
    {
        // Mảng các đường dẫn có thể của hình ảnh
        $possiblePaths = [
            $filename, // Đường dẫn chính xác như được truyền vào
            'advertisements/' . $filename, // Thư mục advertisements/
            'images/quangcao/' . $filename, // Thư mục images/quangcao/
            'images/' . $filename, // Thư mục images/
            'public/' . $filename, // Thư mục public/
        ];
        
        $path = null;
        
        // Tìm kiếm hình ảnh trong các đường dẫn có thể
        foreach ($possiblePaths as $possiblePath) {
            if (Storage::disk('public')->exists($possiblePath)) {
                $path = $possiblePath;
                break;
            }
        }
        
        // Nếu vẫn không tìm thấy, tìm kiếm toàn bộ đường dẫn đầy đủ
        if (!$path && strpos($filename, '/') !== false) {
            if (Storage::disk('public')->exists($filename)) {
                $path = $filename;
            }
        }
        
        // Nếu không tìm thấy hình ảnh, trả về hình mặc định hoặc 404
        if (!$path) {
            // Trả về hình ảnh mặc định nếu có
            $defaultPath = 'images/default-ad.jpg';
            if (Storage::disk('public')->exists($defaultPath)) {
                $path = $defaultPath;
            } else {
                abort(404, 'Image not found');
            }
        }
        
        try {
            $file = Storage::disk('public')->get($path);
            $type = Storage::disk('public')->mimeType($path);
            
            return response($file, 200)->header('Content-Type', $type);
        } catch (\Exception $e) {
            abort(404, 'Error loading image: ' . $e->getMessage());
        }
    }

    // Phương thức mới để hiển thị hình ảnh từ bất kỳ đường dẫn nào
    public function showAnyImage(Request $request, $path)
    {
        $fullPath = $request->path;
        if (!$fullPath) {
            $fullPath = $path;
        }
        
        // Nếu path đã được mã hóa URL, giải mã nó
        $fullPath = urldecode($fullPath);
        
        try {
            // Thử tìm file trong storage
            if (Storage::disk('public')->exists($fullPath)) {
                $file = Storage::disk('public')->get($fullPath);
                $type = Storage::disk('public')->mimeType($fullPath);
                
                return response($file, 200)->header('Content-Type', $type);
            }
            
            // Nếu không có trong storage/public, thử tìm trong document root
            $publicPath = public_path($fullPath);
            if (file_exists($publicPath)) {
                return response()->file($publicPath);
            }
            
            // Nếu không tìm thấy, trả về 404
            return response()->json(['error' => 'Image not found', 'path' => $fullPath], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'path' => $fullPath], 500);
        }
    }
}