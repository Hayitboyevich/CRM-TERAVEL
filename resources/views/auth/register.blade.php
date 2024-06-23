<x-auth>
    <form id="login-form" action="{{ route('login') }}" class="ajax-form" method="POST">
        {{ csrf_field() }}
        <h3 class="text-capitalize mb-4 f-w-500">@lang('app.signUpAsClient')</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group text-left">
            <label for="name">@lang('app.name') <sup class="f-14 mr-1">*</sup></label>
            <input type="text" tabindex="1" name="name"
                   class="form-control height-50 f-15 light_text"
                   placeholder="@lang('placeholders.name')" id="name" autofocus>
        </div>

        <div class="form-group text-left">
            <label for="mobile">@lang('auth.mobile') <sup class="f-14 mr-1">*</sup></label>
            <input tabindex="2" type="number" name="mobile"
                   class="form-control height-50 f-15 light_text"
                   placeholder="e.g. 99 999 99 99" id="mobile">
            <input type="hidden" id="g_recaptcha" name="g_recaptcha">
        </div>

        <div class="form-group text-left">
            <label for="password">@lang('app.password') <sup class="f-14 mr-1">*</sup></label>
            <x-forms.input-group>
                <input type="password" name="password" id="password"
                       placeholder="@lang('placeholders.password')" tabindex="3"
                       class="form-control height-50 f-15 light_text">
                <x-slot name="append">
                    <button type="button" tabindex="4" data-toggle="tooltip"
                            data-original-title="@lang('app.viewPassword')"
                            class="btn btn-outline-secondary border-grey height-50 toggle-password">
                        <i
                                class="fa fa-eye"></i></button>
                </x-slot>
            </x-forms.input-group>
        </div>

        <div class="form-group text-left">
            <label for="company_name">@lang('modules.client.companyName')<sup class="f-14 mr-1">*</sup></label>
            <input type="text" tabindex="5" name="company_name"
                   class="form-control height-50 f-15 light_text"
                   placeholder="@lang('placeholders.company')" id="company_name" required>
            <!-- Error message container -->
            <div id="company_name_error" class="text-danger" style="display: none;"></div>
        </div>

        <div class="form-group text-left">
            <label for="subdomain">@lang('modules.client.subdomain')<sup class="f-14 mr-1">*</sup></label>
            <input type="text" tabindex="6" name="subdomain"
                   class="form-control height-50 f-15 light_text required"
                   placeholder=@lang('modules.client.subdomain') id="subdomain" required>
            <!-- Error message container -->
            <div id="subdomain_error" class="text-danger" style="display: none;"></div>
        </div>

{{--        @if ($globalSetting->google_recaptcha_status == 'active' && $globalSetting->google_recaptcha_v2_status == 'active')--}}
{{--            <div class="form-group" id="captcha_container"></div>--}}
{{--        @endif--}}

        @if ($errors->has('g-recaptcha-response'))
            <div class="help-block with-errors">{{ $errors->first('g-recaptcha-response') }}
            </div>
        @endif

        <button type="button" id="submit-register"
                class="btn-primary f-w-500 rounded w-100 height-50 f-18">
            @lang('app.signUp') <i class="fa fa-arrow-right pl-1"></i>
        </button>

        <a href="{{ route('login') }}"
           class="btn-secondary f-w-500 rounded w-100 height-50 f-15 mt-3">
            @lang('app.login')
        </a>
    </form>

    <x-slot name="scripts">
{{--        @if ($globalSetting->google_recaptcha_status == 'active' && $globalSetting->google_recaptcha_v2_status == 'active')--}}
{{--            <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async--}}
{{--                    defer></script>--}}
{{--            <script>--}}
{{--                var gcv3;--}}
{{--                var onloadCallback = function () {--}}
{{--                    // Renders the HTML element with id 'captcha_container' as a reCAPTCHA widget.--}}
{{--                    // The id of the reCAPTCHA widget is assigned to 'gcv3'.--}}
{{--                    gcv3 = grecaptcha.render('captcha_container', {--}}
{{--                        'sitekey': '{{ $globalSetting->google_recaptcha_v2_site_key }}',--}}
{{--                        'theme': 'light',--}}
{{--                        'callback': function (response) {--}}
{{--                            if (response) {--}}
{{--                                $('#g_recaptcha').val(response);--}}
{{--                            }--}}
{{--                        },--}}
{{--                    });--}}
{{--                };--}}
{{--            </script>--}}
{{--        @endif--}}
{{--        @if ($globalSetting->google_recaptcha_status == 'active' && $globalSetting->google_recaptcha_v3_status == 'active')--}}
{{--            <script--}}
{{--                    src="https://www.google.com/recaptcha/api.js?render={{ $globalSetting->google_recaptcha_v3_site_key }}"></script>--}}
{{--            <script>--}}
{{--                grecaptcha.ready(function () {--}}
{{--                    grecaptcha.execute('{{ $globalSetting->google_recaptcha_v3_site_key }}').then(function (token) {--}}
{{--                        // Add your logic to submit to your backend server here.--}}
{{--                        $('#g_recaptcha').val(token);--}}
{{--                    });--}}
{{--                });--}}
{{--            </script>--}}
{{--        @endif--}}

        <script>
            $(document).ready(function () {

                $('#submit-register').click(function (e) { // Ensure 'e' is passed here
                    // Clear any existing error messages
                    $('#company_name_error').hide().text('');

                    // Check if company_name is empty
                    if ($('#company_name').val().trim() === '') {
                        // Prevent form submission
                        e.preventDefault(); // Use 'e' to prevent default action

                        // Display error message
                        $('#company_name_error').text("Field is required.").show(); // Ensure this language line exists
                        return; // Stop the function here
                    }

                    $('#subdomain_error').hide().text('');

                    // Check if company_name is empty
                    if ($('#subdomain').val().trim() === '') {
                        // Prevent form submission
                        e.preventDefault(); // Use 'e' to prevent default action

                        // Display error message
                        $('#subdomain_error').text("Field is required.").show(); // Ensure this language line exists
                        return; // Stop the function here
                    }

                    // Proceed with AJAX form submission if validation passes...
                    const url = "{{ route('register-company') }}";

                    $.easyAjax({
                        url: url,
                        container: '.login_box',
                        disableButton: true,
                        buttonSelector: "#submit-register",
                        type: "POST",
                        blockUI: true,
                        data: $('#login-form').serialize(),
                        success: function (response) {
                            window.location.href = "{{ route('dashboard') }}";
                        }
                    });
                });


                @if (session('message'))
                Swal.fire({
                    icon: 'error',
                    text: '{{ session('message') }}',
                    showConfirmButton: true,
                    customClass: {
                        confirmButton: 'btn btn-primary',
                    },
                    showClass: {
                        popup: 'swal2-noanimation',
                        backdrop: 'swal2-noanimation'
                    },
                })
                @endif

            });
        </script>
    </x-slot>

</x-auth>
