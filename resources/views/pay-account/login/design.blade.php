  
    <?php
      //Customise icon
      $logoUrl = \iProtek\Core\Helpers\AppVarHelper::get('business_logo_url', '');
    ?>  
  
  
  <div class="login-box w-100">
    <div class="login-logo">
      @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ config('app.name') }}" class="brand-image elevation-3 round-50" style="border-radius:50%; max-height:100px;" >
      @else
        {{ config('app.name') }}
      @endif
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session.</p>
        @if(!config('iprotek_account.url') || config('iprotek.app_type') == 'ACCOUNT SYSTEM' )
          <form method="POST" action="{{ route('pay-login') }}">
            @csrf   
            <div>
              <div class="input-group mb-3">
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ (old('email')?: $email ) }}" required autocomplete="email" autofocus>
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
                @error('email')
                  <span class="invalid-feedback d-block" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div>
              <div class="input-group mb-3">
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div> 
              @error('password')
                <span class="invalid-feedback  d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            <div class="row">
              <div class="col-sm-12 text-center">
                    
                  @error('verify_email')
                        <span class="invalid-feedback" role="alert" style="display:block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror 

              </div>
              <div class="col-8">
                <div class="icheck-primary">
                  <input type="checkbox" checked disabled>
                  <label for="remember">
                    Remember Me
                  </label>
                </div>
              </div>
              <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
              </div> 
            </div> 

            <!--<div class="social-auth-links text-center mb-3">
              <p>- OR -</p>
              <a href="#" class="btn btn-block btn-primary">
                <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
              </a>
              <a href="#" class="btn btn-block btn-danger">
                <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
              </a>
            </div>-->
            <!-- /.social-auth-links -->
          </form>

          <p class="mb-1">
            <forgot-password-link></forgot-password-link>
          </p>
          <!--<p class="mb-0">
            <a href="register.html" class="text-center">Register a new membership</a>
          </p>-->
        @else 
          @error('email')
          <span class="invalid-feedback d-block" role="alert">
              <strong>{{ $message }}</strong>
          </span>
          <br/>
          @enderror
          <div class="col-12 text-center"><button onclick="clickPopUp()" class="btn btn-primary btn-block">Sign In</button></div>
        @endif
        </div>
    </div>
  </div>