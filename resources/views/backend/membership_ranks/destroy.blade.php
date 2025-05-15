@extends('backend.layouts.app')

@section('title', 'Xóa hạng thành viên')

@section('content')
    <div class="container-fluid">
        <h1>Xóa hạng thành viên</h1>

        <div class="alert alert-warning">
            Bạn có chắc chắn muốn xóa hạng thành viên <strong>{{ $rank->Tenhang }}</strong> (Mã: {{ $rank->Mahang }}) không? Hành động này không thể hoàn tác.
        </div>

        <form action="{{ route('admin.membership_ranks.destroy', $rank->Mahang) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="{{ route('admin.membership_ranks.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection