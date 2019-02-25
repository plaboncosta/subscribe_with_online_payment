@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Manage Subcripstions</h4>
                    @if (Auth::user()->subscription('primary') && Auth::user()->subscription('primary')->onGracePeriod())
                        <div class="alert alert-danger">
                            You are in grace period. So you will get advantage of your Subscription time.
                        </div>
                    @endif

                    @if (Auth::user()->subscription('primary') && Auth::user()->subscription('primary')->onTrial())
                        <div class="alert alert-info">
                            I hope you will enjoy your tiral period.
                        </div>
                    @endif

                    @if(Auth::user()->subscribed('primary'))
                        <p>You are subscribed!</p>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Invoice Date</th>
                                    <th scope="col">Total</th>
                                    <th scope="col">Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (Auth::user()->invoices() as $invoice)
                                    <tr>
                                        <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                                        <td>{{ $invoice->total() }}</td>
                                        <td><a href="/user/invoice/{{ $invoice->id }}">Download</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            </table>

                        @if (!Auth::user()->subscription('primary')->onGracePeriod())
                            @if (Auth::user()->subscribedToPlan('monthly', 'primary'))
                                <a href="/pay/yearly" class="btn btn-primary">Upgrade to yearly</a>  
                            @else
                                <a href="/pay/monthly" class="btn btn-primary">Downgrade to monthly</a>
                            @endif
                        <a href="/cancel" class="btn btn-danger">Cancel</a>
                        @endif
                    @else
                    <h5>Subscribe: </h5>
                    <form action="/pay/monthly" method="POST">
                        @csrf
                      <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="{{ env('STRIPE_KEY') }}"
                        data-amount="1000"
                        data-name="Cashier Inc."
                        data-description="Monthly Subscripstions"
                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                        data-locale="auto" data-label="Subscribe Monthly" data-panel-label="Subscribe">
                      </script>
                    </form>
                    
                    <form style="margin-top: 10px;" action="/pay/yearly" method="POST">
                       @csrf 
                      <script
                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="{{ env('STRIPE_KEY') }}"
                        data-amount="10000"
                        data-name="Cashier Inc."
                        data-description="Yearly Subscripstions"
                        data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                        data-locale="auto" data-label="Subscribe Yearly" data-panel-label="Subscribe">
                      </script>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
