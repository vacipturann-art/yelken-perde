/* Yelken Perde — Ana sayfa kaydırma efektleri (sinematik giriş + yakınlaşan kolaj)
   Sadece <html class="motion-ok"> iken çalışır; değerleri CSS değişkeni olarak yazar. */
// Menü: giriş bölümü bitip sayfa kaymaya başlayınca gizlenir; sonra görünürse logo krem zemine geçer
(function () {
  const hero = document.querySelector('.cine-hero');
  if (!hero) return;
  let queued = false;
  let lastY = window.scrollY;
  const update = () => {
    queued = false;
    const y = window.scrollY;
    const body = document.body;
    body.classList.toggle('past-hero', hero.getBoundingClientRect().bottom < 140);
    // Giriş fotoğrafları değişirken sahne sabit durur, menü görünür kalır.
    // Sayfa gerçekten kaymaya başlayınca (giriş bölümü bitince) aşağı kaydırmada gizlenir,
    // yukarı kaydırmada geri gelir.
    const inHero = hero.getBoundingClientRect().bottom > window.innerHeight + 2;
    if (body.classList.contains('menu-open') || inHero) body.classList.remove('nav-hidden');
    else if (y > lastY + 4) body.classList.add('nav-hidden');
    else if (y < lastY - 4) body.classList.remove('nav-hidden');
    lastY = y;
  };
  window.addEventListener('scroll', () => { if (!queued) { queued = true; requestAnimationFrame(update); } }, { passive: true });
  update();
})();

(function () {
  if (!document.documentElement.classList.contains('motion-ok')) return;

  const hero = document.querySelector('.cine-hero');
  const slides = hero ? Array.from(hero.querySelectorAll('.cine-slide')) : [];
  const captions = hero ? Array.from(hero.querySelectorAll('.cine-caption')) : [];
  const bars = hero ? Array.from(hero.querySelectorAll('.cine-bars i')) : [];
  let activeSlide = 0;
  const collage = document.querySelector('.zoom-collage');
  const layers = collage ? Array.from(collage.querySelectorAll('.zc-layer')) : [];
  const SCALES = [4, 5, 6, 5, 6, 8, 9];  // ortadaki fotoğraf 4 katında ekranı tam doldurur

  const clamp = (v) => Math.min(1, Math.max(0, v));
  const easeInOut = (t) => (t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2);

  // Bölümün yapışkan alanı içinde ne kadar kaydırıldı (0–1)
  const progress = (el) => {
    const r = el.getBoundingClientRect();
    const total = r.height - window.innerHeight;
    return total > 0 ? clamp(-r.top / total) : 0;
  };

  let queued = false;

  function update() {
    queued = false;

    if (hero && slides.length) {
      // u: kaç fotoğraf ilerlendi (0 … N). Her fotoğraf bir öncekinin üzerine yarım adımda belirir.
      const n = slides.length;
      const u = progress(hero) * n;
      slides.forEach((slide, i) => {
        const a = i === 0 ? 1 : easeInOut(clamp((u - i + 0.5) / 0.5));
        const z = clamp((u - i + 0.5) / 1.5);
        slide.style.setProperty('--a', a.toFixed(3));
        slide.style.setProperty('--z', z.toFixed(3));
      });
      bars.forEach((bar, i) => bar.style.setProperty('--f', clamp(u - i).toFixed(3)));
      const active = Math.min(n - 1, Math.floor(u + 0.25));
      if (active !== activeSlide) {
        captions.forEach((c, i) => c.classList.toggle('is-active', i === active));
        activeSlide = active;
      }
    }

    if (collage && layers.length) {
      const p = progress(collage);
      const z = easeInOut(clamp((p - 0.08) / 0.64));  // önce kolaj kısa bir süre bütün hâliyle görünür
      layers.forEach((layer, i) => layer.style.setProperty('--s', (1 + z * (SCALES[i] - 1)).toFixed(4)));
      const c = clamp((p - 0.7) / 0.2);
      collage.style.setProperty('--c', c.toFixed(3));
      collage.classList.toggle('is-full', c > 0.5);
    }
  }

  const schedule = () => {
    if (!queued) {
      queued = true;
      requestAnimationFrame(update);
    }
  };

  window.addEventListener('scroll', schedule, { passive: true });
  window.addEventListener('resize', schedule);
  update();
})();
