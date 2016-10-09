@extends('layouts.master')

@section('title', trans('profile.page_title'))

@push('css')
    <link href="{{ URL::asset('assets/css/components.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ URL::asset('assets/js/metronic.js') }}" type="text/javascript"></script>
@endpush

@section('content')
    <section class="container bets-wrapper">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class="panel-title"><strong>ДОСТУПНЫЕ ПАКЕТЫ СТАВОК</strong></h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12 font-md">
                            @if (session('robokassa_status') == 'success')
                                <div class="alert alert-success">
                                    Вы успешно купили ставки.
                                </div>
                            @elseif(session('robokassa_status') == 'fail')
                                <div class="alert alert-danger">
                                    Что то неправильно пошло.
                                </div>
                            @endif
                            <p>
                                Покупка пакетов ставок осуществляется через сервис приема платежей "Робокасса" любым удобным для вас способом. После нажатия кнопки "Купить ставки" вы будете перенаправлены на страницу платежной системы, где необходимо будет осуществить оплату. Как только от сервиса "Робокасса" будет получено подтверждение покупки, мы зачислим приобретенное количество ставок на ваш счет в системе. Срок действия ставок – 1 год со дня покупки.

                                <p class="text-primary"><span>Увеличивайте ваши шансы на победу в аукционах.</span></p>

                                <p>Покупайте большие пакеты ставок по более выгодной цене — чем больше пакет, тем ниже цена одной ставки!</p>
                            </p>
                        </div>
                    </div>
                    <br>
                    <form id="form_buy_bets_package" action="payment/robokassa" method="POST" data-model="no" role="form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row bets">
                            <h6 class="col-xs-12 col-md-12">Выберите пакет ставок: </h6>
                            <div class="col-xs-12 col-md-12">
                                <div class="row bets-items">
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <img src="{{ asset('assets/img/bets/icon-bet15.png') }}" alt="Bet 15" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet15" name="betpack" class="md-radiobtn" value="15" checked>
                                                    <label for="radio_bet15">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>15 ставок</b> за 300 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <img src="{{ asset('assets/img/bets/icon-bet25.png') }}" alt="Bet 15" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet25" name="betpack" class="md-radiobtn" value="25">
                                                    <label for="radio_bet25">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>25 ставок</b> за 550 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <div class="badge-bet summer-sky font-xxs"> <span>Топ продаж</span> </div>
                                            <img src="{{ asset('assets/img/bets/icon-bet50.png') }}" alt="Bet 50" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet50" name="betpack" class="md-radiobtn" value="50">
                                                    <label for="radio_bet50">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>50 ставок</b> за 1000 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <img src="{{ asset('assets/img/bets/icon-bet100.png') }}" alt="Bet 100" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet100" name="betpack" class="md-radiobtn" value="100">
                                                    <label for="radio_bet100">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>100 ставок</b> за 1300 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <div class="badge-bet roman font-md"><span>-30%</span></div>
                                            <img src="{{ asset('assets/img/bets/icon-bet250.png') }}" alt="Bet 250" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet250" name="betpack" class="md-radiobtn" value="250">
                                                    <label for="radio_bet250">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>250 ставок за</b> <del> 1500 </del> руб.
                                                        <div class="text-right text-danger">1300 руб.</div>
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <img src="{{ asset('assets/img/bets/icon-bet500.png') }}" alt="Bet 500" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet500" name="betpack" class="md-radiobtn" value="500">
                                                    <label for="radio_bet500">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>500 ставок</b> за 2000 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <img src="{{ asset('assets/img/bets/icon-bet1000.png') }}" alt="Bet 1000" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet1000" name="betpack" class="md-radiobtn" value="1000">
                                                    <label for="radio_bet1000">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>1000 ставок</b> за 2900 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                    <div class="col-xs-6 col-md-3">
                                        <figure class="figure bet-item">
                                            <img src="{{ asset('assets/img/bets/icon-bet2500.png') }}" alt="Bet 2500" class="img-thumbnail">
                                            <figcaption class="figure-caption">
                                                <div class="md-radio">
                                                    <input type="radio" id="radio_bet2500" name="betpack" class="md-radiobtn" value="2500">
                                                    <label for="radio_bet2500">
                                                        <span class="inc"></span>
                                                        <span class="check"></span>
                                                        <span class="box"></span>
                                                        <b>2500 ставок</b> за 4000 руб.
                                                    </label>
                                                </div>
                                            </figcaption>
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row payment">
                            <h6 class="col-xs-12 col-md-12"> Способ оплаты: </h6>
                            <div class="col-md-11 col-md-offset-1">
                                <ul class="list-inline">
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-visa-master-card.png') }}" alt="Visa MasterCard" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-qiwi.png') }}" alt="Bet 15" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-yandex.png') }}" alt="Bet 15" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-mts.png') }}" alt="Bet 15" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-megafon.png') }}" alt="Bet 15" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-beeline.png') }}" alt="Bet 15" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                    <li>
                                        <figure class="figure">
                                            <div class="pm-img-thumbnail">
                                                <img src="{{ asset('assets/img/payments/icon-pm-tele2.png') }}" alt="Bet 15" class="img-responsive">
                                            </div>
                                        </figure>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row pay-action">
                        <div class="row text-center">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-long summer-sky btn-circle curious-blue-stripe buy">КУПИТЬ СТАВКИ</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>
@stop