@extends('backend.layouts.app')

@section('title', 'Xóa phương thức')

@section('content')
<div class="container">
    <h1>Xóa phương thức</h1>

    <div class="alert alert-warning">
        Bạn có chắc chắn muốn xóa phương thức <strong>{{ $phuongThuc->TenPT }}</strong>? Hành động này không thể hoàn tác.
    </div>

    <form action="{{ route('admin.phuongthuc.destroy', $phuongThuc->MaPT) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Xóa</button>
        <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection