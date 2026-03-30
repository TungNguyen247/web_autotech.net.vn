<?php
/**
 * index.php — Dynamic homepage.
 * Replaces index.html as the primary entry point.
 * Preserves all existing front-end CSS/JS behaviour.
 */

require_once __DIR__ . '/includes/helpers.php';
bootstrap();
session_start_secure();

// Load active products grouped by category
$products   = [];
$categories = [];

try {
    $pdo = db();

    $cats = $pdo->query(
        'SELECT id, name_vi, name_en FROM categories ORDER BY id'
    )->fetchAll();

    foreach ($cats as $cat) {
        $categories[$cat['id']] = $cat;
    }

    $stmt = $pdo->prepare(
        'SELECT id, category_id, name_vi, name_en, short_desc_vi, short_desc_en,
                price, image_path
         FROM products
         WHERE is_active = 1
         ORDER BY category_id, id'
    );
    $stmt->execute();
    $products = $stmt->fetchAll();
} catch (Exception $e) {
    // Gracefully degrade — page still renders with static content
    $products   = [];
    $categories = [];
}

// Flash message from contact_submit.php redirect
$contactFlash = get_flashes();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Autotech - Công ty chuyên cung cấp biến tần, PLC, HMI và giải pháp tự động hóa dây chuyền chuyên nghiệp tại Việt Nam">
  <meta name="keywords" content="biến tần, inverter, PLC, HMI, tự động hóa, automation, Autotech">
  <meta name="robots" content="index, follow">
  <meta property="og:title" content="Autotech - Công nghệ Tự động hóa & Biến tần">
  <meta property="og:description" content="Autotech - Công ty chuyên cung cấp biến tần, PLC, HMI và giải pháp tự động hóa dây chuyền chuyên nghiệp tại Việt Nam">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= h(defined('APP_URL') ? APP_URL : '') ?>">
  <link rel="canonical" href="<?= h(defined('APP_URL') ? APP_URL : '') ?>">
  <title>Autotech - Công nghệ Tự động hóa & Biến tần</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!-- Main CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

  <!-- ========== NAVBAR ========== -->
  <header class="navbar" id="navbar">
    <div class="container navbar__inner">
      <a href="#home" class="navbar__logo" aria-label="Autotech">
        <span class="navbar__logo-icon">⚙️</span>
        <span class="navbar__logo-text">Autotech</span>
      </a>

      <nav class="navbar__nav" aria-label="Main navigation">
        <ul class="navbar__list">
          <li><a href="#home"     class="navbar__link" data-vi="Trang chủ"   data-en="Home">Trang chủ</a></li>
          <li><a href="#products" class="navbar__link" data-vi="Sản phẩm"    data-en="Products">Sản phẩm</a></li>
          <li><a href="#services" class="navbar__link" data-vi="Dịch vụ"     data-en="Services">Dịch vụ</a></li>
          <li><a href="#about"    class="navbar__link" data-vi="Về chúng tôi" data-en="About Us">Về chúng tôi</a></li>
          <li><a href="#download" class="navbar__link" data-vi="Tải xuống"   data-en="Download">Tải xuống</a></li>
          <li><a href="#faq"      class="navbar__link" data-vi="Hỏi đáp"     data-en="FAQ">Hỏi đáp</a></li>
          <li><a href="#contact"  class="navbar__link" data-vi="Liên hệ"     data-en="Contact">Liên hệ</a></li>
        </ul>
      </nav>

      <div class="navbar__actions">
        <button class="lang-btn" id="langBtn" aria-label="Switch language">
          <span class="lang-btn__flag">🇻🇳</span>
          <span class="lang-btn__text" id="langText">VI</span>
        </button>
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>

    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
      <ul class="mobile-menu__list">
        <li><a href="#home"     class="mobile-menu__link" data-vi="Trang chủ"   data-en="Home">Trang chủ</a></li>
        <li><a href="#products" class="mobile-menu__link" data-vi="Sản phẩm"    data-en="Products">Sản phẩm</a></li>
        <li><a href="#services" class="mobile-menu__link" data-vi="Dịch vụ"     data-en="Services">Dịch vụ</a></li>
        <li><a href="#about"    class="mobile-menu__link" data-vi="Về chúng tôi" data-en="About Us">Về chúng tôi</a></li>
        <li><a href="#download" class="mobile-menu__link" data-vi="Tải xuống"   data-en="Download">Tải xuống</a></li>
        <li><a href="#faq"      class="mobile-menu__link" data-vi="Hỏi đáp"     data-en="FAQ">Hỏi đáp</a></li>
        <li><a href="#contact"  class="mobile-menu__link" data-vi="Liên hệ"     data-en="Contact">Liên hệ</a></li>
      </ul>
    </div>
  </header>

  <main>

    <!-- ========== HERO SECTION ========== -->
    <section class="hero" id="home">
      <div class="hero__bg-shapes">
        <div class="hero__shape hero__shape--1"></div>
        <div class="hero__shape hero__shape--2"></div>
        <div class="hero__shape hero__shape--3"></div>
      </div>
      <div class="container hero__content">
        <div class="hero__text aos-fade-up">
          <span class="hero__badge" data-vi="Công nghệ Tự động hóa" data-en="Automation Technology">Công nghệ Tự động hóa</span>
          <h1 class="hero__title">
            <span data-vi="Giải pháp tự động hóa" data-en="Automation Solutions">Giải pháp tự động hóa</span><br>
            <span class="hero__title--gradient" data-vi="& Biến tần chuyên nghiệp" data-en="& Professional Inverter">& Biến tần chuyên nghiệp</span>
          </h1>
          <p class="hero__description"
             data-vi="Autotech cung cấp thiết bị và giải pháp tự động hóa công nghiệp hàng đầu — biến tần, PLC, HMI và hệ thống tự động hóa dây chuyền. Đối tác tin cậy của doanh nghiệp Việt."
             data-en="Autotech provides leading industrial automation equipment and solutions — inverters, PLCs, HMIs and conveyor automation systems. The trusted partner for Vietnamese businesses.">
            Autotech cung cấp thiết bị và giải pháp tự động hóa công nghiệp hàng đầu — biến tần, PLC, HMI và hệ thống tự động hóa dây chuyền. Đối tác tin cậy của doanh nghiệp Việt.
          </p>
          <div class="hero__buttons">
            <a href="#products" class="btn btn--primary" data-vi="Xem sản phẩm" data-en="View Products">
              <i class="fas fa-th-large" aria-hidden="true"></i> Xem sản phẩm
            </a>
            <a href="#contact" class="btn btn--outline" data-vi="Liên hệ ngay" data-en="Contact Now">
              <i class="fas fa-phone" aria-hidden="true"></i> Liên hệ ngay
            </a>
          </div>
          <div class="hero__stats">
            <div class="hero__stat">
              <strong>10+</strong>
              <span data-vi="Năm kinh nghiệm" data-en="Years experience">Năm kinh nghiệm</span>
            </div>
            <div class="hero__stat-divider"></div>
            <div class="hero__stat">
              <strong>500+</strong>
              <span data-vi="Dự án hoàn thành" data-en="Projects completed">Dự án hoàn thành</span>
            </div>
            <div class="hero__stat-divider"></div>
            <div class="hero__stat">
              <strong>200+</strong>
              <span data-vi="Khách hàng tin dùng" data-en="Trusted customers">Khách hàng tin dùng</span>
            </div>
          </div>
        </div>
        <div class="hero__visual aos-fade-left">
          <div class="hero__card-group">
            <div class="hero__card">
              <i class="fas fa-bolt" aria-hidden="true"></i>
              <span data-vi="Biến tần" data-en="Inverter">Biến tần</span>
            </div>
            <div class="hero__card">
              <i class="fas fa-microchip" aria-hidden="true"></i>
              <span>PLC / HMI</span>
            </div>
            <div class="hero__card">
              <i class="fas fa-industry" aria-hidden="true"></i>
              <span data-vi="Tự động hóa" data-en="Automation">Tự động hóa</span>
            </div>
            <div class="hero__card">
              <i class="fas fa-tools" aria-hidden="true"></i>
              <span data-vi="Tư vấn" data-en="Consulting">Tư vấn</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========== ABOUT SECTION ========== -->
    <section class="about section" id="about">
      <div class="container">
        <div class="section__header aos-fade-up">
          <span class="section__label" data-vi="Về chúng tôi" data-en="About Us">Về chúng tôi</span>
          <h2 class="section__title" data-vi="Autotech — Đối tác công nghệ đáng tin cậy" data-en="Autotech — Your Trusted Technology Partner">
            Autotech — Đối tác công nghệ đáng tin cậy
          </h2>
          <p class="section__subtitle"
             data-vi="Chúng tôi tự hào là đơn vị tiên phong trong lĩnh vực cung cấp thiết bị và giải pháp tự động hóa công nghiệp tại Việt Nam."
             data-en="We are proud to be a pioneer in providing industrial automation equipment and solutions in Vietnam.">
            Chúng tôi tự hào là đơn vị tiên phong trong lĩnh vực cung cấp thiết bị và giải pháp tự động hóa công nghiệp tại Việt Nam.
          </p>
        </div>

        <div class="about__grid">
          <div class="about__text aos-fade-right">
            <p data-vi="Autotech được thành lập với sứ mệnh mang đến những giải pháp tự động hóa hiện đại, giúp doanh nghiệp Việt Nam tối ưu hóa quy trình sản xuất, nâng cao năng suất và giảm chi phí vận hành."
               data-en="Autotech was founded with the mission of bringing modern automation solutions, helping Vietnamese businesses optimize production processes, increase productivity and reduce operating costs.">
              Autotech được thành lập với sứ mệnh mang đến những giải pháp tự động hóa hiện đại, giúp doanh nghiệp Việt Nam tối ưu hóa quy trình sản xuất, nâng cao năng suất và giảm chi phí vận hành.
            </p>
            <p data-vi="Với đội ngũ kỹ sư giàu kinh nghiệm và am hiểu sâu về công nghệ, chúng tôi cung cấp các sản phẩm chất lượng cao từ các thương hiệu hàng đầu thế giới cùng dịch vụ tư vấn, lắp đặt và bảo trì chuyên nghiệp."
               data-en="With a team of experienced engineers with deep technology knowledge, we provide high-quality products from the world's leading brands along with professional consulting, installation and maintenance services.">
              Với đội ngũ kỹ sư giàu kinh nghiệm và am hiểu sâu về công nghệ, chúng tôi cung cấp các sản phẩm chất lượng cao từ các thương hiệu hàng đầu thế giới cùng dịch vụ tư vấn, lắp đặt và bảo trì chuyên nghiệp.
            </p>
            <a href="#contact" class="btn btn--primary" data-vi="Tìm hiểu thêm" data-en="Learn More">
              <i class="fas fa-arrow-right" aria-hidden="true"></i> Tìm hiểu thêm
            </a>
          </div>

          <div class="about__features aos-fade-left">
            <div class="about__feature">
              <div class="about__feature-icon"><i class="fas fa-medal" aria-hidden="true"></i></div>
              <div class="about__feature-content">
                <h3 data-vi="Kinh nghiệm 10+ năm" data-en="10+ Years Experience">Kinh nghiệm 10+ năm</h3>
                <p data-vi="Hơn 10 năm hoạt động trong ngành tự động hóa công nghiệp" data-en="Over 10 years of operation in industrial automation">Hơn 10 năm hoạt động trong ngành tự động hóa công nghiệp</p>
              </div>
            </div>
            <div class="about__feature">
              <div class="about__feature-icon"><i class="fas fa-certificate" aria-hidden="true"></i></div>
              <div class="about__feature-content">
                <h3 data-vi="Sản phẩm chính hãng" data-en="Genuine Products">Sản phẩm chính hãng</h3>
                <p data-vi="Phân phối chính thức từ các thương hiệu hàng đầu thế giới" data-en="Official distribution from the world's top brands">Phân phối chính thức từ các thương hiệu hàng đầu thế giới</p>
              </div>
            </div>
            <div class="about__feature">
              <div class="about__feature-icon"><i class="fas fa-users" aria-hidden="true"></i></div>
              <div class="about__feature-content">
                <h3 data-vi="Đội ngũ chuyên nghiệp" data-en="Professional Team">Đội ngũ chuyên nghiệp</h3>
                <p data-vi="Kỹ sư được đào tạo bài bản, có chứng chỉ quốc tế" data-en="Well-trained engineers with international certifications">Kỹ sư được đào tạo bài bản, có chứng chỉ quốc tế</p>
              </div>
            </div>
            <div class="about__feature">
              <div class="about__feature-icon"><i class="fas fa-handshake" aria-hidden="true"></i></div>
              <div class="about__feature-content">
                <h3 data-vi="Uy tín & Tin cậy" data-en="Reputable & Trustworthy">Uy tín &amp; Tin cậy</h3>
                <p data-vi="Hơn 200 khách hàng doanh nghiệp tin tưởng lựa chọn" data-en="Over 200 enterprise customers choose and trust us">Hơn 200 khách hàng doanh nghiệp tin tưởng lựa chọn</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========== PRODUCTS SECTION ========== -->
    <section class="products section section--gray" id="products">
      <div class="container">
        <div class="section__header aos-fade-up">
          <span class="section__label" data-vi="Sản phẩm" data-en="Products">Sản phẩm</span>
          <h2 class="section__title" data-vi="Sản phẩm & Dịch vụ" data-en="Products & Services">Sản phẩm &amp; Dịch vụ</h2>
          <p class="section__subtitle"
             data-vi="Chúng tôi cung cấp đầy đủ thiết bị và giải pháp tự động hóa công nghiệp cho mọi quy mô doanh nghiệp."
             data-en="We provide complete industrial automation equipment and solutions for businesses of all sizes.">
            Chúng tôi cung cấp đầy đủ thiết bị và giải pháp tự động hóa công nghiệp cho mọi quy mô doanh nghiệp.
          </p>
        </div>

        <div class="products__grid" id="services">
<?php if (!empty($products)): ?>
<?php
  $delay = 0;
  foreach ($products as $prod):
    $imgSrc = !empty($prod['image_path'])
        ? h($prod['image_path'])
        : null;
?>
          <article class="product-card aos-fade-up" style="--delay: <?= $delay ?>s">
            <div class="product-card__icon">
              <i class="fas fa-bolt" aria-hidden="true"></i>
            </div>
<?php if ($imgSrc): ?>
            <img src="<?= $imgSrc ?>" alt="<?= h($prod['name_vi']) ?>" class="product-card__img" loading="lazy">
<?php endif; ?>
            <h3 class="product-card__title"
                data-vi="<?= h($prod['name_vi']) ?>"
                data-en="<?= h($prod['name_en']) ?>"><?= h($prod['name_vi']) ?></h3>
            <p class="product-card__desc"
               data-vi="<?= h($prod['short_desc_vi'] ?? '') ?>"
               data-en="<?= h($prod['short_desc_en'] ?? '') ?>"><?= h($prod['short_desc_vi'] ?? '') ?></p>
<?php if (!empty($prod['price'])): ?>
            <p class="product-card__price"><?= number_format((float)$prod['price'], 0, ',', '.') ?> ₫</p>
<?php endif; ?>
            <a href="#contact" class="product-card__btn">
              <span data-vi="Tìm hiểu thêm" data-en="Learn More">Tìm hiểu thêm</span>
              <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </article>
<?php
    $delay = round($delay + 0.1, 1);
  endforeach;
?>
<?php else: ?>
          <!-- Static fallback cards (shown when DB is empty or unavailable) -->
          <article class="product-card aos-fade-up" style="--delay: 0s">
            <div class="product-card__icon"><i class="fas fa-bolt" aria-hidden="true"></i></div>
            <h3 class="product-card__title" data-vi="Biến tần (Inverter)" data-en="Inverter">Biến tần (Inverter)</h3>
            <p class="product-card__desc"
               data-vi="Cung cấp và lắp đặt biến tần thương hiệu EasyDrive. Điều khiển tốc độ động cơ chính xác, tiết kiệm năng lượng."
               data-en="Supply and installation of EasyDrive brand inverters. Precise motor speed control, energy saving.">
              Cung cấp và lắp đặt biến tần thương hiệu EasyDrive.
            </p>
            <ul class="product-card__features">
              <li data-vi="Biến tần 1 pha &amp; 3 pha" data-en="Single phase &amp; 3-phase inverter">Biến tần 1 pha &amp; 3 pha</li>
              <li data-vi="Dải công suất từ 0.4kW đến 500kW" data-en="Power range from 0.4kW to 500kW">Dải công suất từ 0.4kW đến 500kW</li>
              <li data-vi="Bảo hành chính hãng EasyDrive" data-en="Official EasyDrive warranty">Bảo hành chính hãng EasyDrive</li>
            </ul>
            <div class="easydrive-accordion">
              <button class="easydrive-accordion__toggle" type="button"
                      aria-expanded="false" aria-controls="easydrive-models">
                <span data-vi="Xem danh sách model" data-en="View Model List">Xem danh sách model</span>
                <i class="fas fa-chevron-down easydrive-accordion__icon" aria-hidden="true"></i>
              </button>
              <div class="easydrive-accordion__body" id="easydrive-models" hidden>
                <ul class="easydrive-accordion__list">
                  <li class="easydrive-accordion__item">
                    <span class="easydrive-accordion__model">ED-100</span>
                    <span class="easydrive-accordion__desc" data-vi="Biến tần 1 pha, 0.4-2.2kW" data-en="Single-phase, 0.4-2.2kW">Biến tần 1 pha, 0.4-2.2kW</span>
                  </li>
                  <li class="easydrive-accordion__item">
                    <span class="easydrive-accordion__model">ED-200</span>
                    <span class="easydrive-accordion__desc" data-vi="Biến tần 3 pha, 0.75-7.5kW" data-en="3-phase, 0.75-7.5kW">Biến tần 3 pha, 0.75-7.5kW</span>
                  </li>
                  <li class="easydrive-accordion__item">
                    <span class="easydrive-accordion__model">ED-300</span>
                    <span class="easydrive-accordion__desc" data-vi="Biến tần 3 pha, 11-55kW" data-en="3-phase, 11-55kW">Biến tần 3 pha, 11-55kW</span>
                  </li>
                  <li class="easydrive-accordion__item">
                    <span class="easydrive-accordion__model">ED-500</span>
                    <span class="easydrive-accordion__desc" data-vi="Biến tần công suất cao, 75-500kW" data-en="High-power, 75-500kW">Biến tần công suất cao, 75-500kW</span>
                  </li>
                </ul>
                <p class="easydrive-accordion__note" data-vi="Liên hệ để được báo giá và tư vấn model phù hợp" data-en="Contact us for pricing and model recommendations">
                  Liên hệ để được báo giá và tư vấn model phù hợp
                </p>
              </div>
            </div>
            <a href="inverter.html" class="product-card__btn">
              <span data-vi="Xem trang sản phẩm" data-en="View Product Page">Xem trang sản phẩm</span>
              <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </article>

          <article class="product-card aos-fade-up" style="--delay: 0.1s">
            <div class="product-card__icon"><i class="fas fa-microchip" aria-hidden="true"></i></div>
            <h3 class="product-card__title">PLC, HMI</h3>
            <p class="product-card__desc"
               data-vi="Lập trình và cung cấp thiết bị PLC và HMI cho hệ thống điều khiển tự động hóa công nghiệp."
               data-en="Programming and supplying PLC and HMI devices for industrial automation control systems.">
              Lập trình và cung cấp thiết bị PLC và HMI.
            </p>
            <ul class="product-card__features">
              <li data-vi="Siemens S7-1200, S7-1500" data-en="Siemens S7-1200, S7-1500">Siemens S7-1200, S7-1500</li>
              <li data-vi="Mitsubishi FX, Q Series" data-en="Mitsubishi FX, Q Series">Mitsubishi FX, Q Series</li>
              <li data-vi="Lập trình theo yêu cầu" data-en="Custom programming">Lập trình theo yêu cầu</li>
            </ul>
            <a href="#contact" class="product-card__btn">
              <span data-vi="Tìm hiểu thêm" data-en="Learn More">Tìm hiểu thêm</span>
              <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </article>

          <article class="product-card aos-fade-up" style="--delay: 0.2s">
            <div class="product-card__icon"><i class="fas fa-industry" aria-hidden="true"></i></div>
            <h3 class="product-card__title" data-vi="Tự động hóa dây chuyền" data-en="Conveyor Automation">Tự động hóa dây chuyền</h3>
            <p class="product-card__desc"
               data-vi="Thiết kế và thi công toàn bộ hệ thống tự động hóa dây chuyền sản xuất."
               data-en="Design and construct complete conveyor production automation systems.">
              Thiết kế và thi công toàn bộ hệ thống tự động hóa dây chuyền sản xuất.
            </p>
            <ul class="product-card__features">
              <li data-vi="Dây chuyền lắp ráp tự động" data-en="Automated assembly lines">Dây chuyền lắp ráp tự động</li>
              <li data-vi="Hệ thống SCADA / DCS" data-en="SCADA / DCS systems">Hệ thống SCADA / DCS</li>
              <li data-vi="Tích hợp Robot công nghiệp" data-en="Industrial robot integration">Tích hợp Robot công nghiệp</li>
            </ul>
            <a href="#contact" class="product-card__btn">
              <span data-vi="Tìm hiểu thêm" data-en="Learn More">Tìm hiểu thêm</span>
              <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </article>

          <article class="product-card aos-fade-up" style="--delay: 0.3s">
            <div class="product-card__icon"><i class="fas fa-tools" aria-hidden="true"></i></div>
            <h3 class="product-card__title" data-vi="Tư vấn & Lắp đặt" data-en="Consulting & Installation">Tư vấn &amp; Lắp đặt</h3>
            <p class="product-card__desc"
               data-vi="Tư vấn giải pháp kỹ thuật tối ưu và lắp đặt hệ thống tự động hóa hoàn chỉnh."
               data-en="Optimal technical solution consulting and complete automation system installation.">
              Tư vấn giải pháp kỹ thuật tối ưu và lắp đặt hệ thống tự động hóa hoàn chỉnh.
            </p>
            <ul class="product-card__features">
              <li data-vi="Khảo sát &amp; phân tích miễn phí" data-en="Free survey &amp; analysis">Khảo sát &amp; phân tích miễn phí</li>
              <li data-vi="Thiết kế hệ thống theo yêu cầu" data-en="Custom system design">Thiết kế hệ thống theo yêu cầu</li>
              <li data-vi="Bảo trì định kỳ" data-en="Periodic maintenance">Bảo trì định kỳ</li>
            </ul>
            <a href="#contact" class="product-card__btn">
              <span data-vi="Tìm hiểu thêm" data-en="Learn More">Tìm hiểu thêm</span>
              <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
          </article>
<?php endif; ?>
        </div>
      </div>
    </section>

    <!-- ========== WHY CHOOSE US ========== -->
    <section class="why section" id="why-us">
      <div class="container">
        <div class="section__header aos-fade-up">
          <span class="section__label" data-vi="Tại sao chọn chúng tôi" data-en="Why Choose Us">Tại sao chọn chúng tôi</span>
          <h2 class="section__title" data-vi="Cam kết của Autotech" data-en="Autotech's Commitment">Cam kết của Autotech</h2>
          <p class="section__subtitle"
             data-vi="Chúng tôi không chỉ bán sản phẩm — chúng tôi cung cấp giải pháp toàn diện."
             data-en="We don't just sell products — we provide comprehensive solutions.">
            Chúng tôi không chỉ bán sản phẩm — chúng tôi cung cấp giải pháp toàn diện.
          </p>
        </div>
        <div class="why__grid">
          <div class="why__item aos-fade-up" style="--delay: 0s">
            <div class="why__icon why__icon--1"><i class="fas fa-shield-alt" aria-hidden="true"></i></div>
            <h3 data-vi="Chất lượng cao" data-en="High Quality">Chất lượng cao</h3>
            <p data-vi="100% sản phẩm chính hãng, có chứng chỉ chất lượng quốc tế ISO, CE" data-en="100% genuine products, with international quality certificates ISO, CE">100% sản phẩm chính hãng, có chứng chỉ chất lượng quốc tế ISO, CE</p>
          </div>
          <div class="why__item aos-fade-up" style="--delay: 0.1s">
            <div class="why__icon why__icon--2"><i class="fas fa-user-tie" aria-hidden="true"></i></div>
            <h3 data-vi="Đội ngũ chuyên nghiệp" data-en="Professional Team">Đội ngũ chuyên nghiệp</h3>
            <p data-vi="Kỹ sư được đào tạo bài bản, có chứng chỉ kỹ thuật quốc tế từ Siemens, ABB, Mitsubishi" data-en="Well-trained engineers with international technical certificates from Siemens, ABB, Mitsubishi">Kỹ sư được đào tạo bài bản, có chứng chỉ kỹ thuật quốc tế từ Siemens, ABB, Mitsubishi</p>
          </div>
          <div class="why__item aos-fade-up" style="--delay: 0.2s">
            <div class="why__icon why__icon--3"><i class="fas fa-headset" aria-hidden="true"></i></div>
            <h3 data-vi="Hỗ trợ 24/7" data-en="24/7 Support">Hỗ trợ 24/7</h3>
            <p data-vi="Đội ngũ hỗ trợ kỹ thuật luôn sẵn sàng 24/7 giải quyết mọi sự cố nhanh chóng" data-en="Technical support team always available 24/7 to resolve any issues quickly">Đội ngũ hỗ trợ kỹ thuật luôn sẵn sàng 24/7 giải quyết mọi sự cố nhanh chóng</p>
          </div>
          <div class="why__item aos-fade-up" style="--delay: 0.3s">
            <div class="why__icon why__icon--4"><i class="fas fa-tags" aria-hidden="true"></i></div>
            <h3 data-vi="Giá cạnh tranh" data-en="Competitive Pricing">Giá cạnh tranh</h3>
            <p data-vi="Báo giá minh bạch, cạnh tranh trên thị trường. Không phát sinh chi phí ẩn" data-en="Transparent, market-competitive pricing. No hidden costs">Báo giá minh bạch, cạnh tranh trên thị trường. Không phát sinh chi phí ẩn</p>
          </div>
          <div class="why__item aos-fade-up" style="--delay: 0.4s">
            <div class="why__icon why__icon--5"><i class="fas fa-sync-alt" aria-hidden="true"></i></div>
            <h3 data-vi="Bảo hành dài hạn" data-en="Long-term Warranty">Bảo hành dài hạn</h3>
            <p data-vi="Chính sách bảo hành rõ ràng, bảo trì định kỳ và hỗ trợ thay thế linh kiện" data-en="Clear warranty policy, periodic maintenance and spare parts replacement support">Chính sách bảo hành rõ ràng, bảo trì định kỳ và hỗ trợ thay thế linh kiện</p>
          </div>
          <div class="why__item aos-fade-up" style="--delay: 0.5s">
            <div class="why__icon why__icon--6"><i class="fas fa-rocket" aria-hidden="true"></i></div>
            <h3 data-vi="Triển khai nhanh" data-en="Fast Deployment">Triển khai nhanh</h3>
            <p data-vi="Cam kết tiến độ, triển khai dự án nhanh chóng và đúng thời hạn" data-en="Committed to schedule, fast and on-time project deployment">Cam kết tiến độ, triển khai dự án nhanh chóng và đúng thời hạn</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ========== DOWNLOAD SECTION ========== -->
    <section class="download section section--gray" id="download">
      <div class="container">
        <div class="section__header aos-fade-up">
          <span class="section__label" data-vi="Tài liệu" data-en="Resources">Tài liệu</span>
          <h2 class="section__title" data-vi="Tải xuống tài liệu kỹ thuật" data-en="Download Technical Documents">Tải xuống tài liệu kỹ thuật</h2>
          <p class="section__subtitle"
             data-vi="Tải xuống catalogue, hướng dẫn cài đặt và tài liệu kỹ thuật miễn phí."
             data-en="Download catalogues, installation guides and technical documents for free.">
            Tải xuống catalogue, hướng dẫn cài đặt và tài liệu kỹ thuật miễn phí.
          </p>
        </div>
        <div class="download__grid">
          <div class="download__item aos-fade-up" style="--delay: 0s">
            <div class="download__icon"><i class="fas fa-file-pdf" aria-hidden="true"></i></div>
            <div class="download__info">
              <h3 data-vi="Catalogue Biến tần EasyDrive" data-en="EasyDrive Inverter Catalogue">Catalogue Biến tần EasyDrive</h3>
              <p data-vi="Thông số kỹ thuật đầy đủ các dòng biến tần EasyDrive" data-en="Full technical specifications for EasyDrive inverter series">Thông số kỹ thuật đầy đủ các dòng biến tần EasyDrive</p>
              <span class="download__meta">PDF · 4.2 MB</span>
            </div>
            <a href="#" class="btn btn--outline download__btn" data-vi="Tải xuống" data-en="Download"><i class="fas fa-download" aria-hidden="true"></i> Tải xuống</a>
          </div>
          <div class="download__item aos-fade-up" style="--delay: 0.1s">
            <div class="download__icon"><i class="fas fa-file-pdf" aria-hidden="true"></i></div>
            <div class="download__info">
              <h3 data-vi="Hướng dẫn lắp đặt & cài đặt" data-en="Installation & Setup Guide">Hướng dẫn lắp đặt &amp; cài đặt</h3>
              <p data-vi="Hướng dẫn từng bước lắp đặt và cấu hình biến tần" data-en="Step-by-step guide to install and configure inverters">Hướng dẫn từng bước lắp đặt và cấu hình biến tần</p>
              <span class="download__meta">PDF · 2.8 MB</span>
            </div>
            <a href="#" class="btn btn--outline download__btn" data-vi="Tải xuống" data-en="Download"><i class="fas fa-download" aria-hidden="true"></i> Tải xuống</a>
          </div>
          <div class="download__item aos-fade-up" style="--delay: 0.2s">
            <div class="download__icon"><i class="fas fa-file-alt" aria-hidden="true"></i></div>
            <div class="download__info">
              <h3 data-vi="Tài liệu lập trình PLC" data-en="PLC Programming Manual">Tài liệu lập trình PLC</h3>
              <p data-vi="Tài liệu tham khảo lập trình PLC Siemens, Mitsubishi" data-en="Reference manual for Siemens and Mitsubishi PLC programming">Tài liệu tham khảo lập trình PLC Siemens, Mitsubishi</p>
              <span class="download__meta">PDF · 6.5 MB</span>
            </div>
            <a href="#" class="btn btn--outline download__btn" data-vi="Tải xuống" data-en="Download"><i class="fas fa-download" aria-hidden="true"></i> Tải xuống</a>
          </div>
          <div class="download__item aos-fade-up" style="--delay: 0.3s">
            <div class="download__icon"><i class="fas fa-file-alt" aria-hidden="true"></i></div>
            <div class="download__info">
              <h3 data-vi="Bảng giá sản phẩm 2024" data-en="Product Price List 2024">Bảng giá sản phẩm 2024</h3>
              <p data-vi="Cập nhật giá mới nhất các thiết bị tự động hóa công nghiệp" data-en="Latest prices for industrial automation equipment">Cập nhật giá mới nhất các thiết bị tự động hóa công nghiệp</p>
              <span class="download__meta">PDF · 1.1 MB</span>
            </div>
            <a href="#" class="btn btn--outline download__btn" data-vi="Tải xuống" data-en="Download"><i class="fas fa-download" aria-hidden="true"></i> Tải xuống</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ========== FAQ SECTION ========== -->
    <section class="faq section" id="faq">
      <div class="container">
        <div class="section__header aos-fade-up">
          <span class="section__label" data-vi="Hỏi đáp" data-en="FAQ">Hỏi đáp</span>
          <h2 class="section__title" data-vi="Câu hỏi thường gặp" data-en="Frequently Asked Questions">Câu hỏi thường gặp</h2>
          <p class="section__subtitle"
             data-vi="Tổng hợp những câu hỏi phổ biến nhất từ khách hàng của chúng tôi."
             data-en="A collection of the most common questions from our customers.">
            Tổng hợp những câu hỏi phổ biến nhất từ khách hàng của chúng tôi.
          </p>
        </div>
        <div class="faq__list">
          <div class="faq__item aos-fade-up" style="--delay: 0s">
            <button class="faq__question" aria-expanded="false">
              <span data-vi="Autotech cung cấp những dòng biến tần nào?" data-en="What inverter series does Autotech provide?">Autotech cung cấp những dòng biến tần nào?</span>
              <i class="fas fa-chevron-down faq__icon" aria-hidden="true"></i>
            </button>
            <div class="faq__answer" hidden>
              <p data-vi="Autotech cung cấp đầy đủ các dòng biến tần chính hãng như EasyDrive, Siemens SINAMICS, ABB ACS, Mitsubishi FR và nhiều thương hiệu uy tín khác phù hợp với mọi ứng dụng công nghiệp."
                 data-en="Autotech provides a full range of genuine inverter brands including EasyDrive, Siemens SINAMICS, ABB ACS, Mitsubishi FR and many other reputable brands suitable for all industrial applications.">
                Autotech cung cấp đầy đủ các dòng biến tần chính hãng như EasyDrive, Siemens SINAMICS, ABB ACS, Mitsubishi FR và nhiều thương hiệu uy tín khác.
              </p>
            </div>
          </div>
          <div class="faq__item aos-fade-up" style="--delay: 0.05s">
            <button class="faq__question" aria-expanded="false">
              <span data-vi="Thời gian bảo hành sản phẩm là bao lâu?" data-en="What is the product warranty period?">Thời gian bảo hành sản phẩm là bao lâu?</span>
              <i class="fas fa-chevron-down faq__icon" aria-hidden="true"></i>
            </button>
            <div class="faq__answer" hidden>
              <p data-vi="Tất cả sản phẩm chính hãng được bảo hành từ 12 đến 24 tháng tùy loại. Autotech hỗ trợ bảo hành tận nơi, thay thế linh kiện nhanh chóng và miễn phí kiểm tra định kỳ."
                 data-en="All genuine products are warranted from 12 to 24 months depending on the type. Autotech provides on-site warranty support, fast component replacement, and free periodic inspections.">
                Tất cả sản phẩm chính hãng được bảo hành từ 12 đến 24 tháng tùy loại.
              </p>
            </div>
          </div>
          <div class="faq__item aos-fade-up" style="--delay: 0.1s">
            <button class="faq__question" aria-expanded="false">
              <span data-vi="Autotech có hỗ trợ lắp đặt và cấu hình không?" data-en="Does Autotech provide installation and configuration support?">Autotech có hỗ trợ lắp đặt và cấu hình không?</span>
              <i class="fas fa-chevron-down faq__icon" aria-hidden="true"></i>
            </button>
            <div class="faq__answer" hidden>
              <p data-vi="Có. Đội ngũ kỹ sư chuyên nghiệp của Autotech hỗ trợ lắp đặt, cấu hình và đào tạo vận hành trực tiếp tại công trình của khách hàng trên toàn quốc."
                 data-en="Yes. Autotech's team of professional engineers provides on-site installation, configuration and operation training at customer sites nationwide.">
                Có. Đội ngũ kỹ sư chuyên nghiệp của Autotech hỗ trợ lắp đặt, cấu hình và đào tạo vận hành tại công trình của khách hàng.
              </p>
            </div>
          </div>
          <div class="faq__item aos-fade-up" style="--delay: 0.15s">
            <button class="faq__question" aria-expanded="false">
              <span data-vi="Làm thế nào để nhận báo giá sản phẩm?" data-en="How do I get a product quote?">Làm thế nào để nhận báo giá sản phẩm?</span>
              <i class="fas fa-chevron-down faq__icon" aria-hidden="true"></i>
            </button>
            <div class="faq__answer" hidden>
              <p data-vi="Bạn có thể điền vào form liên hệ ở cuối trang, gọi điện hoặc gửi email trực tiếp cho chúng tôi. Đội ngũ kinh doanh sẽ phản hồi báo giá trong vòng 2 giờ làm việc."
                 data-en="You can fill in the contact form at the bottom of the page, call, or email us directly. Our sales team will respond with a quote within 2 business hours.">
                Bạn có thể điền vào form liên hệ ở cuối trang hoặc gọi điện trực tiếp. Đội ngũ kinh doanh sẽ phản hồi trong vòng 2 giờ làm việc.
              </p>
            </div>
          </div>
          <div class="faq__item aos-fade-up" style="--delay: 0.2s">
            <button class="faq__question" aria-expanded="false">
              <span data-vi="Autotech có hỗ trợ kỹ thuật 24/7 không?" data-en="Does Autotech offer 24/7 technical support?">Autotech có hỗ trợ kỹ thuật 24/7 không?</span>
              <i class="fas fa-chevron-down faq__icon" aria-hidden="true"></i>
            </button>
            <div class="faq__answer" hidden>
              <p data-vi="Có. Hotline hỗ trợ kỹ thuật của Autotech hoạt động 24/7. Đối với các sự cố khẩn cấp, kỹ sư sẽ được cử đến hiện trường trong vòng 4 giờ trong bán kính 50km."
                 data-en="Yes. Autotech's technical support hotline operates 24/7. For urgent issues, an engineer will be dispatched to the site within 4 hours within a 50km radius.">
                Có. Hotline hỗ trợ kỹ thuật của Autotech hoạt động 24/7.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ========== CONTACT SECTION ========== -->
    <section class="contact section section--gray" id="contact">
      <div class="container">
        <div class="section__header aos-fade-up">
          <span class="section__label" data-vi="Liên hệ" data-en="Contact">Liên hệ</span>
          <h2 class="section__title" data-vi="Liên hệ với chúng tôi" data-en="Get In Touch">Liên hệ với chúng tôi</h2>
          <p class="section__subtitle"
             data-vi="Hãy để lại thông tin, chúng tôi sẽ liên hệ lại trong vòng 24 giờ làm việc."
             data-en="Leave your information, we will contact you within 24 business hours.">
            Hãy để lại thông tin, chúng tôi sẽ liên hệ lại trong vòng 24 giờ làm việc.
          </p>
        </div>

        <div class="contact__grid">
          <!-- Contact Info -->
          <div class="contact__info aos-fade-right">
            <div class="contact__info-item">
              <div class="contact__info-icon"><i class="fas fa-map-marker-alt" aria-hidden="true"></i></div>
              <div class="contact__info-content">
                <h4 data-vi="Địa chỉ" data-en="Address">Địa chỉ</h4>
                <p>[Địa chỉ công ty]</p>
              </div>
            </div>
            <div class="contact__info-item">
              <div class="contact__info-icon"><i class="fas fa-phone-alt" aria-hidden="true"></i></div>
              <div class="contact__info-content">
                <h4 data-vi="Điện thoại" data-en="Phone">Điện thoại</h4>
                <p>[Số điện thoại]</p>
              </div>
            </div>
            <div class="contact__info-item">
              <div class="contact__info-icon"><i class="fas fa-envelope" aria-hidden="true"></i></div>
              <div class="contact__info-content">
                <h4>Email</h4>
                <p>[Email]</p>
              </div>
            </div>
            <div class="contact__info-item">
              <div class="contact__info-icon"><i class="fas fa-clock" aria-hidden="true"></i></div>
              <div class="contact__info-content">
                <h4 data-vi="Giờ làm việc" data-en="Working Hours">Giờ làm việc</h4>
                <p data-vi="Thứ 2 – Thứ 6: 8:00 – 17:30" data-en="Mon – Fri: 8:00 – 17:30">Thứ 2 – Thứ 6: 8:00 – 17:30</p>
                <p data-vi="Thứ 7: 8:00 – 12:00" data-en="Sat: 8:00 – 12:00">Thứ 7: 8:00 – 12:00</p>
              </div>
            </div>
            <div class="contact__social">
              <a href="#" class="contact__social-link" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
              <a href="#" class="contact__social-link" aria-label="Zalo"><i class="fas fa-comment" aria-hidden="true"></i></a>
              <a href="#" class="contact__social-link" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
              <a href="#" class="contact__social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
            </div>
          </div>

          <!-- Contact Form (submits to contact_submit.php) -->
          <form class="contact__form aos-fade-left" id="contactForm"
                method="POST" action="contact_submit.php" novalidate>
            <?= csrf_field() ?>

            <?php foreach ($contactFlash as $f): ?>
            <div class="alert alert--<?= h($f['type']) ?>" role="alert"><?= h($f['message']) ?></div>
            <?php endforeach; ?>

            <div class="form__row">
              <div class="form__group">
                <label for="name" data-vi="Họ và tên *" data-en="Full Name *">Họ và tên *</label>
                <input type="text" id="name" name="name" placeholder="Nguyễn Văn A" required aria-required="true">
                <span class="form__error" id="nameError"></span>
              </div>
              <div class="form__group">
                <label for="phone" data-vi="Số điện thoại *" data-en="Phone Number *">Số điện thoại *</label>
                <input type="tel" id="phone" name="phone" placeholder="0901 234 567" required aria-required="true">
                <span class="form__error" id="phoneError"></span>
              </div>
            </div>
            <div class="form__group">
              <label for="email">Email *</label>
              <input type="email" id="email" name="email" placeholder="example@email.com" required aria-required="true">
              <span class="form__error" id="emailError"></span>
            </div>
            <div class="form__group">
              <label for="subject" data-vi="Chủ đề" data-en="Subject">Chủ đề</label>
              <select id="subject" name="subject">
                <option value="" data-vi="-- Chọn chủ đề --" data-en="-- Select subject --">-- Chọn chủ đề --</option>
                <option value="inverter"   data-vi="Biến tần (Inverter)"       data-en="Inverter">Biến tần (Inverter)</option>
                <option value="plc-hmi"    data-vi="PLC, HMI"                  data-en="PLC, HMI">PLC, HMI</option>
                <option value="automation" data-vi="Tự động hóa dây chuyền"    data-en="Conveyor Automation">Tự động hóa dây chuyền</option>
                <option value="consulting" data-vi="Tư vấn & Lắp đặt"          data-en="Consulting & Installation">Tư vấn &amp; Lắp đặt</option>
                <option value="other"      data-vi="Khác"                       data-en="Other">Khác</option>
              </select>
            </div>
            <div class="form__group">
              <label for="message" data-vi="Nội dung *" data-en="Message *">Nội dung *</label>
              <textarea id="message" name="message" rows="5"
                        placeholder="Nhập nội dung cần tư vấn..." required aria-required="true"></textarea>
              <span class="form__error" id="messageError"></span>
            </div>
            <button type="submit" class="btn btn--primary btn--full" id="submitBtn">
              <i class="fas fa-paper-plane" aria-hidden="true"></i>
              <span data-vi="Gửi tin nhắn" data-en="Send Message">Gửi tin nhắn</span>
            </button>
            <div class="form__success" id="formSuccess" aria-live="polite">
              <i class="fas fa-check-circle" aria-hidden="true"></i>
              <span data-vi="Cảm ơn! Chúng tôi sẽ liên hệ lại sớm nhất." data-en="Thank you! We will contact you as soon as possible.">
                Cảm ơn! Chúng tôi sẽ liên hệ lại sớm nhất.
              </span>
            </div>
          </form>
        </div>
      </div>
    </section>

  </main>

  <!-- ========== FOOTER ========== -->
  <footer class="footer">
    <div class="container">
      <div class="footer__grid">
        <div class="footer__brand">
          <a href="#home" class="footer__logo" aria-label="Autotech"><span>⚙️</span> Autotech</a>
          <p data-vi="Chuyên cung cấp thiết bị và giải pháp tự động hóa công nghiệp chuyên nghiệp tại Việt Nam."
             data-en="Specializing in professional industrial automation equipment and solutions in Vietnam.">
            Chuyên cung cấp thiết bị và giải pháp tự động hóa công nghiệp chuyên nghiệp tại Việt Nam.
          </p>
          <div class="footer__social">
            <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>
            <a href="#" aria-label="Zalo"><i class="fas fa-comment" aria-hidden="true"></i></a>
            <a href="#" aria-label="YouTube"><i class="fab fa-youtube" aria-hidden="true"></i></a>
            <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>
          </div>
        </div>
        <div class="footer__links">
          <h4 data-vi="Liên kết nhanh" data-en="Quick Links">Liên kết nhanh</h4>
          <ul>
            <li><a href="#home"     data-vi="Trang chủ"   data-en="Home">Trang chủ</a></li>
            <li><a href="#about"    data-vi="Về chúng tôi" data-en="About Us">Về chúng tôi</a></li>
            <li><a href="#products" data-vi="Sản phẩm"    data-en="Products">Sản phẩm</a></li>
            <li><a href="#services" data-vi="Dịch vụ"     data-en="Services">Dịch vụ</a></li>
            <li><a href="#download" data-vi="Tải xuống"   data-en="Download">Tải xuống</a></li>
            <li><a href="#faq"      data-vi="Hỏi đáp"     data-en="FAQ">Hỏi đáp</a></li>
            <li><a href="#contact"  data-vi="Liên hệ"     data-en="Contact">Liên hệ</a></li>
          </ul>
        </div>
        <div class="footer__links">
          <h4 data-vi="Sản phẩm &amp; Dịch vụ" data-en="Products &amp; Services">Sản phẩm &amp; Dịch vụ</h4>
          <ul>
            <li><a href="#products" data-vi="Biến tần (Inverter)"        data-en="Inverter">Biến tần (Inverter)</a></li>
            <li><a href="#products">PLC, HMI</a></li>
            <li><a href="#products" data-vi="Tự động hóa dây chuyền"     data-en="Conveyor Automation">Tự động hóa dây chuyền</a></li>
            <li><a href="#products" data-vi="Tư vấn &amp; Lắp đặt"       data-en="Consulting &amp; Installation">Tư vấn &amp; Lắp đặt</a></li>
          </ul>
        </div>
        <div class="footer__contact">
          <h4 data-vi="Thông tin liên hệ" data-en="Contact Information">Thông tin liên hệ</h4>
          <ul>
            <li><i class="fas fa-map-marker-alt" aria-hidden="true"></i> <span>[Địa chỉ công ty]</span></li>
            <li><i class="fas fa-phone-alt"       aria-hidden="true"></i> <span>[Số điện thoại]</span></li>
            <li><i class="fas fa-envelope"        aria-hidden="true"></i> <span>[Email]</span></li>
          </ul>
        </div>
      </div>
      <div class="footer__bottom">
        <p data-vi="© 2026 Autotech. Tất cả quyền được bảo lưu." data-en="© 2026 Autotech. All rights reserved.">
          &copy; 2026 Autotech. Tất cả quyền được bảo lưu.
        </p>
        <p>
          <span data-vi="Thiết kế bởi" data-en="Designed by">Thiết kế bởi</span>
          <span class="footer__brand-name">Autotech</span>
        </p>
      </div>
    </div>
  </footer>

  <button class="back-to-top" id="backToTop" aria-label="Back to top">
    <i class="fas fa-chevron-up" aria-hidden="true"></i>
  </button>

  <script src="assets/js/main.js"></script>
</body>
</html>
