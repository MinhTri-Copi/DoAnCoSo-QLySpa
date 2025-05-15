@extends('backend.layouts.app')

@section('title', 'Chi tiết hạng thành viên')

@section('content')
    <div class="container-fluid">
        <h1>Chi tiết hạng thành viên</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Hạng: {{ $rank->Tenhang }}</h5>
                <p class="card-text">
                    <strong>Mã hạng:</strong> {{ $rank->Mahang }}<br>
                    <strong>Tên hạng:</strong> {{ $rank->Tenhang }}<br>
                    <strong>Mô tả:</strong> {{ $rank->Mota ?? 'Không có mô tả' }}<br>
                    <strong>Người tạo:</strong> {{ $rank->user->Hoten ?? 'Không xác định' }} (Mã: {{ $rank->Manguoidung }})<br>
                </p>
                <a href="{{ route('admin.membership_ranks.index') }}" class="btn btn-secondary">Quay lại</a>
                <a href="{{ route('admin.membership_ranks.edit', $rank->Mahang) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.membership_ranks.confirm-destroy', $rank->Mahang) }}" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
@endsection