<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\DichVu;
use App\Models\Account;
use App\Models\DatLich;
use App\Models\QuangCao;
use App\Models\TrangThaiPhong;
use App\Models\Phong;
use App\Models\PhuongThuc;
use App\Models\TrangThai;
use App\Models\TrangThaiQC;

use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        //Role
        // Role::create([
        //     'RoleID' => '1',
        //     'Tenrole' => 'Admin',
        // ]);

        // Role::create([
        //     'RoleID' => '2',
        //     'Tenrole' => 'Customer',
        // ]);

        //seed for dịch vụ
//         DichVu::insert([
//     ['MaDV' => 1, 'Tendichvu' => 'Massage body', 'Gia' => 300000, 'MoTa' => 'Thư giãn toàn thân'],
//     ['MaDV' => 2, 'Tendichvu' => 'Chăm sóc da mặt', 'Gia' => 250000, 'MoTa' => 'Làm sạch và dưỡng da'],
//     ['MaDV' => 3, 'Tendichvu' => 'Tắm trắng toàn thân', 'Gia' => 450000, 'MoTa' => 'Làm sáng da, mịn màng tự nhiên'],
//     ['MaDV' => 4, 'Tendichvu' => 'Chăm sóc da chuyên sâu', 'Gia' => 500000, 'MoTa' => 'Điều trị mụn, thâm, se khít lỗ chân lông'],
//     ['MaDV' => 5, 'Tendichvu' => 'Sauna & xông hơi', 'Gia' => 200000, 'MoTa' => 'Thư giãn, đào thải độc tố'],
//     ['MaDV' => 6, 'Tendichvu' => 'Gội đầu dưỡng sinh', 'Gia' => 180000, 'MoTa' => 'Thư giãn đầu, vai gáy'],
//     ['MaDV' => 7, 'Tendichvu' => 'Làm móng tay', 'Gia' => 120000, 'MoTa' => 'Cắt, sơn móng chuyên nghiệp'],
//     ['MaDV' => 8, 'Tendichvu' => 'Làm móng chân', 'Gia' => 150000, 'MoTa' => 'Spa móng chân thư giãn'],
//     ['MaDV' => 9, 'Tendichvu' => 'Wax lông toàn thân', 'Gia' => 350000, 'MoTa' => 'Làm sạch và mịn da'],
//     ['MaDV' => 10, 'Tendichvu' => 'Phun xăm thẩm mỹ', 'Gia' => 1000000, 'MoTa' => 'Làm đẹp tự nhiên, lâu dài'],
//     ['MaDV' => 11, 'Tendichvu' => 'Chăm sóc tóc hư tổn', 'Gia' => 400000, 'MoTa' => 'Phục hồi tóc bằng tinh chất đặc trị'],
//     ['MaDV' => 12, 'Tendichvu' => 'Tẩy tế bào chết toàn thân', 'Gia' => 270000, 'MoTa' => 'Làm sạch và mềm mịn da'],
//     ['MaDV' => 13, 'Tendichvu' => 'Đắp mặt nạ collagen', 'Gia' => 220000, 'MoTa' => 'Giúp da căng bóng và đàn hồi'],
//     ['MaDV' => 14, 'Tendichvu' => 'Liệu trình trẻ hóa da', 'Gia' => 950000, 'MoTa' => 'Làm mờ nếp nhăn, săn chắc da'],
//     ['MaDV' => 15, 'Tendichvu' => 'Massage chân thảo dược', 'Gia' => 250000, 'MoTa' => 'Giảm đau, lưu thông khí huyết'],
//     ['MaDV' => 16, 'Tendichvu' => 'Triệt lông vĩnh viễn', 'Gia' => 850000, 'MoTa' => 'Công nghệ IPL hiện đại, an toàn'],
//     ['MaDV' => 17, 'Tendichvu' => 'Điều trị nám - tàn nhang', 'Gia' => 1100000, 'MoTa' => 'Công nghệ laser trị nám chuyên sâu'],
//     ['MaDV' => 18, 'Tendichvu' => 'Làm trắng răng', 'Gia' => 700000, 'MoTa' => 'Làm sáng răng bằng công nghệ cao'],
// ]);


        //seed for Account
        // Account::insert([
        //     ['MaTK' => 2, 'RoleID' => 2, 'Tendangnhap' => 'BinhUser', 'Matkhau' => Hash::make('BinhUser')],
        //     ['MaTK' => 3, 'RoleID' => 2, 'Tendangnhap' => 'ChiUser', 'Matkhau' => Hash::make('ChiUser')],
        //     ['MaTK' => 4, 'RoleID' => 2, 'Tendangnhap' => 'DungUser', 'Matkhau' => Hash::make('DungUser')],
        //     ['MaTK' => 5, 'RoleID' => 2, 'Tendangnhap' => 'HanhUser', 'Matkhau' => Hash::make('HanhUser')],
        //     ['MaTK' => 6, 'RoleID' => 2, 'Tendangnhap' => 'HoangUser', 'Matkhau' => Hash::make('HoangUser')],
        //     ['MaTK' => 7, 'RoleID' => 2, 'Tendangnhap' => 'KhanhUser', 'Matkhau' => Hash::make('KhanhUser')],
        //     ['MaTK' => 8, 'RoleID' => 2, 'Tendangnhap' => 'LinhUser', 'Matkhau' => Hash::make('LinhUser')],
        //     ['MaTK' => 9, 'RoleID' => 2, 'Tendangnhap' => 'MinhUser', 'Matkhau' => Hash::make('MinhUser')],
        //     ['MaTK' => 10, 'RoleID' => 2, 'Tendangnhap' => 'NamUser', 'Matkhau' => Hash::make('NamUser')],
        // ]);

        //seed for User
    //   User::insert([
    //         ['Manguoidung' => 2, 'MaTK' => 2, 'Hoten' => 'Nguyễn Văn Bình', 'SDT' => '0901123456', 'DiaChi' => 'Quận 1, TP.HCM', 'Email' => 'binh@example.com', 'Ngaysinh' => '1998-01-01', 'Gioitinh' => 'Nam'],
    //         ['Manguoidung' => 3, 'MaTK' => 3, 'Hoten' => 'Trần Thị Chi', 'SDT' => '0902234567', 'DiaChi' => 'Quận 2, TP.HCM', 'Email' => 'chi@example.com', 'Ngaysinh' => '1999-02-02', 'Gioitinh' => 'Nữ'],
    //         ['Manguoidung' => 4, 'MaTK' => 4, 'Hoten' => 'Lê Văn Dũng', 'SDT' => '0903345678', 'DiaChi' => 'Quận 3, TP.HCM', 'Email' => 'dung@example.com', 'Ngaysinh' => '2000-03-03', 'Gioitinh' => 'Nam'],
    //         ['Manguoidung' => 5, 'MaTK' => 5, 'Hoten' => 'Phạm Thị Hạnh', 'SDT' => '0904456789', 'DiaChi' => 'Quận 4, TP.HCM', 'Email' => 'hanh@example.com', 'Ngaysinh' => '2001-04-04', 'Gioitinh' => 'Nữ'],
    //         ['Manguoidung' => 6, 'MaTK' => 6, 'Hoten' => 'Hoàng Văn Hoàng', 'SDT' => '0905567890', 'DiaChi' => 'Quận 5, TP.HCM', 'Email' => 'hoang@example.com', 'Ngaysinh' => '1997-05-05', 'Gioitinh' => 'Nam'],
    //         ['Manguoidung' => 7, 'MaTK' => 7, 'Hoten' => 'Nguyễn Thị Khánh', 'SDT' => '0906678901', 'DiaChi' => 'Quận 6, TP.HCM', 'Email' => 'khanh@example.com', 'Ngaysinh' => '1996-06-06', 'Gioitinh' => 'Nữ'],
    //         ['Manguoidung' => 8, 'MaTK' => 8, 'Hoten' => 'Đinh Văn Linh', 'SDT' => '0907789012', 'DiaChi' => 'Quận 7, TP.HCM', 'Email' => 'linh@example.com', 'Ngaysinh' => '1995-07-07', 'Gioitinh' => 'Nam'],
    //         ['Manguoidung' => 9, 'MaTK' => 9, 'Hoten' => 'Lâm Thị Minh', 'SDT' => '0908890123', 'DiaChi' => 'Quận 8, TP.HCM', 'Email' => 'minh@example.com', 'Ngaysinh' => '1994-08-08', 'Gioitinh' => 'Nữ'],
    //         ['Manguoidung' => 10, 'MaTK' => 10, 'Hoten' => 'Trương Văn Nam', 'SDT' => '0909901234', 'DiaChi' => 'Quận 9, TP.HCM', 'Email' => 'nam@example.com', 'Ngaysinh' => '1993-09-09', 'Gioitinh' => 'Nam'],
    //     ]);
        
    //        //seed for DatLich
    //     DatLich::insert([
    //         ['MaDL' => 1, 'Manguoidung' => 1, 'Thoigiandatlich' => '2025-04-20 09:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 1],
    //         ['MaDL' => 2, 'Manguoidung' => 2, 'Thoigiandatlich' => '2025-04-22 10:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 3],
    //         ['MaDL' => 3, 'Manguoidung' => 3, 'Thoigiandatlich' => '2025-05-01 09:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 3],
    //         ['MaDL' => 4, 'Manguoidung' => 4, 'Thoigiandatlich' => '2025-05-01 11:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 4],
    //         ['MaDL' => 5, 'Manguoidung' => 5, 'Thoigiandatlich' => '2025-05-02 10:00:00', 'Trangthai_' => 'Chờ xác nhận', 'MaDV' => 5],
    //         ['MaDL' => 6, 'Manguoidung' => 6, 'Thoigiandatlich' => '2025-05-02 14:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 6],
    //         ['MaDL' => 7, 'Manguoidung' => 7, 'Thoigiandatlich' => '2025-05-03 13:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 7],
    //         ['MaDL' => 8, 'Manguoidung' => 8, 'Thoigiandatlich' => '2025-05-03 15:00:00', 'Trangthai_' => 'Chờ xác nhận', 'MaDV' => 8],
    //         ['MaDL' => 9, 'Manguoidung' => 9, 'Thoigiandatlich' => '2025-05-04 09:30:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 9],
    //         ['MaDL' => 10, 'Manguoidung' => 10, 'Thoigiandatlich' => '2025-05-04 11:30:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 10],
    //         ['MaDL' => 11, 'Manguoidung' => 1, 'Thoigiandatlich' => '2025-05-05 10:30:00', 'Trangthai_' => 'Đã hủy', 'MaDV' => 11],
    //         ['MaDL' => 12, 'Manguoidung' => 2, 'Thoigiandatlich' => '2025-05-05 12:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 12],
    //         ['MaDL' => 13, 'Manguoidung' => 3, 'Thoigiandatlich' => '2025-05-06 14:30:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 13],
    //         ['MaDL' => 14, 'Manguoidung' => 4, 'Thoigiandatlich' => '2025-05-06 16:00:00', 'Trangthai_' => 'Chờ xác nhận', 'MaDV' => 14],
    //         ['MaDL' => 15, 'Manguoidung' => 5, 'Thoigiandatlich' => '2025-05-07 09:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 15],
    //         ['MaDL' => 16, 'Manguoidung' => 6, 'Thoigiandatlich' => '2025-05-07 11:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 16],
    //         ['MaDL' => 17, 'Manguoidung' => 7, 'Thoigiandatlich' => '2025-05-08 10:00:00', 'Trangthai_' => 'Đã hủy', 'MaDV' => 17],
    //         ['MaDL' => 18, 'Manguoidung' => 8, 'Thoigiandatlich' => '2025-05-08 12:30:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 18],
    //         ['MaDL' => 19, 'Manguoidung' => 9, 'Thoigiandatlich' => '2025-05-09 15:00:00', 'Trangthai_' => 'Đã xác nhận', 'MaDV' => 1],
    //         ['MaDL' => 20, 'Manguoidung' => 10, 'Thoigiandatlich' => '2025-05-10 09:30:00', 'Trangthai_' => 'Chờ xác nhận', 'MaDV' => 2],
    //     ]

    //     );

    //seed for TrangThaiPhong
    // TrangThaiPhong::insert([
    //     ['MatrangthaiP' => 1, 'Tentrangthai' => 'Trống'],
    //     ['MatrangthaiP' => 2, 'Tentrangthai' => 'Đang sử dụng'],
    //     ['MatrangthaiP' => 3, 'Tentrangthai' => 'Đang dọn dẹp'],
    //     ['MatrangthaiP' => 4, 'Tentrangthai' => 'Bảo trì']
    // ]);

    //seed for Phong
    // Phong::insert([
    //     ['Maphong' => 1, 'Tenphong' => 'Phòng Massage Thư Giãn', 'Loaiphong' => 'Thường', 'MatrangthaiP' => 1],
    //     ['Maphong' => 2, 'Tenphong' => 'Phòng Chăm sóc Da Mặt', 'Loaiphong' => 'Cao cấp', 'MatrangthaiP' => 2],
    //     ['Maphong' => 3, 'Tenphong' => 'Phòng Chăm sóc Toàn Thân', 'Loaiphong' => 'Thường', 'MatrangthaiP' => 3],
    //     ['Maphong' => 4, 'Tenphong' => 'Phòng Làm Móng', 'Loaiphong' => 'Thường', 'MatrangthaiP' => 4],
    //     ['Maphong' => 5, 'Tenphong' => 'Phòng Chăm sóc Tóc', 'Loaiphong' => 'Thường', 'MatrangthaiP' => 1],
    //     ['Maphong' => 6, 'Tenphong' => 'Phòng Thẩm mỹ Công nghệ Cao', 'Loaiphong' => 'Cao cấp', 'MatrangthaiP' => 2],
    // ]);

    //seed for PhuongThuc
//    PhuongThuc::insert([
//         ['MaPT' => 1, 'TenPT' => 'VNPay', 'Mota' => 'Thanh toán qua VNPay'],
//         ['MaPT' => 2, 'TenPT' => 'Momo', 'Mota' => 'Thanh toán qua ví điện tử Momo'],
//     ]);

        // TrangThai::insert([
        //     ['Matrangthai' => 1, 'Tentrangthai' => 'Chờ xử lý'],
        //     ['Matrangthai' => 2, 'Tentrangthai' => 'Đang xử lý'],
        //     ['Matrangthai' => 3, 'Tentrangthai' => 'Hoàn tất'],
        //     ['Matrangthai' => 4, 'Tentrangthai' => 'Đã thanh toán'],
        //     ['Matrangthai' => 5, 'Tentrangthai' => 'Hủy'],
        //     ['Matrangthai' => 6, 'Tentrangthai' => 'Chờ thanh toán'],
        //     ['Matrangthai' => 7, 'Tentrangthai' => 'Chờ phản hồi'],
        //     ['Matrangthai' => 8, 'Tentrangthai' => 'Đã phản hồi'],
        // ]);

        // TrangThaiQC::insert([
        //     ['MaTTQC' => 1, 'TenTT' => 'Đang hoạt động'],
        //     ['MaTTQC' => 2, 'TenTT' => 'Ngưng hoạt động'],
        //     ['MaTTQC' => 3, 'TenTT' => 'Đã hết hạn'],
        // ]);

        // $this->call([
        //     UpdateDichVuSeeder::class,
        // ]);
    }
}
