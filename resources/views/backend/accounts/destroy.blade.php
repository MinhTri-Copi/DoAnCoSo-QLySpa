@extends('backend.layouts.app')

@section('title', 'Xóa tài khoản')

@section('content')
    <div class="container-fluid">
        <h1>Xóa tài khoản</h1>

        <div class="alert alert-warning">
            Bạn có chắc chắn muốn xóa tài khoản <strong>{{ $account->username }}</strong> (Mã: {{ $account->MaTK }}) không? Hành động này không thể hoàn tác.
        </div>

        <form action="{{ route('admin.accounts.destroy', $account->MaTK) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection