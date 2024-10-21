/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
! function (e) {
    e.fn.niceSelect = function (t) {
        function s(t) {
            t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class") || "").addClass(t.attr("disabled") ? "disabled" : "").attr("tabindex", t.attr("disabled") ? null : "0").html('<span class="current"></span><ul class="list"></ul>'));
            var s = t.next(),
                n = t.find("option"),
                i = t.find("option:selected");
            s.find(".current").html(i.data("display") || i.text()), n.each(function (t) {
                var n = e(this),
                    i = n.data("display");
                s.find("ul").append(e("<li></li>").attr("data-value", n.val()).attr("data-display", i || null).addClass("option" + (n.is(":selected") ? " selected" : "") + (n.is(":disabled") ? " disabled" : "")).html(n.text()))
            })
        }
        if ("string" == typeof t) return "update" == t ? this.each(function () {
            var t = e(this),
                n = e(this).next(".nice-select"),
                i = n.hasClass("open");
            n.length && (n.remove(), s(t), i && t.next().trigger("click"))
        }) : "destroy" == t ? (this.each(function () {
            var t = e(this),
                s = e(this).next(".nice-select");
            s.length && (s.remove(), t.css("display", ""))
        }), 0 == e(".nice-select").length && e(document).off(".nice_select")) : console.log('Method "' + t + '" does not exist.'), this;
        this.hide(), this.each(function () {
            var t = e(this);
            t.next().hasClass("nice-select") || s(t)
        }), e(document).off(".nice_select"), e(document).on("click.nice_select", ".nice-select", function (t) {
            var s = e(this);
            e(".nice-select").not(s).removeClass("open"), s.toggleClass("open"), s.hasClass("open") ? (s.find(".option"), s.find(".focus").removeClass("focus"), s.find(".selected").addClass("focus")) : s.focus()
        }), e(document).on("click.nice_select", function (t) {
            0 === e(t.target).closest(".nice-select").length && e(".nice-select").removeClass("open").find(".option")
        }), e(document).on("click.nice_select", ".nice-select .option:not(.disabled)", function (t) {
            var s = e(this),
                n = s.closest(".nice-select");
            n.find(".selected").removeClass("selected"), s.addClass("selected");
            var i = s.data("display") || s.text();
            n.find(".current").text(i), n.prev("select").val(s.data("value")).trigger("change")
        }), e(document).on("keydown.nice_select", ".nice-select", function (t) {
            var s = e(this),
                n = e(s.find(".focus") || s.find(".list .option.selected"));
            if (32 == t.keyCode || 13 == t.keyCode) return s.hasClass("open") ? n.trigger("click") : s.trigger("click"), !1;
            if (40 == t.keyCode) {
                if (s.hasClass("open")) {
                    var i = n.nextAll(".option:not(.disabled)").first();
                    i.length > 0 && (s.find(".focus").removeClass("focus"), i.addClass("focus"))
                } else s.trigger("click");
                return !1
            }
            if (38 == t.keyCode) {
                if (s.hasClass("open")) {
                    var l = n.prevAll(".option:not(.disabled)").first();
                    l.length > 0 && (s.find(".focus").removeClass("focus"), l.addClass("focus"))
                } else s.trigger("click");
                return !1
            }
            if (27 == t.keyCode) s.hasClass("open") && s.trigger("click");
            else if (9 == t.keyCode && s.hasClass("open")) return !1
        });
        var n = document.createElement("a").style;
        return n.cssText = "pointer-events:auto", "auto" !== n.pointerEvents && e("html").addClass("no-csspointerevents"), this
    }
}(jQuery);

$(document).ready(function () {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
        } else {
            $("header").removeClass("head-sticky");
        }
    });
    /*********  Header Search Popup  ********/
    $(".search-header a").click(function () {
        $(".search-popup").toggleClass("active");
        $("body").toggleClass("no-scroll");
    });
    $(".close-search").click(function () {
        $(".search-popup").removeClass("active");
        $("body").removeClass("no-scroll");
    });
    /********* Mobile Filter Popup ********/

    $('body').on('click', '.overlay.active, .close-filter', function (e) {
        e.preventDefault();
        $('.overlay').removeClass('active');
        $('body').removeClass('no-scroll filter-open');
    });
    /********* qty spinner ********/
    // var quantity = 0;
    // $('.quantity-increment').click(function () {
    //     ;
    //     var t = $(this).siblings('.quantity');
    //     var quantity = parseInt($(t).val());
    //     $(t).val(quantity + 1);
    // });
    // $('.quantity-decrement').click(function () {
    //     var t = $(this).siblings('.quantity');
    //     var quantity = parseInt($(t).val());
    //     if (quantity > 1) {
    //         $(t).val(quantity - 1);
    //     }
    // });
    /******  Nice Select  ******/
    $('select').niceSelect();
    /*********  Multi-level accordion nav  ********/
    $('.acnav-label').click(function () {
        var label = $(this);
        var parent = label.parent('.has-children');
        var list = label.siblings('.acnav-list');
        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
        } else {
            list.slideDown('fast');
            parent.addClass('is-open');
        }
    });

    // grid-js
    $("#h-grid").click(function () {
        $('.tabs-container .tab-content .product-card').toggleClass('h-grid');
    });
    $("#grid").click(function () {
        $('.tabs-container .tab-content .product-card').removeClass('h-grid');
    });


    /****  TAB Js ****/
    $('ul.tabs li').click(function () {
        var $this = $(this);
        var $theTab = $(this).attr('data-tab');
        // console.log($theTab);
        if ($this.hasClass('active')) {
            // do nothing
        } else {
            $this.closest('.tabs-wrapper').find('ul.tabs li, .tabs-container .tab-content').removeClass('active');
            $('.tabs-container .tab-content[id="' + $theTab + '"], ul.tabs li[data-tab="' + $theTab + '"]').addClass('active');
        }
        $('.bestpro-slider').slick('refresh');
        $('.bestsell-slider').slick('refresh');
        $('.testimonial-slider').slick('refresh');
    });
    // PRODUCT-PAGE
    // product slider
    var backButtonSec = '<div class="slick-prev1 fourth-left"><img src="assets/images/arrow.png"></div>';
    var nextButtonSec = '<div class="slick-next1 fourth-right"><img src="assets/images/right-arr.png"></div>'
    $('.product-main-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: $('.fourth-left'),
        nextArrow: $('.fourth-right'),
        dots: false,
        infinite: true,
        speed: 1000,
        rtl: true,
        loop: true,
        asNavFor: '.product-thumb-slider',
        autoplay: false,
        responsive: [{
            breakpoint: 576,
            settings: {
                arrows: false,
                dots: false,
            }
        }]
    });
    $('.product-thumb-slider').slick({
        slidesToShow: 3,
        arrows: false,
        asNavFor: '.product-main-slider',
        dots: false,
        speed: 1000,
        slidesToScroll: 1,
        prevArrow: '<button class="slide-arrow slick-prev"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
        nextArrow: '<button class="slide-arrow slick-next"><svg viewBox="0 0 10 5"><path d="M2.37755e-08 2.57132C-3.38931e-06 2.7911 0.178166 2.96928 0.397953 2.96928L8.17233 2.9694L7.23718 3.87785C7.07954 4.031 7.07589 4.28295 7.22903 4.44059C7.38218 4.59824 7.63413 4.60189 7.79177 4.44874L9.43039 2.85691C9.50753 2.78197 9.55105 2.679 9.55105 2.57146C9.55105 2.46392 9.50753 2.36095 9.43039 2.28602L7.79177 0.69418C7.63413 0.541034 7.38218 0.544682 7.22903 0.702329C7.07589 0.859976 7.07954 1.11192 7.23718 1.26507L8.1723 2.17349L0.397965 2.17336C0.178179 2.17336 3.46059e-06 2.35153 2.37755e-08 2.57132Z"></path></svg></button>',
        touchMove: true,
        focusOnSelect: true,
        loop: true,
        rtl: true,
        infinite: true,
        // vertical: true,
        responsive: [{
            breakpoint: 576,
            settings: {
                arrows: true,
                // vertical: false,
            }
        }]
    });

    // Modal Window
    // (() => {
    //     const modalBtns = Array.from(document.querySelectorAll(".modal-target"));
    //     modalBtns.forEach(btn => {
    //     btn.onclick = function() {
    //         const modal = btn.getAttribute('data-modal');
    //         document.getElementById(modal).classList.toggle("active");
    //         document.querySelector("body").classList.toggle("no-scroll");
    //     }
    //     });
    //     const closeBtns = Array.from(document.querySelectorAll(".close-button"));
    //     closeBtns.forEach(btn => {
    //     btn.onclick = function() {
    //         let modal = btn.closest('.modal');
    //         btn.closest('.modal-popup').classList.toggle("active");
    //         document.querySelector("body").classList.toggle("no-scroll");
    //         document.querySelector("body").classList.remove("no-scroll");
    //     }
    //     });
    //     window.onclick = function(event) {
    //     if (event.target.className === "modal") {
    //         event.target.style.display = "none";
    //     }
    //     }
    // })();
});

// HOME BANNER SLIDER JS //
$(window).on('load resize orientationchange', function () {
    /********* Wrapper top space ********/
    var header_hright = $('header').outerHeight();
    $('header').next('.wrapper').css('margin-top', header_hright + 'px');
});

/******  STEPPY FORM  CSS  ******/
var totalSteps = $(".steps li").length;
$(".submit").on("click", function () {
    return false;
});

$(".steps li:nth-of-type(1)").addClass("active");
$(".myContainer .step-container:nth-of-type(1)").addClass("active");

$(".step-container").on("click", ".next", function () {
    $(".steps li").eq($(this).parents(".step-container").index() + 1).addClass("active");
    $(this).parents(".step-container").removeClass("active").next().addClass("active");
    $('.right-slider').slick('refresh');
});

$(".step-container").on("click", ".back", function () {
    $(".steps li").eq($(this).parents(".step-container").index() - totalSteps).removeClass("active");
    $(this).parents(".step-container").removeClass("active").prev().addClass("active");
    $('.right-slider').slick('refresh');
});


/******  STEPPY FORM  CSS  End******/
$('.filter-title').on('click', function (e) {
    e.preventDefault();
    setTimeout(function () {
        $('.banner-col-left ').toggleClass('active');
        $('body').toggleClass('no-scroll');
    }, 50);
});


$(".close-filter").click(function () {
    $('.banner-col-left ').removeClass('active')
    $('body').removeClass('no-scroll')
})





$(".order-md-1 .btn").click(function () {
    $('.order-md-2 ').toggleClass('active')
})

// $(".order-md-1 .btn").click(function(){
//     $('.order-md-2 ').removeClass('active')
// })


$(document).ready(function () {
    /********* On scroll heder Sticky *********/
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
        } else {
            $("header").removeClass("head-sticky");
        }
    });
    $(window).scroll(function () {
        var scroll = $(window).scrollTop();
        if (scroll >= 50) {
            $("header").addClass("head-sticky");
            $(".top-header-wrapper").slideUp('slow');
        } else {
            $("header").removeClass("head-sticky");
            $(".top-header-wrapper").slideDown('slow');
        }
    });
    /********* Mobile Menu ********/
    $('.menu-toggle-btn').on('click', function (e) {
        e.preventDefault();
        setTimeout(function () {
            $('body').addClass('no-scroll active-menu');
            $(".side-menu-wrapper").toggleClass("active-menu");
            $('.overlay').addClass('menu-overlay');
        }, 50);
    });
    $('body').on('click', '.overlay.menu-overlay, .menu-close-icon svg', function (e) {
        e.preventDefault();
        $('body').removeClass('no-scroll active-menu');
        $(".side-menu-wrapper").removeClass("active-menu");
        $('.overlay').removeClass('menu-overlay');
    });
    /********* Cart Popup ********/
    $('.cart-header').on('click', function (e) {
        e.preventDefault();
        setTimeout(function () {
            $('body').addClass('no-scroll cartOpen');
            $('.overlay').addClass('cart-overlay');
        }, 50);
    });
    $('body').on('click', '.overlay.cart-overlay, .closecart', function (e) {
        e.preventDefault();
        $('.overlay').removeClass('cart-overlay');
        $('body').removeClass('no-scroll cartOpen');
    });
    /********* Mobile Filter Popup ********/
    $('.filter-title').on('click', function (e) {
        e.preventDefault();
        setTimeout(function () {
            $('body').addClass('no-scroll filter-open');
            // $('.overlay').addClass('active');
        }, 50);
    });
    $('body').on('click', '.overlay.active, .close-filter', function (e) {
        e.preventDefault();
        $('.overlay').removeClass('active');
        $('body').removeClass('no-scroll filter-open');
    });
    /******* Cookie Js *******/
    $('.cookie-close').click(function () {
        $('.cookie').slideUp();
    });
    /******* Subscribe popup Js *******/
    $('.close-sub-btn').click(function () {
        $('.subscribe-popup').slideUp();
        $(".subscribe-overlay").removeClass("open");
    });
    /********* qty spinner ********/
    // var quantity = 0;
    // $('.quantity-increment').click(function () {
    //     var t = $(this).siblings('.quantity');
    //     var quantity = parseInt($(t).val());
    //     $(t).val(quantity + 1);
    // });
    // $('.quantity-decrement').click(function () {
    //     var t = $(this).siblings('.quantity');
    //     var quantity = parseInt($(t).val());
    //     if (quantity > 1) {
    //         $(t).val(quantity - 1);
    //     }
    // });
    /******  Nice Select  ******/
    $('select').niceSelect();
    /*********  Multi-level accordion nav  ********/
    $('.acnav-label').click(function () {
        var label = $(this);
        var parent = label.parent('.has-children');
        var list = label.siblings('.acnav-list');
        if (parent.hasClass('is-open')) {
            list.slideUp('fast');
            parent.removeClass('is-open');
        } else {
            list.slideDown('fast');
            parent.addClass('is-open');
        }
    });
    /****  TAB Js ****/
    $('ul.tabs li').click(function () {
        var $this = $(this);
        var $theTab = $(this).attr('data-tab');
        if ($this.hasClass('active')) {
        // do nothing
        } else {
            $this.closest('.tabs-wrapper').find('ul.tabs li, .tabs-container .tab-content').removeClass('active');
            $('.tabs-container .tab-content[id="' + $theTab + '"], ul.tabs li[data-tab="' + $theTab + '"]').addClass('active');
        }
    });
       /********* Side Menu ********/
       $('.mobile-menu-button').on('click', function (e) {
        e.preventDefault();
        setTimeout(function () {
            $('body').addClass('no-scroll active-menu');
            $(".mobile-menu-wrapper").toggleClass("active-menu");
            $('.overlay').addClass('menu-overlay');
        }, 50);
    });
    $('body').on('click', '.overlay.menu-overlay, .menu-close-icon svg', function (e) {
        e.preventDefault();
        $('body').removeClass('no-scroll active-menu');
        $(".mobile-menu-wrapper").removeClass("active-menu");
        $('.overlay').removeClass('menu-overlay');
    });
    /********* Search popup ********/
    $('.search-drp-btn').on('click', function (e) {
        e.preventDefault();
        setTimeout(function () {
            $(".header-search form").toggleClass("active");
            $('.overlay').addClass('menu-overlay menu-search');
        }, 50);
    });
    $('body').on('click', '.overlay.menu-overlay', function (e) {
        e.preventDefault();
        $('body').removeClass('no-scroll active-menu');
        $(".header-search form").removeClass("active");
        $('.overlay').removeClass('menu-overlay');
    });
        // view Product modal
       $(".view-btn").click(function() {
        $("#viewProduct").toggleClass("active");
        $("body").toggleClass("no-scroll");
    });
    $(".close-button").click(function() {
        $("#viewProduct").removeClass("active");
        $("body").removeClass("no-scroll");
    });
        // Popup add to cart variant start
        $(".btn.cart-btn").click(function() {
            $("#cartVariant").toggleClass("active");
            $("body").toggleClass("no-scroll");
        });
        $(".close-button").click(function() {
            $("#cartVariant").removeClass("active");
            $("body").removeClass("no-scroll");
        });
        // Popup info start
        $(".item-remove").click(function() {
            $("#Popupinfo").toggleClass("active");
            $("body").toggleClass("no-scroll");
        });
        $(".close-button").click(function() {
            $("#Popupinfo").removeClass("active");
            $("body").removeClass("no-scroll");
        });
        // Popup info start
        $(".ac-viewbtn").click(function() {
            $("#Orderview").toggleClass("active");
            $("body").toggleClass("no-scroll");
        });
        $(".close-button").click(function() {
            $("#Orderview").removeClass("active");
            $("body").removeClass("no-scroll");
        });
});

/********* Wrapper top space ********/
$(window).on('load resize orientationchange', function() {
    var header_hright = $('header').outerHeight();
    $('header').next('.wrapper').css('margin-top', header_hright + 'px');
});

// pdp slider's
var pdpslider = new Swiper('.pdp-main-slider', {
    slidesPerView: 1,
    centeredSlides: false,
    loop: true,
    rtl:true,
    speed: 1000,
    loopedSlides: 6,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

var pdpthumbs = new Swiper('.pdp-thumb-slider', {
    slidesPerView: 2,
    spaceBetween: 10,
    centeredSlides: false,
    speed: 1000,
    loop: true,
    rtl:true,
    slideToClickedSlide: true,
    breakpoints: {
        575: {
            slidesPerView: 3,
        },
        991: {
            slidesPerView: 3,
        },
        980: {
            slidesPerView: 3,
        }
    },
});
pdpslider.controller.control = pdpthumbs;
pdpthumbs.controller.control = pdpslider;
