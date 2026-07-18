@extends('pet/theme')
@section('title', 'ข่าวสาร')
@section('content')
@include('pet/menu')
<!-- Masthead-->
<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">
        <!-- Masthead Heading-->
        <h1 class="masthead-heading text-uppercase mb-0">ข่าวสารโครงการจัดการปัญหาสุนัข</h1>
        <!-- Icon Divider-->
        <div class="divider-custom divider-light">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon"><i class="fas fa-paw"></i></div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- Masthead Subheading-->
        <p class="masthead-subheading font-weight-light mb-0" style="font-size: 38px">โครงการจัดการปัญหาสุนัข</p>
    </div>
</header>
<section class="page-section portfolio" id="info">
    <div class="container">
        <!-- Carousel-->
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Carousel -->
                @for($i=0;  $i< count($news); $i+=3)
                    <div class="carousel-item {{ $i==0 ? "active" : "" }}">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                <!-- Item 1-->
                                    <div class="card">
                                        <div class="card-body">
                                            <h1 class="card-title">{{ isset($news[$i]->title) ? $news[$i]->title : 'ไม่มีชื่อเรื่อง' }}</h1>
                                            <h3 class="card-subtitle mb-2 text-muted">{{ isset($news[$i]->subtitle) ? $news[$i]->subtitle : 'ไม่มีชื่อเรื่อง' }}</h3>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary" data-bs-toggle="modal" onClick="message({{ isset($news[$i]->id) ? $news[$i]->id : '' }})" style="font-size: 24px">รายละเอียด</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Item 2-->
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <h1 class="card-title">{{ isset($news[$i+1]->title) ? $news[$i+1]->title : 'ไม่มีชื่อเรื่อง' }}</h1>
                                            <h3 class="card-subtitle mb-2 text-muted">{{ isset($news[$i+1]->subtitle) ? $news[$i+1]->subtitle : 'ไม่มีชื่อเรื่อง' }}</h3>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary" data-bs-toggle="modal" onClick="message({{ isset($news[$i+1]->id) ? $news[$i+1]->id : '' }})" style="font-size: 24px">รายละเอียด</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Item 3-->
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <h1 class="card-title">{{ isset($news[$i+2]->title) ? $news[$i+2]->title : 'ไม่มีชื่อเรื่อง' }}</h1>
                                            <h3 class="card-subtitle mb-2 text-muted">{{ isset($news[$i+2]->subtitle) ? $news[$i+2]->subtitle : 'ไม่มีชื่อเรื่อง' }}</h3>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-primary" data-bs-toggle="modal" onClick="message({{ isset($news[$i+2]->id) ? $news[$i+2]->id : '' }})" style="font-size: 24px">รายละเอียด</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="d-flex justify-content-center mt-4">
            {{ $news->links() }}
        </div>
    </div>
</section>
@endsection
