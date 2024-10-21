@php
    $logo = asset(Storage::url('uploads/logo/'));
    $users = \Auth::user();
    $currantLang = $users->currentLanguages();
    $languages = \App\Models\Utility::languages();
    $settings = Utility::settings();
    $footer_text = !empty($settings['footer_text']) ? $settings['footer_text'] : '';
    $setting = App\Models\Utility::colorset();
    $SITE_RTL = $settings['SITE_RTL'];

    $color = !empty($setting['color']) ? $setting['color'] : 'theme-3';

    if(isset($setting['color_flag']) && $setting['color_flag'] == 'true')
    {
        $themeColor = 'custom-color';
    }
    else {
        $themeColor = $color;
    }

    if (\Auth::user()->type == 'Super Admin') {
        $company_logo = Utility::get_superadmin_logo();
    } else {
        $company_logo = Utility::get_company_logo();
    }
    $plan = \Auth::user()->currentPlan;
@endphp
<!DOCTYPE html>
<html lang="en" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">
@include('partials.admin.head')

<body class="{{ $themeColor }}">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->

    <!-- [ navigation menu ] start -->
    @include('partials.admin.menu')
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    @include('partials.admin.header')
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    @include('partials.admin.content')
    <!-- [ Main Content ] end -->

    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="commonModalOver" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>

    <footer class="dash-footer">
        <div class="footer-wrapper">
            <div class="py-1">
                <span class="text-muted">
                     &copy;
                     {{ date('Y') }}
                    {{ !empty($settings['footer_text']) ? $settings['footer_text'] : config('app.name', 'Whatsstore SaaS') }}
                </span>
            </div>
        </div>
    </footer>
    @include('partials.admin.footer')

    <script>
        $(document).ready(function() {
            cust_theme_bg();
            cust_darklayout();
        });



        feather.replace();
        var pctoggle = document.querySelector("#pct-toggler");
        if (pctoggle) {
            pctoggle.addEventListener("click", function() {
                if (
                    !document.querySelector(".pct-customizer").classList.contains("active")
                ) {
                    document.querySelector(".pct-customizer").classList.add("active");
                } else {
                    document.querySelector(".pct-customizer").classList.remove("active");
                }
            });
        }

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];

            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        function cust_theme_bg() {
            var custthemebg = document.querySelector("#cust-theme-bg");
            // custthemebg.addEventListener("click", function() {

            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
            // });
        }
        var custthemebg = document.querySelector("#cust-theme-bg");
        custthemebg.addEventListener("click", function() {
            if (custthemebg.checked) {
                document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.add("transprent-bg");
            } else {
                document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                document
                    .querySelector(".dash-header:not(.dash-mob-header)")
                    .classList.remove("transprent-bg");
            }
        });


        function cust_darklayout() {
            var custdarklayout = document.querySelector("#cust-darklayout");
            // custdarklayout.addEventListener("click", function() {
            @if (\Auth::user()->type == 'super admin')
                if (custdarklayout.checked) {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . 'logo-light.png' }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . 'logo-dark.png' }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                }
            @else
                if (custdarklayout.checked) {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . $company_logo }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . $company_logo }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                }
            @endif
        }

        var custdarklayout = document.querySelector("#cust-darklayout");
        custdarklayout.addEventListener("click", function() {
            @if (\Auth::user()->type == 'super admin')
                if (custdarklayout.checked) {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . 'logo-light.png' }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . 'logo-dark.png' }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                }
            @else
                if (custdarklayout.checked) {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . $company_logo }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style-dark.css') }}");
                } else {
                    document
                        .querySelector(".m-header > .b-brand > .logo-lg")
                        .setAttribute("src", "{{ $logo . '/' . $company_logo }}");
                    document
                        .querySelector("#main-style-link")
                        .setAttribute("href", "{{ asset('assets/css/style.css') }}");
                }
            @endif
        });

        function removeClassByPrefix(node, prefix) {
            for (let i = 0; i < node.classList.length; i++) {
                let value = node.classList[i];
                if (value.startsWith(prefix)) {
                    node.classList.remove(value);
                }
            }
        }
    </script>

</body>

</html>
