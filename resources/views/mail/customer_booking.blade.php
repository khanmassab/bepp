
<div class="cotainer">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{__('Hi ' ).$name .','}}</div>
                <div class="card-body">

                  @if (Session::has('message'))
                       <div class="alert alert-success" role="alert">
                          {{ Session::get('message') }}
                      </div>
                  @endif

                    <form action="" method="POST">
                        @csrf
                        <div class="form-group row">
                              <strong>{{__('Your Booking Id is: ') .$booking_id}}</strong>
                              <p>{{__('Use This id for review after comppletion work')}}</p>
                            <div class="col-md-12">
                           {{__('We have received the contact details you supplied, and your information about the agreed work with your trader, and have stored these securely in relation to your guarantee registration.')}}
                            </div>
                            <div class="col-md-12">
                                {{__('Once the agreed work has been completed by your tradesperson, please return to TrustATrader.com to leave a review. You will receive a reminder email about this, but you can also use the link below.')}}
                            </div>
                        </div>
                        </div>
                    </form>
                      
                </div>
            </div>
        </div>
    </div>
</div>
</main>
