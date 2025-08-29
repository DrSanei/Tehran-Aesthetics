<?php
// Helper to link by Persian title safely
function ta_link_by_title($title, $fallback = ''){
  $p = get_page_by_title($title);
  if ($p) return get_permalink($p);
  // If fallback is an absolute URL, return it directly
  if ($fallback && preg_match('#^https?://#i', $fallback)) return $fallback;
  // Otherwise treat as site-relative path
  return home_url($fallback ?: '/');
}


// Load styles + header/menu script
add_action('wp_enqueue_scripts', function(){
  wp_enqueue_style('ta-child', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
  wp_enqueue_script('ta-menu', get_stylesheet_directory_uri().'/menu.js', [], wp_get_theme()->get('Version'), true);
});

// Header bar output (centered logo + menu icon)
add_action('wp_body_open', function(){
  $home    = home_url('/');
  $branches = ta_link_by_title('شعب', '/branches');
  $services = ta_link_by_title('خدمات', '/services');
  $tips     = ta_link_by_title('نکات زیبایی', '/beauty-tips');
  $offers   = ta_link_by_title('تخفیف‌ها', '/offers');
  $consult  = 'https://healio.ir'; // force external
  $reserve  = ta_link_by_title('رزرو نوبت', '/reserve');

  echo '<div class="ta-header">';
  // Menu button (icon + label)
echo '<button class="menu-btn" type="button" aria-label="منو" data-ta-menu-open>';
echo   '<span class="material-icons">menu</span>';
echo '</button>';




  // Logo (larger + linked to خانه)
  echo '<div class="logo">';
  if (function_exists('the_custom_logo') && has_custom_logo()) {
   $logo = wp_get_attachment_image( get_theme_mod('custom_logo'), 'full', false, [
  'style' => 'height:80px;width:auto'
]);

    echo '<a href="'. esc_url($home) .'" aria-label="خانه">'. $logo .'</a>';
  } else {
    echo '<a href="'. esc_url($home) .'" class="text-logo">Tehran-Aesthetics</a>';
  }
  echo '</div>';
  echo '</div>';

  // Global Offcanvas (shared by header & bottom nav)
  echo '<div class="ta-menu-overlay" id="taMenuOverlay" hidden>
          <div class="ta-menu-panel" role="dialog" aria-label="منوی سایت">
            <div class="ta-menu-head">
              <div class="ta-menu-title">منو</div>
              <button class="ta-menu-close" aria-label="بستن" data-ta-menu-close>✕</button>
            </div>
            <nav class="ta-menu-list" dir="rtl">
              <a href="'. esc_url( $home ) .'"><span>خانه</span><small>صفحه اصلی</small></a>
              <a href="'. esc_url( $branches ) .'"><span>شعب</span><small>آدرس و نقشه</small></a>
<a href="'. esc_url( $offers ) .'"><span>جشنواره %</span><small>تخفیف خدمات</small></a>
              <a href="'. esc_url( $tips ) .'"><span>نکات زیبایی</span><small>مقالات و مراقبت</small></a>
              <a href="'. esc_url( $offers ) .'"><span>تخفیف‌ها</span><small>پکیج‌ها و آفرها</small></a>
              <a href="'. esc_url( $consult ) .'" target="_blank" rel="noopener"><span>مشاوره آنلاین</span><small>غیرهمزمان</small></a>
              <a href="'. esc_url( $reserve ) .'"><span>رزرو نوبت</span><small>تقویم فارسی</small></a>
              <a href="https://t.me/TehranAesthetics" target="_blank" rel="noopener"><span>تلگرام</span><small>@TehranAesthetics</small></a>
            </nav>
            <div class="ta-cta">
              <a class="btn btn-primary" href="'. esc_url( $home ) .'">خانه</a>
              <a class="btn btn-ghost" href="'. esc_url( $consult ) .'" target="_blank" rel="noopener">مشاوره آنلاین</a>
            </div>
          </div>
        </div>';
});

// Footer copyright
add_action('wp_footer', function(){
  echo '<div class="footer-copy">همه حقوق و منافع این سایت متعلق به شرکت لوتوس طب فراگیر می‌باشد.</div>';
});

add_action('template_redirect', function() {
  // English slug
  if (is_page('consult')) {
    wp_redirect('https://healio.ir', 301);
    exit;
  }

  // Persian slug (مشاوره-آنلاین)
  if (is_page('مشاوره-آنلاین')) {
    wp_redirect('https://healio.ir', 301);
    exit;
  }
});
