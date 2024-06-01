
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include("layout.template.adminlte-3-1-0.head")
    </head>
    <body class="sidebar-mini" style="height: auto;">
        @include("layout.template.adminlte-3-1-0.content")
    </body>
    <footer>
        @include("layout.template.adminlte-3-1-0.footer")
    </footer>
</html>
