@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Trạng Thái')

@section('content')
<div class="container">
    <h2 class="mb-4">Xác Nhận Xóa Trạng Thái</h2>
    <p>Bạn có chắc chắn muốn xóa trạng thái <strong>{{ $trangThai->Tentrangthai }}</strong> (Mã: {{ $trangThai->Matrangthai }}) không?</p>
    <p class="text-danger">Hành động này không thể hoàn tác!</p>

    <form action="{{ route('admin.trangthai.destroy', $trangThai->Matrangthai) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.trangthai.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection