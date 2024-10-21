<x-guest-layout>
    <x-auth-card>
        @section('title')
            {{ __('Reset Password') }}
        @endsection
        @php
            $settings = Utility::settings();
        @endphp
        @section('language-bar')
            @php
                $languages = App\Models\Utility::languages();
            @endphp
            <div class="lang-dropdown-only-desk">
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <span class="drp-text"> {{ ucFirst($languages[$lang]) }}
                        </span>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                        @foreach ($languages as $code => $language)
                            <a href="{{ route('change.langPass', $code) }}" tabindex="0"
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
                    <h2 class="mb-3 f-w-600">{{ __('Forgot Password') }}</h2>
                </div>
                @if (session('status'))
                    <div class="alert alert-primary">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                placeholder="{{ __('Enter your email') }}" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <small>{{ $message }}</small>
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
        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block mt-2"
                                id="login_button">{{ __('Send Password Reset Link') }}</button>
                        </div>


                        <div class="my-4 text-xs text-center">
                            <p>
                                {{ __('Already have an account?') }}
                                <a href="{{ route('login', $lang) }}">{{ __('Login') }}</a>
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
        @endpush
    </x-auth-card>
</x-guest-layout>
