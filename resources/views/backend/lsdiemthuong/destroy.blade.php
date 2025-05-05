@extends('backend.layouts.app')

@section('title', 'Xóa lịch sử điểm thưởng')

@section('content')
<div class="container">
    <h1>Xóa lịch sử điểm thưởng</h1>

    <div class="alert alert-warning">
        Bạn có chắc chắn muốn xóa lịch sử điểm thưởng <strong>{{ $pointHistory->MaLSDT }}</strong>? Hành động này không thể hoàn tác.
    </div>

    <form action="{{ route('admin.lsdiemthuong.destroy', $pointHistory->MaLSDT) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.lsdiemthuong.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection