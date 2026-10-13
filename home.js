/* Yelken Perde — Ana sayfa kaydırma efektleri (perde girişi + yakınlaşan kolaj)
   Sadece <html class="motion-ok"> iken çalışır; değerleri CSS değişkeni olarak yazar. */
(function () {
  if (!document.documentElement.classList.contains('motion-ok')) return;

  const hero = document.querySelector('.curtain-hero');
  const collage = document.querySelector('.zoom-collage');
  const layers = collage ? Array.from(collage.querySelectorAll('.zc-layer')) : [];
  const SCALES = [4, 5, 6, 5, 6, 8, 9];  // ortadaki fotoğraf 4 katında ekranı tam doldurur

  const clamp = (v) => Math.min(1, Math.max(0, v));
  const easeOut = (t) => 1 - Math.pow(1 - t, 3);
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

    if (hero) {
      const p = progress(hero);
      const o = easeInOut(clamp(p / 0.62));
      const r = easeOut(clamp((p - 0.5) / 0.3));
      hero.style.setProperty('--p', p.toFixed(4));
      hero.style.setProperty('--o', o.toFixed(4));
      hero.style.setProperty('--r', r.toFixed(4));
      hero.classList.toggle('is-open', r > 0.5);
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
