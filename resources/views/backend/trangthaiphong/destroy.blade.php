@extends('backend.layouts.app')

@section('title', 'Xác Nhận Xóa Trạng Thái Phòng')

@section('content')
<div class="container">
    <h2 class="mb-4">Xác Nhận Xóa Trạng Thái Phòng</h2>
    <p>Bạn có chắc chắn muốn xóa trạng thái phòng <strong>{{ $trangThaiPhong->Tentrangthai }}</strong> (Mã: {{ $trangThaiPhong->MatrangthaiP }}) không?</p>
    <p class="text-danger">Hành động này không thể hoàn tác!</p>

    <form action="{{ route('admin.trangthaiphong.destroy', $trangThaiPhong->MatrangthaiP) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.trangthaiphong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection