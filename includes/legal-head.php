<?php
$legal_base_url = 'https://samutkarshias.in';
$legal_canonical = $legal_base_url . (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
$legal_og_image = $legal_base_url . '/assets/images/logo.webp';
$legal_full_title = (isset($legal_title) ? $legal_title . ' | ' : '') . 'Samutkarsh IAS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($legal_full_title); ?></title>
  <meta name="description" content="<?php echo htmlspecialchars($legal_description ?? ''); ?>">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?php echo htmlspecialchars($legal_canonical); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($legal_full_title); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($legal_description ?? ''); ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo htmlspecialchars($legal_canonical); ?>">
  <meta property="og:image" content="<?php echo htmlspecialchars($legal_og_image); ?>">
  <meta property="og:site_name" content="Samutkarsh IAS Academy">
  <meta name="twitter:card" content="summary">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($legal_full_title); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($legal_description ?? ''); ?>">
  <link rel="icon" type="image/webp" href="assets/images/logo.webp">
  <?php require_once __DIR__ . '/ga4.php'; ?>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    .legal-page { min-height: 100vh; background: linear-gradient(to bottom right, #f9fafb, #f1f5f9); }
    .legal-card { border: 1px solid var(--slate-100); border-radius: 0.5rem; overflow: hidden; }
    .legal-card .card-header { background: #fff; border-bottom: 1px solid var(--slate-100); font-weight: 600; }
  </style>
</head>
<body class="legal-page">
