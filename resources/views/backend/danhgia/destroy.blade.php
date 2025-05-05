@extends('backend.layouts.app')

@section('title', 'Xóa đánh giá')

@section('content')
<div class="container">
    <h1>Xóa đánh giá</h1>

    <div class="alert alert-warning">
        Bạn có chắc chắn muốn xóa đánh giá <strong>{{ $danhGia->MaDG }}</strong>? Hành động này không thể hoàn tác.
    </div>

    <form action="{{ route('admin.danhgia.destroy', $danhGia->MaDG) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.danhgia.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection