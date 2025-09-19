

// Pürüzsüz kaydırma efekti ekle
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    
    // Belirli bölüme kaydırma işlemi
    const targetId = this.getAttribute('href').substring(1);  // Hedef bölümü id olarak alıyoruz
    const targetElement = document.getElementById(targetId);
    
    // Hedefe yavaşça kaydırma işlemi
    targetElement.scrollIntoView({
      behavior: 'smooth',  // Yavaşça kaydırmak için smooth davranışı
      block: 'start'       // Hedef bölümün başlangıcına kaydırılmasını sağlıyoruz
    });
  });
});

// Scroll to Top Button Functionality
const scrollToTopBtn = document.getElementById('scrollToTopBtn');

// Sayfa kaydırıldıkça butonu göster/gizle
window.addEventListener('scroll', () => {
  if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
    scrollToTopBtn.style.display = 'block'; // Butonu göster
  } else {
    scrollToTopBtn.style.display = 'none'; // Butonu gizle
  }
});

// Scroll to top butonuna tıklandığında üst kısma kaydır
scrollToTopBtn.addEventListener('click', () => {
  window.scrollTo({ top: 0, behavior: 'smooth' }); // Yavaşça başa kaydır
});


// Sıkça sorulan soruların cevabını açma/kapama
// SSS bölümünde sadece + simgesine ve soruya tıklanınca açılma-kapanma
document.querySelectorAll('.faq-item').forEach(item => {
  const wrapper = item.querySelector('.question-wrapper');
  const answer = item.querySelector('.answer');
  const icon = item.querySelector('.toggle-icon');

  wrapper.addEventListener('click', () => {
    const isOpen = answer.style.display === 'block';

    // Diğer tüm cevapları kapat
    document.querySelectorAll('.answer').forEach(ans => ans.style.display = 'none');
    document.querySelectorAll('.toggle-icon').forEach(icn => icn.textContent = '+');

    if (!isOpen) {
      answer.style.display = 'block';
      icon.textContent = '−';
    } else {
      answer.style.display = 'none';
      icon.textContent = '+';
    }
  });

  icon.addEventListener('click', (e) => {
    e.stopPropagation(); // ikon içindeki tıklamayı yayma
    wrapper.click();     // wrapper'a tıklama tetikle
  });
});





// Görsellere tıklanınca belirli bölümlere kaydırma yapılmasını sağlamak
document.getElementById('image2').addEventListener('click', function() {
  document.getElementById('akademi').scrollIntoView({ behavior: 'smooth' }); // Akademi kısmına kaydırma
});

document.getElementById('image3').addEventListener('click', function() {
  document.getElementById('tesis').scrollIntoView({ behavior: 'smooth' }); // Tesis kısmına kaydırma
});

document.getElementById('image4').addEventListener('click', function() {
  document.getElementById('turnuva').scrollIntoView({ behavior: 'smooth' }); // Turnuva kısmına kaydırma
});

document.getElementById('image5').addEventListener('click', function() {
  document.getElementById('restoran').scrollIntoView({ behavior: 'smooth' }); // Restoran kısmına kaydırma
});

// Bilgilendirme formunu oluşturacak fonksiyon
function showInfoForm(imageId) {
  let title, message, imagePath, hasNext = false, nextTitle = '', nextMessage = '', nextImagePath = '';

  switch(imageId) {
    case 'image7':
      title = "Üye Tipleri";
      message = `
       <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.5; color: #333;"> <strong>Spor İstasyon</strong>, tesisinizdeki tüm kullanıcı rollerini tek bir sistem altında düzenler.<br><br>
        Yönetici, antrenör, üye ve görevli gibi farklı profillerle işletmenizi verimli ve kontrollü bir şekilde yönetebilirsiniz.<br><br>
        Her kullanıcı yalnızca kendi yetki alanına erişir.<br><br>
        Bu yapı, operasyonel verimliliği artırır ve sistem güvenliğini sağlar.</p>
        <p style="font-size: 2.2rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Roller net. Yönetim kolay.</p>
        </div>`;
      imagePath = 'images/üyeyönetimi1.png';
      
      hasNext = true;
      nextTitle = "Üye Detayları";
      nextMessage = `
      <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.4; color: #333;"><strong>Spor İstasyon</strong>, üyelerinizin tüm bilgilerine tek ekran üzerinden ulaşmanızı sağlar.<br><br>
        Üyeye ait borç durumu, hizmet geçmişi, hesap özeti gibi veriler kolayca görüntülenebilir.<br><br>
        Ayrıca kullanıcıların rezervasyon yapma, açık hesap kullanma, lig durumu, yetki düzeyleri ve daha fazlası tek dokunuşla güncellenebilir.<br><br>
        İster yeni hizmet ekleyin, ister alt kullanıcı tanımlayın — tüm işlemler elinizin altında.</p>
        <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Detaylı takip. Yetkili yönetim.</p>
        </div>`;
      nextImagePath = 'images/üyeyönetimi2.png';
      break;
    case 'image8':
      title = "Akademi Yönetimi";
      message = `
      <div class="card-text">
        <p style="font-size: 1.4rem; line-height: 1.5; color: #333;"><strong>Spor İstasyon</strong>, spor akademilerindeki tüm öğrenci, veli ve antrenör ilişkilerini dijital olarak organize etmenizi sağlar.<br><br>
        Veli profiline bağlı <strong>alt kullanıcı</strong> ekleme özelliği ile çocuklar sisteme tanımlanır ve tüm süreç ebeveynler tarafından şeffaf bir şekilde takip edilir.<br><br>
        <strong>Grup oluşturma</strong> özelliği ile yaş gruplarına, seviye veya programa göre sınıflar tanımlanabilir; bu gruplara antrenör atanarak eğitim planlaması yapılabilir.<br><br>
        Her grup, özel ders kategorisi altında parametrik hale getirilir. Özel derslerin ücretleri tanımlanır, ders gerçekleştikçe:<br>
        • Kullanıcılara borç olarak,<br>
        • Antrenörlere ise hakediş olarak yansıtılır.<br><br>
        Üyeler, borçlarını uygulama içinden dijital ödeme sistemleri ile kolayca ödeyebilir. Bu yapı sayesinde tüm finansal akışlar sistemli, şeffaf ve kontrol edilebilir hale gelir.</p>
        <p style="font-size: 1.6rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Kolay takip. Net finans. Dijital akademi.</p>
         </div>`;
      imagePath = 'images/akademiyönetimi1.png';
      break;
    case 'image9':
      title = "Kort Rezervasyonu";
      message = `
      <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.4; color: #333;"><strong>Spor İstasyon</strong>, tüm kort rezervasyonlarını hızlı, pratik ve görsel olarak anlaşılır bir şekilde planlamanızı sağlar.<br><br>
        Kullanıcılar, antrenörler veya yöneticiler tarafından yapılabilen rezervasyonlar; <strong>özel ders, grup dersi, kort kiralama, kort kapatma</strong> ve <strong>misafir</strong> rezervasyonu gibi farklı türlerde tanımlanabilir.<br><br>
        Her rezervasyon tipi <strong>farklı renkte</strong> gösterilir — böylece gün içindeki doluluk durumu ve etkinlik türleri tek bakışta anlaşılır hale gelir.<br><br>
        Rezervasyon oluştururken tarih, saat, rezervasyon türü ve eşleşme seçenekleri seçilir. Ayrıca bir rezervasyon saatine tıklanarak <strong>detaylar yöneticiler tarafından görüntülenebilir</strong> ve yönetilebilir.</p>
        <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Renkli görünüm. Net planlama. Tam kontrol.</p>
         </div>`;
      imagePath = 'images/rezervasyon1.png';
      break;
    case 'image10':
      title = "Grup Oluşturma";
      message = `
      <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.4; color: #333;"><strong>Spor İstasyon</strong>, tüm grup yapılarınızı ve sınıflandırmalarınızı dijital olarak modüler biçimde tanımlamanızı sağlar.<br><br>
        Eğitim programlarına uygun gruplar oluşturabilir, bu gruplara öğrenci ve antrenör atayabilir, grup bazlı dersleri ve ödemeleri yönetebilirsiniz.<br><br>
        Esnek yapı sayesinde grup türleri (özel grup, yaş grubu, performans grubu vs.) kolayca tanımlanabilir ve düzenlenebilir. <br><br>
        Bu yapı, planlamanızı kolaylaştırırken, finansal takibin de gruplar özelinde yapılabilmesini sağlar.</p>
        <p style="font-size: 2.2rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Modüler yapı. Esnek kullanım. Finansal denge.</p>
          </div>`;
      imagePath = 'images/grupolusturma1.png';
      break;
    case 'image12':
      title = "Tesis Parametreleri";
      message = `
      <div class="card-text">
          <p style="font-size: 1.6rem; line-height: 1.4; color: #333;"><strong>Spor İstasyon</strong>, hazır kalıplarla değil, tesisinize özel esneklikle çalışır.<br><br>
          Kort açılış/kapanış saatlerinden, rezervasyon sürelerine; misafir politikalarından, online ödeme seçeneklerine kadar tüm parametreler tesisin ihtiyaçlarına göre özelleştirilebilir.<br><br>
          İster defi ligi olan bir kulüp olun, ister misafir oyuncu kabul eden bir tesis — sistem tüm bu farklılıklara göre yapılandırılır. Her tesis kendi kurallarını koyar, Spor İstasyon bu kurallara uyum sağlar.</p>
          <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Hazır sistem değil. Tesisinize özel çözüm.</p>
           </div>`;
      imagePath = 'images/tesisparametre1.png';
      break;
      case 'image13':
  title = "Raporlama ve Analiz";
  message = `
  <div class="card-text">
    <div style="text-align: center;">
      <img src='images/raporlama1.png' class='fixed-image13-img'>
    </div>
    <p style="font-size: 1.4rem; line-height: 1.3; color: #333;"><strong>Spor İstasyon</strong>, sadece kayıt tutmaz — veriye dayalı yönetim sağlar.<br><br>
    Sistem üzerinden toplanan tüm <strong>veriler</strong>, anlamlı ve okunabilir raporlar haline getirilerek yöneticilere sunulur. Böylece tesisinizde neler olup bittiğini sezgilerle değil, <strong>gerçek sayılarla</strong> görebilirsiniz.<br><br>
    Tüm raporlar <strong>tablo ve grafiklerle</strong> desteklenir, karşılaştırmalı analizler yapılabilir. İster antrenör performansını ölçün, ister işletmenizin yoğunluk haritasını çıkarın — tüm veriler elinizin altında.</p>
    <p style="font-size: 2.0rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">Veriye dayalı kararlar. Güçlü işletme yönetimi.</p>
    </div>`;
  imagePath = '';
  break;


        case 'image14':
        title = "Tesis Parametreleri";
        message = `
        <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.5; color: #333;">
        <strong>Spor İstasyon</strong>, tesisinizin tüm finansal hareketlerini tek panelden yönetmenizi sağlar.<br><br>

        Hizmet türüne göre tanımlanabilen <strong>gelir kalemleri</strong>, örneğin: “Özel Ders - 200₺”, doğrudan hizmet işlemine bağlanır. Böylece bir antrenöre özel ders atandığında, sistem bu dersi gelir olarak otomatik kaydeder.<br><br>

        Benzer şekilde <strong>gider kalemleri</strong> de (kira, maaş, SGK, elektrik vb.) sisteme eklenebilir ve periyodik olarak güncellenebilir.<br><br>

        Tüm <strong>gelir ve gider</strong> hareketleri, tarih aralığına göre filtrelenebilir ve <strong>kar-zarar tablosu</strong> anında oluşturulabilir. Bu yapı sayesinde:<br>
        • <strong>Kâr marjınızı net</strong> olarak görebilir,<br>
        • <strong>Gelir kaynaklarınızı analiz</strong> edebilir,<br>
        • <strong>Gereksiz giderleri hızlıca tespit</strong> edebilirsiniz.
        </p>
        <p style="font-size: 1.7rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">
        Finansal kontrol sizde. Kayıp yok, sürpriz yok.
        </p>
        </div>`;
        imagePath = 'images/gelirgider1.png';
        break;
        case 'image15':
        title = "Tesis Parametreleri";
        message = `
        <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.4; color: #333;">
        <strong>Spor İstasyon</strong>, antrenör ve personel yönetimini sadece kullanıcı eklemekle bırakmaz — finansal süreçleri de uçtan uca kontrol altına alır.<br><br>
        Sisteme <strong>personel, antrenör</strong> gibi özel rollerle kullanıcı tanımlanabilir. Her personele özel hizmet başı ücret belirlenir ve bu ücretler, yapılan ders ya da hizmet sayısına göre otomatik olarak hakediş olarak hesaplanır.<br><br>
        Böylece:
        </p>
        <ul style="font-size: 1.8rem; line-height: 2.2; color: #333; margin-top: 10px; margin-bottom: 10px;">
        <li>Antrenör bazında ders sayısı ve toplam tutar izlenebilir,</li>
        <li>Hakedişler düzenli olarak görüntülenebilir,</li>
        <li>Kişiye <strong>özel fiyatlandırma</strong> yapılabilir,</li>
        <li>Tüm bu veriler, gider kalemlerine entegre edilerek <strong>finansal tabloya</strong> otomatik yansıtılır.</li>
        </ul>
        <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">
        İş yükü azalır, karışıklık sona erer. Herkes hak ettiğini alır.
        </p>
        </div>`;
        imagePath = 'images/finans1.png';
  break;
  case 'image17':
    title = "Dijital Menü Oluşturma";
    message = `
    <div class="card-text">
      <p style="font-size: 1.6rem; line-height: 1.4; color: #333;">
        <strong>Spor İstasyon</strong>, tesisinizdeki yeme-içme hizmetlerini de dijitalleştiriyor.<br><br>
        İşletme sahipleri kendi kategorilerini (örneğin Kahvaltı, Tatlılar, Izgaralar) <strong>ürünlerini ve görsellerini kolayca sisteme yükleyebilir</strong>. Ürün fiyatlarını diledikleri zaman <strong>güncelleyebilir</strong> ve müşterilere sunulacak şık, kullanıcı dostu bir <strong>dijital menü</strong> oluşturabilirler.<br><br>
        Bu yapı sayesinde:
      </p>
      <ul style="font-size: 1.6rem; line-height: 1.4; color: #333; margin-top: 10px;">
        <li>Menü sürekli <strong>güncel</strong> kalır,</li>
        <li>Müşteriler şeffaf ve net bir biçimde sipariş verir,</li>
        <li>Kategoriler arasında geçişler <strong>hızlı ve estetik</strong> olur.</li>
      </ul>
      <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">
        Dijital menüyle menünüz de profesyonel.
      </p>
      </div>`;
    imagePath = 'images/menü1.png';
    break;
    case 'image18':
      title = "Dijital Ödeme ve Ödeme Seçenekleri";
      message = `
      <div class="card-text">
        <p style="font-size: 1.6rem; line-height: 1.9; color: #333;">
          <strong>Spor İstasyon</strong>, kullanıcı deneyimini en üst düzeye çıkaran dijital ödeme sistemiyle fark yaratır.<br><br>
          Kullanıcılar:
          <ul style="font-size: 1.6rem; line-height: 2; margin-top: 10px; margin-bottom: 10px; color: #333;">
            <li><strong>Kartlarını kaydedebilir</strong>,</li>
            <li><strong>Dijital cüzdanlarına</strong> bakiye yükleyebilir,</li>
            <li><strong>Bekleyen ödemelerini</strong> kolayca takip edebilir,</li>
            <li><strong>Geçmiş siparişlerini</strong> detaylı olarak inceleyebilir.</li>
          </ul>
        </p>
        <p style="font-size: 1.8rem; line-height: 2; margin-top: 10px; color: #444;">
          Örneğin; kullanıcı kortta tenis oynarken mobil uygulama üzerinden siparişini verir, sporu bittiğinde ise ürününü <strong>hazır</strong> şekilde kafeteryadan <strong>teslim alır</strong>. Ne kasa sırası, ne zaman kaybı.
        </p>
        <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">
          Temassız, zahmetsiz, hızlı.
        </p>
          </div>`;
      imagePath = 'images/ödeme1.png';
      break;
      case 'image19':
  title = "Promosyon ve Kampanyalar";
  message = `
    <div class="card-text">
    <p style="font-size: 1.6rem; line-height: 1.9; color: #333;">
      <strong>Sporistasyon</strong>, sadece antrenman ve rezervasyon süreçlerini değil, aynı zamanda işletmenize değer katan pazarlama araçlarını da sizin için sunuyor. Promosyon ve kampanya modülü sayesinde:
    </p>
    <ul style="font-size: 1.6rem; line-height: 2; margin-top: 10px; margin-bottom: 10px; color: #333;">
      <li>Belirli kullanıcı gruplarına <strong>özel teklifler</strong> tanımlanabilir (örn. sadakat programı, üyelik tipi, antrenman sıklığı vb.).</li>
      <li>Tüm kullanıcıları kapsayan <strong>dönemsel kampanyalar</strong> (örn. "10 kahve al, 1 kahve bizden" gibi) oluşturulabilir.</li>
      <li>Kullanıcılar bu kampanyaları uygulama üzerinden <strong>takip edebilir</strong>, ne kadar hak kazandıklarını ve ne zaman ödül alacaklarını net şekilde görebilir.</li>
      <li><strong>Kampanyalara özel görsel ve metinler</strong> eklenebilir, kullanıcıların ilgisi artırılabilir.</li>
    </ul>
    <p style="font-size: 1.6rem; line-height: 1.9; color: #333; margin-top: 10px;">
      Bu sayede işletmeler hem müşteri memnuniyetini hem de tekrar kullanım oranlarını artırabilir.
    </p>
    <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 2rem;">
      Sporistasyon ile yalnızca sahada değil, işletmenin her köşesinde fark yaratın!
    </p>
    </div>`;
  imagePath = 'images/promosyon1.png';
  break;
  case 'image20':
    title = "Stok Yönetimi";
    message = `
    <div class="card-text">
      <p style="font-size: 1.6rem; line-height: 1.9; color: #333;">
        <strong>Sporistasyon</strong>, restoran işletmeleri için geliştirilen <strong>stok yönetimi</strong> modülüyle ürün takibini zahmetsiz hale getirir.
        İşletmeler, menülerine ekledikleri her ürünü kolayca <strong>stokla ilişkilendirir</strong>. Sipariş verildikçe stok miktarı otomatik olarak düşer ve işletmeye güncel durum anlık olarak yansıtılır.
        Bu sayede hem <strong>eksik stok</strong> hem de <strong>fazla alım</strong> problemlerinin önüne geçilir.
      </p>
      <p style="font-size: 1.8rem; font-weight: 900; color:rgb(0, 0, 0); margin-top: 1rem;">
        Sporistasyon ile restoran operasyonları, siparişten stok takibine kadar tek bir ekranda.
      </p>
      </div>`;
    imagePath = 'images/stokyönetimi1.png';
    break;
    case 'image22':
  title = "Turnuva Oluşturma";
  message = `
  <div class="card-text">
    <p><strong>Spor İstasyon</strong>, turnuva düzenlemeyi zahmetli bir süreç olmaktan çıkarır. <strong>Hazır parametreler</strong> üzerinden kolayca turnuva oluşturabilir, her detayı özelleştirebilirsiniz.</p>
    <p><strong>Turnuva tipi</strong> (grup/eleminasyon), tekli veya çiftli format, katılım ücreti, zemin tipi, set sayısı, oyuncu seviyesi ve daha fazlası tamamen <strong>sizin kontrolünüzdedir</strong>.</p>
    <p>Katılımcılar ve eşleşmeler sistem tarafından objektif, <strong>matematiksel bir algoritma</strong> ile otomatik atanır. Böylece her tur adil, dengeli ve <strong>karışıklık yaşanmadan</strong> ilerler.</p>
    <p><strong>Fiyatlandırma</strong> yapabilir, maksimum oyuncu sayısını belirleyebilir ve <strong>tüm süreci tek ekran üzerinden</strong> yönetebilirsiniz.</p>
    <p style="font-size:1.8rem; margin-top: 20px; font-weight:bold; color:rgb(0, 0, 0);">Turnuva düzenlemek artık saniyeler sürüyor.</p>
  </div>`;
  imagePath = 'images/turnuvaolusturma1.png';
  break;
  case 'image23':
  title = "Turnuva Katılımı";
  message = `
  <div class="card-text">
    <div class="form-text-wrapper" style="font-size:1.5rem; line-height:1.2;">
      Yeni bir turnuva eklendiğinde üyeler anında bilgilendirilir — <b>uygulama içi bildirimler</b> ve uyarılar sayesinde <b>hiçbir gelişme kaçmaz</b>.<br><br>
      <b>Kullanıcılar</b>, mevcut tüm turnuvaları detaylarıyla görüntüleyebilir. Turnuva tipi, zemin, set sayısı, katılım ücreti, oyuncu sayısı gibi <b>tüm bilgiler açıkça listelenir</b>.<br><br>
      <b>Aktif, geçmiş ve pasif turnuvalar filtrelenebilir</b>. Dilerseniz "Turnuvaya Katıl" butonuyla kayıt olabilir, <b>ödeme işlemini</b> çevrimiçi olarak kolayca tamamlayabilirsiniz.<br><br>
      Bu yapı sayesinde:
    </div>
    <ul class="form-text-wrapper" style="font-size:1.5rem; line-height:1.4; margin-left:2rem;">
      <li>Her turnuva <b>şeffaf</b> bir şekilde duyurulur,</li>
      <li>Katılım süreci <b>dijitale</b> taşınır,</li>
      <li>Kulüplerin organizasyonları daha <b>profesyonel</b> hale gelir.</li>
    </ul>
    <p style="font-weight:bold; color:rgb(0, 0, 0); font-size:1.6rem; margin-top:20px;">
      Dijital katılım. Anlık bilgilendirme. Şeffaf organizasyon.
    </p>
  </div>`;
  imagePath = 'images/turnuvakatılım1.png';
  break;

case 'image24':
  title = "Turnuva Süreci ve Maç Takibi";
  message = `
  <div class="card-text">
    <div class="form-text-wrapper" style="font-size:1.5rem; line-height:1.3;">
      <b>Spor İstasyon</b>, yalnızca turnuva oluşturmakla kalmaz tüm süreci uçtan uca yönetmenizi sağlar.<br><br>
      Turnuva başladıktan sonra; <b>grup eşleşmeleri</b>, <b>fikstürler</b>, <b>maç skorları</b> ve <b>puan durumu</b> gibi tüm veriler sistem tarafından anlık olarak işlenir. Oyuncuların toplam maç sayısı, galibiyet-mağlubiyet istatistikleri, <b>averajları</b> ve <b>puanları</b> net şekilde sunulur.<br><br>
      <b>Katılımcılar:</b>
    </div>
    <ul class="form-text-wrapper" style="font-size:1.5rem; line-height:1.3; margin-left:2rem;">
      <li>Kendi performanslarını ve grup sıralamasını <b>takip edebilir</b>,</li>
      <li>Oynanmış ve oynanacak <b>maçları detaylarıyla</b> görebilir.</li>
    </ul>
    <div class="form-text-wrapper" style="font-size:1.5rem; line-height:1.3; margin-top: 1rem;">
      <b>Yöneticiler:</b>
    </div>
    <ul class="form-text-wrapper" style="font-size:1.5rem; line-height:1.3; margin-left:2rem;">
      <li><b>Maç skorlarını</b> sisteme girer veya <b>günceller</b>,</li>
      <li><b>Maç rezervasyonlarını</b> doğrudan sistem üzerinden atar,</li>
      <li>Eksik karşılaşmaları kolayca <b>organize eder</b>.</li>
    </ul>
    <p style="font-weight:bold; color:rgb(0, 0, 0); font-size:1.8rem; margin-top:20px;">
      Spor İstasyon ile rekabet şeffaf, yönetim kolay.
    </p>
   </div>`;
  imagePath = 'images/turnuvasüreci1.png';
  break;


      
      

  


          
      
    
    default:
      title = "Bilinmeyen Bölüm";
      message = "Bu görsele özel bilgilendirme metni bulunmamaktadır.";
      imagePath = null;
  }

  const formSection = document.createElement('section');
  formSection.classList.add('info-form-container');
  formSection.setAttribute('data-image-id', imageId);
  formSection.innerHTML = `
    <div class="info-form">
      <button class="close-form" style="position: absolute; top: 10px; right: 15px; font-size: 3rem; background: none; border: none; color: #444; cursor: pointer;">&times;</button>
      <h2>${title}</h2>
      <div style="display: flex; gap: 30px; align-items: center; flex-wrap: wrap; max-width: 100%;">
        <div style="flex: 1;">${message}</div>
        ${imagePath ? `<img src="${imagePath}" style="max-width: 100%; width: 250px; height: auto; border-radius: 12px; flex-shrink: 0;" class="responsive-form-image">` : ''}


      </div>
      ${hasNext ? '<button class="next-form">İleri →</button>' : ''}
    </div>
  `;
  document.body.appendChild(formSection);
  

  formSection.querySelector('.close-form').addEventListener('click', () => {
    document.body.removeChild(formSection);
  });

  if (hasNext) {
    formSection.querySelector('.next-form').addEventListener('click', () => {
      const updated = document.createElement('section');
      updated.classList.add('info-form-container');
      updated.innerHTML = `
        <div class="info-form">
          <button class="close-form" style="position: absolute; top: 10px; right: 15px; font-size: 3rem; background: none; border: none; color: #444; cursor: pointer;">&times;</button>
          <h2>${nextTitle}</h2>
          <div style="display: flex; gap: 30px; align-items: center; flex-wrap: wrap; max-width: 100%;">
            <div style="flex: 1;">${nextMessage}</div>
            ${nextImagePath ? `<img src="${nextImagePath}" style="max-width: 500px; width: 100%; height: auto; border-radius: 12px; flex-shrink: 0;">` : ''}
          </div>
          <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <button class="back-form">← Geri</button>
          </div>
        </div>
      `;
      document.body.removeChild(formSection);
      document.body.appendChild(updated);
  
      updated.querySelector('.close-form').addEventListener('click', () => {
        document.body.removeChild(updated);
        document.body.style.overflow = 'auto';
      });
  
      // 🔽 Bu kısmı buraya EKLE:
      updated.querySelector('.back-form').addEventListener('click', () => {
        document.body.removeChild(updated);
        showInfoForm(imageId); // ilk formu geri çağır
      });
  
      // Scroll'u kapat
      document.body.style.overflow = 'hidden';
    });
  }
  
}






// Görselleri seçme ve tıklama işlemi
const imageIds = [
  'image7', 'image8', 'image9', 'image10', 
  'image12', 'image13', 'image14', 'image15', 
  'image17', 'image18', 'image19', 'image20',
  'image22', 'image23', 'image24'  // 🆕 Burası ekleniyor
];


imageIds.forEach(imageId => {
  const image = document.getElementById(imageId);
  if (image) {
    image.addEventListener('click', () => showInfoForm(imageId));
  }
});

// Video bittiğinde fotoğrafı göster
const introVideo = document.getElementById('introVideo');
const kampanyaImage = document.getElementById('kampanyaImage');

introVideo.addEventListener('ended', () => {
  // Önce videoyu tamamen gizle
  introVideo.style.display = 'none';
  
  // Sonra resmi görünür yap
  kampanyaImage.style.display = 'block';
  
  // Hafif geçiş efekti olsun istersen:
  kampanyaImage.style.opacity = '0';
  setTimeout(() => {
    kampanyaImage.style.transition = 'opacity 1s ease';
    kampanyaImage.style.opacity = '1';
  }, 50); 
});
const sections = document.querySelectorAll("section[id]");
const navLinks = document.querySelectorAll("nav ul li a");

window.addEventListener("scroll", () => {
  let current = "";

  sections.forEach(section => {
    const sectionTop = section.offsetTop - 100;
    if (scrollY >= sectionTop) {
      current = section.getAttribute("id");
    }
  });

  navLinks.forEach(link => {
    link.classList.remove("active");
    if (link.getAttribute("href") === `#${current}`) {
      link.classList.add("active");
    }
  });
});
// Hamburger menü işlevselliği
const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");

hamburger.addEventListener("click", () => {
  navMenu.classList.toggle("active");
});

// Menü dışına tıklayınca kapatma
document.addEventListener('click', (event) => {
  const isClickInside = hamburger.contains(event.target) || navMenu.contains(event.target);

  if (!isClickInside) {
    navMenu.classList.remove('active');
  }
});

// Menü linklerine tıklayınca menüyü kapatma
document.querySelectorAll('.nav-menu a').forEach(link => {
  link.addEventListener('click', () => {
    navMenu.classList.remove('active');
  });
});