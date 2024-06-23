<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $globalSetting->favicon_url }}">
    <link rel="manifest" href="{{ $globalSetting->favicon_url }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ $globalSetting->favicon_url }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('vendor/css/all.min.css') }}">

    <!-- Template CSS -->
    <link href="{{ asset('vendor/froiden-helper/helper.css') }}" rel="stylesheet">
    <link type="text/css" rel="stylesheet" media="all" href="{{ asset('css/main.css') }}">

    <title>{{ $globalSetting->global_app_name }}</title>


    @stack('styles')
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

    <style>
        .login_header {
            background-color: {{ $globalSetting->logo_background_color }}                                           !important;
        }

    </style>
    @include('sections.theme_css')
    @if ($globalSetting->login_background_url)
        <style>
            .login_section {
                background: url("{{ $globalSetting->login_background_url }}") center center/cover no-repeat !important;
            }

        </style>
    @endif

    @if(file_exists(public_path().'/css/login-custom.css'))
        <link href="{{ asset('css/login-custom.css') }}" rel="stylesheet">
    @endif

    @if ($globalSetting->sidebar_logo_style == 'full')
        <style>
            .login_header img {
                max-width: unset;
            }
        </style>
    @endif

</head>

<body class="{{ $globalSetting->auth_theme == 'dark' ? 'dark-theme' : '' }}">

<header class="sticky-top d-flex justify-content-center align-items-center login_header bg-white px-4">
    <img class="mr-2 rounded" src="{{asset('storage/crm_travel_logo.png')}}" alt="Logo"/>
{{--    @if ($globalSetting->sidebar_logo_style != 'full')--}}
{{--        <h3 class="mb-0 pl-1 {{ $globalSetting->auth_theme_text == 'light' ? ($globalSetting->auth_theme == 'dark' ? 'text-dark' : 'text-white') : '' }}">{{ $globalSetting->global_app_name ?? $globalSetting->app_name }}</h3>--}}
{{--    @endif--}}
</header>


<section class="bg-grey py-5 login_section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">

                <div class="login_box mx-auto rounded bg-white text-center">
                    {{ $slot }}
                </div>
{{--                <div class="mx-auto text-center bg-white col-lg-6 mt-3">--}}
{{--                    <table class="table ">--}}
{{--                        <thead>--}}
{{--                        <tr class="border-bottom">--}}
{{--                            <th class="font-weight-bold">Name</th>--}}
{{--                            <th class="font-weight-bold">Mobile</th>--}}
{{--                            <th class="font-weight-bold">Password</th>--}}
{{--                            <th class="font-weight-bold">Action</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        <tr>--}}
{{--                            <td>Demo Superadmin</td>--}}
{{--                            <td>999999999</td>--}}
{{--                            <td>11111111</td>--}}
{{--                            <td>--}}
{{--                                <button class="bt btn-outline-primary p-3 copyBtn">copy</button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <td>Demo Operator</td>--}}
{{--                            <td>888888888</td>--}}
{{--                            <td>22222222</td>--}}
{{--                            <td>--}}
{{--                                <button class="bt btn-outline-primary p-3 copyBtn">copy</button>--}}
{{--                            </td>--}}
{{--                        </tr>--}}

{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                </div>--}}

                {{ $outsideLoginBox ?? '' }}


            </div>
        </div>

    </div>

</section>
<!-- Global Required Javascript -->
<script src="{{ asset('vendor/bootstrap/javascript/bootstrap-native.js') }}"></script>

<!-- Font Awesome -->
<script src="{{ asset('vendor/jquery/all.min.js') }}"></script>
<!-- Template JS -->
<script src="{{ asset('js/main.js') }}"></script>
<script>

    const MODAL_DEFAULT = '#myModalDefault';
    const MODAL_LG = '#myModal';
    const MODAL_XL = '#myModalXl';
    const MODAL_HEADING = '#modelHeading';
    const RIGHT_MODAL = '#task-detail-1';
    const RIGHT_MODAL_CONTENT = '#right-modal-content';
    const RIGHT_MODAL_TITLE = '#right-modal-title';
    $('.copyBtn').click(function (event) {

    });
    const dropifyMessages = {
        default: "@lang('app.dragDrop')",
        replace: "@lang('app.dragDropReplace')",
        remove: "@lang('app.remove')",
        error: "@lang('messages.errorOccured')",
    };
    $('.change-lang').click(function (event) {
        const locale = $(this).data("lang");
        event.preventDefault();
        let url = "{{ route('front.changeLang', ':locale') }}";
        url = url.replace(':locale', locale);
        $.easyAjax({
            url: url,
            container: '#login-form',
            blockUI: true,
            type: "GET",
            success: function (response) {
                if (response.status === 'success') {
                    window.location.reload();
                }
            }
        })
    });
</script>

{{ $scripts }}

</body>

</html>
