@extends('theme/mdb')
@section('title', 'จัดการข้อมูล (สำหรับผู้ดูแลระบบ)')
@section('content')
    <div class="container mt-4">
        <div class="row row-cols-2 row-cols-md-5 g-3 mb-4">
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="fw-bold mb-0">{{ $stats['total_dogs'] }}</h3>
                        <div class="text-muted">สุนัขทั้งหมด</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="fw-bold mb-0">{{ $stats['alive_dogs'] }}</h3>
                        <div class="text-muted">มีชีวิตอยู่</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="fw-bold mb-0">{{ $stats['deceased_dogs'] }}</h3>
                        <div class="text-muted">เสียชีวิตแล้ว</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="fw-bold mb-0">{{ $stats['news_count'] }}</h3>
                        <div class="text-muted">ข่าวสาร</div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <h3 class="fw-bold mb-0">{{ $stats['contact_count'] }}</h3>
                        <div class="text-muted">ข้อความติดต่อ</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <div class="col">
                <div class="card bg-dark text-body bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img src="{{ asset('source/home_4.jpg') }}" class="card-img" alt="..." />
                    <div class="card-img-overlay">
                        <h2 class="card-title fw-bold">จัดการข้อมูลสุนัข</h2>
                    </div>
                    <a href="{{ url('/') }}/dog">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card bg-dark text-black bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img src="{{ asset('source/home_2.png') }}" class="card-img" alt="..." />
                    <div class="card-img-overlay">
                        <h2 class="card-title fw-bold">จัดการข้อความ</h2>
                    </div>
                    <a href="{{ url('/') }}/contact">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </a>
                </div>
            </div>
            <div class="col">
                <div class="card bg-dark text-white bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img src="{{ asset('source/home_3.jpg') }}" class="card-img" alt="..." />
                    <div class="card-img-overlay">
                        <h2 class="card-title fw-bold">จัดการข้อมูลข่าวสาร</h2>
                    </div>
                    <a href="{{ url('/') }}/message">
                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
