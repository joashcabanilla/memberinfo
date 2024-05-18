<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{$titlePage}}</title>

        <!-- Website Icon -->
        <link rel="icon" href="{{asset('image/logo.png')}}" type="image/png">

        {{-- ADMIN LTE --}}
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('adminlte/plugins/fontawesome-free/css/all.min.css')}}">

        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="{{asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/jqvmap/jqvmap.min.css') }}">

        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('adminlte/dist/css/adminlte.min.css')}}">
        
        <!-- Sweetalert style -->
        <link rel="stylesheet" href="{{asset('adminlte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">

        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.css') }}">

        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.css') }}">

        <!-- fullcalendar -->
        <link rel="stylesheet" href="{{ asset('adminlte/plugins/fullcalendar/main.css') }}">

        <!-- Toastr -->
        <link rel="stylesheet" href="{{asset('adminlte/plugins/toastr/toastr.min.css')}}">

        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('adminlte/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
        
        <!-- Ekko Lightbox -->
        <link rel="stylesheet" href="{{asset('adminlte/plugins/ekko-lightbox/ekko-lightbox.css')}}">

        <!-- library or Plugins -->
        <!-- Jquery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

        <link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/sc-2.1.1/datatables.min.css" rel="stylesheet"/>

        <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/af-2.5.3/b-2.3.6/b-colvis-2.3.6/fc-4.2.2/fh-3.3.2/kt-2.9.0/r-2.4.1/sc-2.1.1/datatables.min.js"></script>

        <script src="{{asset('bootbox/bootbox.all.min.js')}}"></script>
        
        {{--css for page --}}
        <link rel="stylesheet" href="{{asset('css/app.css')}}?{{ filemtime(public_path('css/app.css'))}}" />
    </head>

    <body>
        <div class="wrapper">  
            <!-- Preloader -->
            <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{asset('image/logo.png')}}" alt="mis" height="80" width="80">
            </div> 
            @yield('content')
        </div>
    </body>
    
    {{-- script for adminlte --}}
    <!-- Bootstrap 4 -->
    <script src="{{asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- daterangepicker -->
    <script src="{{ asset('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Summernote -->
    <script src="{{ asset('adminlte/plugins/summernote/summernote-bs4.min.js') }}"></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{asset('adminlte/dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('adminlte/plugins/fullcalendar/main.js') }}"></script>
    <!-- Select2 -->
    <script src="{{asset('adminlte/plugins/select2/js/select2.full.min.js')}}"></script>

    <!-- SweetAlert App -->
    <script src="{{asset('adminlte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Toastr -->
    <script src="{{asset('adminlte/plugins/toastr/toastr.min.js')}}"></script>

    <!-- Ekko Lightbox -->
    <script src="{{asset('adminlte/plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>

    {{--script for Loading plugin --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.LoadingOverlaySetup({
        background: "rgba(255, 255, 255, 0.3)",
        fontawesome : "fa fa-spinner fa-spin",
        fontawesomeColor: "#343a40",
        image: "",
        });

        $(document).ajaxStart(function(){
            $.LoadingOverlay("show");
        });
        
        $(document).ajaxStop(function(){
            $.LoadingOverlay("hide");
        });
    </script>
    
    {{--script for page --}}
    <script src="{{asset('js/guest.js')}}?{{ filemtime(public_path('js/guest.js'))}}"></script>
</html>