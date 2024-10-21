{{-- <link rel='stylesheet' href='https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.8.9/dist/cookieconsent.css'
    media="screen" />
<script src='https://cdn.jsdelivr.net/gh/orestbida/cookieconsent@v2.8.9/dist/cookieconsent.js'></script> --}}
<link rel='stylesheet' href='{{asset('assets/css/cookieconsent.css')}}' media="screen" />
<script src="{{ asset('assets/js/cookieconsent.js') }}"></script>
@php
    $settings = App\Models\Utility::AdminSettings();
@endphp
<script>
    let language_code = document.documentElement.getAttribute('lang');
    let languages = {};
    languages[language_code] = {
        consent_modal: {
            title: 'hello',
            description: 'description',
            primary_btn: {
                text: 'primary_btn text',
                role: 'accept_all'
            },
            secondary_btn: {
                        text: 'secondary_btn text',
                        role: 'accept_necessary'
                    }
                },
                settings_modal: {
                    title: 'settings_modal',
                    save_settings_btn: 'save_settings_btn',
                    accept_all_btn: 'accept_all_btn',
                    reject_all_btn: 'reject_all_btn',
                    close_btn_label: 'close_btn_label',
                    blocks: [{
                            title: 'block title',
                            description: 'block description'
                        },

                        {
                            title: 'title',
                            description: 'description',
                            toggle: {
                                value: 'necessary',
                                enabled: true,
                                readonly: false
                            }
                        },
                    ]
                }
            };
            </script>
        <script>
            function setCookie(cname, cvalue, exdays) {
                const d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                let expires = "expires=" + d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                let name = cname + "=";
                let decodedCookie = decodeURIComponent(document.cookie);
                let ca = decodedCookie.split(';');
                for (let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                        c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                        return c.substring(name.length, c.length);
                    }
                }
                return "";
            }


            // obtain plugin
            var cc = initCookieConsent();
            // run plugin with your configuration
            cc.run({
                current_lang: 'en',
                autoclear_cookies: true, // default: false
                page_scripts: true,
                // ...
                gui_options: {
                    consent_modal: {
                        layout: 'cloud', // box/cloud/bar
                        position: 'bottom center', // bottom/middle/top + left/right/center
                        transition: 'slide', // zoom/slide
                        swap_buttons: false // enable to invert buttons
                    },
                    settings_modal: {
                        layout: 'box', // box/bar
                        // position: 'left',           // left/right
                        transition: 'slide' // zoom/slide
                    }
                },

                onChange: function(cookie, changed_preferences) {},
                onAccept: function(cookie) {
                    if (!getCookie('cookie_consent_logged')) {
                        var cookie = cookie.level;
                        $.ajax({
                            url: '{{ route('cookie-consent') }}',
                            datType: 'json',
                            data: {
                                cookie: cookie,
                            },
                        })
                        setCookie('cookie_consent_logged', '1', 182, '/');
                    }
                },

                languages: {
                    '{{ env('DEFAULT_LANG') }}': {
                        consent_modal: {
                            title: '{{ !empty($settings["cookie_title"]) ? $settings["cookie_title"] : "We use cookies!" }}' ,
                            description: '{{ (!empty($settings["cookie_description"]) ? $settings["cookie_description"] : "Hi, this website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.") }} <button type="button" data-cc="c-settings" class="cc-link">Let me choose</button>'  ,
                            primary_btn: {
                                text: 'Accept all',
                                role: 'accept_all' // 'accept_selected' or 'accept_all'
                            },
                            secondary_btn: {
                                text: 'Reject all',
                                role: 'accept_necessary' // 'settings' or 'accept_necessary'
                            },
                        },
                        settings_modal: {
                            title: 'Cookie preferences',
                            save_settings_btn: 'Save settings',
                            accept_all_btn: 'Accept all',
                            reject_all_btn: 'Reject all',
                            close_btn_label: 'Close',
                            cookie_table_headers: [{
                                col1: 'Name'
                            },
                            {
                                col2: 'Domain'
                                },
                                {
                                    col3: 'Expiration'
                                },
                                {
                                    col4: 'Description'
                                }
                            ],
                            blocks: [{
                                title: '{{ !empty($settings["cookie_title"]) ? $settings["cookie_title"] : "Cookie usage" }}  :loudspeaker:',
                                description: '{{ (!empty($settings["cookie_description"]) ? $settings["cookie_description"] : "I use cookies to ensure the basic functionalities of the website and to enhance your online experience. You can choose for each category to opt-in/out whenever you want. For more details relative to cookies and other sensitive data, please read the full.") }} '
                            }, {
                                title: '{{ !empty($settings["strictly_cookie_title"]) ? $settings["strictly_cookie_title"] : "Strictly necessary cookies!" }} ',
                                description: '{{ !empty($settings["strictly_cookie_description"]) ? $settings["strictly_cookie_description"] : "These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly" }}',
                                toggle: {
                                    value: 'necessary',
                                    enabled: true,
                                    readonly: true // cookie categories with readonly=true are all treated as "necessary cookies"
                                }
                            }, {
                                title: '{{ !empty($settings["more_information_title"]) ? $settings["more_information_title"] : "More information" }} ',
                                description: 'For any queries in relation to our policy on cookies and your choices, please <a class="cc-link" href="{{ !empty($settings["contactus_url"]) ? $settings["contactus_url"] : "#" }}""  >contact us</a>.',
                            }]
                        }
                    }
                }

            });
        </script>
