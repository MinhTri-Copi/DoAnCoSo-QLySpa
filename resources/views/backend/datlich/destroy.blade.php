@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Đặt Lịch')

@section('content')
<div class="container">
    <h2 class="mb-4">Xác Nhận Xóa Đặt Lịch</h2>
    <p>Bạn có chắc chắn muốn xóa đặt lịch <strong>{{ $datLich->MaDL }}</strong> (Người dùng: {{ $datLich->user->Hoten ?? 'N/A' }}) không?</p>
    <p class="text-danger">Hành động này không thể hoàn tác!</p>

    <form action="{{ route('admin.datlich.destroy', $datLich->MaDL) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.datlich.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection