@extends('backend.layouts.app')

@section('title', 'Chi tiết lịch sử điểm thưởng')

@section('content')
<div class="container">
    <h1>Chi tiết lịch sử điểm thưởng</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Mã lịch sử:</strong> {{ $pointHistory->MaLSDT }}</p>
            <p><strong>Thời gian:</strong> {{ $pointHistory->Thoigian }}</p>
            <p><strong>Số điểm:</strong> {{ $pointHistory->Sodiem }}</p>
            <p><strong>Người dùng:</strong> {{ $pointHistory->user->name ?? 'N/A' }}</p>
            <p><strong>Mã hóa đơn:</strong> {{ $pointHistory->hoaDon->MaHD ?? 'N/A' }}</p>
        </div>
    </div>

    <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection