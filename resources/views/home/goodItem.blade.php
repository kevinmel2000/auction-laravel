<div class="goods-item {{ $product->status == 2 ? 'register' : '' }}" data-key="{{ $product->mongo_id }}">
    <a href="{{ route('product.page', [$product->id]) }}">
        <div class="goods-photo">
            <div class="header">
                <p class="pull-left">{{ $product->template->name }}</p>
                <span class="basket pull-right flaticon-interface"></span>
            </div>
            <div class="body">
                <img src="{!! $product->template->photos != null ? asset('assets/templates/photos/' . $product->template->photos[0]->name) : '' !!}">
            </div>
        </div>
        <div class="goods-info">
            <div class="row">
                <div class="col-md-12 price">
                    @if($product->status == 1)
                        <span>{{ $product->price }} руб.</span>
                    @elseif($product->status == 2)
                        <span></span>
                    @elseif($product->status == 3)
                        <span>{{ $product->price }} руб.</span>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 sand">
                    <span>
                        @if($product->status == 1)
                            <span class="flaticon-clock"></span>
                            <div class="auction-start-timer" data-time="{{ $product->start_date }}"></div>
                        @elseif($product->status == 2)
                            <span class="flaticon-clock"></span>
                            <div class="auction-timer">15</div>
                        @elseif($product->status == 3)
                            <span></span>
                            <div class="auction-end-timer" data-time="{{ $product->end_date }}"></div>
                        @endif
                    </span>
                </div>
            </div>
            <div class="row text" id="user-name">
                @if($product->status == 1)
                    <label class="text-center col-md-12 name">{{ trans('homeIndex.soon_start') }}</label>
                @elseif($product->status == 2)
                    <label class="text-center col-md-12 name"></label>
                @elseif($product->status == 3)
                    <label class="text-center col-md-12 name">name</label>
                @endif
            </div>
            <div class="bottom">
                @if($product->status == 1)
                    <button class="btn btn-circle dim-gray curious-dim-gray-stripe auction-apply" {{ !empty($product->products()->where('user_id', Auth::id())->first()) ? 'disabled' : '' }}>{{ trans('homeIndex.apply') }}</button>
                @elseif($product->status == 2)
                    <button class="btn btn-circle turquoise curious-turquoise-stripe make-bid">{{ trans('homeIndex.goods_count') }}</button>
                @elseif($product->status == 3)
                    <p class="finished">{{ trans('homeIndex.auction_finished') }}</p>
                @endif
            </div>
        </div>
    </a>
</div>