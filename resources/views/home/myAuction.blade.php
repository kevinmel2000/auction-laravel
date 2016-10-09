@extends('layouts.master')

@section('title', 'My auction')

@section('content')
    <div class="row my-auction">
        <div class="col-md-10 col-md-offset-1">
            <div class="row bid">
                <label class="col-md-2 col-md-offset-7">{{ trans('homeIndex.left_betting') }}</label>
                <div class="col-md-1 count">14</div>
                <div class="col-md-2">
                    <button class="btn btn-circle turquoise center-block">{{ trans('homeIndex.buy_bids') }}</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-9">
                    <div class="panel panel-auction">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-10">
                                    <h3 class="panel-title">{{ trans('homeIndex.recommend_product_header') }}</h3>
                                </div>
                                <div class="col-md-2">
                                    <i class="fa fa-bars active"></i>
                                    <i class="fa fa-th-large"></i>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>{{ trans('homeIndex.auction_header_product') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label>{{ trans('homeIndex.auction_header_time') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label>{{ trans('homeIndex.auction_header_price') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label>{{ trans('homeIndex.auction_header_user') }}</label>
                                </div>
                                <div class="col-md-1">
                                    <label>{{ trans('homeIndex.auction_header_bid') }}</label>
                                </div>
                                <div class="col-md-2">
                                    <label>{{ trans('homeIndex.auction_header_status') }}</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>{{ $recommend->template->name }}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="auction-start-timer" data-time="{{ $recommend->start_date }}"></div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="price">{{ $recommend->price }} {{ trans('homeIndex.currency') }}</div>
                                        </div>
                                        <div class="col-md-2">
                                            <label>{{ trans('homeIndex.auction_header_user') }}</label>
                                        </div>
                                        <div class="col-md-1">
                                            <label>{{ trans('homeIndex.auction_header_bid') }}</label>
                                        </div>
                                        <div class="col-md-2">
                                            <label>{{ trans('homeIndex.auction_header_status') }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="panel panel-recommend">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ trans('homeIndex.recommend_product_header') }}</h3>
                        </div>
                        <div class="panel-body">
                            @include('home.goodItem', ['product' => $recommend])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

@endpush