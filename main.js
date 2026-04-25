/* Yelken Perde — Main JS */

// Nav scroll efekti
(function() {
  const navbar = document.getElementById('navbar');
  if (!navbar) return;

  const onScroll = () => {
    navbar.classList.toggle('scrolled', window.scrollY > 60);
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
})();

// Mobil menü
function toggleMenu() {
  const links = document.getElementById('navLinks');
  if (links) links.classList.toggle('open');
  document.body.classList.toggle('menu-open');
}

// Mobil menü linklerini kapat
document.addEventListener('DOMContentLoaded', () => {
  const links = document.querySelectorAll('#navLinks a');
  links.forEach(a => {
    a.addEventListener('click', () => {
      const navLinks = document.getElementById('navLinks');
      if (navLinks) navLinks.classList.remove('open');
      document.body.classList.remove('menu-open');
    });
  });
});

// Scroll reveal animasyonu
(function() {
  const revealEls = document.querySelectorAll('.reveal');
  if (!revealEls.length) return;

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

    revealEls.forEach(el => observer.observe(el));
  } else {
    // Fallback
    revealEls.forEach(el => el.classList.add('visible'));
  }
})();

// Galeri lightbox (sadece gallery sayfasında çalışır)
(function() {
  const items = document.querySelectorAll('[data-lightbox]');
  if (!items.length) return;

  const lightbox = document.createElement('div');
  lightbox.className = 'lightbox';
  lightbox.innerHTML = `
    <button class="lightbox-close" aria-label="Kapat">&times;</button>
    <button class="lightbox-prev" aria-label="Önceki">&lsaquo;</button>
    <button class="lightbox-next" aria-label="Sonraki">&rsaquo;</button>
    <div class="lightbox-content">
      <img src="" alt="">
      <p class="lightbox-caption"></p>
    </div>
  `;
  document.body.appendChild(lightbox);

  const img = lightbox.querySelector('img');
  const caption = lightbox.querySelector('.lightbox-caption');
  let currentIndex = 0;
  const imageList = Array.from(items);

  const show = (index) => {
    currentIndex = index;
    const el = imageList[index];
    const src = el.getAttribute('data-lightbox');
    const cap = el.getAttribute('data-caption') || '';
    img.src = src;
    caption.textContent = cap;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  const hide = () => {
    lightbox.classList.remove('active');
    document.body.style.overflow = '';
  };

  const next = () => show((currentIndex + 1) % imageList.length);
  const prev = () => show((currentIndex - 1 + imageList.length) % imageList.length);

  imageList.forEach((el, idx) => {
    el.addEventListener('click', (e) => {
      e.preventDefault();
      show(idx);
    });
  });

  lightbox.querySelector('.lightbox-close').addEventListener('click', hide);
  lightbox.querySelector('.lightbox-next').addEventListener('click', next);
  lightbox.querySelector('.lightbox-prev').addEventListener('click', prev);
  lightbox.addEventListener('click', (e) => {
    if (e.target === lightbox) hide();
  });

  document.addEventListener('keydown', (e) => {
    if (!lightbox.classList.contains('active')) return;
    if (e.key === 'Escape') hide();
    if (e.key === 'ArrowRight') next();
    if (e.key === 'ArrowLeft') prev();
  });
})();

// Galeri filtre
(function() {
  const filterBtns = document.querySelectorAll('[data-filter]');
  if (!filterBtns.length) return;

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const filter = btn.getAttribute('data-filter');

      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const items = document.querySelectorAll('[data-category]');
      items.forEach(item => {
        const cat = item.getAttribute('data-category');
        if (filter === 'all' || cat === filter || (cat && cat.includes(filter))) {
          item.style.display = '';
          setTimeout(() => item.classList.remove('hidden'), 10);
        } else {
          item.classList.add('hidden');
          setTimeout(() => { item.style.display = 'none'; }, 300);
        }
      });
    });
  });
})();

// İletişim form (demo — gerçek entegrasyon için formspree/netlify forms vb. gerekir)
(function() {
  const form = document.getElementById('contact-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const data = {
      name: form.name?.value,
      phone: form.phone?.value,
      message: form.message?.value,
    };

    // WhatsApp'a yönlendir
    const text = `Merhaba, web siteniz üzerinden bilgi almak istiyorum.%0A%0AAd: ${data.name}%0ATelefon: ${data.phone}%0AMesaj: ${data.message}`;
    window.open(`https://wa.me/905423313413?text=${text}`, '_blank');
  });
})();
