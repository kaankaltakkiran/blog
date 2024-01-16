# Blog Uygulaması
 ## Proje  öyküsü
 Php ile hazırlanmış bir blog sitesidir. Kullanıcılar isterse anasayfada ki tüm blogları görüp istediği bloğu seçip okuyabilir ya da navbar da yer alan kategori seçeneğine göre veya yazar isminie göre seçim yapabilir.

Admin yetkisindeki kişi siteye **admin** ekleyebilir, **yazar** ekleyebilir ve blog **kategori türü** ekleyebilir.

Writer yetkisindeki kişi ise blog **ekleyebilir** ve eklediği bloğu **düzenleyebilir**.

 ## Proje nasıl kullanılır?
- [ ] Proje clone edilir.
- [ ] Database klasöründe ki sql dosyası database import edilir.
- [ ] Projedeki db.php dosyasındaki bilgiler doğru şekilde doldurulur.
- [X] Kullanıma hazır.
      
   > **Note:**  Bu web sitesini, bu dosyadaki **kullanıcı giriş bilgileriyle**  veya **kendiniz oluşturduğunuz hesap bilgileri**  ile kullanabilirsiniz.
   
   > **Note:**  Sayfada blog ekleme kısmı  giriş yaptıktan sonra navbarda yer alan  örneğin **hello kaan**  yazan dropdown list altında yer alan **add blog** bölümündedir.

   > **Note:**  Sayfada profile  kısmı  giriş yaptıktan sonra navbarda yer alan  örneğin **hello kaan**  yazan dropdown list altında yer alan **profile** bölümündedir.

 ## Kullanıcı Bilgileri
 

| Users               |Email                          |Password                         |
|----------------|-------------------------------|-----------------------------|
|User Admin|         `admin@gmail.com`              |`admin`          |
|User Ahmet          |`ahmet@gmail.com`            |`123`           |
|User Kaan          |`kaan_fb_aslan@hotmail.com`  |`123`

 > **Note:** /admin yazarak admin login sayfasına ulaşabilirsiniz.

  > **Note:** /writer yazarak writer login sayfasına ulaşabilirsiniz.

 ## Proje eksikleri
-  [ ] writer tablosu ile users tablosu ayrı mı olmalı ?
-  [X] Yazar istediği bloğu silmeli.(Tekil silme var toplu silme ? yada başka sayfalarda)
 - [X] Kullanıcı profil sayfası eklenmeli.(Eklendi fakat sadece şuan şifre değiştirme ekranı var.)
 - [ ] Sayfanın frontend tasırımı sürdürülebilir yapıda değil. Yeniden tasarlanmalı çünkü bloglar eklendikçe blogların olduğu bölümde bloglar ve kategoriler uyumlu gözükmemektedir.
 - [ ] Genel olarak resim boyutları gözden geçirilebilir.
 - [ ] Tekil blog sayfasındaki tasarım gözden geçirilmeli(yan taraflar çok boş kaldı).

 ## Yapılan Temel Geliştirmeler
 - [X] **Admin**, /admin yazarak admin login sayfasına gider ve login olur. Login olduktan sonra kendisinin yapabileceği işlemleri görür.
 - [X] Eğer rolü olmayan ve rolü dışında adminin yaptığı işlemlere erişmeye çalışırsa **yetkilendirme hatası**  meydana geliyor.
 - [X] **Yazar**, /writer yazarak writer login sayfasına gider ve login olur. Login olduktan sonra kendisinin yapabileceği işlemleri görür.
 - [X] **Yazar**, blog ekledikten sonra kendi yazarlar sekmesine gelerek kendi bloğunu düzenleyebilir. Her yazar kendi bloğunu düzenler. Başkasının bloğunu düzenlemeye çalışırsa hata alır.
 - [X] **Yazar**, blog eklerken eğer **Publish My Blog** seçeneğini seçmezse bloğu yayınlanmaz.
 - [X] **Yazar**, tüm bloglarını görebilirken kullanıcılar ise sadece görmesi gereken blogları görmektedir.
 - [X] Yazar, blog eklerken seçtiği tarihler günümüz tarihleri arasında değilse yayınlanmaz
 - [X] Eğer yazarın içeriği yoksa uyarı mesajı ile karşılaşır ve o boş sayfaya yönlenmez.
 - [X] Navbar kısmında blog başlığına göre arama sonucu getiren **blog arama bölümü**  bulunmaktadır.
 - [X] Adminler veya yazar profile sekmesinden **şifrelerini değiştirebilirler** .
 - [X] Yazar, kendi blog sayfasında en son aktif olduğu zamanı görür ve sonrasında güncellenir.




      
## Site Resimleri
https://github.com/kaankaltakkiran/php-proje-resimleri/tree/main/blog_resimler
