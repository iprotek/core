@section('nav_bar_color', 'navbar-dark shadow-sm')

@extends('iprotek_core::layouts.app')

@section('content')
    <?php
        $email = isset($email) ? $email : '';//auth()->user()->email;
        $login_request_id = 0;
        $login_request_code = "";
    ?>
    <div id="main-content" class="contianer" style="min-height:85vh;">
        <div class="row mx-4 justify-content-center">
            <div class="col-sm-4 text-dark mt-5" style="min-width:350px;"> 
                    @if(!auth('admin')->check()) 
                        @include('iprotek_core::pay-account.login.design')
                    @else
                        <code>You are already logged.</code>
                    @endif
            </div>
        </div>
        <!-- ADD SOME POP-UP LOG-IN HERE FOR ACCOUNT WEBSITE -->
         @if(config('iprotek_account.url') && config('iprotek.app_type') != 'ACCOUNT SYSTEM')
            <!-- CHECKIF IF THE ACCOUNT SYSTEM HAS LOGGED IN -->
                @if(!auth('admin')->check())
                     <?php
                        $resp = \iProtek\Account\Helpers\AccountHelper::submitLoginRequest(request());
                        if($resp["status"] == 1){
                            if($resp["result"] && $resp["result"]["status"] == 1){
                                $login_request_id = $resp["result"]["id"];
                                $login_request_code = $resp["result"]["code"];
                            }
                        }
                     ?>
                     <form id="login-request-form" method="POST">
                        @csrf   
                        <input type="hidden" id="login-request-id" name="login_request_id" value="{{$login_request_id}}" />
                        <input type="hidden" id="login-request-code" name="login_code" value="{{$login_request_code}}" />
                        <input type="hidden" id="login-account-auth-code" name="login_account_auth_code" value="" />
                     </form>

                @endif
         @endif

    </div>
@endsection

@section('foot')
    @include('iprotek_core::layouts.copyright-footer')
    <script src="/iprotek/design/templates/adminlte3.1.0/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/iprotek/js/pay-forgot-password.js"></script>
    <!--<script src="/js/app.js"></script>-->
    <!-- POP-UP FOR LOGIN SCRIPTING ON ACCOUNT WEBSITE HERE -->
    @if(config('iprotek_account.url') && config('iprotek.app_type') != 'ACCOUNT SYSTEM')
        <script>
            function queryString(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            }
            function clickPopUp(){
                
                const popupWidth = 600;
                const popupHeight = 600;

                // Current window position (even across multiple monitors)
                const dualScreenLeft = window.screenX !== undefined ? window.screenX : window.screenLeft;
                const dualScreenTop = window.screenY !== undefined ? window.screenY : window.screenTop;

                // Current window size
                const windowWidth = window.innerWidth || document.documentElement.clientWidth || screen.width;
                const windowHeight = window.innerHeight || document.documentElement.clientHeight || screen.height;

                // Center popup within the current screen
                const left = dualScreenLeft + (windowWidth - popupWidth) / 2;
                const top = dualScreenTop + (windowHeight - popupHeight) / 2;

                var url = encodeURIComponent('{{request()->fullUrl()}}'); 
                var qstr = queryString({ login_request_id: '{{$login_request_id}}', requestor_origin_url: url});
                const popup = window.open(
                    'http://account.iprotek.internal/handshake/login-request?'+qstr, 
                    'authPopup', 
                    `scrollbars=yes,resizable=yes,width=${popupWidth},height=${popupHeight},top=${top},left=${left}`);
                //USE THIS SCRIPT ON POPU - and click button authorize when success
                //window.opener.postMessage({ token: 'abc123' }, 'http://your-main-app.internal');
                window.isTriggered = false;
                window.addEventListener('message', (event) => {
                        if(window.isTrigger) return;
                        window.isTrigger = true;
                        //console.log('Received message:', event.data, event.origin);
                        if(event.data.code == '{{$login_request_code}}'){
                            //LOGIN VALIDATION
                            console.log("Its validated, and trying to renders from auths", event.data);
                            document.querySelector('#login-account-auth-code').value = event.data.account_auth_code;
                            const form = document.querySelector('#login-request-form');
                            form.submit();
                        }
                        if(event.data && event.data.is_close){
                            //console.log("CLOSED");
                            popup.close();
                        }
                    //}
                });
            }
        </script>
        
    @endif
@endsection