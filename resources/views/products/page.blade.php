@extends('layouts.master')
@section('title', trans('adminProducts.products_page_title'))

@section('content')
    <div class="row product-page">
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <h2 class="col-md-12"><strong>{{ $product->template->name }}</strong></h2>
                    </div>
                    <div class="row">
                        <div id="loading" class="col-md-12" style="height: 500px">
                            <div class="sk-circle">
                                <div class="sk-circle1 sk-child"></div>
                                <div class="sk-circle2 sk-child"></div>
                                <div class="sk-circle3 sk-child"></div>
                                <div class="sk-circle4 sk-child"></div>
                                <div class="sk-circle5 sk-child"></div>
                                <div class="sk-circle6 sk-child"></div>
                                <div class="sk-circle7 sk-child"></div>
                                <div class="sk-circle8 sk-child"></div>
                                <div class="sk-circle9 sk-child"></div>
                                <div class="sk-circle10 sk-child"></div>
                                <div class="sk-circle11 sk-child"></div>
                                <div class="sk-circle12 sk-child"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="slider hidden" id="slider">
                                @foreach($product->template->photos as $photo)
                                    <div>
                                        <img src="{{ asset('assets/templates/photos/' . $photo->name) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="slider-viewer hidden" id="slider-viewer">
                                @foreach($product->template->photos as $photo)
                                    <div>
                                        <img src="{{ asset('assets/templates/photos/' . $photo->name) }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h4><strong>{{ trans('homeIndex.product_information') }}</strong></h4>
                        <iframe frameborder="0" scrolling="no" width="100%" height="auto" onload="this.style.height = this.contentDocument.body.scrollHeight + 'px';" src="{{ route('product.information', [$product->template->id]) }}"></iframe>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row buy-bid">
                        <div class="col-md-12">
                            <button class="btn btn-circle turquoise center-block">{{ trans('homeIndex.buy_bids') }}</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-offset-2 col-md-8">
                            <div class="auction" data-key="{{ $product->mongo_id }}">
                                <div data-type="1" class="soon-start {{ $product->status != 1 ? 'hidden' : '' }}">
                                    <h2 class="center-block">{{ trans('homeIndex.soon_start_upper') }}</h2>
                                    <div class="auction-start-timer" data-time="{{ $product->start_date }}"></div>
                                    <button class="btn turquoise center-block">{{ trans('homeIndex.apply') }}</button>
                                </div>
                                <div data-type="2" class="auction-process {{ $product->status != 2 ? 'hidden' : '' }}">
                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="current-price">
                                                        <label>{{ trans('homeIndex.current_price') }}</label>
                                                        <span><span id="current_price">100</span> руб</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div id="auction-timer" class="auction-timer ">
                                                        <div class="text">15</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <button class="btn turquoise center-block make-bid">{{ trans('homeIndex.goods_count') }}</button>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="auction-type center-block">
                                                <div>
                                                    <input type="checkbox" checked="checked" id="manual">
                                                    <label for="manual">{{ trans('homeIndex.manual') }}</label>
                                                </div>
                                                <div>
                                                    <input type="checkbox" id="automatic">
                                                    <label for="automatic">{{ trans('homeIndex.automatic') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">
                                            <div class="current-winner">
                                                <span>{{ trans('homeIndex.current_winner') }}</span>
                                                <span id="current-winner"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">
                                            <div class="auction-history">
                                                <table>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div data-type="3" class="auction-finished {{ $product->status != 3 ? 'hidden' : '' }}">
                                    <div class="finished-date">
                                        <h3 class="center-block">{{ trans('homeIndex.auction_finished') }}</h3>
                                        <h3 class="center-block" data-date="{{ $product->end_date }}"></h3>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-offset-1 col-md-10">
                                            <div class="current-winner">
                                                <span>{{ trans('homeIndex.current_winner') }}</span>
                                                <span id="current-winner"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row term-header">
                        <div class="col-md-10 col-md-offset-2">
                            <h4><strong>{{ trans('homeIndex.auction_terms') }}</strong></h4>
                        </div>
                    </div>
                    <div class="row term-item">
                        <div class="col-md-2">
                            <img src="{{ asset('assets/img/auction/terms/1.png') }}" class="term-icon pull-right">
                        </div>
                        <div class="col-md-10">
                            <p>{{ trans('homeIndex.auction_term_1') }}</p>
                        </div>
                    </div>
                    <div class="row term-item">
                        <div class="col-md-2">
                            <img src="{{ asset('assets/img/auction/terms/2.png') }}" class="term-icon pull-right">
                        </div>
                        <div class="col-md-10">
                            <p>{{ trans('homeIndex.auction_term_2') }}</p>
                        </div>
                    </div>
                    <div class="row term-item">
                        <div class="col-md-2">
                            <img src="{{ asset('assets/img/auction/terms/3.png') }}" class="term-icon pull-right">
                        </div>
                        <div class="col-md-10">
                            <p>{{ trans('homeIndex.auction_term_3') }}</p>
                        </div>
                    </div>
                    <div class="row term-item">
                        <div class="col-md-2">
                            <img src="{{ asset('assets/img/auction/terms/4.png') }}" class="term-icon pull-right">
                        </div>
                        <div class="col-md-10">
                            <p>{{ trans('homeIndex.auction_term_4') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('css')
    <link href="{{ asset('assets/css/slick.css') }}" rel="stylesheet" type="text/css">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/slick.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/circle-progress.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('#slider').slick({
                centerMode: true,
                vertical: true,
                slidesToShow: 3,
                slidesToScroll: 1,
                asNavFor: '#slider-viewer',
                focusOnSelect: true,
                prevArrow: '<i class="fa fa-angle-up center-block"></i>',
                nextArrow: '<i class="fa fa-angle-down center-block"></i>',
                onInit: function() {
                    $('#slider').removeClass('hidden');
                    $('#loading').addClass('hidden');
                }
            });

            $('#slider-viewer').slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '#slider',
                onInit: function() {
                    $('#slider-viewer').removeClass('hidden');
                }
            });

            @if($product->status == 2)
                $('#auction-timer').circleProgress({
                    startAngle: -Math.PI/2,
                    value: 1,
                    size: 90,
                    emptyFill: 'rgba(232, 232, 232, 1)',
                    thickness: 6,
                    fill: {color: '#45B6AF'}
                });
            @elseif($product->status == 3)
                var $end = $('.finished-date [data-date]');
                $end.text(SystemProvider.getLocalDate($end.data('date'), 'moment').format('DD/MM/YYYY HH:mm:ss'));
            @endif
            @if($product->status != 3)
                var event = setInterval(function () {
                    if (window.node !== undefined) {
                        clearInterval(event);

                        window.node.emit('productViewer', {
                            products: ['{{ $product->mongo_id }}'],
                            token: SystemProvider.cookies['node']
                        });
                    }
                }, 100);
            @endif
        });
    </script>
@endpush