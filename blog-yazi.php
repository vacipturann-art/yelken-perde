<?php
require __DIR__ . '/inc/lib.php';

$slug = (string)($_GET['slug'] ?? '');
$post = preg_match('/^[a-z0-9-]+$/', $slug) ? yp_find_post($slug) : null;
if ($post && empty($post['published'])) $post = null;
if (!$post) http_response_code(404);

// Sayfa /blog/yazi.html adresinden açıldığı için göreli bağlantılar site köküne göre çözülür
$base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/';
$site = 'https://yelkenperde.com/';
$url = $post ? $site . 'blog/' . $post['slug'] . '.html' : $site . 'blog.html';
$desc = $post ? ($post['excerpt'] ?? '') : '';
$related = $post ? array_slice(array_values(array_filter(yp_posts(), function ($p) use ($post) { return $p['id'] !== $post['id']; })), 0, 3) : [];
?><!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="<?= e($base) ?>">
<?php if ($post): ?>
<title><?= e($post['title']) ?> — Yelken Perde Blog</title>
<meta name="description" content="<?= e($desc) ?>">
<link rel="canonical" href="<?= e($url) ?>">
<meta property="og:type" content="article">
<meta property="og:title" content="<?= e($post['title']) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<?php if (!empty($post['cover'])): ?>
<meta property="og:image" content="<?= e($site . $post['cover']) ?>">
<?php endif; ?>
<meta property="og:url" content="<?= e($url) ?>">
<meta property="og:locale" content="tr_TR">
<script type="application/ld+json">
<?= json_encode([
  '@context' => 'https://schema.org',
  '@type' => 'BlogPosting',
  'headline' => $post['title'],
  'description' => $desc,
  'image' => !empty($post['cover']) ? $site . $post['cover'] : null,
  'datePublished' => $post['date'],
  'author' => ['@type' => 'Organization', 'name' => 'Yelken Perde'],
  'publisher' => ['@type' => 'Organization', 'name' => 'Yelken Perde', 'logo' => ['@type' => 'ImageObject', 'url' => $site . 'images/logo.jpg']],
  'mainEntityOfPage' => $url,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) ?>

</script>
<?php else: ?>
<title>Yazı bulunamadı — Yelken Perde Blog</title>
<meta name="robots" content="noindex">
<?php endif; ?>
<link rel="icon" type="image/png" href="images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styles.css">

<style>
  .blog-list {
    padding: 40px 80px 100px;
    background: var(--cream);
  }

  .blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
    max-width: 1400px;
    margin: 0 auto;
  }

  .blog-card {
    display: flex;
    flex-direction: column;
    background: var(--warm-white);
    border-radius: 4px;
    overflow: hidden;
    transition: all 0.4s;
    border: 1px solid var(--linen);
  }

  .blog-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: transparent;
  }

  .blog-card-image {
    aspect-ratio: 4/3;
    overflow: hidden;
    background: var(--linen);
  }

  .blog-card-image img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform 0.8s;
  }

  .blog-card:hover .blog-card-image img { transform: scale(1.05); }

  .blog-card-body {
    padding: 28px 28px 32px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    flex: 1;
  }

  .blog-card-meta {
    display: flex;
    gap: 12px;
    align-items: center;
    font-size: 11px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--gold);
  }

  .blog-card-meta span { color: var(--sand); }

  .blog-card-title {
    font-family: var(--serif);
    font-size: 22px;
    font-weight: 400;
    line-height: 1.3;
    color: var(--charcoal);
    transition: color 0.3s;
  }

  .blog-card:hover .blog-card-title { color: var(--gold); }

  .blog-card-excerpt {
    font-size: 14px;
    font-weight: 300;
    line-height: 1.7;
    color: var(--stone);
    flex: 1;
  }

  .blog-card-readmore {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--charcoal);
    display: inline-flex;
    gap: 8px;
    align-items: center;
    margin-top: 8px;
    transition: color 0.3s, gap 0.3s;
  }

  .blog-card:hover .blog-card-readmore {
    color: var(--gold);
    gap: 12px;
  }

  @media (max-width: 1024px) {
    .blog-grid { grid-template-columns: 1fr 1fr; gap: 24px; }
  }

  @media (max-width: 700px) {
    .blog-list { padding: 32px 24px 64px; }
    .blog-grid { grid-template-columns: 1fr; gap: 20px; }
    .blog-card-body { padding: 24px; }
    .blog-card-title { font-size: 20px; }
  }

  /* YAZI */
  .post-header .section-title { font-size: clamp(34px, 5vw, 56px); }

  .post-meta {
    font-size: 12px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--stone);
  }

  .post-meta span { color: var(--sand); margin: 0 6px; }

  .post-cover {
    max-width: 1100px;
    margin: -40px auto 0;
    padding: 0 40px;
    position: relative;
  }

  .post-cover img {
    width: 100%;
    aspect-ratio: 16 / 8;
    object-fit: cover;
    border-radius: 4px;
    box-shadow: var(--shadow-lg);
  }

  .post-body {
    max-width: 720px;
    margin: 0 auto;
    padding: 64px 24px 40px;
    font-size: 18px;
    line-height: 1.85;
    color: var(--umber);
  }

  .post-body > * + * { margin-top: 1.1em; }

  .post-body h2 {
    font-family: var(--serif);
    font-weight: 400;
    font-size: 34px;
    line-height: 1.2;
    color: var(--charcoal);
    margin-top: 1.8em;
  }

  .post-body h3 {
    font-family: var(--serif);
    font-weight: 500;
    font-size: 25px;
    color: var(--charcoal);
    margin-top: 1.5em;
  }

  .post-body strong { font-weight: 500; color: var(--charcoal); }
  .post-body a { color: var(--gold-dark); text-decoration: underline; text-underline-offset: 3px; }
  .post-body ul, .post-body ol { padding-left: 1.3em; }
  .post-body li + li { margin-top: 0.4em; }
  .post-body li::marker { color: var(--gold); }

  .post-body blockquote {
    border-left: 2px solid var(--gold);
    padding: 4px 0 4px 24px;
    font-family: var(--serif);
    font-size: 24px;
    font-style: italic;
    color: var(--charcoal);
  }

  .post-body figure img { border-radius: 4px; width: 100%; }
  .post-body figcaption { font-size: 14px; color: var(--stone); text-align: center; margin-top: 10px; }

  .post-cta {
    max-width: 720px;
    margin: 0 auto 100px;
    padding: 40px;
    background: var(--warm-white);
    border: 1px solid var(--linen);
    border-radius: 4px;
    text-align: center;
  }

  .post-cta h2 {
    font-family: var(--serif);
    font-weight: 300;
    font-size: 32px;
    margin-bottom: 12px;
  }

  .post-cta p { color: var(--stone); margin-bottom: 24px; }
  .post-cta .row { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

  .post-related {
    background: var(--warm-white);
    border-top: 1px solid var(--linen);
    padding-top: 80px;
  }

  .post-related > .section-title { text-align: center; margin: 0 auto 48px; }

  @media (max-width: 900px) {
    .post-cover { padding: 0 16px; margin-top: -24px; }
    .post-body { font-size: 17px; padding-top: 48px; }
    .post-body h2 { font-size: 28px; }
    .post-cta { margin: 0 16px 64px; padding: 28px 20px; }
  }
</style>
</head>
<body>

<!-- NAV -->
<nav id="navbar">
  <a href="index.html" class="nav-logo">
    <img src="images/logo-nav.png" alt="Yelken Perde">
  </a>
  <ul class="nav-links" id="navLinks">
    <li><a href="index.html">Ana Sayfa</a></li>
    <li><a href="urunler.html">Ürünler</a></li>
    <li><a href="galeri.html">Galeri</a></li>
    <li><a href="hakkimizda.html">Hakkımızda</a></li>
    <li><a href="blog.html" class="active">Blog</a></li>
    <li><a href="iletisim.html">İletişim</a></li>
    <li><a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20ölçüm%20randevusu%20almak%20istiyorum." class="nav-cta" target="_blank">Randevu Al</a></li>
  </ul>
  <button class="nav-mobile-toggle" onclick="toggleMenu()" aria-label="Menü">
    <span></span><span></span><span></span>
  </button>
</nav>

<?php if ($post): ?>
<!-- YAZI BAŞLIĞI -->
<header class="page-header post-header">
  <div class="breadcrumb">
    <a href="index.html">Ana Sayfa</a>
    <span>/</span>
    <a href="blog.html">Blog</a>
    <span>/</span>
    <span><?= e($post['category'] ?: 'Yazı') ?></span>
  </div>
  <h1 class="section-title"><?= e($post['title']) ?></h1>
  <p class="post-meta">
    <?= e(yp_date_tr($post['date'])) ?><span>·</span><?= yp_read_time($post) ?> dk okuma
  </p>
</header>

<?php if (!empty($post['cover'])): ?>
<div class="post-cover">
  <img src="<?= e($post['cover']) ?>" alt="<?= e($post['title']) ?>">
</div>
<?php endif; ?>

<article class="post-body">
<?= yp_markdown($post['content'] ?? '') ?>
</article>

<aside class="post-cta">
  <h2>Pencereniz için en doğru seçim</h2>
  <p>Ücretsiz keşif randevusunda numuneleri evinize getiriyor, ölçüyü yerinde alıyoruz.</p>
  <div class="row">
    <a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20keşif%20randevusu%20almak%20istiyorum." class="btn-primary" target="_blank">WhatsApp'tan Randevu Al</a>
    <a href="urunler.html" class="btn-secondary">Ürünleri İncele</a>
  </div>
</aside>

<?php if ($related): ?>
<section class="blog-list post-related">
  <h2 class="section-title">Diğer <em>Yazılarımız</em></h2>
  <div class="blog-grid">
<?php foreach ($related as $r): ?>
    <a href="blog/<?= e($r['slug']) ?>.html" class="blog-card reveal">
      <div class="blog-card-image">
<?php if (!empty($r['cover'])): ?>
        <img src="<?= e($r['cover']) ?>" alt="<?= e($r['title']) ?>" loading="lazy">
<?php endif; ?>
      </div>
      <div class="blog-card-body">
        <div class="blog-card-meta">
          <?php if (!empty($r['category'])): ?><?= e($r['category']) ?> <span>·</span> <?php endif; ?><?= yp_read_time($r) ?> dk okuma
        </div>
        <h3 class="blog-card-title"><?= e($r['title']) ?></h3>
        <p class="blog-card-excerpt"><?= e($r['excerpt'] ?? '') ?></p>
        <span class="blog-card-readmore">
          Yazıyı Oku
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </span>
      </div>
    </a>
<?php endforeach; ?>
  </div>
</section>
<?php endif; ?>

<?php else: ?>
<header class="page-header">
  <p class="section-eyebrow">404</p>
  <h1 class="section-title">Aradığınız yazı <em>bulunamadı</em></h1>
  <p class="section-desc">Yazı kaldırılmış ya da adresi değişmiş olabilir.</p>
  <p style="margin-top:32px"><a href="blog.html" class="btn-primary">Tüm Yazılar</a></p>
</header>
<?php endif; ?>

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
      <a href="#">Gizlilik Politikası</a>
      <a href="#">KVKK</a>
    </div>
  </div>
</footer>

<a href="https://wa.me/905423313413?text=Merhaba,%20bilgi%20almak%20istiyorum." class="whatsapp-float" target="_blank" aria-label="WhatsApp">
  <svg viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zM12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
</a>

<script src="main.js"></script>
</body>
</html>
