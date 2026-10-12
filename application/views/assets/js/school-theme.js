/* =========================================================
   LITTLE HEARTS SCHOOL — GLOBAL THEME SCRIPTS
   Navbar / ticker / scroll-top / wow init — all external.
   ========================================================= */

(function ($) {
    "use strict";

    $(document).ready(function () {

        /* ---------- 1. NAVBAR: shrink on scroll ---------- */
        var $navbar = $(".navbar.navbar-default, .navbar");

        $(window).on("scroll", function () {
            if ($(window).scrollTop() > 60) {
                $navbar.addClass("lh-scrolled");
            } else {
                $navbar.removeClass("lh-scrolled");
            }
        });

        /* ---------- 2. NAVBAR: active page highlighting ---------- */
        var path = window.location.pathname.replace(/\/index\.php\/?/, "/"),
            segments = path.split("/").filter(Boolean),
            current = segments.length ? segments[segments.length - 1].toLowerCase() : "";

        if (current) {
            $(".navbar-nav a, .dropdown-menu a").each(function () {
                var href = ($(this).attr("href") || "").toLowerCase();
                if (href && href.indexOf(current) !== -1 && href.indexOf("#") !== 0) {
                    $(this).closest("li").addClass("lh-active");
                    $(this).closest("li.dropdown").addClass("lh-active");
                }
            });
        }

        /* ---------- 3. NEWS TICKER: seamless loop (duplicate content once) ---------- */
        var $list = $(".news-ticker-list");
        if ($list.length && $list.children("li").length) {
            $list.addClass("lh-ticker-ready");
            var originalHtml = $list.html();
            $list.html(originalHtml + originalHtml);
        }

        /* ---------- 4. MOBILE DROPDOWN: tap-to-open on first tap ---------- */
        if ($(window).width() <= 991) {
            $(".navbar-nav li.dropdown > a").on("click", function (e) {
                var $li = $(this).parent();
                if (!$li.hasClass("open")) {
                    e.preventDefault();
                    $(".navbar-nav li.dropdown").removeClass("open");
                    $li.addClass("open");
                    $li.children(".dropdown-menu").stop(true, true).slideDown(220);
                    $(".navbar-nav li.dropdown").not($li).children(".dropdown-menu").stop(true, true).slideUp(180);
                }
            });
        }

        /* ---------- 5. SCROLL-TO-TOP button ---------- */
        var $toTop = $(".scrollToTop");
        if ($toTop.length) {
            $(window).on("scroll", function () {
                if ($(window).scrollTop() > 300) {
                    $toTop.addClass("lh-show");
                } else {
                    $toTop.removeClass("lh-show");
                }
            });
            $toTop.on("click", function (e) {
                e.preventDefault();
                $("html, body").animate({ scrollTop: 0 }, 500);
            });
        }

        /* ---------- 5b. HAMBURGER <-> CLOSE ICON SWAP + SLIDE ANIMATION ----------
           show/hide fire at click-time, so our CSS transition starts instantly
           (no waiting for Bootstrap's inline height measuring). */
        $(document)
            .on("show.bs.collapse", "#navbar-menu", function () {
                $(this).addClass("lh-open");
                $(".navbar-toggle i").attr("class", "fa fa-times");
            })
            .on("hide.bs.collapse", "#navbar-menu", function () {
                $(this).removeClass("lh-open");
                $(".navbar-toggle i").attr("class", "fa fa-bars");
            });

        /* ---------- 6. WOW.JS (guarded) ---------- */
        if (typeof WOW === "function") {
            new WOW({ boxClass: "wow", animateClass: "animated", offset: 60, mobile: true, live: true }).init();
        }

        /* ---------- 7. COUNTERS (jquery-countTo guarded) ---------- */
        if ($.fn.countTo) {
            $(".number-counters").appear &&
                $(".number-counters").appear();
            $(document.body).on("appear", ".counters-item strong[data-to]", function () {
                var $this = $(this);
                if (!$this.data("counted")) {
                    $this.countTo({ formatter: function (v) { return Math.round(v); } });
                    $this.data("counted", true);
                }
            });
        }
    });
})(jQuery);
