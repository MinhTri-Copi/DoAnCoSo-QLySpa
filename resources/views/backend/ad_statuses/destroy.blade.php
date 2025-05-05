@extends('backend.layouts.app')

@section('title', 'Xóa trạng thái quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Xóa trạng thái quảng cáo</h1>

        <div class="alert alert-warning">
            Bạn có chắc chắn muốn xóa trạng thái <strong>{{ $status->TenTT }}</strong> (Mã: {{ $status->MaTTQC }}) không? Hành động này không thể hoàn tác.
        </div>

        <form action="{{ route('ad-statuses.destroy', $status->MaTTQC) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="{{ route('ad-statuses.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection