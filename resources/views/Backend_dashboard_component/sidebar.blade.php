<div class="sidebar">
    <ul>
        <li>
            <img src="{{ asset('img/a7.jpg') }}" alt="Icon">
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </li>
        <li>
            <img src="{{ asset('img/a4.jpg') }}" alt="Icon">
            <a href="{{ url('/admin/khachhang') }}">Quản lý khách hàng</a>
        </li>
        <li>
            <img src="{{ asset('img/a1.jpg') }}" alt="Icon">
            <a href="{{ url('/admin/quangcao') }}">Quản lý quảng cáo</a>
        </li>
        <li>
            <img src="{{ asset('img/logout.jpg') }}" alt="Icon">
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>