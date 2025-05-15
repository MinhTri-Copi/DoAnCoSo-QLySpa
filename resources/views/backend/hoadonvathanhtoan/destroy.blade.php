@extends('backend.layouts.app')

@section('title', 'Xóa hóa đơn')

@section('content')
<div class="container">
    <h1>Xóa hóa đơn</h1>

    <div class="alert alert-warning">
        Bạn có chắc chắn muốn xóa hóa đơn <strong>{{ $hoaDon->MaHD }}</strong>? Hành động này không thể hoàn tác.
    </div>

    <form action="{{ route('admin.hoadonvathanhtoan.destroy', $hoaDon->MaHD) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.hoadonvathanhtoan.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection