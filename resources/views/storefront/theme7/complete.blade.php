@php
    $logo = \App\Models\Utility::get_file('uploads/logo/');
    $company_favicon = \App\Models\Utility::getValByName('company_favicon');
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{ ucfirst($store->name) }} - {{ ucfirst($store->tagline) }}">


    <title>{{ __('Completed') }} - {{ $store->tagline ? $store->tagline : config('APP_NAME', 'WhatsStore') }}</title>

    <link rel="icon"
        href="{{ $logo . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
        type="image" sizes="16x16">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('custom/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/swiper-bundle.min.css') }}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('custom/libs/animate.css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('custom/css/theme7-v1.css') }}" id="stylesheet">
    <link rel="stylesheet" href="{{ asset('custom/css/custom.css') }}" id="stylesheet')}}">
    <script type="text/javascript" src="{{ asset('custom/js/jquery.min.js') }}"></script>
    @stack('css-page')
</head>

<body>
    <div class="order-complete-wrap">
        <div class="order-complete-content">
            <div class="order-complete-main">
                <div class="order-complete-img">
                    <img src="{{asset('custom/img/celebration.png')}}" alt="">
                </div>
                <div class="order-complete-desc">
                    <h1><b>Your Order</b> <br> Succesfully Completed</h1>
                    <p>We received your purchase request, <br> we’ll be in touch shortly</p>
                    <form action="" class="copy-link">
                        <div class="input-wrapper">
                            <input type="text" value="{{route('user.order',[$store->slug,$order_id])}}"
                            id="myInput" aria-label="Recipient's username" aria-describedby="button-addon2" readonly>
                        </div>
                        <button class="btn print-btn" onclick="myFunction()" id="button-addon2"  type="button"><svg xmlns="http://www.w3.org/2000/svg"
                                width="17" height="18" viewBox="0 0 17 18" fill="none">
                                <path
                                    d="M7.12465 12.4763C5.66531 12.2029 5.06474 11.2913 5.32208 9.74072L6.21742 4.34608L6.21465 4.3418L3.68878 4.81429C2.22944 5.08983 1.62931 6.00284 1.88865 7.55338L2.91876 13.7556C3.17076 15.3061 4.03006 15.9436 5.48939 15.668L9.86732 14.8492C10.8893 14.6523 11.4975 14.1409 11.6601 13.3221L11.6554 13.3136C11.6034 13.3058 11.5567 13.3058 11.5027 13.2959L7.12465 12.4763Z"
                                    fill="white" />
                                <path opacity="0.4"
                                    d="M13.3027 2.44402L8.92544 1.62376C7.46678 1.35034 6.60807 1.98853 6.35074 3.53908L6.21678 4.34516L5.32144 9.73985C5.0641 11.2904 5.66468 12.202 7.12401 12.4754L11.5008 13.2957C11.5548 13.3056 11.6015 13.3056 11.6535 13.3134C13.0161 13.5124 13.8273 12.8735 14.0753 11.3803L15.1048 5.1796C15.3628 3.62977 14.7621 2.71744 13.3027 2.44402Z"
                                    fill="white" />
                            </svg>{{__('Copy Link')}}</button>
                    </form>
                    <a href="{{route('store.slug',$store->slug)}}" class="back-btn"><svg xmlns="http://www.w3.org/2000/svg" width="25"
                            height="25" viewBox="0 0 25 25" fill="none">
                            <path opacity="0.4"
                                d="M12.8822 22.1462C18.4051 22.1462 22.8822 17.6691 22.8822 12.1462C22.8822 6.62339 18.4051 2.14624 12.8822 2.14624C7.35935 2.14624 2.8822 6.62339 2.8822 12.1462C2.8822 17.6691 7.35935 22.1462 12.8822 22.1462Z"
                                fill="#0CAF60" />
                            <path
                                d="M16.8823 11.3963H10.6934L12.4133 9.67629C12.7063 9.38329 12.7063 8.90826 12.4133 8.61526C12.1203 8.32226 11.6453 8.32226 11.3523 8.61526L8.35229 11.6153C8.28329 11.6843 8.22842 11.7672 8.19042 11.8592C8.11442 12.0422 8.11442 12.2492 8.19042 12.4322C8.22842 12.5242 8.28329 12.6073 8.35229 12.6763L11.3523 15.6763C11.4983 15.8223 11.6903 15.8963 11.8823 15.8963C12.0743 15.8963 12.2663 15.8233 12.4123 15.6763C12.7053 15.3833 12.7053 14.9083 12.4123 14.6153L10.6924 12.8953H16.8823C17.2963 12.8953 17.6323 12.5593 17.6323 12.1453C17.6323 11.7313 17.2963 11.3963 16.8823 11.3963Z"
                                fill="#0CAF60" />
                        </svg>{{__('Return to shop')}}</a>
                </div>

            </div>
        </div>
    </div>
    <!-- Core JS - includes jquery, bootstrap, popper, in-view and sticky-kit -->
    <script src="{{ asset('custom/js/site.core.js') }}"></script>
    <!-- notify -->
    {{-- <script type="text/javascript" src="{{ asset('custom/js/custom.js')}}"></script> --}}
    <script src="{{ asset('custom/js/custom-admin.js') }}"></script>

    <script src="{{ asset('custom/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('custom/libs/swiper/dist/js/swiper.min.js') }}"></script>
    <!-- Site JS -->
    <script src="{{ asset('custom/js/site.js') }}"></script>
    <!-- Demo JS - remove it when starting your project -->
    <script src="{{ asset('custom/js/demo.js') }}"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->

    @php
        $store_settings = \App\Models\Store::where('slug', $store->slug)->first();
    @endphp

    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $store_settings->google_analytic }}"></script>
    {!! $store_settings->storejs !!}
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{ !empty($store_settings->google_analytic) }}');
    </script>



    <!-- Facebook Pixel Code -->
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ !empty($store_settings->facebook_pixel) }}');
        fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id={{ $store_settings->facebook_pixel }}&ev=PageView&noscript=1" /></noscript>
    <!-- End Facebook Pixel Code -->

    @if (Session::has('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
        </script>
        {{ Session::forget('success') }}
    @endif
    @if (Session::has('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
        </script>
        {{ Session::forget('error') }}
    @endif
    <script>
        function myFunction() {
            var copyText = document.getElementById("myInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999)
            document.execCommand("copy");
            show_toastr('Success', 'Link copied', 'success');
        }
    </script>
</body>

</html>
