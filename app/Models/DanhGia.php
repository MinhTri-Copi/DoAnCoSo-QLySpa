<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhGia extends Model
{
    protected $table = 'DANHGIA';
    protected $primaryKey = 'MaDG';
    public $incrementing = false;
    protected $fillable = [
        'MaDG', 
        'Danhgiasao', 
        'Nhanxet', 
        'Ngaydanhgia', 
        'Manguoidung', 
        'MaHD',
        'MaDV',
        'PhanHoi',
        'NgayPhanHoi'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'Manguoidung', 'Manguoidung');
    }

    public function hoaDon()
    {
        return $this->belongsTo(HoaDonVaThanhToan::class, 'MaHD', 'MaHD');
    }
    
    public function dichVu()
    {
        return $this->belongsTo(DichVu::class, 'MaDV', 'MaDV');
    }
    
    /**
     * Lấy thông tin dịch vụ thông qua mối quan hệ Hóa đơn -> Đặt lịch -> Dịch vụ
     *
     * @return \App\Models\DichVu|null
     */
    public function getDichVuThongQuaHoaDon()
    {
        if ($this->hoaDon && $this->hoaDon->datLich && $this->hoaDon->datLich->dichVu) {
            return $this->hoaDon->datLich->dichVu;
        }
        
        return null;
    }
    
    /**
     * Lấy tên dịch vụ từ bất kỳ nguồn nào có sẵn
     *
     * @return string
     */
    public function getTenDichVu()
    {
        // Ưu tiên lấy từ quan hệ trực tiếp dichVu
        if ($this->dichVu) {
            return $this->dichVu->Tendichvu;
        }
        
        // Nếu không có, thử lấy thông qua hóa đơn
        $dichVuQuaHoaDon = $this->getDichVuThongQuaHoaDon();
        if ($dichVuQuaHoaDon) {
            return $dichVuQuaHoaDon->Tendichvu;
        }
        
        return 'Không có thông tin';
    }
}