
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include("iprotek_core::layout.template.adminlte-3-1-0.head")
    </head>
    <body class="sidebar-mini {{ \iProtek\Core\Helpers\UISettingHelper::get( 'sidebar_collapse','adminlte')}}" style="height: auto;">
        @include("iprotek_core::layout.template.adminlte-3-1-0.content")
    </body>
    <footer>
        @include("iprotek_core::layout.template.adminlte-3-1-0.footer")
    </footer>
</html>
