
<footer class="main-footer m-0 sticky-bottom py-1 bg-white">
    <div  class="row mx-4">
        <div class="col-sm-5">
            <strong>Copyright©2024 <a href="/">{{ config('app.name') }}</a>.</strong> All rights reserved.
        </div>
        <div  class="col-sm-4 text-nowrap text-center">
            <a href="/terms-and-conditions" target="_blank">Terms and Conditions</a> | <a href="/privacy-policy" target="_blank">Privacy Policy</a>
        </div> 
        <div  class="col-sm-3 text-center">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0.0.0
            </div>
        </div>
    </div>
</footer>
@if(!auth('admin')->check())
    <div id="guest-chat-container-el" style="bottom:10%; right:10%; position:fixed; z-index:10000;"> 
        <guest-chat-container/>
    </div>
    <script src="/iprotek/js/guest-chat.js"></script>
@endif
