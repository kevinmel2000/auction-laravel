@extends('layouts.master')

@section('title', 'Game Zone')

@section('content')
    <div class="row auction-page game-zone">
        <img src="{{ asset('assets/img/game-zone.png') }}">
        <div class="col-md-12">
            <div class="row filter-header" data-type="game_zone">
                <div class="col-md-8">
                    <div class="row">
                        <h3 class="col-md-3">{{ trans('homeIndex.filter') }}</h3>
                        <div class="filter-content">
                            <button class="btn btn-default btn-circle" data-type="time">{{ trans('homeIndex.filter_by_time') }}</button>
                            <button class="btn btn-default btn-circle" data-type="category">{{ trans('homeIndex.filter_by_category') }}</button>
                            <button class="btn btn-default btn-circle" data-type="type">{{ trans('homeIndex.filter_by_type') }}</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-circle turquoise center-block">{{ trans('homeIndex.buy_bids') }}</button>
                </div>
            </div>
            <div class="row goods">
                <div class="col-md-12">
                    <div class="goods-container" id="container">
                        @foreach($products as $product)
                            @include('home.goodItem', ['product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pagination-content center-block"></div>
                </div>
            </div>
            <div class="row closed-header">
                <div class="col-md-12">
                    <div class="row">
                        <h3 class="col-md-6">{{ trans('homeIndex.recently_complete_auctions') }}</h3>
                    </div>
                </div>
            </div>
            <div class="row goods">
                <div class="col-md-12">
                    <div class="goods-container">
                        @foreach($closed as $close)
                            @include('home.goodItem', ['product' => $close])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('assets/js/jquery.bootpag.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.filter-header').data('filter', JSON.parse('{!! $filters !!}'));

            $('.pagination-content').bootpag({
                total: '{{ $products->total() }}',
                maxVisible: '{{ $products->total() < 50 ? ceil($products->total() / 10) : 5 }}'
            });
        });
    </script>
@endpush