@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Phương Thức Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Xác Nhận Xóa Phương Thức Hỗ Trợ</h2>
    <p>Bạn có chắc chắn muốn xóa phương thức hỗ trợ <strong>{{ $pthotro->TenPT }}</strong> (Mã: {{ $pthotro->MaPTHT }}) không?</p>
    <p class="text-danger">Hành động này không thể hoàn tác!</p>

    <form action="{{ route('admin.pthotro.destroy', $pthotro->MaPTHT) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.pthotro.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection