@extends('backend.layouts.app')

@section('title', 'Chi tiết quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Chi tiết quảng cáo</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Quảng cáo: {{ $advertisement->Tieude }}</h5>
                <p class="card-text">
                    <strong>Mã quảng cáo:</strong> {{ $advertisement->MaQC }}<br>
                    <strong>Tiêu đề:</strong> {{ $advertisement->Tieude }}<br>
                    <strong>Nội dung:</strong> {{ $advertisement->Noidung }}<br>
                    <strong>Người dùng:</strong> {{ $advertisement->user->Hoten ?? 'Không xác định' }} (Mã: {{ $advertisement->Manguoidung }})<br>
                                        <strong>Loại quảng cáo:</strong> {{ $advertisement->Loaiquangcao ?? 'Chưa phân loại' }}<br>
                    <strong>Trạng thái:</strong> {{ $advertisement->trangThaiQC->TenTT }}<br>
                    <strong>Ngày bắt đầu:</strong> {{ $advertisement->Ngaybatdau }}<br>
                    <strong>Ngày kết thúc:</strong> {{ $advertisement->Ngayketthuc }}<br>
                    <strong>Hình ảnh:</strong><br>
                    @if ($advertisement->Image)
                        <img src="{{ route('storage.image', ['path' => $advertisement->Image]) }}" alt="{{ $advertisement->Tieude }}" style="max-width: 300px;">
                    @else
                        Không có hình ảnh.
                    @endif
                </p>
                <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('admin.advertisements.edit', $advertisement->MaQC) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.advertisements.confirm-destroy', $advertisement->MaQC) }}" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
@endsection