@extends('backend.layouts.app')

@section('title', 'Thêm phương thức mới')

@section('content')
<div class="container">
    <h1>Thêm phương thức mới</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.phuongthuc.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="MaPT" class="form-label">Mã phương thức</label>
            <input type="text" class="form-control" id="MaPT" name="MaPT" value="{{ $suggestedMaPT }}" readonly>
        </div>
        <div class="mb-3">
            <label for="TenPT" class="form-label">Tên phương thức</label>
            <input type="text" class="form-control" id="TenPT" name="TenPT" value="{{ old('TenPT') }}">
        </div>
        <div class="mb-3">
            <label for="Mota" class="form-label">Mô tả</label>
            <textarea class="form-control" id="Mota" name="Mota">{{ old('Mota') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection