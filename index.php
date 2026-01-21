<?php
require_once __DIR__ . '/bootstrap.php';

/**
 * index.php — Maple Urban Gardening
 * Urban micro-gardens for city homes
 */

// -------------------------
// Site configuration
// -------------------------
$siteName = "Maple Urban Gardening";
$tagline  = "Urban micro-gardens for city homes";
$address  = "3343 Overlook Rd, Zellwood, FL 32798, USA";
$phone    = "4078866189";
$emailTo  = "hello@mapleurbangardening.com"; // change if needed

$siteUrl = "https://example.com"; // replace after deploy
$canonicalUrl = rtrim($siteUrl, "/") . "/";

// -------------------------
// Helpers
// -------------------------
function h($v) {
  return htmlspecialchars($v ?? "", ENT_QUOTES, "UTF-8");
}

// -------------------------
// Contact form handling
// -------------------------
$form = ["name"=>"","email"=>"","phone"=>"","message"=>""];
$errors = [];
$success = false;

session_start();
if (!isset($_SESSION["csrf_token"])) {
  $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION["csrf_token"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["contact_form"])) {

  if (!hash_equals($csrfToken, $_POST["csrf_token"] ?? "")) {
    $errors[] = "Security check failed. Please refresh and try again.";
  }

  // Honeypot
  if (!empty($_POST["website"])) {
    $errors[] = "Submission rejected.";
  }

  $form["name"]    = trim($_POST["name"] ?? "");
  $form["email"]   = trim($_POST["email"] ?? "");
  $form["phone"]   = trim($_POST["phone"] ?? "");
  $form["message"] = trim($_POST["message"] ?? "");

  if (strlen($form["name"]) < 2) $errors[] = "Name is required.";
  if (!filter_var($form["email"], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email required.";
  if (strlen($form["message"]) < 10) $errors[] = "Message is too short.";

  if (!$errors) {
    $body =
      "New inquiry — $siteName\n\n" .
      "Name: {$form["name"]}\n" .
      "Email: {$form["email"]}\n" .
      "Phone: {$form["phone"]}\n\n" .
      "Message:\n{$form["message"]}\n";

    $headers = [
      "MIME-Version: 1.0",
      "Content-Type: text/plain; charset=UTF-8",
      "From: {$siteName} <{$emailTo}>",
      "Reply-To: {$form["email"]}"
    ];

    if (@mail($emailTo, "Website Inquiry", $body, implode("\r\n", $headers))) {
      $success = true;
      $form = ["name"=>"","email"=>"","phone"=>"","message"=>""];
    } else {
      file_put_contents(__DIR__."/contact.log", $body."\n\n", FILE_APPEND);
      $errors[] = "Message saved. Please call us if urgent.";
    }
  }
}

// -------------------------
// SEO
// -------------------------
$pageTitle = "$siteName | Urban Micro-Gardens for City Homes";
$description = "Maple Urban Gardening designs and maintains urban micro-gardens for balconies, rooftops, patios, and small city homes in Florida.";
$keywords = "urban gardening, micro garden, balcony garden, rooftop garden, city gardening, home gardening service Florida";
?>
<!doctype html>
<html lang="en">
<head>
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
  <meta charset="utf-8">
  <title><?php echo h($pageTitle); ?></title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="<?php echo h($description); ?>">
  <meta name="keywords" content="<?php echo h($keywords); ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo h($canonicalUrl); ?>">

  <meta name="theme-color" content="#2f7d4c">

  <!-- Schema -->
  <script type="application/ld+json">
  <?php
    echo json_encode([
      "@context"=>"https://schema.org",
      "@type"=>"LocalBusiness",
      "name"=>$siteName,
      "description"=>$description,
      "telephone"=>$phone,
      "address"=>[
        "@type"=>"PostalAddress",
        "streetAddress"=>"3343 Overlook Rd",
        "addressLocality"=>"Zellwood",
        "addressRegion"=>"FL",
        "postalCode"=>"32798",
        "addressCountry"=>"US"
      ]
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
  ?>
  </script>

  <style>
    :root{
      --bg:#f4fbf6;
      --panel:#ffffff;
      --text:#1f3b2c;
      --muted:#5f7f6b;
      --accent:#2f7d4c;
      --accent-soft:#e6f3ec;
      --radius:16px;
      --max:1080px;
      --sans: system-ui, -apple-system, Segoe UI, Roboto, Arial;
    }

    *{box-sizing:border-box}
    body{
      margin:0;
      font-family:var(--sans);
      background:var(--bg);
      color:var(--text);
      line-height:1.6;
    }
    a{text-decoration:none;color:inherit}
    .container{max-width:var(--max);margin:auto;padding:0 20px}

    header{
      background:var(--panel);
      border-bottom:1px solid #e0efe6;
      position:sticky;
      top:0;
      z-index:10;
    }
    .topbar{
      display:flex;
      justify-content:space-between;
      align-items:center;
      padding:16px 0;
    }
    .brand strong{
      font-size:20px;
      letter-spacing:.4px;
    }
    .brand span{
      display:block;
      font-size:12px;
      color:var(--muted);
    }

    nav a{
      margin-left:16px;
      font-size:14px;
      color:var(--muted);
    }
    nav a:hover{color:var(--accent)}

    section{padding:64px 0}
    h1{font-size:40px;margin:0 0 16px}
    h2{font-size:28px;margin:0 0 12px}
    p{margin:0 0 14px}

    .panel{
      background:var(--panel);
      border-radius:var(--radius);
      padding:24px;
      box-shadow:0 10px 25px rgba(0,0,0,.04);
    }
</head>
<body>

<header>
  <div class="container topbar">
    <div class="brand">
      <strong><?php echo h($siteName); ?></strong>
      <span><?php echo h($tagline); ?></span>
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
    <p>
      <?php echo h($siteName); ?> creates custom urban micro-gardens for balconies,
      patios, rooftops, and compact yards—designed for real city living.
    </p>
    <p>
      We combine smart layouts, edible plants, and low-maintenance systems
      so you can enjoy greenery without complexity.
    </p>
  </div>
</section>

<section id="about">
  <div class="container">
    <div class="panel">
      <h2>About Maple Urban Gardening</h2>
      <p>
        City homes deserve green spaces. We specialize in micro-gardens that fit
        modern urban environments—whether that’s a sunny balcony, a shaded patio,
        or a small backyard.
      </p>
      <p>
        Our approach focuses on sustainability, usability, and long-term success.
        Every garden is planned around light, airflow, watering, and your lifestyle.
      </p>
    </div>
  </div>
</section>

<section id="services">
  <div class="container">
    <h2>Our Services</h2>

    <div class="panel" style="margin-bottom:16px;">
      <strong>Urban garden design</strong>
      <p>
        Custom layouts for balconies, rooftops, patios, and compact yards.
        Designed for aesthetics and plant health.
      </p>
    </div>

    <div class="panel" style="margin-bottom:16px;">
      <strong>Edible micro-gardens</strong>
      <p>
        Herbs, vegetables, and fruit plants selected for small spaces and Florida climate.
      </p>
    </div>

    <div class="panel">
      <strong>Maintenance & guidance</strong>
      <p>
        Seasonal maintenance, soil refresh, pruning, and simple care plans for homeowners.
      </p>
    </div>
  </div>
</section>
<section id="contact">
  <div class="container">
    <h2>Contact Us</h2>

    <div class="panel">

      <?php if ($success): ?>
        <p style="color:var(--accent);font-weight:600;">
          Thank you! Your message has been sent.
        </p>
      <?php endif; ?>

      <?php if ($errors): ?>
        <ul style="color:#b00020;">
          <?php foreach ($errors as $e): ?>
            <li><?php echo h($e); ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <form method="post" action="#contact">
        <input type="hidden" name="contact_form" value="1">
        <input type="hidden" name="csrf_token" value="<?php echo h($csrfToken); ?>">
        <input type="text" name="website" style="display:none">

        <p>
          <label>Name<br>
            <input name="name" value="<?php echo h($form["name"]); ?>" required>
          </label>
        </p>

        <p>
          <label>Email<br>
            <input type="email" name="email" value="<?php echo h($form["email"]); ?>" required>
          </label>
        </p>

        <p>
          <label>Phone (optional)<br>
            <input name="phone" value="<?php echo h($form["phone"]); ?>">
          </label>
        </p>

        <p>
          <label>Message<br>
            <textarea name="message" required><?php echo h($form["message"]); ?></textarea>
          </label>
        </p>

        <button type="submit" style="
          background:var(--accent);
          color:white;
          border:none;
          padding:12px 20px;
          border-radius:12px;
          cursor:pointer;
        ">
          Send Message
        </button>
      </form>
    </div>
  </div>
</section>

</main>

<footer>
  <div class="container" style="padding:32px 20px;color:var(--muted);">
    <strong><?php echo h($siteName); ?></strong><br>
    <?php echo h($address); ?><br>
    <a href="tel:<?php echo h($phone); ?>"><?php echo h($phone); ?></a>
  </div>
</footer>

</body>
</html>
