<?php
require_once __DIR__ . '/bootstrap.php';

/**
 * Maple Urban Gardening
 * Urban micro-gardens for city homes
 * SAFE version compatible with existing bootstrap.php
 */

// -------------------------------------------------
// CONFIG
// -------------------------------------------------
$siteName = "Maple Urban Gardening";
$tagline  = "Urban micro-gardens for city homes";
$address  = "3343 Overlook Rd, Zellwood, FL 32798, USA";
$phone    = "4078866189";
$emailTo  = "hello@mapleurbangardening.com";

$siteUrl = "https://example.com"; // change after deploy
$canonicalUrl = rtrim($siteUrl, "/") . "/";

// -------------------------------------------------
// HELPERS
// -------------------------------------------------
function h($v) {
  return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

// -------------------------------------------------
// CSRF (bootstrap.php already handles session)
// -------------------------------------------------
if (!isset($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];

// -------------------------------------------------
// CONTACT FORM
// -------------------------------------------------
$form = ['name'=>'','email'=>'','phone'=>'','message'=>''];
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_form'])) {

  if (!isset($_POST['csrf_token']) || !hash_equals($csrfToken, $_POST['csrf_token'])) {
    $errors[] = "Security validation failed.";
  }

  // Honeypot
  if (!empty($_POST['company'])) {
    $errors[] = "Invalid submission.";
  }

  $form['name']    = trim($_POST['name'] ?? '');
  $form['email']   = trim($_POST['email'] ?? '');
  $form['phone']   = trim($_POST['phone'] ?? '');
  $form['message'] = trim($_POST['message'] ?? '');

  if (strlen($form['name']) < 2) {
    $errors[] = "Name is required.";
  }
  if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required.";
  }
  if (strlen($form['message']) < 10) {
    $errors[] = "Message must be at least 10 characters.";
  }

  if (!$errors) {
    $body =
      "New inquiry – {$siteName}\n\n" .
      "Name: {$form['name']}\n" .
      "Email: {$form['email']}\n" .
      "Phone: {$form['phone']}\n\n" .
      "Message:\n{$form['message']}\n";

    $headers = [
      "Content-Type: text/plain; charset=UTF-8",
      "From: {$siteName} <{$emailTo}>",
      "Reply-To: {$form['email']}"
    ];

    if (@mail($emailTo, "Website Inquiry", $body, implode("\r\n", $headers))) {
      $success = true;
      $form = ['name'=>'','email'=>'','phone'=>'','message'=>''];
    } else {
      file_put_contents(__DIR__ . "/contact.log", $body . "\n\n", FILE_APPEND);
      $errors[] = "Message saved. Please call if urgent.";
    }
  }
}

// -------------------------------------------------
// SEO
// -------------------------------------------------
$pageTitle = "{$siteName} | Urban Micro-Gardens for City Homes";
$description = "Maple Urban Gardening designs compact, sustainable micro-gardens for balconies, patios, rooftops, and small city homes.";
$keywords = "urban gardening, micro gardens, balcony garden, rooftop garden, city gardening Florida";
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= h($pageTitle) ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?= h($description) ?>">
  <meta name="keywords" content="<?= h($keywords) ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= h($canonicalUrl) ?>">

  <meta name="theme-color" content="#355f3b">

  <!-- Schema -->
  <script type="application/ld+json">
  <?= json_encode([
    "@context" => "https://schema.org",
    "@type" => "LocalBusiness",
    "name" => $siteName,
    "description" => $description,
    "telephone" => $phone,
    "address" => [
      "@type" => "PostalAddress",
      "streetAddress" => "3343 Overlook Rd",
      "addressLocality" => "Zellwood",
      "addressRegion" => "FL",
      "postalCode" => "32798",
      "addressCountry" => "US"
    ]
  ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
  </script>

  <style>
    :root{
      --bg:#f6faf7;
      --panel:#ffffff;
      --text:#1e3526;
      --muted:#5f7d69;
      --accent:#355f3b;
      --accent-soft:#e2efe6;
      --radius:14px;
      --max:1040px;
      --font:system-ui,-apple-system,Segoe UI,Roboto,Arial;
    }
    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:var(--font);
      background:var(--bg);
      color:var(--text);
      line-height:1.6;
    }
    a{text-decoration:none;color:inherit}
    .container{max-width:var(--max);margin:auto;padding:0 20px}

    header{
      background:var(--panel);
      border-bottom:1px solid #ddebe2;
      position:sticky;top:0;z-index:10;
    }
    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:16px 0;
    }
    nav a{
      margin-left:16px;
      font-size:14px;
      color:var(--muted);
    }
    nav a:hover{color:var(--accent)}

    section{padding:64px 0}
    h1{font-size:38px;margin:0 0 14px}
    h2{font-size:26px;margin:0 0 12px}
    p{margin:0 0 14px}

    .panel{
      background:var(--panel);
      border-radius:var(--radius);
      padding:24px;
      box-shadow:0 8px 20px rgba(0,0,0,.05);
    }
    input,textarea{
      width:100%;
      padding:12px;
      border-radius:10px;
      border:1px solid #ccdcd1;
      font-family:var(--font);
    }
    button{
      background:var(--accent);
      color:#fff;
      border:none;
      padding:12px 20px;
      border-radius:10px;
      cursor:pointer;
    }
  </style>
</head>

<body>

<header>
  <div class="container topbar">
    <div>
      <strong><?= h($siteName) ?></strong><br>
      <span style="font-size:12px;color:var(--muted)"><?= h($tagline) ?></span>
    </div>
    <nav>
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#services">Services</a>
      <a href="#contact">Contact</a>
    </nav>
  </div>
</header>

<main id="home">

<section>
  <div class="container">
    <h1>Grow more, even in small spaces.</h1>
    <p>We design and install urban micro-gardens that thrive in balconies, patios, rooftops, and compact city homes.</p>
    <p>Smart layouts, climate-appropriate plants, and low-maintenance systems—built for real life.</p>
  </div>
</section>

<section id="about">
  <div class="container">
    <div class="panel">
      <h2>About Maple Urban Gardening</h2>
      <p>We specialize in turning unused city spaces into productive green areas. Every garden is planned around light, airflow, and your daily routine.</p>
    </div>
  </div>
</section>

<section id="services">
  <div class="container">
    <h2>Services</h2>

    <div class="panel" style="margin-bottom:16px">
      <strong>Urban garden design</strong>
      <p>Custom micro-garden layouts for balconies, patios, rooftops, and compact yards.</p>
    </div>

    <div class="panel" style="margin-bottom:16px">
      <strong>Edible gardens</strong>
      <p>Herbs, vegetables, and fruit plants selected for small spaces and Florida climate.</p>
    </div>

    <div class="panel">
      <strong>Maintenance & care</strong>
      <p>Seasonal maintenance, soil refresh, pruning, and simple care plans.</p>
    </div>
  </div>
</section>

<section id="contact">
  <div class="container">
    <h2>Contact Us</h2>

    <div class="panel">

      <?php if ($success): ?>
        <p style="color:var(--accent);font-weight:600">Thank you! Your message has been sent.</p>
      <?php endif; ?>

      <?php if ($errors): ?>
        <ul style="color:#b00020">
          <?php foreach ($errors as $e): ?>
            <li><?= h($e) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <form method="post" action="#contact">
        <input type="hidden" name="contact_form" value="1">
        <input type="hidden" name="csrf_token" value="<?= h($csrfToken) ?>">
        <input type="text" name="company" style="display:none">

        <p><input name="name" placeholder="Your name" value="<?= h($form['name']) ?>" required></p>
        <p><input type="email" name="email" placeholder="Email address" value="<?= h($form['email']) ?>" required></p>
        <p><input name="phone" placeholder="Phone (optional)" value="<?= h($form['phone']) ?>"></p>
        <p><textarea name="message" placeholder="Tell us about your space" required><?= h($form['message']) ?></textarea></p>

        <button type="submit">Send Message</button>
      </form>

    </div>
  </div>
</section>

</main>

<footer>
  <div class="container" style="padding:32px 20px;color:var(--muted)">
    <strong><?= h($siteName) ?></strong><br>
    <?= h($address) ?><br>
    <a href="tel:<?= h($phone) ?>"><?= h($phone) ?></a>
  </div>
     <!-- Histats.com  START  (aync)-->
<script type="text/javascript">var _Hasync= _Hasync|| [];
_Hasync.push(['Histats.start', '1,5004001,4,0,0,0,00010000']);
_Hasync.push(['Histats.fasi', '1']);
_Hasync.push(['Histats.track_hits', '']);
(function() {
var hs = document.createElement('script'); hs.type = 'text/javascript'; hs.async = true;
hs.src = ('//s10.histats.com/js15_as.js');
(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(hs);
})();</script>
<noscript><a href="/" target="_blank"><img  src="//sstatic1.histats.com/0.gif?5004001&101" alt="statistics" border="0"></a></noscript>
<!-- Histats.com  END  -->
</footer>

</body>
</html>

