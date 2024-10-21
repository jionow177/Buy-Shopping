<x-guest-layout>
    <x-auth-card>
@section('title')
    {{__('Reset Password')}}
@endsection

{{-- @section('language-bar')
@php
    $languages = App\Models\Utility::languages();
    $lang = '';
    if ($lang == '') {
            $lang = \App\Models\Utility::getValByName('default_language');
        }
        $language_name = \App\Models\Languages::where('code', $lang)->get()->first();
@endphp
    <div class="lang-dropdown-only-desk">
        <li class="dropdown dash-h-item drp-language">
            <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="drp-text"> {{ ucFirst($languages[$lang]) }}
                </span>
            </a>
            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                @foreach($languages as $code => $language)
                    <a href="{{ route('password.reset',$code) }}" tabindex="0" class="dropdown-item {{ $code == $lang ? 'active':'' }}">
                        <span>{{ ucFirst($language)}}</span>
                    </a>
                @endforeach
            </div>
        </li>
    </div>
@endsection --}}
@section('content')
        <div class="card-body">
            <div>
                <h2 class="mb-3 f-w-600">{{__('Reset Password')}}</h2>
            </div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div class="form-group">
                    {{Form::label('E-Mail Address',__('E-Mail Address'),array('class' => 'form-label'))}}
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('Enter your email') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                {{Form::label('Password',__('Password'),array('class' => 'form-label'))}}
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    {{Form::label('password-confirm',__('Confirm Password'),array('class' => 'form-label'))}}
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm password') }}">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-block mt-2">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </form>
        </div>
@endsection
</x-auth-card>
</x-guest-layout>
