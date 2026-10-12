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

// Galeri lightbox — fotoğraf, video ve YouTube (galeri ve ana sayfa vitrini)
(function() {
  if (!document.querySelector('[data-lightbox]')) return;

  const lightbox = document.createElement('div');
  lightbox.className = 'lightbox';
  lightbox.innerHTML = `
    <button class="lightbox-close" aria-label="Kapat">&times;</button>
    <button class="lightbox-prev" aria-label="Önceki">&lsaquo;</button>
    <button class="lightbox-next" aria-label="Sonraki">&rsaquo;</button>
    <div class="lightbox-content">
      <div class="lightbox-media"></div>
      <p class="lightbox-caption"></p>
    </div>
  `;
  document.body.appendChild(lightbox);

  const mediaBox = lightbox.querySelector('.lightbox-media');
  const caption = lightbox.querySelector('.lightbox-caption');
  let list = [];
  let currentIndex = 0;

  // Filtreyle gizlenen öğeler atlanır
  const visibleItems = () => Array.from(document.querySelectorAll('[data-lightbox]'))
    .filter(el => !el.classList.contains('hidden') && el.style.display !== 'none');

  const show = (index) => {
    currentIndex = index;
    const el = list[index];
    const src = el.getAttribute('data-lightbox');
    const type = el.getAttribute('data-type') || 'image';
    let media;

    if (type === 'video') {
      media = document.createElement('video');
      media.src = src;
      media.controls = true;
      media.autoplay = true;
      media.playsInline = true;
    } else if (type === 'youtube') {
      media = document.createElement('iframe');
      media.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent(src) + '?autoplay=1&rel=0';
      media.allow = 'autoplay; encrypted-media; picture-in-picture';
      media.allowFullscreen = true;
    } else {
      media = document.createElement('img');
      media.src = src;
      media.alt = el.querySelector('img') ? el.querySelector('img').alt : '';
    }

    mediaBox.replaceChildren(media);
    caption.textContent = el.getAttribute('data-caption') || '';
    lightbox.querySelector('.lightbox-prev').hidden = list.length < 2;
    lightbox.querySelector('.lightbox-next').hidden = list.length < 2;
    lightbox.classList.add('active');
    document.body.style.overflow = 'hidden';
  };

  const hide = () => {
    lightbox.classList.remove('active');
    mediaBox.replaceChildren();  // videoyu durdurur
    document.body.style.overflow = '';
  };

  const next = () => show((currentIndex + 1) % list.length);
  const prev = () => show((currentIndex - 1 + list.length) % list.length);

  document.addEventListener('click', (e) => {
    const el = e.target.closest('[data-lightbox]');
    if (!el) return;
    e.preventDefault();
    list = visibleItems();
    show(Math.max(0, list.indexOf(el)));
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

  const apply = (filter) => {
    filterBtns.forEach(b => b.classList.toggle('active', b.getAttribute('data-filter') === filter));

    document.querySelectorAll('[data-category]').forEach(item => {
      const cats = (item.getAttribute('data-category') || '').split(' ');
      if (filter === 'all' || cats.includes(filter)) {
        item.style.display = '';
        setTimeout(() => item.classList.remove('hidden'), 10);
      } else {
        item.classList.add('hidden');
        setTimeout(() => { item.style.display = 'none'; }, 300);
      }
    });
  };

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => apply(btn.getAttribute('data-filter')));
  });

  // Ürün sayfasındaki "Uygulamaları Gör" bağlantıları: galeri.html?cat=jaluzi
  const cat = new URLSearchParams(location.search).get('cat');
  if (cat && document.querySelector('[data-filter="' + CSS.escape(cat) + '"]')) apply(cat);
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
