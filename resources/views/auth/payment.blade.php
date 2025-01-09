@extends('layout.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registration Payment</div>

                <div class="card-body">
                    <h4 class="mb-4">Registration Price: Rp {{ number_format($registrationPrice, 0, ',', '.') }}</h4>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('overpaid'))
                        <div class="alert alert-warning">
                            <p>Sorry you overpaid by Rp {{ number_format(session('excess'), 0, ',', '.') }}</p>
                            <p>Would you like to enter {{ session('coins') }} coins to your wallet balance?</p>
                            <form action="{{ route('register.payment.handle-overpay') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" name="choice" value="yes" class="btn btn-success me-2">Yes</button>
                                <button type="submit" name="choice" value="no" class="btn btn-danger">No</button>
                            </form>
                        </div>
                    @else
                        <form method="POST" action="{{ route('register.payment.process') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="payment" class="form-label">Enter Payment Amount</label>
                                <input type="number"
                                       class="form-control"
                                       id="payment"
                                       name="payment"
                                       required
                                       min="0">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Payment</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
