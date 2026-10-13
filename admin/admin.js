/* Yelken Perde — Yönetim Paneli */
'use strict';

const S = { csrf: '', data: null, route: '' };
const app = document.getElementById('app');

/* ═══════════ Yardımcılar ═══════════ */

// Küçük DOM oluşturucu: h('div', {class: 'x', onclick}, 'metin', çocuk...)
function h(tag, attrs, ...kids) {
  const el = document.createElement(tag);
  for (const [k, v] of Object.entries(attrs || {})) {
    if (v == null || v === false) continue;
    if (k.startsWith('on')) el.addEventListener(k.slice(2), v);
    else if (k === 'class') el.className = v;
    else if (k === 'value') el.value = v;
    else if (k === 'checked') el.checked = !!v;
    else el.setAttribute(k, v === true ? '' : v);
  }
  for (const kid of kids.flat(Infinity)) {
    if (kid == null || kid === false) continue;
    el.append(kid instanceof Node ? kid : document.createTextNode(String(kid)));
  }
  return el;
}

const media = (p) => (!p ? '' : /^https?:/.test(p) ? p : '../' + p);

function toast(msg, type = '') {
  const t = h('div', { class: 'toast ' + type }, msg);
  document.getElementById('toasts').append(t);
  setTimeout(() => t.remove(), type === 'error' ? 6000 : 3000);
}

async function api(action, body) {
  const opts = { method: body ? 'POST' : 'GET', headers: { 'X-CSRF-Token': S.csrf }, credentials: 'same-origin' };
  if (body instanceof FormData) opts.body = body;
  else if (body) { opts.headers['Content-Type'] = 'application/json'; opts.body = JSON.stringify(body); }

  const res = await fetch('api.php?action=' + action, opts);
  let json;
  try { json = await res.json(); } catch { json = { error: 'Sunucu yanıtı okunamadı (' + res.status + ').' }; }
  if (!res.ok || json.error) {
    const err = new Error(json.error || 'İşlem başarısız.');
    err.status = res.status;
    if (res.status === 401 && S.data) { S.data = null; boot(); }
    throw err;
  }
  return json;
}

// Butonu işlem süresince kilitler ve hatayı bildirir
async function busy(btn, fn) {
  if (btn) btn.disabled = true;
  try { return await fn(); }
  catch (e) { toast(e.message, 'error'); }
  finally { if (btn) btn.disabled = false; }
}

const catName = (k) => S.data.categories[k] || k;
const itemThumb = (it) => it.thumb || (it.type === 'youtube' ? `https://img.youtube.com/vi/${it.youtube}/hqdefault.jpg` : it.type === 'image' ? it.src : '');
const typeLabel = { image: 'Fotoğraf', video: 'Video', youtube: 'YouTube' };

function thumbEl(it) {
  const src = itemThumb(it);
  if (src) return h('img', { src: media(src), alt: '', loading: 'lazy' });
  return h('video', { src: media(it.src) + '#t=0.5', muted: true, playsinline: true, preload: 'metadata' });
}

/* ═══════════ Dosya hazırlama ve yükleme ═══════════ */

function loadImage(file) {
  return new Promise((resolve, reject) => {
    const url = URL.createObjectURL(file);
    const img = new Image();
    img.onload = () => { URL.revokeObjectURL(url); resolve(img); };
    img.onerror = () => { URL.revokeObjectURL(url); reject(new Error('Bu fotoğraf tarayıcıda açılamadı. HEIC biçimindeyse JPG olarak gönderin (iPhone: Ayarlar › Kamera › Formatlar › En Uyumlu).')); };
    img.src = url;
  });
}

function canvasBlob(source, w, h2, quality) {
  const c = document.createElement('canvas');
  c.width = w; c.height = h2;
  const ctx = c.getContext('2d');
  ctx.fillStyle = '#fff';
  ctx.fillRect(0, 0, w, h2);
  ctx.drawImage(source, 0, 0, w, h2);
  return new Promise((resolve, reject) => c.toBlob((b) => (b ? resolve(b) : reject(new Error('Görsel işlenemedi.'))), 'image/jpeg', quality));
}

// Telefon fotoğrafları 4000px+ olabilir: siteye uygun boyuta küçültülür (sunucuda ek eklenti gerekmez)
async function resizeImage(file, max, quality = 0.85) {
  const img = await loadImage(file);
  const scale = Math.min(1, max / Math.max(img.naturalWidth, img.naturalHeight));
  if (scale === 1 && file.type === 'image/jpeg' && file.size < 1.5 * 1024 * 1024) return file;
  return canvasBlob(img, Math.round(img.naturalWidth * scale), Math.round(img.naturalHeight * scale), quality);
}

// Videodan kapak karesi üretir; başarısız olursa null döner (site ilk kareyi gösterir)
function videoPoster(file) {
  return new Promise((resolve) => {
    const url = URL.createObjectURL(file);
    const v = document.createElement('video');
    let finished = false;
    const done = (blob) => { if (finished) return; finished = true; URL.revokeObjectURL(url); resolve(blob); };
    setTimeout(() => done(null), 10000);
    v.muted = true; v.playsInline = true; v.preload = 'auto';
    v.onloadeddata = () => { v.currentTime = Math.min(1, (v.duration || 2) / 2); };
    v.onseeked = async () => {
      try {
        const scale = Math.min(1, 900 / Math.max(v.videoWidth, v.videoHeight));
        done(await canvasBlob(v, Math.round(v.videoWidth * scale), Math.round(v.videoHeight * scale), 0.82));
      } catch { done(null); }
    };
    v.onerror = () => done(null);
    v.src = url;
  });
}

function randomId() {
  const a = new Uint8Array(16);
  crypto.getRandomValues(a);
  return Array.from(a, (b) => b.toString(16).padStart(2, '0')).join('');
}

// Dosyayı sunucunun kabul ettiği boyutta parçalara bölerek yükler
async function upload(blob, onProgress) {
  const size = S.data.limits.chunk;
  const total = Math.max(1, Math.ceil(blob.size / size));
  const id = randomId();
  let res;
  for (let i = 0; i < total; i++) {
    const fd = new FormData();
    fd.append('upload_id', id);
    fd.append('index', i);
    fd.append('total', total);
    fd.append('chunk', blob.slice(i * size, (i + 1) * size), 'chunk');
    for (let attempt = 1; ; attempt++) {
      try { res = await api('upload', fd); break; }
      catch (e) {
        // Sadece bağlantı kopmalarında tekrar dene
        if (e.status || attempt >= 4) throw e;
        await new Promise((r) => setTimeout(r, 1500 * attempt));
      }
    }
    if (onProgress) onProgress((i + 1) / total);
  }
  return res;
}

async function uploadImage(file, max, onProgress) {
  const blob = await resizeImage(file, max);
  return (await upload(blob, onProgress)).path;
}

function filePicker(accept, multiple, onFiles) {
  const input = h('input', { type: 'file', accept, multiple: multiple || null });
  input.addEventListener('change', () => { if (input.files.length) onFiles(Array.from(input.files)); });
  input.click();
}

/* ═══════════ Giriş / Kurulum ═══════════ */

function renderAuth(setup) {
  const err = h('div', { class: 'form-error' });
  const pw = h('input', { type: 'password', autocomplete: setup ? 'new-password' : 'current-password', required: true, minlength: setup ? 8 : null });
  const pw2 = setup ? h('input', { type: 'password', autocomplete: 'new-password', required: true }) : null;
  const btn = h('button', { class: 'btn gold block', type: 'submit' }, setup ? 'Şifreyi Oluştur' : 'Giriş Yap');

  const form = h('form', {
    onsubmit: async (e) => {
      e.preventDefault();
      err.textContent = '';
      if (setup && pw.value !== pw2.value) { err.textContent = 'Şifreler aynı değil.'; return; }
      btn.disabled = true;
      try {
        const r = await api(setup ? 'setup' : 'login', { password: pw.value });
        S.csrf = r.csrf;
        await loadApp();
      } catch (e2) { err.textContent = e2.message; btn.disabled = false; pw.select(); }
    },
  },
    h('label', { class: 'field' }, h('span', {}, setup ? 'Yeni şifre (en az 8 karakter)' : 'Şifre'), pw),
    setup && h('label', { class: 'field' }, h('span', {}, 'Şifre tekrar'), pw2),
    err, btn);

  app.replaceChildren(h('div', { class: 'auth' }, h('div', { class: 'auth-card' },
    h('div', { class: 'brand' }, 'Yelken ', h('span', {}, 'Perde')),
    h('p', { class: 'muted' }, setup ? 'İlk kurulum: panel şifresini belirleyin' : 'Yönetim Paneli'),
    form)));
  pw.focus();
}

/* ═══════════ Uygulama iskeleti ═══════════ */

const TABS = [['galeri', 'Galeri'], ['vitrin', 'Ana Sayfa Vitrini'], ['urunler', 'Ürün Görselleri'], ['blog', 'Blog'], ['ayarlar', 'Ayarlar']];
let dirty = false;  // blog editöründe kaydedilmemiş değişiklik

async function loadApp() {
  S.data = await api('state');
  if (!location.hash) history.replaceState(null, '', '#galeri');
  render();
}

function render() {
  const route = location.hash.slice(1) || 'galeri';
  S.route = route;
  const tab = route.split('/')[0];

  const main = h('main');
  app.replaceChildren(
    h('header', { class: 'topbar' },
      h('div', { class: 'brand' }, 'Yelken ', h('span', {}, 'Perde'), h('small', {}, 'Yönetim')),
      h('a', { href: '../', target: '_blank' }, 'Siteyi Gör ↗'),
      h('button', { class: 'link', onclick: logout }, 'Çıkış')),
    h('nav', { class: 'tabs' }, TABS.map(([k, label]) => h('a', { href: '#' + k, class: k === tab ? 'active' : '' }, label))),
    main);

  const views = { galeri: viewGallery, vitrin: viewShowcase, urunler: viewProducts, blog: viewBlog, ayarlar: viewSettings };
  (views[tab] || viewGallery)(main, route);
  window.scrollTo(0, 0);
}

window.addEventListener('hashchange', () => {
  if (!S.data) return;
  if (dirty && !confirm('Kaydedilmemiş değişiklikler var. Yine de çıkılsın mı?')) {
    history.pushState(null, '', '#' + S.route);
    return;
  }
  dirty = false;
  render();
});
window.addEventListener('beforeunload', (e) => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });

async function logout() {
  if (dirty && !confirm('Kaydedilmemiş değişiklikler var. Çıkılsın mı?')) return;
  dirty = false;
  try { await api('logout', {}); } catch { /* yine de çık */ }
  S.data = null;
  boot();
}

/* ═══════════ Galeri ═══════════ */

let galleryFilter = 'all';

function viewGallery(main) {
  const g = S.data.gallery;
  const newCats = new Set();
  let addToShowcase = false;

  // Yükleme kartı
  const queue = h('div', { class: 'queue' });
  const catChips = h('div', { class: 'chips' }, Object.entries(S.data.categories).map(([k, name]) => {
    const c = h('span', { class: 'chip', onclick: () => { c.classList.toggle('on'); newCats.has(k) ? newCats.delete(k) : newCats.add(k); } }, name);
    return c;
  }));

  const drop = h('label', { class: 'drop' },
    svgUpload(),
    h('strong', {}, 'Fotoğraf veya video seçin'),
    h('span', { class: 'muted' }, 'Birden fazla dosya seçebilir ya da buraya sürükleyebilirsiniz · Video en fazla 512 MB'),
    h('input', { type: 'file', accept: 'image/*,video/*', multiple: true, onchange: (e) => { handleFiles(Array.from(e.target.files)); e.target.value = ''; } }));
  drop.addEventListener('dragover', (e) => { e.preventDefault(); drop.classList.add('over'); });
  drop.addEventListener('dragleave', () => drop.classList.remove('over'));
  drop.addEventListener('drop', (e) => { e.preventDefault(); drop.classList.remove('over'); handleFiles(Array.from(e.dataTransfer.files)); });

  const yt = h('input', { type: 'url', placeholder: 'https://www.youtube.com/watch?v=…' });
  const ytBtn = h('button', { class: 'btn ghost', type: 'button', onclick: () => busy(ytBtn, async () => {
    if (!yt.value.trim()) return;
    await addItems([{ type: 'youtube', youtube: yt.value.trim(), categories: [...newCats], title: '' }]);
    yt.value = '';
    toast('YouTube videosu eklendi.', 'ok');
  }) }, 'Ekle');

  async function addItems(items) {
    const r = await api('gallery_add', { items, showcase: addToShowcase });
    S.data.gallery.unshift(...r.items);
    if (addToShowcase) S.data.showcase.push(...r.items.map((i) => i.id));
    drawGrid();
  }

  async function handleFiles(files) {
    // Sırayla yükle: zayıf mobil bağlantılarda daha güvenli
    for (const file of files) {
      const status = h('span', { class: 'status' }, 'Hazırlanıyor…');
      const bar = h('i');
      const row = h('div', { class: 'q-item' }, h('span', { class: 'name' }, file.name), status, h('div', { class: 'bar' }, bar));
      queue.prepend(row);
      const progress = (base, span) => (p) => { bar.style.width = Math.round((base + p * span) * 100) + '%'; status.textContent = Math.round((base + p * span) * 100) + '%'; };
      const title = file.name.replace(/\.[^.]+$/, '').replace(/[-_]+/g, ' ').replace(/^(IMG|VID|PXL|DSC)\s?\d+.*$/i, '').trim();

      try {
        const isVideo = file.type.startsWith('video/') || /\.(mp4|mov|webm|m4v)$/i.test(file.name);
        let item;
        if (isVideo) {
          if (file.size > S.data.limits.video) throw new Error('Video 512 MB’dan büyük olamaz.');
          const poster = await videoPoster(file);
          status.textContent = 'Yükleniyor…';
          const vid = await upload(file, progress(0, poster ? 0.95 : 1));
          const thumb = poster ? (await upload(poster, progress(0.95, 0.05))).path : '';
          item = { type: 'video', src: vid.path, thumb, title, categories: [...newCats] };
        } else {
          const full = await uploadImage(file, 2000, progress(0, 0.8));
          const thumb = await uploadImage(file, 800, progress(0.8, 0.2));
          item = { type: 'image', src: full, thumb, title, categories: [...newCats] };
        }
        await addItems([item]);
        row.classList.add('done');
        status.textContent = 'Eklendi ✓';
        bar.style.width = '100%';
      } catch (e) {
        row.classList.add('error');
        status.textContent = e.message;
      }
    }
  }

  main.append(
    h('div', { class: 'page-head' },
      h('div', {}, h('h1', {}, 'Galeri'), h('p', { class: 'muted' }, 'Galeri sayfasında görünen fotoğraf ve videolar. Yeni eklenenler en başta gösterilir.'))),
    h('section', { class: 'card stack' },
      h('h2', {}, 'Yeni içerik ekle'),
      h('div', { class: 'stack' },
        h('div', { class: 'muted' }, '1. Önce kategorileri seçin (yüklenecek tüm dosyalara uygulanır, sonradan da değiştirebilirsiniz):'),
        catChips,
        h('label', { class: 'check' }, h('input', { type: 'checkbox', onchange: (e) => { addToShowcase = e.target.checked; } }), 'Ana sayfa vitrinine de ekle')),
      drop,
      queue,
      h('div', { class: 'row' }, h('span', { class: 'muted' }, 'veya YouTube videosu:'), h('div', { style: 'flex:1;min-width:200px' }, yt), ytBtn)));

  const gridWrap = h('section');
  main.append(gridWrap);
  drawGrid();

  function drawGrid() {
    const counts = {};
    S.data.gallery.forEach((it) => (it.categories || []).forEach((c) => (counts[c] = (counts[c] || 0) + 1)));
    const videos = S.data.gallery.filter((it) => it.type !== 'image').length;

    const filters = [['all', 'Tümü', S.data.gallery.length], ...Object.keys(S.data.categories).filter((k) => counts[k]).map((k) => [k, catName(k), counts[k]])];
    if (videos) filters.push(['_video', 'Videolar', videos]);

    const shown = S.data.gallery.filter((it) => galleryFilter === 'all' || (galleryFilter === '_video' ? it.type !== 'image' : (it.categories || []).includes(galleryFilter)));
    const showcase = new Set(S.data.showcase);

    gridWrap.replaceChildren(
      h('div', { class: 'row', style: 'margin-bottom:14px' },
        h('div', { class: 'chips' }, filters.map(([k, label, n]) => h('span', { class: 'chip' + (galleryFilter === k ? ' on' : ''), onclick: () => { galleryFilter = k; drawGrid(); } }, label, h('span', { class: 'count' }, n))))),
      shown.length ? h('div', { class: 'media-grid' }, shown.map((it) => h('button', { class: 'tile', type: 'button', onclick: () => editItem(it, drawGrid) },
        h('div', { class: 'thumb' }, thumbEl(it),
          it.type !== 'image' && h('span', { class: 'badge' }, '▶ ' + typeLabel[it.type]),
          showcase.has(it.id) && h('span', { class: 'badge star', title: 'Ana sayfa vitrininde' }, '★')),
        h('div', { class: 'meta' },
          h('div', { class: 'title' }, it.title || 'Başlıksız'),
          h('div', { class: 'sub' }, (it.categories || []).map(catName).join(', ') || 'Kategori yok')))))
        : h('div', { class: 'empty' }, 'Bu filtrede içerik yok.'));
  }
}

function svgUpload() {
  const ns = 'http://www.w3.org/2000/svg';
  const svg = document.createElementNS(ns, 'svg');
  svg.setAttribute('viewBox', '0 0 24 24');
  svg.setAttribute('class', 'drop-icon');
  svg.setAttribute('fill', 'none');
  svg.setAttribute('stroke', 'currentColor');
  svg.setAttribute('stroke-width', '1.5');
  const p = document.createElementNS(ns, 'path');
  p.setAttribute('d', 'M12 16V4m0 0L7 9m5-5 5 5M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3');
  svg.append(p);
  return svg;
}

function mediaPreview(it) {
  if (it.type === 'youtube') return h('iframe', { src: `https://www.youtube-nocookie.com/embed/${it.youtube}`, allow: 'encrypted-media; picture-in-picture', allowfullscreen: true });
  if (it.type === 'video') return h('video', { src: media(it.src), poster: media(it.thumb) || null, controls: true, playsinline: true, preload: 'metadata' });
  return h('img', { src: media(it.src), alt: '' });
}

function editItem(it, onChange) {
  const cats = new Set(it.categories || []);
  const title = h('input', { type: 'text', value: it.title || '', maxlength: 120 });
  const label = h('input', { type: 'text', value: it.label || '', maxlength: 80, placeholder: 'ör. Modern · Fon + Tül' });
  const caption = h('textarea', { maxlength: 240, rows: 2, placeholder: 'Büyük görünümde altta yazan açıklama' }, it.caption || '');
  const inShowcase = h('input', { type: 'checkbox', checked: S.data.showcase.includes(it.id) });

  const idx = () => S.data.gallery.findIndex((x) => x.id === it.id);
  async function move(to) {
    const list = S.data.gallery;
    const from = idx();
    to = Math.max(0, Math.min(list.length - 1, to));
    if (from === to) return;
    list.splice(to, 0, list.splice(from, 1)[0]);
    try {
      await api('gallery_order', { ids: list.map((x) => x.id) });
      toast(`Sıra güncellendi (${to + 1}. sırada).`, 'ok');
      onChange();
      pos.textContent = `Galerideki sırası: ${idx() + 1} / ${list.length}`;
    } catch (e) { toast(e.message, 'error'); }
  }
  const pos = h('span', { class: 'muted' }, `Galerideki sırası: ${idx() + 1} / ${S.data.gallery.length}`);

  const saveBtn = h('button', { class: 'btn gold', type: 'submit' }, 'Kaydet');
  const delBtn = h('button', { class: 'btn danger', type: 'button', onclick: () => {
    if (!confirm('Bu içerik galeriden ve sunucudan silinsin mi? Bu işlem geri alınamaz.')) return;
    busy(delBtn, async () => {
      await api('gallery_delete', { id: it.id });
      S.data.gallery.splice(idx(), 1);
      S.data.showcase = S.data.showcase.filter((x) => x !== it.id);
      dlg.close();
      toast('Silindi.', 'ok');
      onChange();
    });
  } }, 'Sil');

  const form = h('form', { class: 'dlg-body', onsubmit: (e) => {
    e.preventDefault();
    busy(saveBtn, async () => {
      const r = await api('gallery_update', { item: { id: it.id, title: title.value, label: label.value, caption: caption.value, categories: [...cats], showcase: inShowcase.checked } });
      Object.assign(it, r.item);
      S.data.showcase = S.data.showcase.filter((x) => x !== it.id);
      if (inShowcase.checked) S.data.showcase.push(it.id);
      dlg.close();
      toast('Kaydedildi.', 'ok');
      onChange();
    });
  } },
    h('div', { class: 'dlg-head' }, h('h2', { style: 'margin:0' }, typeLabel[it.type] + ' düzenle'), h('button', { class: 'dlg-close', type: 'button', 'aria-label': 'Kapat', onclick: () => dlg.close() }, '×')),
    h('label', { class: 'field' }, h('span', {}, 'Başlık'), title),
    h('label', { class: 'field' }, h('span', {}, 'Kısa etiket'), label),
    h('label', { class: 'field' }, h('span', {}, 'Açıklama'), caption),
    h('div', { class: 'field' }, h('span', { class: 'muted' }, 'Kategoriler'),
      h('div', { class: 'chips' }, Object.entries(S.data.categories).map(([k, name]) => {
        const c = h('span', { class: 'chip' + (cats.has(k) ? ' on' : ''), onclick: () => { cats.has(k) ? cats.delete(k) : cats.add(k); c.classList.toggle('on'); } }, name);
        return c;
      }))),
    h('label', { class: 'check' }, inShowcase, 'Ana sayfa vitrininde göster'),
    h('div', { class: 'row' }, pos, h('span', { class: 'spacer' }), h('span', { class: 'order-btns' },
      h('button', { class: 'icon-btn', type: 'button', title: 'En başa al', onclick: () => move(0) }, '⇤'),
      h('button', { class: 'icon-btn', type: 'button', title: 'Bir öne al', onclick: () => move(idx() - 1) }, '←'),
      h('button', { class: 'icon-btn', type: 'button', title: 'Bir arkaya al', onclick: () => move(idx() + 1) }, '→'),
      h('button', { class: 'icon-btn', type: 'button', title: 'En sona al', onclick: () => move(S.data.gallery.length - 1) }, '⇥'))),
    h('div', { class: 'dlg-foot' }, saveBtn, h('span', { class: 'spacer' }), delBtn));

  const dlg = h('dialog', {}, h('div', { class: 'dlg' }, h('div', { class: 'dlg-media' }, mediaPreview(it)), form));
  openDialog(dlg);
}

function openDialog(dlg) {
  document.body.append(dlg);
  dlg.addEventListener('close', () => dlg.remove());
  dlg.addEventListener('click', (e) => { if (e.target === dlg) dlg.close(); });
  dlg.showModal();
}

/* ═══════════ Ana sayfa vitrini ═══════════ */

function viewShowcase(main) {
  const byId = Object.fromEntries(S.data.gallery.map((it) => [it.id, it]));
  const list = h('div', { class: 'list' });

  async function save(ids) {
    const prev = S.data.showcase;
    S.data.showcase = ids;
    draw();
    try { await api('showcase_save', { ids }); }
    catch (e) { S.data.showcase = prev; draw(); toast(e.message, 'error'); }
  }

  function draw() {
    const ids = S.data.showcase.filter((id) => byId[id]);
    list.replaceChildren(...(ids.length ? ids.map((id, i) => {
      const it = byId[id];
      const swap = (j) => { const n = ids.slice(); [n[i], n[j]] = [n[j], n[i]]; save(n); };
      return h('div', { class: 'list-row' },
        h('span', { class: 'num' }, i + 1),
        itemThumb(it) ? h('img', { src: media(itemThumb(it)), alt: '' }) : h('div', { class: 'ph' }),
        h('div', { class: 'info' }, h('div', { class: 'title' }, it.title || 'Başlıksız'), h('div', { class: 'muted' }, typeLabel[it.type])),
        h('button', { class: 'icon-btn', title: 'Yukarı', disabled: i === 0, onclick: () => swap(i - 1) }, '↑'),
        h('button', { class: 'icon-btn', title: 'Aşağı', disabled: i === ids.length - 1, onclick: () => swap(i + 1) }, '↓'),
        h('button', { class: 'icon-btn', title: 'Vitrinden çıkar', onclick: () => save(ids.filter((x) => x !== id)) }, '✕'));
    }) : [h('div', { class: 'empty' }, 'Vitrin boş. Ana sayfada bu bölüm gizlenir.')]));
    count.textContent = `${ids.length} öğe` + (ids.length !== 7 ? ' · Ana sayfadaki kolaj 7 öğeyle en iyi görünür (fazlası gösterilmez)' : '');
  }

  function pick() {
    const chosen = new Set(S.data.showcase);
    const grid = h('div', { class: 'media-grid' }, S.data.gallery.map((it) => {
      const tile = h('button', { class: 'tile', type: 'button', onclick: () => {
        chosen.has(it.id) ? chosen.delete(it.id) : chosen.add(it.id);
        star.hidden = !chosen.has(it.id);
      } }, h('div', { class: 'thumb' }, thumbEl(it)), h('div', { class: 'meta' }, h('div', { class: 'title' }, it.title || 'Başlıksız')));
      const star = h('span', { class: 'badge star' }, '★ Seçili');
      star.hidden = !chosen.has(it.id);
      tile.firstChild.append(star);
      return tile;
    }));
    const dlg = h('dialog', {}, h('div', { class: 'picker' },
      h('div', { class: 'dlg-head' }, h('h2', { style: 'margin:0' }, 'Galeriden seç'), h('button', { class: 'dlg-close', 'aria-label': 'Kapat', onclick: () => dlg.close() }, '×')),
      h('p', { class: 'muted' }, 'Vitrinde görünmesini istediğiniz içeriklere dokunun.'),
      grid,
      h('div', { class: 'row' }, h('span', { class: 'spacer' }), h('button', { class: 'btn gold', onclick: () => {
        // Mevcut sırayı koru, yeni seçilenleri sona ekle
        const kept = S.data.showcase.filter((id) => chosen.has(id));
        const added = S.data.gallery.map((it) => it.id).filter((id) => chosen.has(id) && !kept.includes(id));
        save([...kept, ...added]);
        dlg.close();
      } }, 'Tamam'))));
    openDialog(dlg);
  }

  const count = h('span', { class: 'muted' });
  main.append(
    h('div', { class: 'page-head' },
      h('div', {}, h('h1', {}, 'Ana Sayfa Vitrini'), h('p', { class: 'muted' }, 'Ana sayfadaki “Son İşlerimizden Bir Seçki” bölümü. Değişiklikler anında kaydedilir.')),
      h('button', { class: 'btn gold', onclick: pick }, '+ Galeriden Seç')),
    h('section', { class: 'card' }, h('div', { style: 'margin-bottom:12px' }, count), list));
  draw();
}

/* ═══════════ Ürün görselleri ═══════════ */

function viewProducts(main) {
  main.append(h('div', { class: 'page-head' },
    h('div', {}, h('h1', {}, 'Ürün Görselleri'), h('p', { class: 'muted' }, 'Ürünler sayfası ve ana sayfadaki koleksiyon kartlarında kullanılan görseller.'))));

  main.append(h('div', { class: 'products' }, S.data.products.map((p) => {
    const imgBox = h('div', { class: 'img' }, p.image ? h('img', { src: media(p.image), alt: '' }) : null);
    const alt = h('input', { type: 'text', value: p.alt || '', maxlength: 160 });
    const save = (image) => api('product_save', { key: p.key, image, alt: alt.value }).then(() => { p.image = image; p.alt = alt.value; });

    const changeBtn = h('button', { class: 'btn ghost small', type: 'button', onclick: () => filePicker('image/*', false, ([file]) => busy(changeBtn, async () => {
      imgBox.classList.add('busy');
      try {
        const path = await uploadImage(file, 1800);
        await save(path);
        imgBox.replaceChildren(h('img', { src: media(path), alt: '' }));
        toast(p.name + ' görseli güncellendi.', 'ok');
      } finally { imgBox.classList.remove('busy'); }
    })) }, 'Görseli Değiştir');

    alt.addEventListener('change', () => busy(null, async () => { await save(p.image); toast('Açıklama kaydedildi.', 'ok'); }));

    return h('div', { class: 'product' }, imgBox, h('div', { class: 'body' },
      h('div', { class: 'row' }, h('h3', {}, p.name), h('span', { class: 'spacer' }), changeBtn),
      h('label', { class: 'field' }, h('span', {}, 'Görsel açıklaması (SEO)'), alt)));
  })));
}

/* ═══════════ Blog ═══════════ */

function viewBlog(main, route) {
  const id = route.split('/')[1];
  if (id) return blogEditor(main, id === 'yeni' ? null : S.data.blog.find((p) => p.id === id));

  main.append(
    h('div', { class: 'page-head' },
      h('div', {}, h('h1', {}, 'Blog'), h('p', { class: 'muted' }, 'Taslak yazılar sitede görünmez.')),
      h('a', { class: 'btn gold', href: '#blog/yeni' }, '+ Yeni Yazı')),
    h('section', { class: 'card' }, S.data.blog.length ? h('div', { class: 'list' }, S.data.blog.map((p) =>
      h('a', { class: 'list-row', href: '#blog/' + p.id, style: 'color:inherit;text-decoration:none' },
        p.cover ? h('img', { src: media(p.cover), alt: '' }) : h('div', { class: 'ph' }),
        h('div', { class: 'info' },
          h('div', { class: 'title' }, p.title),
          h('div', { class: 'muted' }, [p.category, formatDate(p.date)].filter(Boolean).join(' · '))),
        p.static && h('span', { class: 'pill', title: 'Özel tasarımlı sayfa' }, 'Tasarımlı'),
        h('span', { class: 'pill' + (p.published ? ' live' : '') }, p.published ? 'Yayında' : 'Taslak'))))
      : h('div', { class: 'empty' }, 'Henüz yazı yok.')));
}

const MONTHS = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
function formatDate(ymd) {
  const m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(ymd || '');
  return m ? `${+m[3]} ${MONTHS[+m[2] - 1]} ${m[1]}` : '';
}

function slugify(t) {
  const map = { ç: 'c', ğ: 'g', ı: 'i', ö: 'o', ş: 's', ü: 'u', â: 'a', î: 'i', û: 'u' };
  return t.toLocaleLowerCase('tr').replace(/[çğıöşüâîû]/g, (c) => map[c] || c).replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '').slice(0, 80);
}

function blogEditor(main, post) {
  const isNew = !post;
  post = post || { title: '', slug: '', category: '', date: new Date().toISOString().slice(0, 10), cover: '', excerpt: '', content: '', published: false };
  let cover = post.cover;
  let slugTouched = !isNew;
  const isStatic = !!post.static;  // blog/ klasöründeki tasarımlı sabit sayfa
  const markDirty = () => { dirty = true; };

  const title = h('input', { type: 'text', value: post.title, maxlength: 160, required: true, oninput: () => { if (!slugTouched) slug.value = slugify(title.value); markDirty(); } });
  const slug = h('input', { type: 'text', value: post.slug, maxlength: 80, disabled: isStatic, oninput: () => { slugTouched = true; markDirty(); } });
  const readTime = h('input', { type: 'text', inputmode: 'numeric', value: post.read_time || '', maxlength: 3, oninput: markDirty });
  const category = h('input', { type: 'text', value: post.category, maxlength: 60, placeholder: 'ör. Bakım Rehberi', oninput: markDirty });
  const date = h('input', { type: 'date', value: post.date, oninput: markDirty });
  const excerpt = h('textarea', { rows: 3, maxlength: 400, placeholder: 'Blog listesinde ve Google’da görünen kısa özet', oninput: markDirty }, post.excerpt);
  const content = h('textarea', { class: 'editor', oninput: markDirty }, post.content);
  const published = h('input', { type: 'checkbox', checked: post.published, onchange: markDirty });

  // Kapak görseli
  const coverImg = h('div', { class: 'img' }, cover ? h('img', { src: media(cover), alt: '' }) : null);
  const coverBtn = h('button', { class: 'btn ghost small', type: 'button', onclick: () => filePicker('image/*', false, ([f]) => busy(coverBtn, async () => {
    coverImg.classList.add('busy');
    try {
      cover = await uploadImage(f, 1600);
      coverImg.replaceChildren(h('img', { src: media(cover), alt: '' }));
      markDirty();
    } finally { coverImg.classList.remove('busy'); }
  })) }, cover ? 'Kapak Görselini Değiştir' : 'Kapak Görseli Seç');

  // Editör araç çubuğu
  function wrap(before, after = '', placeholder = '') {
    const { selectionStart: s, selectionEnd: e, value } = content;
    const sel = value.slice(s, e) || placeholder;
    content.setRangeText(before + sel + after, s, e, 'end');
    content.focus();
    content.setSelectionRange(s + before.length, s + before.length + sel.length);
    markDirty();
  }
  function linePrefix(prefix) {
    const { selectionStart: s, selectionEnd: e, value } = content;
    const start = value.lastIndexOf('\n', s - 1) + 1;
    const block = value.slice(start, e).split('\n').map((l) => prefix + l.replace(/^(#{2,3}\s|[-*]\s|\d+\.\s|>\s)/, '')).join('\n');
    content.setRangeText(block, start, e, 'end');
    content.focus();
    markDirty();
  }
  const imgBtn = h('button', { type: 'button', title: 'Yazıya görsel ekle', onclick: () => filePicker('image/*', false, ([f]) => busy(imgBtn, async () => {
    toast('Görsel yükleniyor…');
    const path = await uploadImage(f, 1600);
    wrap('\n\n![', `](${path})\n\n`, 'Görsel açıklaması');
  })) }, '🖼 Görsel');

  const preview = h('div', { class: 'preview', hidden: true });
  const previewBtn = h('button', { type: 'button', onclick: async () => {
    if (preview.hidden) {
      try {
        const r = await api('preview', { content: content.value });
        preview.innerHTML = r.html.replace(/src="(?!https?:)/g, 'src="../');  // sunucuda güvenli şekilde üretildi
        preview.hidden = false; content.hidden = true; previewBtn.textContent = '✎ Düzenle';
      } catch (e) { toast(e.message, 'error'); }
    } else {
      preview.hidden = true; content.hidden = false; previewBtn.textContent = '👁 Önizleme';
    }
  } }, '👁 Önizleme');

  const toolbar = h('div', { class: 'toolbar' },
    h('button', { type: 'button', title: 'Ara başlık', onclick: () => linePrefix('## ') }, 'Başlık'),
    h('button', { type: 'button', title: 'Küçük başlık', onclick: () => linePrefix('### ') }, 'Alt başlık'),
    h('button', { type: 'button', title: 'Kalın', onclick: () => wrap('**', '**', 'kalın metin') }, h('b', {}, 'K')),
    h('button', { type: 'button', title: 'İtalik', onclick: () => wrap('*', '*', 'italik metin') }, h('i', {}, 'İ')),
    h('button', { type: 'button', title: 'Madde işaretli liste', onclick: () => linePrefix('- ') }, '• Liste'),
    h('button', { type: 'button', title: 'Numaralı liste', onclick: () => linePrefix('1. ') }, '1. Liste'),
    h('button', { type: 'button', title: 'Alıntı', onclick: () => linePrefix('> ') }, '❝'),
    h('button', { type: 'button', title: 'Bağlantı', onclick: () => {
      const url = prompt('Bağlantı adresi (ör. https://… veya urunler.html):');
      if (url) wrap('[', `](${url.trim()})`, 'bağlantı metni');
    } }, '🔗 Bağlantı'),
    imgBtn,
    h('span', { class: 'spacer' }),
    previewBtn);

  const saveBtn = h('button', { class: 'btn gold', type: 'submit' }, 'Kaydet');
  const delBtn = !isNew && !isStatic && h('button', { class: 'btn danger', type: 'button', onclick: () => {
    if (!confirm('Bu yazı kalıcı olarak silinsin mi?')) return;
    busy(delBtn, async () => {
      await api('blog_delete', { id: post.id });
      S.data.blog = S.data.blog.filter((p) => p.id !== post.id);
      dirty = false;
      toast('Yazı silindi.', 'ok');
      location.hash = '#blog';
    });
  } }, 'Sil');

  const form = h('form', { onsubmit: (e) => {
    e.preventDefault();
    busy(saveBtn, async () => {
      const r = await api('blog_save', { post: { id: post.id, title: title.value, slug: slug.value, category: category.value, date: date.value, cover, excerpt: excerpt.value, content: content.value, read_time: readTime.value, published: published.checked } });
      const i = S.data.blog.findIndex((p) => p.id === r.post.id);
      if (i >= 0) S.data.blog[i] = r.post; else S.data.blog.unshift(r.post);
      S.data.blog.sort((a, b) => (b.date || '').localeCompare(a.date || ''));
      dirty = false;
      toast(r.post.published ? 'Yazı kaydedildi ve yayında.' : 'Taslak kaydedildi.', 'ok');
      if (isNew) location.hash = '#blog/' + r.post.id;
      else render();
    });
  } },
    h('div', { class: 'page-head' },
      h('div', {}, h('a', { href: '#blog', class: 'muted' }, '← Tüm yazılar'), h('h1', {}, isNew ? 'Yeni Yazı' : 'Yazıyı Düzenle')),
      h('div', { class: 'row' },
        !isNew && post.published && h('a', { class: 'btn ghost', href: `../blog/${post.slug}.html`, target: '_blank' }, 'Sitede Gör ↗'),
        saveBtn)),
    h('section', { class: 'card stack' },
      h('label', { class: 'field' }, h('span', {}, 'Başlık'), title),
      h('div', { class: 'grid-2' },
        h('label', { class: 'field' }, h('span', {}, 'Kategori'), category),
        h('label', { class: 'field' }, h('span', {}, 'Tarih'), date)),
      h('label', { class: 'field' }, h('span', {}, 'Adres (blog/…html)'), slug),
      isStatic && h('label', { class: 'field', style: 'max-width:200px' }, h('span', {}, 'Okuma süresi (dk)'), readTime),
      h('div', { class: 'field' }, h('span', { class: 'muted' }, 'Kapak görseli'), h('div', { class: 'cover-box' }, coverImg, coverBtn)),
      h('label', { class: 'field' }, h('span', {}, 'Kısa özet'), excerpt)),
    isStatic ? h('section', { class: 'card stack' },
      h('h2', {}, 'Yazı içeriği'),
      h('p', { class: 'help' }, 'Bu yazı özel tasarımlı bir sayfadır (blog/' + post.slug + '.html). İçeriği panelden değiştirilemez; buradan sadece blog listesinde görünen başlık, özet, kapak görseli ve kategori düzenlenir. “Yayında” işareti kaldırılırsa yazı blog listesinden gizlenir.'))
    : h('section', { class: 'card stack' },
      h('h2', {}, 'Yazı içeriği'),
      h('div', {}, toolbar, content, preview),
      h('div', { class: 'help' },
        'Paragraflar arasında bir boş satır bırakın. ',
        h('code', {}, '## Başlık'), ' ara başlık, ', h('code', {}, '- madde'), ' liste, ',
        h('code', {}, '**kalın**'), ' kalın yazı, ', h('code', {}, '[metin](adres)'), ' bağlantı oluşturur.')),
    h('section', { class: 'card row' },
      h('label', { class: 'check' }, published, 'Yayında (sitede görünsün)'),
      h('span', { class: 'spacer' }),
      delBtn,
      h('button', { class: 'btn gold', type: 'submit' }, 'Kaydet')));

  main.append(form);
}

/* ═══════════ Ayarlar ═══════════ */

function viewSettings(main) {
  const cur = h('input', { type: 'password', autocomplete: 'current-password', required: true });
  const pw = h('input', { type: 'password', autocomplete: 'new-password', required: true, minlength: 8 });
  const pw2 = h('input', { type: 'password', autocomplete: 'new-password', required: true });
  const btn = h('button', { class: 'btn gold', type: 'submit' }, 'Şifreyi Değiştir');
  const mb = (n) => Math.round(n / 1024 / 1024 * 10) / 10 + ' MB';

  main.append(
    h('div', { class: 'page-head' }, h('h1', {}, 'Ayarlar')),
    h('form', { class: 'card stack', style: 'max-width:480px', onsubmit: (e) => {
      e.preventDefault();
      if (pw.value !== pw2.value) { toast('Yeni şifreler aynı değil.', 'error'); return; }
      busy(btn, async () => {
        await api('password', { current: cur.value, new: pw.value });
        e.target.reset();
        toast('Şifre değiştirildi.', 'ok');
      });
    } },
      h('h2', {}, 'Panel şifresi'),
      h('label', { class: 'field' }, h('span', {}, 'Mevcut şifre'), cur),
      h('label', { class: 'field' }, h('span', {}, 'Yeni şifre (en az 8 karakter)'), pw),
      h('label', { class: 'field' }, h('span', {}, 'Yeni şifre tekrar'), pw2),
      h('div', {}, btn)),
    h('section', { class: 'card stack', style: 'max-width:480px' },
      h('h2', {}, 'Yükleme bilgileri'),
      h('p', { class: 'muted' }, `Fotoğraflar yüklenmeden önce otomatik olarak küçültülür. Videolar ${mb(S.data.limits.chunk)}’lık parçalar halinde gönderilir; en fazla ${mb(S.data.limits.video)}.`),
      h('p', { class: 'muted' }, 'iPhone videoları her tarayıcıda oynamayabilir. En uyumlu sonuç için iPhone’da Ayarlar › Kamera › Formatlar › “En Uyumlu” seçeneğini kullanın.')));
}

/* ═══════════ Başlangıç ═══════════ */

async function boot() {
  try {
    const s = await api('session');
    S.csrf = s.csrf;
    if (s.needs_setup) renderAuth(true);
    else if (!s.logged_in) renderAuth(false);
    else await loadApp();
  } catch (e) {
    app.replaceChildren(h('div', { class: 'boot' }, 'Panel açılamadı: ' + e.message));
  }
}

boot();
