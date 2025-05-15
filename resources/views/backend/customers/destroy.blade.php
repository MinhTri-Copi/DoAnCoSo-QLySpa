@extends('backend.layouts.app')

@section('title', 'Xóa khách hàng')

@section('content')
    <div class="container-fluid">
        <h1>Xóa khách hàng</h1>

        <div class="alert alert-warning">
            Bạn có chắc chắn muốn xóa khách hàng <strong>{{ $customer->Hoten }}</strong> (Mã: {{ $customer->Manguoidung }}) không? Hành động này không thể hoàn tác.
        </div>

        <form action="{{ route('admin.customers.destroy', $customer->Manguoidung) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection