@extends('layouts.master')

@section('title')
    Laravel Shopping Cart
@endsection

@section('content')


    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4 col-sm-offset-3">
            <h1>Checkout</h1>
            <h4>Your Total: ${{$total}}</h4>
            <div id="charge-error" class="alert alert-danger {{Session::has('error') ? 'hidden' : ''}}">
                {{Session::get('error')}}
            </div>
            <form action="{{url('/checkout')}}" method="post" id="checkout-form">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="address">Адрес</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="card-name">Телефон</label>
                            <input type="text" name="tel" id="card-name" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="card-number">Email</label>
                            <input type="text" name="email" id="card-number" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="card-expiry-month">Expiration Month</label>
                                    <input type="text" id="card-expiry-month" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="card-expiry-year">Expiration Year</label>
                                    <input type="text" id="card-expiry-year" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="form-group">
                            <label for="card-cvc">CVC</label>
                            <input type="text" id="card-cvc" class="form-control" required>
                        </div>
                    </div>
                </div>
                {{csrf_field()}}
                <button type="submit" class="btn btn-success">Buy now</button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{asset('js/checkout.js')}}"></script>
@endsection