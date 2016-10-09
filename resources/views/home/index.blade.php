@extends('layouts.master')

@section('title', 'Home')

@section('content')
    <div class="row">
        {{--<div class="col-md-12">--}}
            <div id="" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                </ol>
                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <div class="item active">
                        <img src="{{ URL::asset('assets/img/main/carousel/1.png') }}" alt="" style="width: 100%">
                        <div class="carousel-text">
                            <img src="{{ URL::asset('assets/img/main/rectangle.png') }}">
                            <p>НОВЫЙ iMAC НА ШАГ ВПЕРЕДИ ТЕХНОЛОГИЙ</p>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                </a>
            </div>
        {{--</div>--}}
    </div>
    <div class="row under-menu">
        <div class="col-md-4 col-sm-4">
            <img src="{{ URL::asset('assets/img/main/hammer.png') }}">
            <p class="auction">{{ Lang::get('homeIndex.under_menu_auction') }}</p>
        </div>
        <div class="col-md-4 col-sm-4">
            <img src="{{ URL::asset('assets/img/main/question-answer.png') }}">
            <p class="question-answer">{{ Lang::get('homeIndex.under_menu_question_answer') }}</p>
        </div>
        <div class="col-md-4 col-sm-4">
            <img src="{{ URL::asset('assets/img/main/dota-2.png') }}">
            <p class="dota-2">{{ Lang::get('homeIndex.under_menu_dota_2') }}</p>
        </div>
    </div>
    <div class="row goods">
        <div class="col-md-12">
            <div class="goods-container">
                @foreach($products as $product)
                    @include('home.goodItem', ['product' => $product])
                @endforeach
            </div>
        </div>
    </div>
@stop