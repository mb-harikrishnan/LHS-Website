<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/edua-icons.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/animate.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.carousel.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/owl.transitions.css') ?> ">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/cubeportfolio.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/settings.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootsnav.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/loader.css') ?>">
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">

    <title>Little Hearts | Activity Gallery</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet">

    <style>
        /* =========================================================
           LITTLE HEARTS — ACTIVITY GALLERY (friendly lightbox design)
           ========================================================= */
        :root {
            --g-navy: #0b2447;
            --g-navy2: #123a6b;
            --g-gold: #f5a623;
            --g-gold2: #e2911a;
            --g-ink: #22314a;
            --g-muted: #5b6b84;
            --g-paper: #f6f9ff;
            --g-line: #e3ecf7;
            --g-r: 22px;
            --g-sh: 0 16px 36px rgba(11, 36, 71, .10);
        }

        body { font-family: 'Nunito Sans', sans-serif; color: var(--g-ink); background: var(--g-paper); }

        .gallery-page {
            padding: 70px 0 90px;
            background:
                radial-gradient(circle at 8% 6%, rgba(142, 111, 232, .07) 0, transparent 26%),
                radial-gradient(circle at 96% 92%, rgba(245, 166, 35, .08) 0, transparent 30%),
                var(--g-paper);
            min-height: 60vh;
        }

        .gal-title { text-align: center; margin-bottom: 40px; }
        .gal-title .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            color: var(--g-gold2); font-weight: 800; font-size: 13px; letter-spacing: 1px; text-transform: uppercase;
        }
        .gal-title .eyebrow .dash { width: 22px; height: 2px; background: currentColor; }
        .gal-title h2 {
            font-family: 'Fredoka', cursive; color: var(--g-navy);
            font-size: 36px; font-weight: 700; margin: 10px 0 0;
        }
        .gal-title h2::after {
            content: ""; display: block; height: 6px; width: 90px; margin: 12px auto 0; border-radius: 8px;
            background:
                radial-gradient(circle at 6px 3px, var(--g-gold) 2.6px, transparent 3px) left/22px 6px repeat-x,
                linear-gradient(90deg, #8e6fe8, var(--g-gold));
        }

        .gal-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
        .gallery-item { padding: 0; min-width: 0; }
        .gallery-item .gallery-card { height: 280px; }

        .gallery-card {
            position: relative;
            overflow: hidden;
            border-radius: var(--g-r);
            box-shadow: var(--g-sh);
            cursor: pointer;
            background: #fff;
            border: 3px solid #fff;
            transition: transform .35s cubic-bezier(.34, 1.56, .64, 1), box-shadow .35s ease;
            height: 280px;
        }
        .gallery-card:hover {
            transform: translateY(-8px) rotate(-.5deg);
            box-shadow: 0 26px 50px rgba(11, 36, 71, .2);
        }
        .gallery-img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; display: block; }
        .gallery-card:hover .gallery-img { transform: scale(1.09); }

        .overlay {
            position: absolute; inset: 0;
            display: flex; align-items: flex-end; justify-content: space-between;
            padding: 16px;
            background: linear-gradient(to top, rgba(8, 26, 52, .82) 0%, rgba(8, 26, 52, 0) 55%);
            opacity: 0;
            transition: opacity .35s ease;
            z-index: 2;
        }
        .gallery-card:hover .overlay { opacity: 1; }
        .overlay-title { color: #fff; font-family: 'Fredoka', cursive; font-size: 15px; font-weight: 600; }
        .overlay-actions { display: flex; gap: 8px; }
        .overlay-btn {
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--g-gold);
            color: var(--g-navy);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
            text-decoration: none !important;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .3);
            transition: .3s cubic-bezier(.34, 1.56, .64, 1);
            border: none; cursor: pointer;
        }
        .overlay-btn:hover { transform: translateY(-4px) scale(1.08); color: var(--g-navy); }

        .gallery-item { animation: galIn .5s ease both; }
        @keyframes galIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: none; } }

        /* ================= LIGHTBOX ================= */
        .lightbox {
            position: fixed; inset: 0; z-index: 99999;
            display: none;
            background: rgba(6, 18, 38, .93);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .lightbox.open { display: flex; flex-direction: column; animation: lbIn .3s ease; }
        @keyframes lbIn { from { opacity: 0; } to { opacity: 1; } }

        .lb-top { display: flex; align-items: center; justify-content: space-between; padding: 14px 22px; color: #fff; }
        .lb-count { font-family: 'Fredoka', cursive; font-size: 15px; letter-spacing: .5px; color: rgba(255,255,255,.85); }
        .lb-count b { color: var(--g-gold); font-size: 18px; }
        .lb-title { font-size: 14px; color: rgba(255,255,255,.75); max-width: 55%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .lb-tools { display: flex; gap: 10px; }
        .lb-tool {
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            color: #fff; font-size: 15px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: .25s ease; text-decoration: none;
        }
        .lb-tool:hover { background: var(--g-gold); color: var(--g-navy); border-color: transparent; transform: translateY(-2px); }

        .lb-stage {
            flex: 1; display: flex; align-items: center; justify-content: center;
            position: relative; padding: 0 84px; min-height: 0;
        }
        .lb-stage img {
            max-width: 100%; max-height: 100%;
            border-radius: 16px;
            box-shadow: 0 40px 90px rgba(0, 0, 0, .6);
            transition: transform .25s ease;
            cursor: zoom-in;
            user-select: none;
        }
        .lb-stage img.zoomed { cursor: zoom-out; }

        .lb-nav {
            position: absolute; top: 50%; transform: translateY(-50%);
            width: 54px; height: 54px; border-radius: 50%;
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff; font-size: 22px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: .25s ease; z-index: 3;
        }
        .lb-nav:hover { background: var(--g-gold); color: var(--g-navy); border-color: transparent; }
        .lb-prev { left: 18px; }
        .lb-next { right: 18px; }

        .lb-thumbs { display: flex; gap: 10px; justify-content: center; padding: 16px 22px 20px; overflow-x: auto; }
        .lb-thumbs::-webkit-scrollbar { height: 6px; }
        .lb-thumbs::-webkit-scrollbar-thumb { background: rgba(255,255,255,.25); border-radius: 6px; }
        .lb-thumb {
            width: 74px; height: 54px; border-radius: 10px;
            object-fit: cover; cursor: pointer;
            opacity: .45; border: 2px solid transparent;
            transition: .25s ease; flex: none;
        }
        .lb-thumb:hover { opacity: .85; }
        .lb-thumb.current { opacity: 1; border-color: var(--g-gold); transform: translateY(-3px); }

        @media (max-width: 991px) { .gal-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 767px) {
            .gal-grid { grid-template-columns: 1fr; }
            .gallery-item .gallery-card { height: 240px; }
            .lb-stage { padding: 0 12px 0 12px; }
            .lb-nav { width: 42px; height: 42px; font-size: 17px; }
            .lb-prev { left: 8px; } .lb-next { right: 8px; }
            .lb-title { display: none; }
            .lb-thumbs { padding: 12px; }
        }
    </style>
</head>

<body>

<section class="gallery-page">
    <div class="container">

        <!-- Title -->
        <div class="gal-title">
            <span class="eyebrow"><span class="dash"></span> Our Activities</span>
            <h2>Activity Moments</h2>
        </div>

        <!-- GALLERY GRID -->
        <div class="gal-grid" id="galGrid">

            <?php $gi = 0; foreach ($gallery as $value) { $gi++; ?>
                <div class="gallery-item <?php echo strtolower($value->c_type); ?>">
                    <div class="gallery-card wow fadeInUp" onclick="openLightbox(<?php echo $gi - 1; ?>)">

                        <img src="<?php echo base_url('assets/images/gallery/' . $value->c_images); ?>"
                             class="gallery-img"
                             alt="<?php echo ucwords(str_replace('_', ' ', $value->c_type)); ?>"
                             data-img="<?php echo base_url('assets/images/gallery/' . $value->c_images); ?>">

                        <div class="overlay">
                            <span class="overlay-title"><?php echo ucwords(str_replace('_', ' ', $value->c_type)); ?></span>
                            <span class="overlay-actions">
                                <a href="<?php echo base_url('assets/images/gallery/' . $value->c_images); ?>"
                                   download class="overlay-btn" onclick="event.stopPropagation();">
                                    <i class="fa fa-download"></i>
                                </a>
                                <span class="overlay-btn" title="View"><i class="fa fa-search-plus"></i></span>
                            </span>
                        </div>

                    </div>
                </div>
            <?php } ?>

        </div>

    </div>
</section>

<!-- ================= LIGHTBOX ================= -->
<div id="lightbox" class="lightbox">
    <div class="lb-top">
        <span class="lb-count"><b id="lbCurrent">1</b> / <span id="lbTotal">1</span></span>
        <span class="lb-title" id="lbTitle"></span>
        <span class="lb-tools">
            <a class="lb-tool" id="lbDownload" download title="Download"><i class="fa fa-download"></i></a>
            <span class="lb-tool" onclick="closeLightbox()" title="Close"><i class="fa fa-times"></i></span>
        </span>
    </div>

    <div class="lb-stage">
        <span class="lb-nav lb-prev" onclick="lbStep(-1)"><i class="fa fa-angle-left"></i></span>
        <img id="lbImg" src="" alt="Gallery image">
        <span class="lb-nav lb-next" onclick="lbStep(1)"><i class="fa fa-angle-right"></i></span>
    </div>

    <div class="lb-thumbs" id="lbThumbs"></div>
</div>

<script>
/* =========================================================
   LITTLE HEARTS — ACTIVITY GALLERY LIGHTBOX (vanilla JS)
   ========================================================= */
(function () {
    var cards = Array.prototype.slice.call(document.querySelectorAll('.gallery-card'));
    var lightbox = document.getElementById('lightbox');
    var lbImg = document.getElementById('lbImg');
    var lbTitle = document.getElementById('lbTitle');
    var lbCount = document.getElementById('lbCurrent');
    var lbTotal = document.getElementById('lbTotal');
    var lbThumbs = document.getElementById('lbThumbs');
    var lbDownload = document.getElementById('lbDownload');
    var current = 0;
    var scale = 1;

    function show(i) {
        if (!cards.length) { return; }
        current = (i + cards.length) % cards.length;
        var card = cards[current];
        var src = card.querySelector('.gallery-img').getAttribute('data-img');
        var label = card.querySelector('.overlay-title');
        lbImg.src = src;
        lbImg.style.transform = 'scale(1)';
        scale = 1;
        lbImg.classList.remove('zoomed');
        lbTitle.textContent = label ? label.textContent : '';
        lbCount.textContent = current + 1;
        lbTotal.textContent = cards.length;
        lbDownload.href = src;
        lbThumbs.innerHTML = '';
        cards.forEach(function (c, idx) {
            var img = document.createElement('img');
            img.src = c.querySelector('.gallery-img').getAttribute('data-img');
            img.className = 'lb-thumb' + (idx === current ? ' current' : '');
            img.onclick = function () { show(idx); };
            lbThumbs.appendChild(img);
        });
        var activeThumb = lbThumbs.querySelector('.lb-thumb.current');
        if (activeThumb) { activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' }); }
    }

    window.openLightbox = function (index) {
        show(index);
        lightbox.classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    window.closeLightbox = function () {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
    };

    window.lbStep = function (dir) { show(current + dir); };

    lightbox.addEventListener('click', function (e) {
        if (e.target === lightbox || e.target.classList.contains('lb-stage')) { closeLightbox(); }
    });

    lbImg.addEventListener('wheel', function (e) {
        e.preventDefault();
        scale += e.deltaY < 0 ? 0.15 : -0.15;
        if (scale < 1) { scale = 1; }
        if (scale > 4) { scale = 4; }
        lbImg.style.transform = 'scale(' + scale + ')';
        lbImg.classList.toggle('zoomed', scale > 1);
    });

    lbImg.addEventListener('click', function () {
        scale = scale > 1 ? 1 : 2;
        lbImg.style.transform = 'scale(' + scale + ')';
        lbImg.classList.toggle('zoomed', scale > 1);
    });

    document.addEventListener('keydown', function (e) {
        if (!lightbox.classList.contains('open')) { return; }
        if (e.key === 'Escape') { closeLightbox(); }
        if (e.key === 'ArrowLeft') { lbStep(-1); }
        if (e.key === 'ArrowRight') { lbStep(1); }
    });

    var touchX = null;
    lbImg.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
    lbImg.addEventListener('touchend', function (e) {
        if (touchX === null) { return; }
        var dx = e.changedTouches[0].clientX - touchX;
        if (Math.abs(dx) > 50) { lbStep(dx < 0 ? 1 : -1); }
        touchX = null;
    }, { passive: true });
})();
</script>

<script src="<?php echo base_url("assets/js/wow.min.js") ?>"></script>
<script>if (typeof WOW === "function") { new WOW({ offset: 60, mobile: true, live: true }).init(); }</script>
</body>
</html>
