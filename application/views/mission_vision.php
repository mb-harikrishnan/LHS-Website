<!--Search-->
<div id="search">
  <button type="button" class="close">×</button>
  <form>
    <input type="search" value="" placeholder="Search here...." required />
    <button type="submit" class="btn btn_common blue">Search</button>
  </form>
</div>
<!-- Mission and Vision -->
<!-- Mission and Vision -->
<section id="mission_vision" class="padding bg_light">
  <div class="container">

    <!-- Heading -->
    <div class="row">
      <div class="col-md-12 text-center">
        <div class="main_title wow fadeInUp margin_bottom">
          <h2>Our Mission And Vision</h2>
          <p>
            Inspiring minds with excellence, compassion, truth, and grace.
          </p>
        </div>
      </div>
    </div>

    <!-- Content -->
    <div class="row">
      <div class="col-md-10 col-md-offset-1">

        <div class="mission_box wow fadeInUp">

          <div class="mission_icon">
            <i class="fa fa-book"></i>
          </div>

          <div class="mission_content">

            <p>
              Guided by the ardent pursuit of
              <span>“STRIVE FOR EXCELLENCE,”</span>
              we, as stewards of divine trust, embrace the sacred charge
              of nurturing each precious soul entrusted to our care.
              Within these hallowed halls, we cultivate not merely scholars,
              but luminous beings whose spirits blossom into paragons of integrity,
              boundless creativity, and unwavering devotion—a magnificent tapestry
              woven with threads of empathy and reverence for all humanity.
            </p>

            <p>
              Here, every heartbeat echoes a covenant: to kindle within each pupil
            </p>

            <ul>
              <li>a radiant awareness of the Sacred,</li>
              <li>a profound veneration for the sanctity of life,</li>
              <li>and a fervent kinship with the marginalized.</li>
            </ul>

            <p>
              We labour tirelessly to ensure that every soul departing our embrace
              carries forth the torch of justice to sculpt a world where mercy
              and righteousness reign supreme— a testament to the transformative
              power of education rooted in grace and truth.
            </p>

          </div>

        </div>

      </div>
    </div>

  </div>
</section>

<style>


    /* ---- Mission list: same round pastel ✓ badge style ---- */
.mission_content ul{
    list-style:none;
    margin:0 0 18px;
    padding:0;
}
.mission_content ul li{
    position:relative;
    padding-left:30px !important;
    margin-bottom:10px;
    color:var(--fl-muted);
    font-size:15px;
    line-height:1.9;
}
.mission_content ul li::before{
    content:"✓" !important;
    font-family:'Nunito Sans' !important;
    position:absolute;
    left:0;
    top:5px;
    width:20px;
    height:20px;
    border-radius:50%;
    background:color-mix(in srgb, var(--acc) 16%, #fff);
    color:var(--acc);
    display:inline-flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:900;
}

.curriculum_card ul li::before,.fee_card ul li::before,.support_card ul li::before,
.rules_card ul li::before,.study_list li::before,
.mission_content ul li::before{ ... }

.curriculum_card ul li,.fee_card ul li,.support_card ul li,.rules_card ul li,.study_list li,
.mission_content ul li{padding-left:30px !important;}
/* ============================================================
   LITTLE HEARTS SCHOOL — SCHOOL UI LAYER v2 (internal per-page)
   School design: notebook paper bg, tape-top cards, dashed
   borders, cap/star icon chips, doodle blobs, Fredoka headings.
   Per-page accent color comes from the section id (--acc).
   ============================================================ */
:root{
    --fl-navy:#0b2447; --fl-navy2:#123a6b; --fl-gold:#f5a623; --fl-gold2:#e2911a;
    --fl-ink:#22314a; --fl-muted:#5b6b84; --fl-paper:#f6f9ff; --fl-line:#e3ecf7;
    --fl-r:22px; --fl-sh:0 16px 36px rgba(11,36,71,.10);
    --acc:#2f6fed;
}
/* ---- per-page accent colors ---- */
#about{--acc:#2f6fed} #mission_vision{--acc:#2f6fed} #curriculum{--acc:#22a06b}
#admissions{--acc:#e0475f} #discipline{--acc:#7c5cff} #fee_regulations{--acc:#0e9f6e}
#rules_and_regulations{--acc:#f97316} #scheme_of_studies{--acc:#2f6fed}
#school_uniform{--acc:#0ea5e9} #parental_support{--acc:#ec4899}
#mandatory_disclosure{--acc:#2f6fed} #transfer_certificates{--acc:#f5a623}
#downloads{--acc:#e0475f} #clubs{--acc:#f5a623} #news{--acc:#f5a623}
#sports{--acc:#22a06b} #activities{--acc:#8e6fe8} #fun{--acc:#2fb380}
#contact{--acc:#2f6fed} #teachers{--acc:#8e6fe8} #testinomila_page{--acc:#f5a623}
#vacancy{--acc:#2f6fed} #house_system{--acc:#e0475f} #band_page{--acc:#f5a623}
#study_tour{--acc:#22a06b} #directors_message{--acc:#2f6fed} #principals_message{--acc:#2fb380}

/* ---- notebook-paper page background + doodle blobs ---- */
#about,#mission_vision,#curriculum,#admissions,#discipline,#fee_regulations,
#rules_and_regulations,#scheme_of_studies,#school_uniform,#parental_support,
#mandatory_disclosure,#transfer_certificates,#downloads,#clubs,#news,#sports,
#activities,#fun,#contact,#teachers,#testinomila_page,#vacancy,#house_system,
#band_page,#study_tour,#directors_message,#principals_message{
    background-color:var(--fl-paper) !important;
    background-image:
        radial-gradient(circle at 8% 6%, color-mix(in srgb, var(--acc) 9%, transparent) 0, transparent 24%),
        radial-gradient(circle at 96% 90%, rgba(245,166,35,.10) 0, transparent 28%),
        radial-gradient(rgba(11,36,71,.055) 1.3px, transparent 1.3px) !important;
    background-size:auto, auto, 26px 26px !important;
    position:relative;
    overflow-x:hidden;
}
/* crayon doodle blobs (pure CSS, decorative) */
#about::before,#mission_vision::before,#curriculum::before,#admissions::before,
#discipline::before,#fee_regulations::before,#rules_and_regulations::before,
#scheme_of_studies::before,#school_uniform::before,#parental_support::before,
#downloads::before,#clubs::before,#fun::before,#contact::before{
    content:"";position:absolute;top:70px;right:3%;width:130px;height:130px;z-index:0;
    border-radius:42% 58% 62% 38%/48% 42% 58% 52%;
    border:3px dashed color-mix(in srgb, var(--acc) 38%, transparent);
    animation:fl-float 7s ease-in-out infinite;pointer-events:none;
}
#about::after,#curriculum::after,#admissions::after,#discipline::after,
#fee_regulations::after,#rules_and_regulations::after,#scheme_of_studies::after,
#school_uniform::after,#parental_support::after,#clubs::after,#fun::after{
    content:"";position:absolute;bottom:120px;left:2.5%;width:86px;height:86px;z-index:0;
    border-radius:50%;
    background:
        radial-gradient(circle at 35% 35%, color-mix(in srgb, var(--fl-gold) 55%, transparent), transparent 62%);
    animation:fl-float 9s ease-in-out infinite reverse;pointer-events:none;
}
@keyframes fl-float{0%,100%{transform:translateY(0) rotate(0)}50%{transform:translateY(-16px) rotate(8deg)}}

/* ---- Fredoka headings with marker highlight underline ---- */
h1,h2,h3,h4,h5,h6,.heading,.main_title h2{font-family:'Fredoka',cursive !important;letter-spacing:.2px;}
body,p,li,a,span,td,th,label,input,textarea,select,button{font-family:'Nunito Sans',sans-serif !important;}

.main_title h2,.heading{
    position:relative;display:inline-block;margin-bottom:8px;z-index:1;
}
.main_title h2::after,.heading::after{
    content:"";position:absolute;left:50%;transform:translateX(-50%);
    bottom:-4px;width:110%;height:12px;z-index:-1;border-radius:6px;
    background:linear-gradient(90deg,
        color-mix(in srgb, var(--acc) 26%, transparent),
        color-mix(in srgb, var(--fl-gold) 32%, transparent));
    transform:translateX(-50%) rotate(-.6deg);
}
.heading::after{left:0;transform:rotate(-.6deg);width:100px;}
.divider-left,.divider-center{display:none !important;}
.main_title p{color:var(--fl-muted);font-size:15.5px;line-height:1.9;max-width:860px;margin:16px auto 0;}

/* ---- pencil-dash tag chips ---- */
.admission_tag,.curriculum_tag,.discipline_tag,.fee_tag,.rules_tag,.scheme_tag,
.support_tag,.uniform_tag,.fun_tag,.subtitle{
    display:inline-flex;align-items:center;gap:9px;
    background:#fff;
    color:var(--acc);
    border:2px dashed color-mix(in srgb, var(--acc) 50%, #fff);
    padding:8px 20px;border-radius:50px;font-weight:800;font-size:12.5px;
    letter-spacing:1.1px;text-transform:uppercase;margin-bottom:14px;
    box-shadow:3px 3px 0 color-mix(in srgb, var(--acc) 18%, transparent);
}
.admission_tag::before,.curriculum_tag::before,.discipline_tag::before,.fee_tag::before,
.rules_tag::before,.scheme_tag::before,.support_tag::before,.uniform_tag::before,
.fun_tag::before,.subtitle::before{content:"✏";font-size:13px;}

/* ---- cards: clean white paper ---- */
.admission_card,.curriculum_card,.fee_card,.rules_card,.study_card,.support_card,
.disclosure_card,.uniform_card,.club_box,.download_box,
.mission_box,.fun_content_box,.about-detail-box,.mission-box,.discipline_wrapper,
.director-image-box,.director-message-box,.principal-image-box,.principal-message-box,
.closing_message,.highlight_box,.news_box,.teacher,.note_card{
    position:relative;
    background:#fff !important;
    border:2px solid var(--fl-line) !important;
    border-radius:var(--fl-r) !important;
    box-shadow:var(--fl-sh) !important;
    transition:transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease !important;
}
.admission_card:hover,.curriculum_card:hover,.fee_card:hover,.rules_card:hover,
.study_card:hover,.support_card:hover,.disclosure_card:hover,.uniform_card:hover,
.club_box:hover,.download_box:hover,
.mission_box:hover,.fun_content_box:hover,.about-detail-box:hover,
.director-image-box:hover,.principal-image-box:hover,.news_box:hover,
.teacher:hover,.note_card:hover{
    transform:translateY(-8px) rotate(-.4deg) !important;
    box-shadow:0 26px 50px rgba(11,36,71,.17) !important;
    border-color:color-mix(in srgb, var(--acc) 45%, #fff) !important;
}

/* ---- icon chips: graduation-cap style rounded squares ---- */
.admission_icon,.curriculum_icon,.fee_icon,.rules_icon,.support_icon,.study_header i,
.discipline_item i,.uniform_icon,.note_icon,.intro_icon,.funlearn_card .icon_box,.mission_icon{
    border-radius:16px !important;
    background:linear-gradient(135deg, var(--acc), color-mix(in srgb, var(--acc) 68%, #000)) !important;
    box-shadow:0 10px 20px color-mix(in srgb, var(--acc) 35%, transparent) !important;
    transition:transform .35s cubic-bezier(.34,1.56,.64,1) !important;
}
.admission_card:hover .admission_icon,.curriculum_card:hover .curriculum_icon,
.fee_card:hover .fee_icon,.rules_card:hover .rules_icon,.support_card:hover .support_icon,
.discipline_item:hover i,.uniform_card:hover .uniform_icon,.note_card:hover .note_icon{
    transform:rotate(-8deg) scale(1.1);
}

/* ---- text colors ---- */
.admission_card h3,.curriculum_card h3,.fee_card h3,.rules_card h3,.support_card h3,
.study_card h3,.club_box h3,.download_box h4,.vacancy_title,.fun_content_box h3,
.about-detail-box h3,.news_box h4,.note_content h4,.disclosure_card .card_title{color:var(--fl-navy) !important;}
.admission_card p,.curriculum_card p,.fee_card p,.rules_card p,.support_card p,
.club_box p,.fun_content_box p,.news_box p,.discipline_item p,.note_content p{color:var(--fl-muted) !important;font-size:15px;line-height:1.9;}

/* ---- checklist: round pastel ✓ badges ---- */
.curriculum_card ul li::before,.fee_card ul li::before,.support_card ul li::before,
.rules_card ul li::before,.study_list li::before{
    content:"✓" !important;font-family:'Nunito Sans' !important;
    width:20px;height:20px;border-radius:50%;
    background:color-mix(in srgb, var(--acc) 16%, #fff);
    color:var(--acc);display:inline-flex;align-items:center;justify-content:center;
    font-size:11px;font-weight:900;left:0;top:3px;
}
.curriculum_card ul li,.fee_card ul li,.support_card ul li,.rules_card ul li,.study_list li{padding-left:30px !important;}

/* ---- tables: rounded, pastel header ---- */
.fee_table,.uniform_table,.disclosure_table{border-radius:14px;overflow:hidden;}
.fee_table thead,.uniform_table thead,.disclosure_table thead,.tc_table thead tr th{
    background:linear-gradient(135deg, var(--acc), color-mix(in srgb, var(--acc) 72%, #000)) !important;
}
.fee_table thead th,.uniform_table thead th,.disclosure_table thead th{
    font-family:'Fredoka' !important;font-weight:600;letter-spacing:.4px;
}
.disclosure_table tbody tr:hover td,.fee_table tbody tr:hover td,.uniform_table tbody tr:hover td{
    background:color-mix(in srgb, var(--acc) 7%, #fff) !important;
}

/* ---- buttons: pill with hard offset shadow (sticker feel) ---- */
.btn_common,.btn_download,.view_btn,.apply_btn,.download_btn,.admission_btn,.submit_btn,.back_btn{
    border-radius:50px !important;font-weight:800 !important;letter-spacing:.3px;
    box-shadow:0 6px 0 color-mix(in srgb, var(--acc) 55%, #000) !important;
    transition:transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s ease !important;
}
.btn_common:hover,.btn_download:hover,.view_btn:hover,.apply_btn:hover,
.download_btn:hover,.admission_btn:hover,.submit_btn:hover{
    transform:translateY(-3px) !important;
    box-shadow:0 9px 0 color-mix(in srgb, var(--acc) 55%, #000) !important;
}
.view_btn,.btn_download,.apply_btn{
    background:linear-gradient(135deg, var(--acc), color-mix(in srgb, var(--acc) 75%, #000)) !important;
}

/* ---- banner images: white polaroid frame + tilt ---- */
.house_banner img,.band_banner img{
    border-radius:18px !important;
    border:10px solid #fff;
    box-shadow:0 24px 50px rgba(11,36,71,.18) !important;
    transform:rotate(-.7deg);
}
.house_box{
    border-radius:26px !important;
    border:4px solid rgba(255,255,255,.55) !important;
    min-height:220px;display:flex;align-items:center;justify-content:center;
    box-shadow:0 18px 40px rgba(11,36,71,.16) !important;
}
.house_box h3{font-size:30px !important;letter-spacing:1px;}

/* ---- leaders / messages ---- */
.director-quote{
    background:color-mix(in srgb, var(--acc) 8%, #fff) !important;
    border-left:6px solid var(--acc) !important;border-radius:16px !important;
}
.director-img,.principal-img{border:8px solid #fff !important;box-shadow:var(--fl-sh) !important;border-radius:18px !important;}

/* ---- news cards: gold spine ---- */
.news_box{border-left:6px solid var(--fl-gold) !important;}

/* ---- responsive ---- */
@media(max-width:767px){
    .main_title h2,.heading{font-size:27px !important;}
    #about::before,#admissions::before,#curriculum::before,#discipline::before{width:70px;height:70px;}
}

/* ---- v5 creative flourishes (school theme, internal) ---- */

/* title block: chip on its own line, h2 full block */
.main_title h2 { display: block; margin-top: 4px; }

/* marker underline draws itself in when WOW fires */
.main_title h2::after {
    transform: translateX(-50%) rotate(-.6deg) scaleX(0);
    transform-origin: center;
    transition: transform .7s cubic-bezier(.22, 1, .36, 1) .2s;
}

.main_title.animated h2::after {
    transform: translateX(-50%) rotate(-.6deg) scaleX(1);
}


/* page header: floating doodle ring + gold glow */
.page_header { position: relative; overflow: hidden; }

.page_header::before {
    content: ""; position: absolute; right: 6%; top: 18%;
    width: 120px; height: 120px; pointer-events: none;
    border: 3px dashed rgba(255, 255, 255, .22);
    border-radius: 42% 58% 62% 38% / 48% 42% 58% 52%;
    animation: fl-float 8s ease-in-out infinite;
}

.page_header::after {
    content: ""; position: absolute; left: 5%; bottom: -30px;
    width: 90px; height: 90px; pointer-events: none;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, rgba(245, 166, 35, .25), transparent 62%);
    animation: fl-float 10s ease-in-out infinite reverse;
}

.page_header .page-content { position: relative; z-index: 1; }

/* cards lift with an accent strip (no layout shift) */
.admission_card:hover, .curriculum_card:hover, .fee_card:hover, .rules_card:hover,
.study_card:hover, .support_card:hover, .disclosure_card:hover, .uniform_card:hover,
.club_box:hover, .download_box:hover, .mission_box:hover, .fun_content_box:hover,
.about-detail-box:hover, .director-image-box:hover, .principal-image-box:hover,
.news_box:hover, .teacher:hover, .note_card:hover {
    box-shadow: 0 26px 50px rgba(11, 36, 71, .17), inset 0 5px 0 var(--acc) !important;
}

/* gold glow blob for sections that lacked one */
#mission_vision::after, #downloads::after, #news::after, #sports::after, #activities::after,
#teachers::after, #testinomila_page::after, #vacancy::after, #house_system::after,
#band_page::after, #study_tour::after, #directors_message::after, #principals_message::after,
#contact::after {
    content: ""; position: absolute; bottom: 120px; left: 2.5%;
    width: 86px; height: 86px; z-index: 0; pointer-events: none;
    border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, color-mix(in srgb, var(--fl-gold) 55%, transparent), transparent 62%);
    animation: fl-float 9s ease-in-out infinite reverse;
}

@media (max-width: 767px) {
    .page_header::before { width: 64px; height: 64px; right: 4%; }
    .page_header::after { width: 48px; height: 48px; left: 4%; }
    #mission_vision::after, #downloads::after, #news::after, #sports::after, #activities::after,
    #teachers::after, #testinomila_page::after, #vacancy::after, #house_system::after,
    #band_page::after, #study_tour::after, #directors_message::after, #principals_message::after,
    #contact::after { width: 52px; height: 52px; bottom: 60px; }
}

/* ---- v9: refined card accents (replaces washi tape) ---- */
.admission_card,.curriculum_card,.fee_card,.rules_card,.study_card,.support_card,
.disclosure_card,.uniform_card,.club_box,.download_box,.mission_box,.fun_content_box,
.about-detail-box,.news_box,.teacher,.note_card{
    overflow:hidden;
    border-top:4px solid color-mix(in srgb, var(--acc) 45%, #fff) !important;
}
.admission_card::after,.curriculum_card::after,.fee_card::after,.rules_card::after,
.study_card::after,.support_card::after,.club_box::after,.download_box::after,
.mission_box::after,.fun_content_box::after,.news_box::after,.note_card::after{
    content:"";position:absolute;top:-48px;right:-48px;width:120px;height:120px;
    border-radius:50%;pointer-events:none;z-index:0;
    background:radial-gradient(circle at 32% 32%,
        color-mix(in srgb, var(--acc) 20%, transparent), transparent 68%);
    transition:transform .45s cubic-bezier(.22,1,.36,1);
}
.admission_card:hover::after,.curriculum_card:hover::after,.fee_card:hover::after,
.rules_card:hover::after,.study_card:hover::after,.support_card:hover::after,
.club_box:hover::after,.download_box:hover::after,.mission_box:hover::after,
.fun_content_box:hover::after,.news_box:hover::after,.note_card:hover::after{
    transform:scale(1.3);
}
</style>


