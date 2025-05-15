<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DichVu;

class UpdateDichVuSeeder extends Seeder
{
    public function run()
    {
        $dichVus = [
            '1' => [
                'Thoigian' => '01:30:00', // Massage toàn thân
                'Image' => 'images/dichvu/massage-toan-than.jpg'
            ],
            '2' => [
                'Thoigian' => '01:00:00', // Chăm sóc da mặt
                'Image' => 'images/dichvu/cham-soc-da-mat.jpg'
            ],
            '3' => [
                'Thoigian' => '01:00:00', // Tắm trắng toàn thân
                'Image' => 'images/dichvu/tam-trang-toan-than.jpg'
            ],
            '4' => [
                'Thoigian' => '01:15:00', // Chăm sóc da chuyên sâu
                'Image' => 'images/dichvu/cham-soc-da-chuyen-sau.jpg'
            ],
            '5' => [
                'Thoigian' => '00:30:00', // Sauna - Xông hơi
                'Image' => 'images/dichvu/Sauna-Xong-hoi.jpg'
            ],
            '6' => [
                'Thoigian' => '00:30:00', // Gội đầu dưỡng sinh
                'Image' => 'images/dichvu/Goi-Dau-Duong-Sinh.jpg'
            ],
            '7' => [
                'Thoigian' => '01:00:00', // Làm móng tay
                'Image' => 'images/dichvu/Lam-mong-tay.jpg'
            ],
            '8' => [
                'Thoigian' => '01:00:00', // Làm móng chân
                'Image' => 'images/dichvu/lam-mong-chan.jpg'
            ],
            '9' => [
                'Thoigian' => '00:45:00', // Wax lông toàn thân
                'Image' => 'images/dichvu/wax-long-toan-than.jpg'
            ],
            '10' => [
                'Thoigian' => '01:30:00', // Phun xăm thẩm mỹ
                'Image' => 'images/dichvu/phun-xam-tham-my.jpg'
            ],
            '11' => [
                'Thoigian' => '01:00:00', // Chăm sóc da hư tổn
                'Image' => 'images/dichvu/cham-soc-hu-ton.jpg'
            ],
            '12' => [
                'Thoigian' => '00:45:00', // Tẩy tế bào chết toàn thân
                'Image' => 'images/dichvu/Tay-te-bao-chet-toan-than.jpg'
            ],
            '13' => [
                'Thoigian' => '00:45:00', // Đắp mặt nạ collagen
                'Image' => 'images/dichvu/dap-mat-na-colagen.jpg'
            ],
            '14' => [
                'Thoigian' => '01:00:00', // Trẻ hóa da
                'Image' => 'images/dichvu/tre-hoa-da.jpg'
            ],
            '15' => [
                'Thoigian' => '01:00:00', // Massage chân thảo dược
                'Image' => 'images/dichvu/Masage-chan-thao-duoc.jpg'
            ],
            '16' => [
                'Thoigian' => '01:30:00', // Triệt lông vĩnh viễn
                'Image' => 'images/dichvu/triet-long-vinh-vien.jpg'
            ],
            '17' => [
                'Thoigian' => '01:30:00', // Trị nám
                'Image' => 'images/dichvu/tri-nam.jpg'
            ],
            '18' => [
                'Thoigian' => '00:45:00', // Làm trắng răng
                'Image' => 'images/dichvu/lam-trang-rang.jpg'
            ]
        ];

        foreach ($dichVus as $maDV => $data) {
            $dichVu = DichVu::where('MaDV', $maDV)->first();
            if ($dichVu) {
                $dichVu->update([
                    'Thoigian' => $data['Thoigian'],
                    'Image' => $data['Image']
                ]);
            }
        }
    }
} 