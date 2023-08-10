
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">{{__('Reset Password')}}</div>
                  <div class="card-body">
  
                    @if (Session::has('message'))
                         <div class="alert alert-success" role="alert">
                            {{ Session::get('message') }}
                        </div>
                    @endif
  
                      <form action="" method="POST">
                          @csrf
                          <div class="form-group row">
                            
                              <div class="col-md-6">
                                <h3> {{__('your password is:') . $password}}</h3>
                                <h3> {{__('your member ship number is:') . $member_ship_number}}</h3>
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
