@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Dịch Vụ')

@section('content')
<div class="container">
    <h2 class="mb-4">Xác Nhận Xóa Dịch Vụ</h2>
    <p>Bạn có chắc chắn muốn xóa dịch vụ <strong>{{ $dichVu->Tendichvu }}</strong> (Mã: {{ $dichVu->MaDV }}) không?</p>
    <p class="text-danger">Hành động này không thể hoàn tác!</p>

    <form action="{{ route('admin.dichvu.destroy', $dichVu->MaDV) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.dichvu.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection