

<div class="card">
    <div class="card-header">{{ __('Account Login') }}</div>
    <div class="card-body"> 
        <form method="POST" action="{{ route('pay-login') }}">
            @csrf                 
            <span class="invalid-feedback text-primary d-block" role="alert">
                <strong> Please login your account.  </strong>
            </span> 
            <div class="row mb-3">
                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>
                
                <div class="col-md-6">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ (old('email')?: $email ) }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback  d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                                        
                </div>
            </div>
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">         
                    @error('verify_email')
                        <span class="invalid-feedback" role="alert" style="display:block;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <button type="submit" class="btn btn-primary">
                        {{ __('Login Now') }} <span class="fa fa-arrow-right"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer"> 
        <label>
            <forgot-password-link></forgot-password-link>
        </label>
    </div>
</div>