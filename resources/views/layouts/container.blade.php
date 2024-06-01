
<div id="app">
    <?php
        $shuffle_list = \App\Models\FileUpload::where(["target_name"=>"business_backgrounds","target_id"=>1])->orderBy('is_default', 'DESC')->get();
        $default_image = '/images/21oktober background 8.jpg'; //'/image-preview/70';
        $playList = [];
        $shuffle_info = \App\Helpers\AppVarHelper::get(['allow_shuffle','shuffle_duration']);// ?: 0;
        $allow_shuffle = $shuffle_info['allow_shuffle'] ?: 0;
        $shuffle_duration = $shuffle_info['shuffle_duration'] ?: 120;
        if(count($shuffle_list)>0){
            $default_image = $shuffle_list[0]->public_link;
            if($allow_shuffle == 1){
                foreach($shuffle_list as $l){
                    $playList[] = $l->public_link;
                }
                ?>
                <script>
                    window.shuffle ={};
                    window.shuffle.list = <?=json_encode( $playList)?>;                    
                    window.shuffle.duration = <?=$shuffle_duration?>;
                    window.shuffle.counter = 1;
                    if(window.shuffle.list.length > 1){
                        setInterval(() => {
                            var shuffle_image = window.shuffle.list[window.shuffle.counter];
                            document.querySelector('#body-background').style.backgroundImage = 'url(\''+shuffle_image+'\')';
                            window.shuffle.counter++; 
                            if(window.shuffle.counter >= window.shuffle.list.length){
                                window.shuffle.counter = 0;
                            }
                        },  window.shuffle.duration * 1000);
                    }
                </script>
            <?php
            }
        }
    ?>
    <div id="body-background" style="@yield('bg_image_style');background-image:url('{{$default_image}}');">
        <div style="@yield('bg_color_style')">
            <nav class="navbar navbar-expand-md @yield('nav_bar_color','navbar-light shadow-sm')">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img src="{{ \App\Helpers\AppVarHelper::get('business_logo_url', '/images/email-icon.png') }}" style="max-height:30px; width:auto;"/>
                        {{ \App\Helpers\AppVarHelper::get('business_name',  config('app.name', 'Laravel') )  }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">

                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ms-auto">
                            <!-- Authentication Links -->
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">{{ __('Help') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">{{ __('Contact') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">{{ __('Registration') }}</a>
                            </li>
                            @auth('admin')
                                <li class="nav-item">
                                    <a class="nav-link text-white" href="/manage">{{ __('Manage') }}</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle  text-white" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                <!--
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif-->
                            @else 
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link text-white" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>

            <main style="min-height:70vh;">
                @yield('content')
            </main>
        </div>
    </div>
</div>