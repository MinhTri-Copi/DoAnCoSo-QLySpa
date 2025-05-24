@extends('customer.layouts.app')

@section('title', 'Đánh giá của tôi')

@section('styles')
<style>
    .welcome-banner {
        background: linear-gradient(135deg, #ff6b9d 0%, #ff8db3 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner h1 {
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.8rem;
    }
    
    .welcome-banner p {
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    .shine-line {
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(
            90deg,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.2) 50%,
            rgba(255, 255, 255, 0) 100%
        );
        animation: shine 3s infinite;
    }
    
    @keyframes shine {
        0% {
            left: -100%;
        }
        100% {
            left: 200%;
        }
    }
    
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .clickable-row:hover {
        background-color: rgba(255, 107, 157, 0.05);
    }
    
    .clickable-row td:first-child {
        color: #ff6b9d;
        font-weight: 500;
    }
</style>
@endsection

@section('content')
<div class="container py-5">
    <!-- Welcome banner -->
    <div class="welcome-banner">
        <h1><i class="fas fa-star"></i> Đánh giá của tôi</h1>
        <p>Chia sẻ trải nghiệm của bạn và giúp chúng tôi cải thiện dịch vụ</p>
        <div class="shine-line"></div>
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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr class="clickable-row" data-href="{{ route('customer.danhgia.show', $review->MaDG) }}">
                                    <td>
                                        {{ $review->getTenDichVu() }}
                                    </td>
                                    <td>
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->Danhgiasao)
                                                <i class="fas fa-star text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                    </td>
                                    <td>
                                        {{ \Illuminate\Support\Str::limit($review->Nhanxet, 50) }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($review->Ngaydanhgia)->format('d/m/Y') }}</td>
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = this.dataset.href;
            });
        });
    });
</script>
@endpush
@endsection 