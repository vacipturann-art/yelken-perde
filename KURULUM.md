# Yelken Perde — Kurulum ve Yönetim Paneli

Site PHP ile çalışır (PHP 7.4 veya üzeri). Veritabanı gerekmez; içerik `data/` klasöründeki JSON dosyalarında, yüklenen dosyalar `uploads/` klasöründe tutulur.

## Otomatik yükleme (GitHub → Natro)

`.github/workflows/deploy.yml` **main** dalına her gönderimde depoyu FTP ile `public_html/` klasörüne yükler. GitHub'da deponun **Settings › Secrets and variables › Actions** sayfasına şu üç gizli değişken eklenmelidir:

| Ad | Değer |
|---|---|
| `FTP_SERVER` | Natro'nun verdiği FTP sunucusu (alan adı henüz yönlenmediyse sunucu IP'si) |
| `FTP_USERNAME` | cPanel / FTP kullanıcı adı |
| `FTP_PASSWORD` | FTP şifresi |

Yükleme sadece depodaki dosyaları gönderir; panelin sunucuda oluşturduğu içerik (`data/`, `uploads/`) silinmez.

## Elle yükleme (cPanel)

1. Depodaki tüm dosyaları `public_html/` içine yükleyin (gizli `.htaccess` dosyaları ve `images/` klasörü dahil).
2. `data/` ve `uploads/` klasörlerinin yazılabilir olduğundan emin olun (izin: 755).
3. Hemen ardından `https://yelkenperde.com/admin/` adresine girip **panel şifresini oluşturun**. Şifre oluşturulana kadar ilk giren kişi şifreyi belirleyebilir; bu adımı geciktirmeyin.

> Güncelleme yüklerken `data/` ve `uploads/` klasörlerinin içeriğinin **üzerine yazmayın**: panelden eklenen her şey bu klasörlerdedir. (Depoda bu klasörlerde sadece `.htaccess` bulunur, normal bir yükleme bunları bozmaz.)

## Panelde neler var?

| Bölüm | Ne yapar |
|---|---|
| Galeri | Fotoğraf/video yükleme (telefondan da), YouTube bağlantısı, başlık, kategori, sıralama, silme |
| Ana Sayfa Vitrini | Ana sayfadaki "Son İşlerimizden" bölümünde hangi içeriklerin hangi sırayla görüneceği |
| Ürün Görselleri | Ürünler sayfası ve ana sayfadaki ürün kartlarının görselleri |
| Blog | Yazı ekleme/düzenleme, kapak görseli, taslak/yayında, önizleme |
| Ayarlar | Şifre değiştirme |

- Fotoğraflar yüklenmeden önce tarayıcıda küçültülür (en fazla 2000 px), sunucuda ek eklenti gerekmez.
- Videolar küçük parçalar halinde gönderilir; hostingin yükleme sınırı düşük olsa da 512 MB'a kadar video yüklenebilir.
- Şifre 5 kez yanlış girilirse o IP 15 dakika engellenir. Şifre unutulursa `data/auth.php` dosyası cPanel/FTP ile silinir ve panelden yeni şifre oluşturulur.

## Adresler

Netlify'daki eski adreslerin hepsi aynen çalışır (Google'daki kayıtlar bozulmaz):

- `galeri.html`, `urunler.html`, `blog.html` ve uzantısız hâlleri (`/galeri`, `/urunler`, `/blog`) `.htaccess` ile PHP sayfalarına yönlendirilir.
- `/hakkimizda`, `/kvkk` gibi uzantısız adresler ilgili `.html` dosyasını açar.
- `sitemap.xml` otomatik üretilir; panelden eklenen blog yazıları da içinde yer alır.

## Blog yazıları

- `blog/` klasöründeki 4 yazı özel tasarımlı sayfalardır. Panelde "Tasarımlı" etiketiyle görünürler; panelden sadece blog listesindeki başlık, özet, kapak görseli, kategori ve okuma süresi düzenlenir. "Yayında" işareti kaldırılırsa listeden gizlenir.
- Panelden yazılan yeni yazılar aynı tasarımla `blog/yazi-adi.html` adresinde yayınlanır.

## Dosya yapısı

```
admin/         Yönetim paneli (index.php, api.php, admin.js, admin.css)
inc/           Ortak PHP kodu ve başlangıç içeriği (inc/seed/) — web'den erişilemez
data/          Panelin kaydettiği içerik — web'den erişilemez
uploads/       Panelden yüklenen dosyalar — içinde kod çalıştırılamaz
blog-yazi.php  Tek blog yazısı sayfası
```

## Yerelde deneme

`.htaccess` yerel PHP sunucusunda çalışmadığı için doğrudan `.php` adresleri kullanılır:

```
php -S localhost:8000
# http://localhost:8000/index.php, /galeri.php, /admin/
```
