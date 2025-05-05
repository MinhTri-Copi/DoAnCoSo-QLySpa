@extends('backend.layouts.app')

@section('title', 'Quản lý quảng cáo')

@section('content')
    <div class="container-fluid">
        <h1>Quản lý quảng cáo</h1>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <a href="{{ route('admin.advertisements.create') }}" class="btn btn-primary mb-3">Thêm quảng cáo mới</a>

        @if ($advertisements->isEmpty())
            <p>Chưa có quảng cáo nào.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Mã quảng cáo</th>
                        <th>Tiêu đề</th>
                        <th>Người dùng</th>
                        <th>Loại quảng cáo</th>
                        <th>Trạng thái</th>
                        <th>Ngày bắt đầu</th>
                        <th>Ngày kết thúc</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($advertisements as $advertisement)
                        <tr>
                            <td>{{ $advertisement->MaQC }}</td>
                            <td>{{ $advertisement->Tieude }}</td>
                            <td>{{ $advertisement->user->Hoten ?? 'Không xác định' }}</td>
                            <td>{{ $advertisement->Loaiquangcao }}</td>
                            <td>{{ $advertisement->trangThaiQC->TenTT }}</td>
                            <td>{{ $advertisement->Ngaybatdau }}</td>
                            <td>{{ $advertisement->Ngayketthuc }}</td>
                            <td>
                                <a href="{{ route('admin.advertisements.show', $advertisement->MaQC) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('admin.advertisements.edit', $advertisement->MaQC) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="{{ route('admin.advertisements.confirm-destroy', $advertisement->MaQC) }}" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection