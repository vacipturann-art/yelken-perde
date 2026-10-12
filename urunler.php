<?php require __DIR__ . '/inc/lib.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ürünlerimiz — Yelken Perde | Jaluzi, Stor, Zebra, Tül ve Fon Perde</title>
<meta name="description" content="Yelken Perde ürün kataloğu: ahşap jaluzi, zebra perde, stor perde, tül perde, fon perde, dikey jaluzi. 500+ renk ve desen seçeneği, ücretsiz keşif.">
<link rel="canonical" href="https://yelkenperde.com/urunler.html">
<link rel="icon" type="image/png" href="images/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400&family=Jost:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="styles.css">

<style>
  /* ÜRÜN DETAY KARTLARI */
  .product-section {
    padding: 100px 80px;
    border-bottom: 1px solid var(--linen);
  }

  .product-section:nth-child(even) { background: var(--warm-white); }
  .product-section:nth-child(odd) { background: var(--cream); }

  .product-row {
    display: grid;
    grid-template-columns: 1.2fr 1fr;
    gap: 80px;
    align-items: center;
    max-width: 1400px;
    margin: 0 auto;
  }

  .product-row.reverse {
    grid-template-columns: 1fr 1.2fr;
  }

  .product-row.reverse .product-content {
    order: 2;
  }

  .product-row.reverse .product-visual-wrap {
    order: 1;
  }

  .product-visual-wrap {
    position: relative;
    aspect-ratio: 4/5;
    border-radius: 4px;
    overflow: hidden;
    background: var(--linen);
  }

  .product-visual-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s;
  }

  .product-visual-wrap:hover img { transform: scale(1.03); }

  .product-badge {
    position: absolute;
    top: 24px;
    left: 24px;
    background: var(--charcoal);
    color: white;
    font-size: 10px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    padding: 8px 14px;
    border-radius: 2px;
  }

  .product-category-label {
    font-size: 11px;
    font-weight: 400;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 16px;
  }

  .product-title {
    font-family: var(--serif);
    font-size: clamp(36px, 3.5vw, 48px);
    font-weight: 300;
    line-height: 1.15;
    color: var(--charcoal);
    margin-bottom: 20px;
  }

  .product-title em { font-style: italic; color: var(--gold); }

  .product-desc {
    font-size: 15px;
    font-weight: 300;
    line-height: 1.8;
    color: var(--stone);
    margin-bottom: 28px;
  }

  .product-features {
    list-style: none;
    margin-bottom: 36px;
  }

  .product-features li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    font-size: 14px;
    font-weight: 300;
    color: var(--umber);
    line-height: 1.6;
  }

  .product-features li::before {
    content: '';
    flex-shrink: 0;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: var(--gold);
    margin-top: 9px;
  }

  .product-cta-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
  }

  .product-specs {
    margin-top: 28px;
    padding-top: 28px;
    border-top: 1px solid var(--linen);
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
  }

  .spec-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
  }

  .spec-label {
    font-size: 10px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: var(--stone);
  }

  .spec-value {
    font-size: 13px;
    font-weight: 400;
    color: var(--charcoal);
  }

  /* CTA BAND */
  .cta-band {
    background: var(--charcoal);
    color: white;
    padding: 80px;
    text-align: center;
  }

  .cta-band .section-title { color: white; margin: 0 auto 20px; }
  .cta-band .section-desc { color: rgba(201,188,168,0.75); margin: 0 auto 36px; }

  @media (max-width: 900px) {
    .product-section { padding: 64px 24px; }
    .product-row,
    .product-row.reverse { grid-template-columns: 1fr; gap: 32px; }
    .product-row.reverse .product-content,
    .product-row.reverse .product-visual-wrap { order: initial; }
    .product-specs { grid-template-columns: 1fr; gap: 12px; }
    .cta-band { padding: 56px 24px; }
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
    <li><a href="urunler.html" class="active">Ürünler</a></li>
    <li><a href="galeri.html">Galeri</a></li>
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
    <span>Ürünler</span>
  </div>
  <p class="section-eyebrow">Ürün Koleksiyonumuz</p>
  <h1 class="section-title">Her Mekâna Uygun<br><em>Perde ve Jaluzi Çözümleri</em></h1>
  <p class="section-desc">Geleneksel Türk zarafetinden modern minimalizme uzanan geniş ürün yelpazemizle her tarzdaki mekanın ihtiyacına yanıt veriyoruz.</p>
</header>

<!-- ═══════ AHŞAP JALUZİ ═══════ -->
<section class="product-section" id="ahsap-jaluzi">
  <div class="product-row">
    <div class="product-content reveal">
      <p class="product-category-label">01 · Ahşap Jaluzi</p>
      <h2 class="product-title">Doğal <em>Sıcaklık</em><br>ve Zarafet</h2>
      <p class="product-desc">
        Gerçek ahşaptan üretilen jaluzilerimiz, mekânınıza hem klasik bir dokunuş hem de modern bir şıklık katar. Işığı istediğiniz şekilde ayarlayarak odanızın atmosferini kontrol altında tutar.
      </p>
      <ul class="product-features">
        <li>25 mm ve 50 mm kalınlık seçenekleri</li>
        <li>Gerçek ahşap ve bambu alternatifleri</li>
        <li>30+ farklı ahşap tonu</li>
        <li>Motor ile otomatik kontrol imkanı</li>
        <li>Geniş pencerelerde kullanım uygun</li>
      </ul>
      <div class="product-specs">
        <div class="spec-item">
          <span class="spec-label">Kalınlık</span>
          <span class="spec-value">25 / 50 mm</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Malzeme</span>
          <span class="spec-value">Masif ahşap / Bambu</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Maks. Genişlik</span>
          <span class="spec-value">300 cm</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Garanti</span>
          <span class="spec-value">2 yıl</span>
        </div>
      </div>
      <div class="product-cta-row" style="margin-top:32px">
        <a href="https://wa.me/905423313413?text=Merhaba,%20ahşap%20jaluzi%20hakkında%20bilgi%20almak%20istiyorum." class="btn-primary" target="_blank">Teklif Al</a>
        <a href="galeri.html?cat=jaluzi" class="btn-secondary">Uygulamaları Gör</a>
      </div>
    </div>
    <div class="product-visual-wrap reveal">
      <span class="product-badge">Popüler</span>
      <img src="<?= e(yp_product('ahsap-jaluzi')['image']) ?>" alt="<?= e(yp_product('ahsap-jaluzi')['alt']) ?>">
    </div>
  </div>
</section>

<!-- ═══════ ZEBRA PERDE ═══════ -->
<section class="product-section" id="zebra-perde">
  <div class="product-row reverse">
    <div class="product-visual-wrap reveal">
      <img src="<?= e(yp_product('zebra-perde')['image']) ?>" alt="<?= e(yp_product('zebra-perde')['alt']) ?>">
    </div>
    <div class="product-content reveal">
      <p class="product-category-label">02 · Zebra Perde</p>
      <h2 class="product-title">Modern ve<br><em>Pratik</em> Seçim</h2>
      <p class="product-desc">
        Bantlı tasarımıyla zebra perde, ışığı ve mahremiyeti aynı anda kontrol etmenizi sağlar. Gündüz içeriyi görünür kılmadan doğal ışığı odaya alır, gece tamamen kapatırsınız.
      </p>
      <ul class="product-features">
        <li>100+ renk ve desen seçeneği</li>
        <li>Işığı ve mahremiyeti kolay kontrol</li>
        <li>Modern, minimalist görünüm</li>
        <li>Temizliği pratik, bakımı kolay</li>
        <li>Mutfak, banyo, ofis için ideal</li>
      </ul>
      <div class="product-specs">
        <div class="spec-item">
          <span class="spec-label">Işık Kontrolü</span>
          <span class="spec-value">Çift kademe</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Renk Seçeneği</span>
          <span class="spec-value">100+</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Kumaş</span>
          <span class="spec-value">Polyester karışım</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Garanti</span>
          <span class="spec-value">2 yıl</span>
        </div>
      </div>
      <div class="product-cta-row" style="margin-top:32px">
        <a href="https://wa.me/905423313413?text=Merhaba,%20zebra%20perde%20hakkında%20bilgi%20almak%20istiyorum." class="btn-primary" target="_blank">Teklif Al</a>
        <a href="galeri.html?cat=zebra" class="btn-secondary">Uygulamaları Gör</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ STOR PERDE ═══════ -->
<section class="product-section" id="stor-perde">
  <div class="product-row">
    <div class="product-content reveal">
      <p class="product-category-label">03 · Stor Perde</p>
      <h2 class="product-title">Sade ve<br><em>Fonksiyonel</em></h2>
      <p class="product-desc">
        Stor perdelerimiz her mekâna uyum sağlayan minimalist bir çözüm sunar. Güneş ışığını filtreleyen, karartma özellikli veya desenli seçenekleri ile ihtiyacınıza göre özelleştirilir.
      </p>
      <ul class="product-features">
        <li>Işık geçirgen, yarı şeffaf ve karartmalı seçenekler</li>
        <li>Desenli ve düz kumaş alternatifleri</li>
        <li>Tek kumaş, parça izi olmayan bütün görünüm</li>
        <li>Pencere ölçüsüne tam özel üretim</li>
        <li>Ofis, salon, yatak odası için uygun</li>
      </ul>
      <div class="product-specs">
        <div class="spec-item">
          <span class="spec-label">Kumaş Tipi</span>
          <span class="spec-value">Şeffaf / Karartma</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Desen</span>
          <span class="spec-value">Düz / Motifli</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Maks. Genişlik</span>
          <span class="spec-value">280 cm</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Garanti</span>
          <span class="spec-value">2 yıl</span>
        </div>
      </div>
      <div class="product-cta-row" style="margin-top:32px">
        <a href="https://wa.me/905423313413?text=Merhaba,%20stor%20perde%20hakkında%20bilgi%20almak%20istiyorum." class="btn-primary" target="_blank">Teklif Al</a>
        <a href="galeri.html?cat=stor" class="btn-secondary">Uygulamaları Gör</a>
      </div>
    </div>
    <div class="product-visual-wrap reveal">
      <img src="<?= e(yp_product('stor-perde')['image']) ?>" alt="<?= e(yp_product('stor-perde')['alt']) ?>">
    </div>
  </div>
</section>

<!-- ═══════ TÜL PERDE ═══════ -->
<section class="product-section" id="tul-perde">
  <div class="product-row reverse">
    <div class="product-visual-wrap reveal">
      <img src="<?= e(yp_product('tul-perde')['image']) ?>" alt="<?= e(yp_product('tul-perde')['alt']) ?>">
    </div>
    <div class="product-content reveal">
      <p class="product-category-label">04 · Tül Perde</p>
      <h2 class="product-title">Zarif ve<br><em>Geleneksel</em></h2>
      <p class="product-desc">
        Tül perdeler, ışığı yumuşatarak eve romantik bir atmosfer katar. Fon perde ile kombinlenerek klasik bir görünüm, tek başına kullanılarak modern bir şıklık yaratır.
      </p>
      <ul class="product-features">
        <li>Dantelsiz, dantelli, nakışlı çeşitler</li>
        <li>Fon perde ile mükemmel uyum</li>
        <li>Işığı filtreleyip odayı aydınlatır</li>
        <li>Her pencere boyutuna özel dikim</li>
        <li>Salon, yatak odası, yemek odası ideali</li>
      </ul>
      <div class="product-specs">
        <div class="spec-item">
          <span class="spec-label">Kumaş</span>
          <span class="spec-value">Polyester / Pamuk karışım</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Desen</span>
          <span class="spec-value">Düz / Dantelli / Nakışlı</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Pile</span>
          <span class="spec-value">Düz / Ütülü</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Garanti</span>
          <span class="spec-value">2 yıl</span>
        </div>
      </div>
      <div class="product-cta-row" style="margin-top:32px">
        <a href="https://wa.me/905423313413?text=Merhaba,%20tül%20perde%20hakkında%20bilgi%20almak%20istiyorum." class="btn-primary" target="_blank">Teklif Al</a>
        <a href="galeri.html?cat=tul" class="btn-secondary">Uygulamaları Gör</a>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ FON PERDE ═══════ -->
<section class="product-section" id="fon-perde">
  <div class="product-row">
    <div class="product-content reveal">
      <p class="product-category-label">05 · Fon Perde</p>
      <h2 class="product-title">Klasikten<br><em>Moderne</em></h2>
      <p class="product-desc">
        Fon perdelerimiz evinizin karakterini belirleyen en önemli unsur. Kadifeden jakara, sadeden desenliye 500+ kumaş seçeneği ve sınırsız kombinasyon imkanı sunuyoruz.
      </p>
      <ul class="product-features">
        <li>Kadife, jakar, keten, saten, pamuk seçenekleri</li>
        <li>500+ renk ve desen kombinasyonu</li>
        <li>Drapeli, düz veya pleated dikim tarzları</li>
        <li>Püsküllü, fırfırlı, saten bantlı süslemeler</li>
        <li>Karartma astarlı versiyonlar mevcut</li>
      </ul>
      <div class="product-specs">
        <div class="spec-item">
          <span class="spec-label">Kumaş Tipleri</span>
          <span class="spec-value">20+ farklı seçenek</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Dikim</span>
          <span class="spec-value">Drapeli / Düz / Pleated</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Astar</span>
          <span class="spec-value">İsteğe bağlı</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Garanti</span>
          <span class="spec-value">2 yıl</span>
        </div>
      </div>
      <div class="product-cta-row" style="margin-top:32px">
        <a href="https://wa.me/905423313413?text=Merhaba,%20fon%20perde%20hakkında%20bilgi%20almak%20istiyorum." class="btn-primary" target="_blank">Teklif Al</a>
        <a href="galeri.html?cat=fon" class="btn-secondary">Uygulamaları Gör</a>
      </div>
    </div>
    <div class="product-visual-wrap reveal">
      <span class="product-badge">Öne Çıkan</span>
      <img src="<?= e(yp_product('fon-perde')['image']) ?>" alt="<?= e(yp_product('fon-perde')['alt']) ?>">
    </div>
  </div>
</section>

<!-- ═══════ DİKEY JALUZİ ═══════ -->
<section class="product-section" id="dikey-jaluzi">
  <div class="product-row reverse">
    <div class="product-visual-wrap reveal">
      <img src="<?= e(yp_product('dikey-jaluzi')['image']) ?>" alt="<?= e(yp_product('dikey-jaluzi')['alt']) ?>">
    </div>
    <div class="product-content reveal">
      <p class="product-category-label">06 · Dikey Jaluzi</p>
      <h2 class="product-title">Ofis ve<br><em>Geniş Alanlar</em></h2>
      <p class="product-desc">
        Geniş pencereler, balkon kapıları ve ofis alanları için ideal çözüm. Dikey şeritler ışık kontrolünü kolaylaştırır, temizliği pratiktir.
      </p>
      <ul class="product-features">
        <li>Alüminyum ve kumaş şerit seçenekleri</li>
        <li>89 mm ve 127 mm şerit genişliği</li>
        <li>Geniş pencere ve kapıda ideal</li>
        <li>Dayanıklı, uzun ömürlü</li>
        <li>Ofis, mağaza ve endüstriyel mekanlar için uygun</li>
      </ul>
      <div class="product-specs">
        <div class="spec-item">
          <span class="spec-label">Şerit Genişliği</span>
          <span class="spec-value">89 / 127 mm</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Malzeme</span>
          <span class="spec-value">Kumaş / Alüminyum</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Maks. Genişlik</span>
          <span class="spec-value">500 cm</span>
        </div>
        <div class="spec-item">
          <span class="spec-label">Garanti</span>
          <span class="spec-value">2 yıl</span>
        </div>
      </div>
      <div class="product-cta-row" style="margin-top:32px">
        <a href="https://wa.me/905423313413?text=Merhaba,%20dikey%20jaluzi%20hakkında%20bilgi%20almak%20istiyorum." class="btn-primary" target="_blank">Teklif Al</a>
        <a href="galeri.html" class="btn-secondary">Uygulamaları Gör</a>
      </div>
    </div>
  </div>
</section>

<!-- CTA BAND -->
<section class="cta-band">
  <p class="section-eyebrow" style="color: var(--gold-light)">Hangisi Size Uygun?</p>
  <h2 class="section-title">Karar Vermekte <em>Zorlanıyor Musunuz?</em></h2>
  <p class="section-desc" style="max-width: 600px">
    Ücretsiz keşif hizmetimizle uzmanlarımız evinize gelir, mekanınıza en uygun perdeyi size gösterir. Ne satın almak zorundasınız, ne de ücret ödersiniz.
  </p>
  <a href="https://wa.me/905423313413?text=Merhaba,%20ücretsiz%20keşif%20randevusu%20almak%20istiyorum." class="btn-primary btn-gold" target="_blank">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.554 4.117 1.528 5.845L.057 23.286a.75.75 0 00.921.921l5.441-1.471A11.946 11.946 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-1.89 0-3.66-.49-5.196-1.351l-.367-.213-3.805 1.029 1.029-3.805-.213-.367A9.955 9.955 0 012 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/></svg>
    Ücretsiz Keşif Talebi
  </a>
</section>

<!-- FOOTER -->
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
