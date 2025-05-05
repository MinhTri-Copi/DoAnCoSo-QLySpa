@extends('backend.layouts.app')

@section('title', 'Xóa phòng')

@section('content')
<div class="container">
    <h1>Xóa phòng</h1>

    <div class="alert alert-warning">
        Bạn có chắc chắn muốn xóa phòng <strong>{{ $phong->Tenphong }}</strong>? Hành động này không thể hoàn tác.
    </div>

    <form action="{{ route('admin.phong.destroy', $phong->Maphong) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection