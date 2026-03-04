<?php
/**
 * Google Analytics 4 (GA4)
 * Include in <head> of every page. Uses G-9N10JXHHR0.
 */
if (!defined('GA4_MEASUREMENT_ID')) {
    define('GA4_MEASUREMENT_ID', 'G-9N10JXHHR0');
}
?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo GA4_MEASUREMENT_ID; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?php echo GA4_MEASUREMENT_ID; ?>', {
    'anonymize_ip': true,
    'page_title': document.title,
    'page_location': window.location.href
  });
</script>
