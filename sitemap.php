<?php
/* sitemap.xml — sayfalar + yayındaki tüm blog yazıları (panelden eklenenler dahil) */
require __DIR__ . '/inc/lib.php';

header('Content-Type: application/xml; charset=utf-8');

$site = 'https://yelkenperde.com/';
$mtime = function (string $file) { return date('Y-m-d', @filemtime(__DIR__ . '/' . $file) ?: time()); };
$dataTime = function (string $name) use ($mtime) {
  return is_file(YP_DATA . "/$name.json") ? date('Y-m-d', filemtime(YP_DATA . "/$name.json")) : $mtime('inc/seed/' . $name . '.json');
};

// Adresler sayfaların canonical etiketleriyle aynıdır
$urls = [
  ['',                        $mtime('index.php'),               'weekly',  '1.0'],
  ['urunler.html',            max($mtime('urunler.php'), $dataTime('products')), 'monthly', '0.9'],
  ['galeri.html',             max($mtime('galeri.php'), $dataTime('gallery')),   'weekly',  '0.9'],
  ['hakkimizda.html',         $mtime('hakkimizda.html'),         'monthly', '0.7'],
  ['iletisim.html',           $mtime('iletisim.html'),           'monthly', '0.8'],
  ['blog.html',               max($mtime('blog.php'), $dataTime('blog')),        'weekly',  '0.8'],
  ['gizlilik-politikasi',     $mtime('gizlilik-politikasi.html'), 'yearly',  '0.3'],
  ['kvkk',                    $mtime('kvkk.html'),               'yearly',  '0.3'],
];
foreach (yp_posts() as $p) {
  $urls[] = ['blog/' . $p['slug'] . '.html', $p['date'] ?: date('Y-m-d'), 'monthly', '0.7'];
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$path, $lastmod, $freq, $prio]) {
  echo "  <url>\n    <loc>" . e($site . $path) . "</loc>\n    <lastmod>$lastmod</lastmod>\n"
     . "    <changefreq>$freq</changefreq>\n    <priority>$prio</priority>\n  </url>\n";
}
echo "</urlset>\n";
