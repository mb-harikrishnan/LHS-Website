<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Little Hearts | Gallery</title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/animate.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootsnav.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/loader.css') ?>">
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&family=Nunito+Sans:opsz,wght@6..12,400;6..12,600;6..12,700;6..12,800;6..12,900&display=swap" rel="stylesheet">

    <style>

        :root {
            --g-navy:#0b2447; --g-navy2:#123a6b; --g-gold:#f5a623; --g-gold2:#e2911a;
            --g-ink:#22314a; --g-muted:#5b6b84; --g-paper:#f6f9ff; --g-line:#e3ecf7;
            --g-r:22px; --g-sh:0 16px 36px rgba(11,36,71,.10);
        }
        body { font-family:"Nunito Sans",sans-serif; color:var(--g-ink); background:var(--g-paper); }
        .gallery-page {
            padding:70px 0 90px; min-height:60vh;
            background:
                radial-gradient(circle at 8% 6%, rgba(47,111,237,.07) 0, transparent 26%),
                radial-gradient(circle at 96% 92%, rgba(245,166,35,.08) 0, transparent 30%),
                var(--g-paper);
        }
        .gal-title { text-align:center; margin-bottom:44px; }
        .gal-title .eyebrow { display:inline-flex; align-items:center; gap:8px; color:var(--g-gold2); font-weight:800; font-size:13px; letter-spacing:1px; text-transform:uppercase; }
        .gal-title .eyebrow .dash { width:22px; height:2px; background:currentColor; }
        .gal-title h2 { font-family:"Fredoka",cursive; color:var(--g-navy); font-size:36px; font-weight:700; margin:10px 0 0; }
        .gal-title h2::after { content:""; display:block; height:6px; width:90px; margin:12px auto 0; border-radius:8px; background:linear-gradient(90deg,#2f6fed,var(--g-gold)); }
        .gal-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:26px; }
        .gal-empty { text-align:center; padding:70px 20px; color:var(--g-muted); background:#fff; border:2px dashed var(--g-line); border-radius:var(--g-r); }
        .gal-empty i { font-size:46px; color:var(--g-gold); margin-bottom:16px; display:block; }
        @media (max-width:991px){ .gal-grid{ grid-template-columns:repeat(2,1fr);} }
        @media (max-width:767px){ .gal-grid{ grid-template-columns:1fr;} }


        .event-card {
            display:block; position:relative; overflow:hidden; text-decoration:none !important;
            background:#fff; border:3px solid #fff; border-radius:var(--g-r);
            box-shadow:var(--g-sh);
            transition:transform .35s cubic-bezier(.34,1.56,.64,1), box-shadow .35s ease;
        }
        .event-card:hover, .event-card:focus-visible { transform:translateY(-8px) rotate(-.5deg); box-shadow:0 26px 50px rgba(11,36,71,.2); outline:none; }
        .event-cover { position:relative; height:240px; overflow:hidden; background:var(--g-line); }
        .event-cover img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .6s ease; }
        .event-card:hover .event-cover img { transform:scale(1.09); }
        .event-badge {
            position:absolute; top:14px; right:14px; z-index:2;
            background:rgba(11,36,71,.82); color:#fff; font-weight:800; font-size:12.5px;
            padding:6px 12px; border-radius:50px;
        }
        .event-badge i { color:var(--g-gold); margin-right:5px; }
        .event-body { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:16px 20px 18px; }
        .event-name { font-family:"Fredoka",cursive; color:var(--g-navy); font-size:19px; font-weight:600; margin:0; line-height:1.25; }
        .event-go {
            flex:none; width:40px; height:40px; border-radius:50%;
            background:var(--g-gold); color:var(--g-navy);
            display:flex; align-items:center; justify-content:center; font-size:16px;
            transition:.3s cubic-bezier(.34,1.56,.64,1);
        }
        .event-card:hover .event-go { transform:translateX(4px) scale(1.08); }
    </style>
</head>

<body>

<section class="gallery-page">
    <div class="container">

        <div class="gal-title">
            <span class="eyebrow"><span class="dash"></span> Our Moments</span>
            <h2>Photo Gallery</h2>
        </div>

        <?php if (!empty($all_events)) { ?>
        <div class="gal-grid">
            <?php foreach ($all_events as $ev) { ?>
                <a class="event-card wow fadeInUp" href="<?php echo base_url('gallery/' . $ev->slno); ?>"
                   title="<?php echo htmlspecialchars($ev->name); ?>">
                    <div class="event-cover">
                        <img src="<?php echo base_url('assets/images/gallery/' . $ev->c_cover); ?>"
                             alt="<?php echo htmlspecialchars($ev->name); ?>" loading="lazy">
                        <span class="event-badge"><i class="fa fa-camera"></i><?php echo (int) $ev->photo_count; ?> photos</span>
                    </div>
                    <div class="event-body">
                        <h3 class="event-name"><?php echo htmlspecialchars($ev->name); ?></h3>
                        <span class="event-go"><i class="fa fa-angle-right"></i></span>
                    </div>
                </a>
            <?php } ?>
        </div>
        <?php } else { ?>
            <div class="gal-empty"><i class="fa fa-picture-o"></i>No events have been added yet.</div>
        <?php } ?>

    </div>
</section>

<script src="<?php echo base_url("assets/js/wow.min.js") ?>"></script>
<script>if (typeof WOW === "function") { new WOW({ offset: 60, mobile: true, live: true }).init(); }</script>
</body>
</html>