<!-- @props(['role'])

@php
$menu = [];
if($role == 'admin'){
    $menu = [
        ['name'=>'Trang chủ','route'=>'admin.dashboard'],
        ['name'=>'Quản lý người dùng','route'=>'admin.users'],
        ['name'=>'Quản lý đồ án','route'=>'admin.detai'],
        ['name'=>'Thống kê','route'=>'admin.thongke'],
        ['name'=>'Thông báo','route'=>'admin.thongbao'],
    ];
} elseif($role == 'giangvien'){
    $menu = [
        ['name'=>'Trang chủ','route'=>'giangvien.dashboard'],
        ['name'=>'Quản lý đồ án','route'=>'giangvien.detai'],
        ['name'=>'Chấm điểm','route'=>'giangvien.chamdiem'],
        ['name'=>'Xem tiến độ','route'=>'giangvien.tiendo'],
        ['name'=>'Thông báo','route'=>'giangvien.thongbao'],
    ];
} elseif($role == 'sinhvien'){
    $menu = [
        ['name'=>'Trang chủ','route'=>'sinhvien.dashboard'],
        ['name'=>'Đề tài','route'=>'sinhvien.detai'],
        ['name'=>'Báo cáo tiến độ','route'=>'sinhvien.baocao'],
        ['name'=>'Xem điểm','route'=>'sinhvien.xemdiem'],
        ['name'=>'Thông báo','route'=>'sinhvien.thongbao'],
    ];
} elseif($role == 'canbo'){
    $menu = [
        ['name'=>'Trang chủ','route'=>'canbo.dashboard'],
        ['name'=>'Quản lý sinh viên','route'=>'canbo.sinhvien'],
        ['name'=>'Quản lý giảng viên','route'=>'canbo.giangvien'],
        ['name'=>'Quản lý đồ án','route'=>'canbo.detai'],
        ['name'=>'Thông báo','route'=>'canbo.thongbao'],
    ];
}
@endphp

<aside x-data="{ open: false }" class="bg-white w-64 md:block hidden flex-shrink-0 shadow">
    <div class="p-4 border-b flex justify-between items-center md:block">
        <h1 class="font-bold text-lg">{{ ucfirst($role) }}</h1>
        <button class="md:hidden" @click="open = !open">☰</button>
    </div>

    <nav :class="{'block': open, 'hidden': !open}" class="p-4 space-y-2 md:block">
        @foreach($menu as $item)
            <a href="{{ route($item['route']) }}"
               class="block p-2 rounded hover:bg-gray-200 {{ request()->routeIs($item['route']) ? 'bg-gray-300 font-bold' : '' }}">
                {{ $item['name'] }}
            </a>
        @endforeach
    </nav>

    <form method="POST" action="{{ route('logout') }}" class="p-4 border-t">
        @csrf
        <button type="submit" class="w-full p-2 bg-red-500 text-white rounded hover:bg-red-600">Đăng xuất</button>
    </form>
</aside>
  -->