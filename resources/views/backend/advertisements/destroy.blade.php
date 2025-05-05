@extends('backend.layouts.app')

@section('title', 'Xóa quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Xóa quảng cáo</h1>

        <div class="alert alert-warning">
            Bạn có chắc chắn muốn xóa quảng cáo <strong>{{ $advertisement->Tieude }}</strong> (Mã: {{ $advertisement->MaQC }}) không? Hành động này không thể hoàn tác.
        </div>

        <form action="{{ route('admin.advertisements.destroy', $advertisement->MaQC) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection