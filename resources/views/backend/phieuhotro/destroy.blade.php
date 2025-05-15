@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Phiếu Hỗ Trợ')

@section('content')
<div class="container">
    <h2 class="mb-4">Xác Nhận Xóa Phiếu Hỗ Trợ</h2>
    <p>Bạn có chắc chắn muốn xóa phiếu hỗ trợ <strong>{{ $phieuHoTro->Noidungyeucau }}</strong> (Mã: {{ $phieuHoTro->MaphieuHT }}) không?</p>
    <p class="text-danger">Hành động này không thể hoàn tác!</p>

    <form action="{{ route('admin.phieuhotro.destroy', $phieuHoTro->MaphieuHT) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.phieuhotro.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection