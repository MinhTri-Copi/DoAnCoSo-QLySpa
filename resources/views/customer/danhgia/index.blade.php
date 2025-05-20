@extends('customer.layouts.app')

@section('title', 'Đánh giá của tôi')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Đánh giá của tôi</h1>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($reviews->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <i class="fas fa-star fa-5x text-muted"></i>
                </div>
                <h3>Bạn chưa có đánh giá nào</h3>
                <p class="text-muted">Hãy đánh giá dịch vụ sau khi sử dụng để chia sẻ trải nghiệm của bạn và giúp người khác tìm hiểu về chất lượng dịch vụ.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Dịch vụ</th>
                                <th>Điểm đánh giá</th>
                                <th>Nội dung</th>
                                <th>Ngày đánh giá</th>
                                <th>Phản hồi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td>
                                        @if($review->dichVu)
                                            {{ $review->dichVu->Tendichvu }}
                                        @else
                                            <span class="text-muted fst-italic">Không có thông tin</span>
                                        @endif
                                    </td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->Diemdanhgia)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($review->Noidungdanhgia, 50) }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y') }}</td>
                                    <td>
                                        @if($review->PhanHoi)
                                            <span class="badge bg-success">Đã phản hồi</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa phản hồi</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('customer.danhgia.show', $review->MaDG) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        @if(!$review->PhanHoi)
                                            <a href="{{ route('customer.danhgia.edit', $review->MaDG) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection 