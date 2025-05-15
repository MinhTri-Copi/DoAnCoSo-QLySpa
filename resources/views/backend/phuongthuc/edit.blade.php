@extends('backend.layouts.app')

@section('title', 'Sửa phương thức')

@section('content')
<div class="container">
    <h1>Sửa phương thức</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.phuongthuc.update', $phuongThuc->MaPT) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="MaPT" class="form-label">Mã phương thức</label>
            <input type="text" class="form-control" id="MaPT" name="MaPT" value="{{ $phuongThuc->MaPT }}" readonly>
        </div>
        <div class="mb-3">
            <label for="TenPT" class="form-label">Tên phương thức</label>
            <input type="text" class="form-control" id="TenPT" name="TenPT" value="{{ old('TenPT', $phuongThuc->TenPT) }}">
        </div>
        <div class="mb-3">
            <label for="Mota" class="form-label">Mô tả</label>
            <textarea class="form-control" id="Mota" name="Mota">{{ old('Mota', $phuongThuc->Mota) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.phuongthuc.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection