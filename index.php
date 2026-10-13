<?php require __DIR__ . '/inc/lib.php'; $showcase = yp_showcase_items(); ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Yelken Perde — Sultanbeyli İstanbul | Jaluzi, Perde ve Stor Uzmanı</title>
<meta name="description" content="Yelken Perde Sultanbeyli/İstanbul — 2010'dan beri ahşap jaluzi, zebra perde, stor perde, tül ve fon perde üretim ve montaj hizmeti. Ücretsiz ölçüm ve randevu.">
<meta name="keywords" content="yelken perde, sultanbeyli perde, istanbul jaluzi, ahşap jaluzi, zebra perde, stor perde, fon perde, tül perde, perde montaj">
<meta name="author" content="Yelken Perde">
<link rel="canonical" href="https://yelkenperde.com/">

<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="Yelken Perde — Sultanbeyli İstanbul">
<meta property="og:description" content="2010'dan beri perde ve jaluzi uzmanı. Ücretsiz ölçüm ve profesyonel montaj.">
<meta property="og:image" content="https://yelkenperde.com/images/projects/modern-krem-bej-tul-fon.jpg">
<meta property="og:url" content="https://yelkenperde.com/">
<meta property="og:locale" content="tr_TR">

<!-- Favicon -->
<link rel="icon" type="image/png" href="images/favicon.png">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap"></noscript>

<!-- CSS -->
<link rel="stylesheet" href="styles.css?v=3">
<link rel="stylesheet" href="home.css?v=5">
<script>if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) document.documentElement.classList.add('motion-ok');</script>

<!-- Structured Data (SEO) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "Yelken Perde",
  "image": "https://yelkenperde.com/images/logo.jpg",
  "telephone": "+905423313413",
  "email": "info@yelkenperde.com",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Orhangazi, Eşref Bitlis Blv. No:86",
    "addressLocality": "Sultanbeyli",
    "addressRegion": "İstanbul",
    "postalCode": "34925",
    "addressCountry": "TR"
  },
  "openingHoursSpecification": [
    {
      "@type": "OpeningHoursSpecification",
      "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      "opens": "09:00",
      "closes": "21:00"
    }
  ],
  "priceRange": "$$",
  "url": "https://yelkenperde.com/",
  "sameAs": ["https://www.instagram.com/yelken.perde"]
}
</script>

<style>
  /* ═══════ ANA SAYFA ÖZEL STİLLERİ ═══════ */

  /* MARQUEE */
  .marquee-section {
    background: var(--charcoal);
    padding: 18px 0;
    overflow: hidden;
    white-space: nowrap;
  }

  .marquee-track {
    display: inline-flex;
    animation: marquee 35s linear infinite;
  }

  .marquee-item {
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--sand);
    padding: 0 40px;
  }

  .marquee-dot { color: var(--gold); margin: 0 -24px; }

  @keyframes marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
  }

  /* ÜRÜNLER PREVIEW */
  #urunler-preview {
    background: var(--warm-white);
  }

  .products-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 56px;
    gap: 40px;
    flex-wrap: wrap;
  }

  .products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2px;
  }

  .product-card {
    position: relative;
    cursor: pointer;
    overflow: hidden;
    background: var(--linen);
    aspect-ratio: 3/4;
  }

  .product-card.large {
    grid-row: span 2;
    aspect-ratio: auto;
  }

  .product-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .product-card:hover img { transform: scale(1.05); }

  .product-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(transparent 50%, rgba(30,26,23,0.8));
    z-index: 1;
    transition: opacity 0.4s;
  }

  .product-info {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    padding: 24px 28px;
    color: white;
    z-index: 2;
  }

  .product-name {
    font-family: var(--serif);
    font-size: 22px;
    font-weight: 300;
    margin-bottom: 4px;
  }

  .product-card.large .product-name {
    font-size: 28px;
  }

  .product-sub {
    font-size: 11px;
    font-weight: 300;
    letter-spacing: 0.1em;
    opacity: 0.85;
    text-transform: uppercase;
  }

  .product-arrow {
    position: absolute;
    top: 20px;
    right: 20px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
    transition: all 0.3s;
    opacity: 0;
    transform: translateY(-8px);
  }

  .product-card:hover .product-arrow {
    opacity: 1;
    transform: translateY(0);
    background: var(--gold);
  }

  .product-arrow svg {
    width: 14px;
    height: 14px;
    stroke: var(--charcoal);
    transition: stroke 0.3s;
  }

  .product-card:hover .product-arrow svg { stroke: white; }

  /* NEDEN BİZ */
  #neden {
    background: var(--cream);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
  }

  .features-list {
    list-style: none;
    margin-top: 40px;
  }

  .feature-item {
    display: flex;
    gap: 24px;
    padding: 26px 0;
    border-bottom: 1px solid var(--linen);
    align-items: flex-start;
  }

  .feature-number {
    font-family: var(--serif);
    font-size: 32px;
    font-weight: 300;
    color: var(--gold);
    line-height: 1;
    min-width: 40px;
  }

  .feature-content h3 {
    font-family: var(--sans);
    font-size: 14px;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--charcoal);
    margin-bottom: 8px;
  }

  .feature-content p {
    font-size: 14px;
    font-weight: 300;
    line-height: 1.7;
    color: var(--stone);
  }

  .neden-visual {
    position: relative;
    height: 560px;
  }

  .neden-img-main {
    position: absolute;
    top: 0; right: 0;
    width: 82%;
    height: 78%;
    border-radius: 4px;
    overflow: hidden;
  }

  .neden-img-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .neden-card-accent {
    position: absolute;
    bottom: 0; left: 0;
    width: 58%;
    height: 38%;
    background: var(--charcoal);
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 36px;
    box-shadow: var(--shadow-lg);
  }

  .neden-card-accent .big-number {
    font-family: var(--serif);
    font-size: 58px;
    font-weight: 300;
    color: var(--gold);
    line-height: 1;
  }

  .neden-card-accent p {
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--sand);
    margin-top: 12px;
    line-height: 1.5;
  }

  /* SÜREÇ */
  #surec {
    background: var(--charcoal);
    color: white;
  }

  #surec .section-eyebrow { color: var(--gold-light); }
  #surec .section-title { color: white; }
  #surec .section-desc { color: rgba(201,188,168,0.75); }

  .steps-header {
    text-align: center;
    margin-bottom: 56px;
  }

  .steps-header .section-title,
  .steps-header .section-desc {
    margin-left: auto;
    margin-right: auto;
  }

  .steps-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2px;
  }

  .step-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    padding: 40px 32px;
    border-radius: 2px;
    transition: all 0.4s;
    position: relative;
    overflow: hidden;
  }

  .step-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 2px;
    background: var(--gold);
    transform: scaleX(0);
    transition: transform 0.4s;
  }

  .step-card:hover::before { transform: scaleX(1); }
  .step-card:hover { background: rgba(255,255,255,0.06); }

  .step-num {
    font-family: var(--serif);
    font-size: 52px;
    font-weight: 300;
    color: rgba(184,151,90,0.3);
    line-height: 1;
    margin-bottom: 24px;
  }

  .step-title {
    font-size: 13px;
    font-weight: 500;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: white;
    margin-bottom: 14px;
  }

  .step-desc {
    font-size: 14px;
    font-weight: 300;
    line-height: 1.75;
    color: var(--sand);
  }

  /* REFERANSLAR / ALAN BİLGİSİ */
  #bolgeler {
    background: var(--warm-white);
    padding: 80px 80px;
  }

  .bolgeler-grid {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 60px;
    align-items: center;
  }

  .bolgeler-list {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 28px;
  }

  .bolgeler-list span {
    font-size: 13px;
    font-weight: 300;
    color: var(--stone);
    padding: 10px 14px;
    background: var(--cream);
    border-radius: 2px;
    text-align: center;
    border: 1px solid var(--linen);
  }

  /* RANDEVU CTA */
  #randevu {
    background: var(--cream-dark);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    padding: 0;
    min-height: 560px;
  }

  .randevu-left {
    padding: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .contact-options {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 32px;
  }

  .contact-btn {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 18px 24px;
    background: white;
    border: 1px solid var(--linen);
    border-radius: 4px;
    color: var(--charcoal);
    transition: all 0.3s;
    font-size: 14px;
    font-weight: 400;
  }

  .contact-btn:hover {
    border-color: var(--gold);
    box-shadow: 0 4px 20px rgba(184,151,90,0.12);
    transform: translateX(4px);
  }

  .contact-btn-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .icon-whatsapp { background: #E8F5E9; }
  .icon-phone { background: var(--linen); }
  .icon-maps { background: #E8EAF6; }

  .contact-btn-text strong {
    display: block;
    font-weight: 500;
    font-size: 14px;
    margin-bottom: 2px;
  }

  .contact-btn-text span {
    font-size: 12px;
    color: var(--stone);
    font-weight: 300;
  }

  .randevu-right {
    position: relative;
    background: var(--charcoal);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 56px;
  }

  .randevu-image {
    position: absolute;
    inset: 0;
    z-index: 1;
  }

  .randevu-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.25;
  }

  .randevu-quote {
    position: relative;
    z-index: 2;
  }

  .randevu-quote blockquote {
    font-family: var(--serif);
    font-size: 30px;
    font-weight: 300;
    font-style: italic;
    color: white;
    line-height: 1.4;
    margin-bottom: 24px;
  }

  .randevu-quote cite {
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold);
    font-style: normal;
  }

  .randevu-adres {
    margin-top: 40px;
    padding-top: 28px;
    border-top: 1px solid rgba(255,255,255,0.15);
    display: flex;
    gap: 36px;
    flex-wrap: wrap;
  }

  .adres-item { display: flex; flex-direction: column; gap: 4px; }

  .adres-label {
    font-size: 10px;
    font-weight: 400;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(201,188,168,0.5);
  }

  .adres-value {
    font-size: 13px;
    font-weight: 300;
    color: var(--sand);
  }

  /* MOBİL */
  @media (max-width: 900px) {

    .products-header { flex-direction: column; align-items: flex-start; }
    .products-grid { grid-template-columns: 1fr 1fr; }
    .product-card.large { grid-row: span 1; aspect-ratio: 3/4; }

    #neden { grid-template-columns: 1fr; padding: 64px 24px; }
    .neden-visual { height: 360px; }


    .steps-grid { grid-template-columns: 1fr 1fr; }

    .bolgeler-grid { grid-template-columns: 1fr; gap: 32px; }
    .bolgeler-list { grid-template-columns: 1fr 1fr; }

    #randevu { grid-template-columns: 1fr; }
    .randevu-left { padding: 56px 24px; }
    .randevu-right { padding: 48px 24px; min-height: 320px; }
    .randevu-quote blockquote { font-size: 22px; }
  }

  @media (max-width: 600px) {
    .products-grid { grid-template-columns: 1fr 1fr; gap: 1px; }
    .steps-grid { grid-template-columns: 1fr; }
  }
</style>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XG42521ET7"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XG42521ET7');
</script>

<style>
/* Mobil menü kritik düzeltme - inline */
@media (max-width: 900px) {
  .nav-links.open {
    display: flex !important;
    flex-direction: column !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: #FAF8F4 !important;
    background: #FAF8F4 !important;
    z-index: 9999 !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 28px !important;
    padding: 60px 24px 24px !important;
    margin: 0 !important;
    list-style: none !important;
  }
  body.menu-open {
    overflow: hidden !important;
  }
  body.menu-open .whatsapp-float {
    display: none !important;
  }
  body.menu-open .nav-mobile-toggle {
    z-index: 10000 !important;
    position: fixed !important;
    right: 24px !important;
    top: 18px !important;
  }
}
</style>
</head>
<body class="home">

<!-- ═══════ NAV ═══════ -->
<nav id="navbar">
  <a href="index.html" class="nav-logo">
    <img class="logo-on-dark" src="images/logo-light.png" alt="Yelken Perde" width="173" height="240">
    <img class="logo-on-light" src="images/logo-dark.png" alt="" aria-hidden="true" width="174" height="240">
  </a>
  <ul class="nav-links" id="navLinks" data-menu>
    <li><a href="index.html" class="active">Ana Sayfa</a></li>
    <li><a href="urunler.html">Ürünler</a></li>
    <li><a href="galeri.html">Galeri</a></li>
    <li><a href="hakkimizda.html">Hakkımızda</a></li>
    <li><a href="blog.html">Blog</a></li>
    <li><a href="iletisim.html">İletişim</a></li>
    <li class="nav-cta-item"><a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20ölçüm%20randevusu%20almak%20istiyorum." class="nav-cta" target="_blank">Randevu Al</a></li>
  </ul>
  <a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20ölçüm%20randevusu%20almak%20istiyorum." class="nav-side-cta" target="_blank">Randevu Al</a>
  <button class="nav-mobile-toggle" id="menuToggle" onclick="toggleMenu()" aria-label="Menü">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- ═══════ HERO: kaydırdıkça değişen proje fotoğrafları ═══════ -->
<?php
$heroSlides = [
  ['img' => 'images/projects/modern-krem-bej-tul-fon',      'label' => 'Modern Salon', 'sub' => 'Fon + Tül Perde'],
  ['img' => 'images/projects/kahverengi-ahsap-jalusi-fon',  'label' => 'Klasik Salon', 'sub' => 'Drapeli Fon Perde'],
  ['img' => 'images/projects/ahsap-jaluzi-manzaral-kahve',  'label' => 'Balkon',       'sub' => 'Ahşap Jaluzi'],
  ['img' => 'images/projects/genis-salon-krem-beyaz-perde', 'label' => 'Geniş Salon',  'sub' => 'Tül + Fon Perde'],
];
?>
<section id="hero" class="cine-hero" style="--n: <?= count($heroSlides) ?>">
  <div class="cine-stage">
    <div class="cine-slides">
<?php foreach ($heroSlides as $i => $s): ?>
      <div class="cine-slide"<?= $i ? ' aria-hidden="true"' : '' ?>>
        <picture>
          <source srcset="<?= e($s['img']) ?>-desktop.webp" type="image/webp">
          <img src="<?= e($s['img']) ?>.jpg" alt="<?= e($s['label'] . ' — ' . $s['sub'] . ' uygulaması') ?>"<?= $i ? ' loading="lazy"' : ' fetchpriority="high"' ?>>
        </picture>
      </div>
<?php endforeach; ?>
    </div>
    <div class="cine-shade" aria-hidden="true"></div>

    <div class="cine-copy">
      <p class="cine-eyebrow">Sultanbeyli · İstanbul · 2010'dan beri</p>
      <h1 class="cine-title">
        <span class="cine-line"><span>Pencerenize</span></span>
        <span class="cine-line"><span><em>Değer Katan</em></span></span>
        <span class="cine-line"><span>Perdeler</span></span>
      </h1>
      <p class="cine-subtitle">Jaluzi, Stor ve Perde Uzmanlığı</p>
      <p class="cine-desc">
      15 yıllık tecrübemizle evinizin ve işyerinizin atmosferini dönüştürüyoruz. Ücretsiz keşif, profesyonel ölçüm ve kusursuz montaj ile size özel çözümler sunuyoruz.
    </p>
    <div class="cine-buttons">
      <a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20ölçüm%20randevusu%20almak%20istiyorum." class="btn-primary" target="_blank">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        Ücretsiz Randevu Al
      </a>
      <a href="galeri.html" class="btn-secondary">
        Projelerimizi İnceleyin
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
    <div class="cine-stats">
      <div>
        <div class="stat-number">15<em>+</em></div>
        <div class="stat-label">Yıl Deneyim</div>
      </div>
      <div>
        <div class="stat-number">500<em>+</em></div>
        <div class="stat-label">Tamamlanan Proje</div>
      </div>
      <div>
        <div class="stat-number">%100</div>
        <div class="stat-label">Müşteri Memnuniyeti</div>
      </div>
    </div>
    </div>

    <div class="cine-meta" aria-hidden="true">
      <div class="cine-captions">
<?php foreach ($heroSlides as $i => $s): ?>
        <p class="cine-caption<?= $i ? '' : ' is-active' ?>"><span class="cine-num"><?= sprintf('%02d', $i + 1) ?></span><?= e($s['label']) ?> <em><?= e($s['sub']) ?></em></p>
<?php endforeach; ?>
      </div>
      <div class="cine-bars">
<?php foreach ($heroSlides as $s): ?>
        <span><i></i></span>
<?php endforeach; ?>
      </div>
    </div>

    <p class="cine-cue" aria-hidden="true"><span>Kaydırın</span><i></i></p>
  </div>
</section>

<!-- ═══════ MARQUEE ═══════ -->
<div class="marquee-section">
  <div class="marquee-track">
    <span class="marquee-item">Ahşap Jaluzi</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Zebra Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Stor Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Tül Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Fon Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Dikey Jaluzi</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Ücretsiz Ölçüm</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Profesyonel Montaj</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">İstanbul Geneli</span>
    <span class="marquee-item marquee-dot">·</span>
    <!-- Tekrar -->
    <span class="marquee-item">Ahşap Jaluzi</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Zebra Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Stor Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Tül Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Fon Perde</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Dikey Jaluzi</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Ücretsiz Ölçüm</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">Profesyonel Montaj</span>
    <span class="marquee-item marquee-dot">·</span>
    <span class="marquee-item">İstanbul Geneli</span>
    <span class="marquee-item marquee-dot">·</span>
  </div>
</div>

<!-- ═══════ ÜRÜNLER PREVIEW ═══════ -->
<section id="urunler-preview">
  <div class="products-header reveal">
    <div>
      <p class="section-eyebrow">Koleksiyonumuz</p>
      <h2 class="section-title">Her Mekâna<br>Uygun <em>Çözüm</em></h2>
    </div>
    <a href="urunler.html" class="btn-secondary">Tüm Ürünleri İncele
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>

  <div class="products-grid reveal">
    <a href="urunler.html#fon-perde" class="product-card large">
      <?= yp_img(yp_product('fon-perde')['image'], yp_product('fon-perde')['alt'], ['loading' => 'lazy']) ?>
      <span class="product-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
      <div class="product-info">
        <div class="product-name">Fon Perde</div>
        <div class="product-sub">Modern ve Klasik — 500+ Renk</div>
      </div>
    </a>

    <a href="urunler.html#ahsap-jaluzi" class="product-card">
      <?= yp_img(yp_product('ahsap-jaluzi')['image'], yp_product('ahsap-jaluzi')['alt'], ['loading' => 'lazy']) ?>
      <span class="product-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
      <div class="product-info">
        <div class="product-name">Ahşap Jaluzi</div>
        <div class="product-sub">Doğal · 25 / 50 mm</div>
      </div>
    </a>

    <a href="urunler.html#zebra-perde" class="product-card">
      <?= yp_img(yp_product('zebra-perde')['image'], yp_product('zebra-perde')['alt'], ['loading' => 'lazy']) ?>
      <span class="product-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
      <div class="product-info">
        <div class="product-name">Zebra Perde</div>
        <div class="product-sub">Şık ve Fonksiyonel</div>
      </div>
    </a>

    <a href="urunler.html#stor-perde" class="product-card">
      <?= yp_img(yp_product('stor-perde')['image'], yp_product('stor-perde')['alt'], ['loading' => 'lazy']) ?>
      <span class="product-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
      <div class="product-info">
        <div class="product-name">Stor Perde</div>
        <div class="product-sub">Sade ve Pratik</div>
      </div>
    </a>

    <a href="urunler.html#tul-perde" class="product-card">
      <?= yp_img(yp_product('tul-perde')['image'], yp_product('tul-perde')['alt'], ['loading' => 'lazy']) ?>
      <span class="product-arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M7 17L17 7M7 7h10v10"/></svg></span>
      <div class="product-info">
        <div class="product-name">Tül Perde</div>
        <div class="product-sub">Zarif ve Geleneksel</div>
      </div>
    </a>
  </div>
</section>

<!-- ═══════ NEDEN BİZ ═══════ -->
<section id="neden">
  <div>
    <p class="section-eyebrow reveal">Neden Yelken Perde?</p>
    <h2 class="section-title reveal">15 Yıllık<br><em>Tecrübe ve Güven</em></h2>
    <p class="section-desc reveal">
      2010'dan bu yana Sultanbeyli'deki atölyemizde ürettiğimiz perdelerle İstanbul'un dört bir yanında yüzlerce projeye imza attık. Müşteri memnuniyeti bizim için pazarlık konusu değil.
    </p>
    <ul class="features-list">
      <li class="feature-item reveal">
        <span class="feature-number">01</span>
        <div class="feature-content">
          <h3>Ücretsiz Keşif ve Ölçüm</h3>
          <p>Uzman ekibimiz evinize veya işyerinize gelir, tüm pencerelerinizi ölçer ve mekanınıza en uygun çözümü önerir. Hiçbir ek ücret ödemezsiniz.</p>
        </div>
      </li>
      <li class="feature-item reveal">
        <span class="feature-number">02</span>
        <div class="feature-content">
          <h3>Kendi Atölyemiz, Kendi Üretimimiz</h3>
          <p>Perdelerinizi Sultanbeyli'deki atölyemizde özenle üretiyoruz. Bu sayede hem kaliteyi kontrol ediyor hem uygun fiyat sunuyoruz.</p>
        </div>
      </li>
      <li class="feature-item reveal">
        <span class="feature-number">03</span>
        <div class="feature-content">
          <h3>Hızlı Teslim ve Profesyonel Montaj</h3>
          <p>Siparişinizi en geç 7 iş günü içinde hazırlayıp, randevunuza göre profesyonel ekibimizle kurulumu yapıyoruz.</p>
        </div>
      </li>
      <li class="feature-item reveal">
        <span class="feature-number">04</span>
        <div class="feature-content">
          <h3>Geniş Ürün Çeşitliliği</h3>
          <p>500'den fazla renk ve 20+ farklı kumaş seçeneğiyle her tarza, her bütçeye uygun çözüm sunuyoruz. Klasikten moderne, zengin bir koleksiyon.</p>
        </div>
      </li>
    </ul>
  </div>

  <div class="neden-visual reveal">
    <div class="neden-img-main">
      <picture><source media="(max-width: 600px)" srcset="images/projects/altin-lux-salon-klasik-mobile.webp" type="image/webp"><source media="(max-width: 1024px)" srcset="images/projects/altin-lux-salon-klasik-tablet.webp" type="image/webp"><source srcset="images/projects/altin-lux-salon-klasik-desktop.webp" type="image/webp"><img src="images/projects/altin-lux-salon-klasik.jpg" alt="Lüks salon perde uygulaması"></picture>
    </div>
    <div class="neden-card-accent">
      <div class="big-number">500<span style="font-size: 28px">+</span></div>
      <p>İstanbul genelinde<br>tamamlanan proje</p>
    </div>
  </div>
</section>

<!-- ═══════ GALERİ PREVIEW ═══════ -->
<?php if ($showcase): $collage = array_slice($showcase, 0, 7); ?>
<!-- ═══════ PROJELER: kaydırınca yakınlaşan kolaj ═══════ -->
<section id="projeler-preview" class="zoom-collage zc-n<?= count($collage) ?>">
  <div class="zc-head">
    <p class="section-eyebrow">Referans Projelerimiz</p>
    <h2 class="section-title">Son <em>İşlerimizden</em> Bir Seçki</h2>
  </div>
  <div class="zc-stage">
<?php foreach ($collage as $i => $item):
  // Ortadaki fotoğraf ekranı dolduracağı için tam boyutlu sürüm kullanılır
  $thumb = ($i === 0 && ($item['type'] ?? 'image') === 'image') ? $item['src'] : yp_item_thumb($item); ?>
    <div class="zc-layer zc-<?= $i ?>">
      <div class="zc-img" data-lightbox="<?= e(yp_item_full($item)) ?>" data-type="<?= e($item['type'] ?? 'image') ?>" data-caption="<?= e(($item['caption'] ?? '') ?: ($item['title'] ?? '')) ?>">
<?php if ($thumb !== ''): ?>
        <?= yp_img($thumb, ($item['alt'] ?? '') ?: ($item['title'] ?? ''), ['loading' => 'lazy']) ?>

<?php else: ?>
        <video src="<?= e($item['src']) ?>#t=0.5" muted playsinline preload="metadata"></video>
<?php endif; ?>
<?php if (($item['type'] ?? 'image') !== 'image'): ?>
        <span class="gallery-play" aria-hidden="true"></span>
<?php endif; ?>
      </div>
    </div>
<?php endforeach; ?>
    <div class="zc-caption">
      <p>İstanbul genelindeki evlerde ve işyerlerinde gerçekleştirdiğimiz uygulamalar</p>
      <a href="galeri.html" class="btn-primary zc-btn">
        Tüm Projelerimizi Görün
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ═══════ SÜREÇ ═══════ -->
<section id="surec">
  <div class="steps-header reveal">
    <p class="section-eyebrow">Çalışma Yöntemimiz</p>
    <h2 class="section-title">4 Adımda <em>Yeni Perdeniz</em></h2>
    <p class="section-desc">İlk aramadan montajın tamamlanmasına kadar size eşlik eden şeffaf ve kolay süreç.</p>
  </div>

  <div class="steps-grid">
    <div class="step-card reveal">
      <div class="step-num">01</div>
      <div class="step-title">İletişim</div>
      <p class="step-desc">WhatsApp veya telefon ile bize ulaşın. Size en uygun randevu zamanını birlikte belirleyelim.</p>
    </div>
    <div class="step-card reveal">
      <div class="step-num">02</div>
      <div class="step-title">Ücretsiz Keşif</div>
      <p class="step-desc">Uzman ekibimiz mekanınıza gelir, tüm ölçümleri alır ve ihtiyacınıza göre seçenekler sunar.</p>
    </div>
    <div class="step-card reveal">
      <div class="step-num">03</div>
      <div class="step-title">Seçim & Teklif</div>
      <p class="step-desc">Renk, model ve kumaş örneklerimize bakarak seçiminizi yaparsınız. Net fiyat teklifi anında hazır.</p>
    </div>
    <div class="step-card reveal">
      <div class="step-num">04</div>
      <div class="step-title">Üretim & Montaj</div>
      <p class="step-desc">Ürününüz atölyemizde özenle üretilir, profesyonel ekibimiz tarafından kurulur. Teslim tamamdır.</p>
    </div>
  </div>
</section>

<!-- ═══════ HİZMET BÖLGELERİ ═══════ -->
<section id="bolgeler">
  <div class="bolgeler-grid">
    <div class="reveal">
      <p class="section-eyebrow">Hizmet Alanımız</p>
      <h2 class="section-title">İstanbul'un<br><em>Her Yerinde</em></h2>
      <p class="section-desc">Sultanbeyli'deki merkezimizden İstanbul'un her semtine ücretsiz keşif ve montaj hizmeti sunuyoruz.</p>
    </div>
    <div class="reveal">
      <div class="bolgeler-list">
        <span>Sultanbeyli</span>
        <span>Pendik</span>
        <span>Kartal</span>
        <span>Kadıköy</span>
        <span>Ümraniye</span>
        <span>Sancaktepe</span>
        <span>Maltepe</span>
        <span>Ataşehir</span>
        <span>Üsküdar</span>
        <span>Tuzla</span>
        <span>Çekmeköy</span>
        <span>ve diğerleri</span>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ RANDEVU CTA ═══════ -->
<section id="randevu">
  <div class="randevu-left">
    <p class="section-eyebrow reveal">Hemen Başlayalım</p>
    <h2 class="section-title reveal">Ücretsiz Ölçüm<br><em>Randevusu Alın</em></h2>
    <p class="section-desc reveal">Sizi arayalım mı, yoksa WhatsApp üzerinden mi iletişim kuralım? Seçim sizin.</p>
    <div class="contact-options reveal">
      <a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20ölçüm%20randevusu%20almak%20istiyorum." class="contact-btn" target="_blank">
        <div class="contact-btn-icon icon-whatsapp">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="#25D366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        </div>
        <div class="contact-btn-text">
          <strong>WhatsApp'tan Yaz</strong>
          <span>Genellikle 30 dakika içinde yanıt</span>
        </div>
      </a>
      <a href="tel:+905423313413" class="contact-btn">
        <div class="contact-btn-icon icon-phone">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#4A3F35" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 10.8a19.79 19.79 0 01-3.07-8.67A2 2 0 012 0h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
        </div>
        <div class="contact-btn-text">
          <strong>0542 331 34 13</strong>
          <span>Pzt–Cmt · 09:00–21:00</span>
        </div>
      </a>
      <a href="https://www.google.com/maps/search/?api=1&query=Orhangazi+Eşref+Bitlis+Bulvarı+86+Sultanbeyli+İstanbul" class="contact-btn" target="_blank">
        <div class="contact-btn-icon icon-maps">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3949AB" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div class="contact-btn-text">
          <strong>Mağazamıza Gelin</strong>
          <span>Orhangazi, Sultanbeyli / İstanbul</span>
        </div>
      </a>
    </div>
  </div>

  <div class="randevu-right">
    <div class="randevu-image">
      <picture><source media="(max-width: 600px)" srcset="images/projects/altin-drapeli-modern-klasik-mobile.webp" type="image/webp"><source media="(max-width: 1024px)" srcset="images/projects/altin-drapeli-modern-klasik-tablet.webp" type="image/webp"><source srcset="images/projects/altin-drapeli-modern-klasik-desktop.webp" type="image/webp"><img src="images/projects/altin-drapeli-modern-klasik.jpg" alt=""></picture>
    </div>
    <div class="randevu-quote">
      <blockquote>
        "Doğru perde, bir odayı tamamen dönüştürebilir."
      </blockquote>
      <cite>— Yelken Perde · 2010'dan beri</cite>
      <div class="randevu-adres">
        <div class="adres-item">
          <span class="adres-label">Adres</span>
          <span class="adres-value">Orhangazi Mah. Eşref Bitlis Blv. No:86<br>Sultanbeyli / İstanbul</span>
        </div>
        <div class="adres-item">
          <span class="adres-label">Çalışma Saatleri</span>
          <span class="adres-value">Pzt–Cmt · 09:00–21:00<br>Pazar kapalı</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ FOOTER ═══════ -->
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-brand-logo">Yelken <span>Perde</span></div>
      <p class="footer-brand-desc">
        2010'dan bu yana Sultanbeyli'deki atölyemizde ürettiğimiz perde ve jaluzilerle İstanbul'un pencerelerine değer katıyoruz.
      </p>
      <div class="footer-social">
        <a href="https://instagram.com/yelken.perde" target="_blank" aria-label="Instagram">
          <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.849.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.849.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </a>
        <a href="https://wa.me/905423313413" target="_blank" aria-label="WhatsApp">
          <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-col">
      <h4>Sayfalar</h4>
      <ul>
        <li><a href="index.html">Ana Sayfa</a></li>
        <li><a href="urunler.html">Ürünler</a></li>
        <li><a href="galeri.html">Galeri</a></li>
        <li><a href="hakkimizda.html">Hakkımızda</a></li>
        <li><a href="blog.html">Blog</a></li>
        <li><a href="iletisim.html">İletişim</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>Ürünler</h4>
      <ul>
        <li><a href="urunler.html#ahsap-jaluzi">Ahşap Jaluzi</a></li>
        <li><a href="urunler.html#zebra-perde">Zebra Perde</a></li>
        <li><a href="urunler.html#stor-perde">Stor Perde</a></li>
        <li><a href="urunler.html#tul-perde">Tül Perde</a></li>
        <li><a href="urunler.html#fon-perde">Fon Perde</a></li>
        <li><a href="urunler.html#dikey-jaluzi">Dikey Jaluzi</a></li>
      </ul>
    </div>

    <div class="footer-col">
      <h4>İletişim</h4>
      <div class="footer-contact">
        <strong>Telefon</strong>
        <a href="tel:+905423313413">0542 331 34 13</a><br><br>
        <strong>E-posta</strong>
        <a href="mailto:info@yelkenperde.com">info@yelkenperde.com</a><br><br>
        <strong>Adres</strong>
        Orhangazi Mah. Eşref Bitlis Blv. No:86<br>
        Sultanbeyli / İstanbul
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <p class="footer-copy">© 2026 Yelken Perde. Tüm hakları saklıdır.</p>
    <div class="footer-legal">
      <a href="gizlilik-politikasi.html">Gizlilik Politikası</a>
      <a href="kvkk.html">KVKK</a>
    </div>
  </div>
</footer>

<!-- WhatsApp Sabit Buton -->
<a href="https://wa.me/905423313413?text=Merhaba,%20bilgi%20almak%20istiyorum." class="whatsapp-float" target="_blank" aria-label="WhatsApp ile İletişim">
  <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
</a>

<script src="main.js?v=3"></script>
<script src="home.js?v=6"></script>
</body>
</html>
