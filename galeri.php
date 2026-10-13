<?php
require __DIR__ . '/inc/lib.php';
$gallery = yp_gallery();
$used = [];
foreach ($gallery as $item) foreach ($item['categories'] ?? [] as $c) $used[$c] = true;
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proje Galerimiz — Yelken Perde | Referans Uygulamalar</title>
<meta name="description" content="Yelken Perde'nin İstanbul genelinde tamamladığı perde ve jaluzi projelerinin galerisi. Modern ve klasik uygulamalardan kareler.">
<link rel="canonical" href="https://yelkenperde.com/galeri.html">
<link rel="icon" type="image/png" href="images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap"></noscript>
<link rel="stylesheet" href="styles.css?v=3">

<style>
  /* GALERİ */
  .gallery-filters {
    display: flex;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    padding: 40px 24px;
    background: var(--warm-white);
    border-bottom: 1px solid var(--linen);
  }

  .filter-btn {
    background: transparent;
    border: 1px solid var(--linen);
    color: var(--stone);
    font-family: var(--sans);
    font-size: 12px;
    font-weight: 400;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    padding: 10px 20px;
    border-radius: 2px;
    cursor: pointer;
    transition: all 0.3s;
  }

  .filter-btn:hover {
    border-color: var(--charcoal);
    color: var(--charcoal);
  }

  .filter-btn.active {
    background: var(--charcoal);
    border-color: var(--charcoal);
    color: white;
  }

  .gallery-full {
    padding: 60px 80px 100px;
    background: var(--cream);
  }

  .gallery-full-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    max-width: 1600px;
    margin: 0 auto;
  }

  .gallery-item {
    aspect-ratio: 3/4;
    overflow: hidden;
    border-radius: 4px;
    background: var(--linen);
    cursor: pointer;
    position: relative;
    transition: opacity 0.3s, transform 0.3s;
  }

  .gallery-item.hidden {
    opacity: 0;
    transform: scale(0.9);
  }

  .gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .gallery-item:hover img { transform: scale(1.05); }

  .gallery-item-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(transparent 60%, rgba(30,26,23,0.85));
    display: flex;
    align-items: flex-end;
    padding: 20px 24px;
    opacity: 0;
    transition: opacity 0.3s;
  }

  .gallery-item:hover .gallery-item-overlay { opacity: 1; }

  .gallery-item-info {
    color: white;
  }

  .gallery-item-category {
    font-size: 10px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold-light);
    margin-bottom: 6px;
  }

  .gallery-item-title {
    font-family: var(--serif);
    font-size: 18px;
    font-weight: 300;
    line-height: 1.3;
  }

  .gallery-empty {
    text-align: center;
    color: var(--stone);
    padding: 40px 0;
  }

  .gallery-stats {
    text-align: center;
    margin-top: 48px;
    padding-top: 48px;
    border-top: 1px solid var(--linen);
  }

  .gallery-stats p {
    font-family: var(--serif);
    font-size: 22px;
    font-weight: 300;
    font-style: italic;
    color: var(--stone);
    margin-bottom: 8px;
  }

  @media (max-width: 900px) {
    .gallery-full { padding: 40px 24px 64px; }
    .gallery-full-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
    .gallery-filters { padding: 24px 16px; }
    .filter-btn { font-size: 10px; padding: 8px 14px; }
  }

  @media (max-width: 500px) {
    .gallery-full-grid { grid-template-columns: 1fr; gap: 12px; }
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
<body>

<!-- NAV -->
<nav id="navbar">
  <a href="index.html" class="nav-logo">
    <img src="images/logo-nav.png" alt="Yelken Perde">
  </a>
  <ul class="nav-links" id="navLinks" data-menu>
    <li><a href="index.html">Ana Sayfa</a></li>
    <li><a href="urunler.html">Ürünler</a></li>
    <li><a href="galeri.html" class="active">Galeri</a></li>
    <li><a href="hakkimizda.html">Hakkımızda</a></li>
    <li><a href="blog.html">Blog</a></li>
    <li><a href="iletisim.html">İletişim</a></li>
    <li><a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20ölçüm%20randevusu%20almak%20istiyorum." class="nav-cta" target="_blank">Randevu Al</a></li>
  </ul>
  <button class="nav-mobile-toggle" onclick="toggleMenu()" aria-label="Menü">
    <span></span><span></span><span></span>
  </button>
</nav>

<!-- PAGE HEADER -->
<header class="page-header">
  <div class="breadcrumb">
    <a href="index.html">Ana Sayfa</a>
    <span>/</span>
    <span>Galeri</span>
  </div>
  <p class="section-eyebrow">Referans Projelerimiz</p>
  <h1 class="section-title">İstanbul'un <em>Evlerinden</em><br>İlham Veren Kareler</h1>
  <p class="section-desc">
    Yıllar içinde tamamladığımız yüzlerce projeden bir seçki. Her biri mekânın karakterini yansıtan, özgün bir hikayeye sahip.
  </p>
</header>

<!-- FILTRE -->
<div class="gallery-filters">
  <button class="filter-btn active" data-filter="all">Tümü</button>
<?php foreach (YP_CATEGORIES as $key => $name): if (empty($used[$key])) continue; ?>
  <button class="filter-btn" data-filter="<?= e($key) ?>"><?= e($name) ?></button>
<?php endforeach; ?>
</div>

<!-- GALERİ -->
<section class="gallery-full">
  <div class="gallery-full-grid">
<?php foreach ($gallery as $item): ?>
    <?= yp_gallery_item_html($item) ?>

<?php endforeach; ?>
  </div>
<?php if (!$gallery): ?>
  <p class="gallery-empty">Galeri yakında güncellenecek.</p>
<?php endif; ?>

  <div class="gallery-stats">
    <p>"500+ projemizden sadece bir kısmı."</p>
    <a href="https://instagram.com/yelken.perde" target="_blank" class="btn-secondary" style="margin-top: 20px">
      Instagram'da Daha Fazlası
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div>
      <div class="footer-brand-logo">Yelken <span>Perde</span></div>
      <p class="footer-brand-desc">2010'dan bu yana Sultanbeyli'deki atölyemizde ürettiğimiz perde ve jaluzilerle İstanbul'un pencerelerine değer katıyoruz.</p>
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

<a href="https://wa.me/905423313413?text=Merhaba,%20bilgi%20almak%20istiyorum." class="whatsapp-float" target="_blank" aria-label="WhatsApp">
  <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
</a>

<script src="main.js?v=3"></script>
</body>
</html>
