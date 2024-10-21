 @extends('layouts.guest')
 @section('title')
     {{ __('Register') }}
 @endsection

 @php
    $settings = App\Models\Utility::settings();
    $landingPageSettings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $keyArray = [];
    if (
        is_array(json_decode($landingPageSettings['menubar_page'])) ||
        is_object(json_decode($landingPageSettings['menubar_page']))
    ) {
        foreach (json_decode($landingPageSettings['menubar_page']) as $key => $value) {
            if (in_array($value->menubar_page_name, ['Terms and Conditions']) || in_array($value->menubar_page_name, ['Privacy Policy'])) {
                $keyArray[] = $value->menubar_page_name;
            }
        }
    }
 @endphp

 @section('language-bar')
     @php
         $languages = App\Models\Utility::languages();
     @endphp
     <div class="lang-dropdown-only-desk">
         <li class="dropdown dash-h-item drp-language">
             <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                 <span class="drp-text"> {{ ucFirst($languages[$lang]) }}
                 </span>
             </a>
             <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                 @foreach ($languages as $code => $language)
                     <a href="{{ route('register', [$ref, $code]) }}" tabindex="0"
                         class="dropdown-item {{ $code == $lang ? 'active' : '' }}">
                         <span>{{ ucFirst($language) }}</span>
                     </a>
                 @endforeach
             </div>
         </li>
     </div>
 @endsection

 @section('content')
     <div class="card-body">
         <div>
             <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
         </div>
         @if(session('status'))
             <div class="alert alert-danger">
                 {{ session('status') }}
             </div>
         @endif
         <form method="POST" id="registerForm" action="{{ route('register') }}" class="needs-validation" novalidate="">
             @csrf
             <div class="">
                 <div class="form-group mb-3">
                     <label class="form-label">{{ __('Name') }}</label>
                     <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                         name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                         placeholder="{{ __('Enter your name') }}">
                     @error('name')
                         <span class="error invalid-name text-danger" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>

                 <div class="form-group mb-3">
                     <label class="form-label">{{ __('Store Name') }}</label>
                     <input id="store_name" type="text" class="form-control @error('store_name') is-invalid @enderror"
                         name="store_name" value="{{ old('store_name') }}" required autocomplete="store_name"
                         placeholder="{{ __('Enter your store name') }}">
                     @error('store_name')
                         <span class="error invalid-name text-danger" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>

                 <div class="form-group mb-3">
                     <label class="form-label">{{ __('Email') }}</label>
                     <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                         name="email" value="{{ old('email') }}" required placeholder="{{ __('Enter your email') }}">
                     @error('email')
                         <span class="error invalid-email text-danger" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>

                 <div class="form-group mb-3">
                     <label class="form-label">{{ __('Password') }}</label>
                     <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                         name="password" required autocomplete="new-password"
                         placeholder="{{ __('Enter your password') }}">
                     @error('password')
                         <span class="error invalid-password text-danger" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>

                 <div class="form-group">
                     <label class="form-label">{{ __('Confirm password') }}</label>
                     <input id="password-confirm" type="password"
                         class="form-control @error('password_confirmation') is-invalid @enderror"
                         name="password_confirmation" required autocomplete="new-password"
                         placeholder="{{ __('Enter confirm password') }}">
                     @error('password_confirmation')
                         <span class="error invalid-password_confirmation text-danger" role="alert">
                             <strong>{{ $message }}</strong>
                         </span>
                     @enderror
                 </div>

                @if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
                    @if (isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2')
                        <div class="form-group mb-3">
                            {!! NoCaptcha::display() !!}
                            @error('g-recaptcha-response')
                                <span class="small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @else
                        <div class="form-group col-lg-12 col-md-12 mt-3">
                            <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response" class="form-control">
                            @error('g-recaptcha-response')
                                <span class="error small text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    @endif
                @endif

                @if (count($keyArray) > 0)
                    <div class="form-check custom-checkbox">
                        <input type="checkbox"
                            class="form-check-input @error('terms_condition_check') is-invalid @enderror"
                            id="termsCheckbox" name="terms_condition_check">
                        <input type="hidden" name="terms_condition" id="terms_condition" value="off">

                        <label class="text-sm" for="terms_condition_check">{{ __('I agree to the ') }}
                            @foreach (json_decode($landingPageSettings['menubar_page']) as $key => $value)
                                @if (in_array($value->menubar_page_name, ['Terms and Conditions']) && isset($value->template_name))
                                    <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                        target="_blank">{{ $value->menubar_page_name }}</a>
                                @endif
                            @endforeach
                            @if (count($keyArray) == 2)
                                {{ __('and the ') }}
                            @endif
                            @foreach (json_decode($landingPageSettings['menubar_page']) as $key => $value)
                                @if (in_array($value->menubar_page_name, ['Privacy Policy']) && isset($value->template_name))
                                    <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                        target="_blank">{{ $value->menubar_page_name }}</a>
                                @endif
                            @endforeach
                        </label>
                    </div>
                    @error('terms_condition_check')
                        <span class="error invalid-terms_condition_check text-danger" role="alert">
                            <strong>{{ __('Please check this box if you want to proceed.') }}</strong>
                        </span>
                    @enderror
                @endif

                 <div class="d-grid">
                     <input type="hidden" name="ref_code" value="{{ $ref }}">
                     <button class="btn btn-primary btn-block mt-2" type="submit">{{ __('Register') }}</button>
                 </div>

                 <div class="my-4 text-xs text-center">
                     <p>
                         {{ __('Already have an account?') }} <a
                             href="{{ route('login', $lang) }}">{{ __('Login') }}</a>
                     </p>
                 </div>
             </div>
         </form>
     </div>
 @endsection
 @push('custom-scripts')
    @if (isset($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'yes')
        @if (isset($settings['google_recaptcha_version']) && $settings['google_recaptcha_version'] == 'v2')
            {!! NoCaptcha::renderJs() !!}
        @else
            <script src="https://www.google.com/recaptcha/api.js?render={{ $settings['google_recaptcha_key'] }}"></script>
            <script>
                $(document).ready(function() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $settings['google_recaptcha_key'] }}', {
                            action: 'submit'
                        }).then(function(token) {
                            $('#g-recaptcha-response').val(token);
                        });
                    });
                });
            </script>
        @endif
    @endif
    @if (count($keyArray) > 0)
        <script>
            $('#registerForm').on('submit', function() {
                if ($('#termsCheckbox').prop('checked')) {
                    $('#terms_condition').val('on');
                }
            });
        </script>
    @endif
 @endpush
