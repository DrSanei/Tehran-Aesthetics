<?php
// Helper to link by Persian title safely
function ta_link_by_title($title, $fallback){
  $p = get_page_by_title($title);
  return $p ? get_permalink($p) : home_url($fallback);
}

// Load styles + header/menu script
add_action('wp_enqueue_scripts', function(){
  wp_enqueue_style('ta-child', get_stylesheet_uri(), [], wp_get_theme()->get('Version'));
  wp_enqueue_script('ta-menu', get_stylesheet_directory_uri().'/menu.js', [], wp_get_theme()->get('Version'), true);
});

// Header bar output (centered logo + menu icon)
add_action('wp_body_open', function(){
  echo '<div class="ta-header">';
  echo '<button class="menu-btn" type="button" aria-label="منو" data-ta-menu-open>';
  echo '<span class="bar"></span><span class="bar"></span><span class="bar"></span>';
  echo '</button>';
  if (function_exists('the_custom_logo') && has_custom_logo()) {
    echo '<div class="logo">'. wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, ['style'=>'height:26px;width:auto'] ) .'</div>';
  } else {
    echo '<div class="logo"><span>Tehran‑Aesthetics</span></div>';
  }
  echo '</div>';
  // Global Offcanvas (shared by header & bottom nav)
  $branches = ta_link_by_title('شعب', '/branches');
  $services = ta_link_by_title('خدمات', '/services');
  $tips     = ta_link_by_title('نکات زیبایی', '/beauty-tips');
  $offers   = ta_link_by_title('تخفیف‌ها', '/offers');
  $consult  = ta_link_by_title('مشاوره آنلاین', '/consult');
  $reserve  = ta_link_by_title('رزرو نوبت', '/reserve');

  echo '<div class="ta-menu-overlay" id="taMenuOverlay" hidden>
          <div class="ta-menu-panel" role="dialog" aria-label="منوی سایت">
            <div class="ta-menu-head">
              <div class="ta-menu-title">منو</div>
              <button class="ta-menu-close" aria-label="بستن" data-ta-menu-close>✕</button>
            </div>
            <nav class="ta-menu-list" dir="rtl">
              <a href="'. esc_url( $branches ) .'"><span>شعب</span><small>آدرس و نقشه</small></a>
              <a href="'. esc_url( $services ) .'"><span>خدمات</span><small>خدمات زیبایی</small></a>
              <a href="'. esc_url( $tips ) .'"><span>نکات زیبایی</span><small>مقالات و مراقبت پوست</small></a>
              <a href="'. esc_url( $offers ) .'"><span>تخفیف‌ها</span><small>پکیج‌ها و آفرها</small></a>
              <a href="'. esc_url( $consult ) .'"><span>مشاوره آنلاین</span><small>غیرهمزمان</small></a>
              <a href="'. esc_url( $reserve ) .'"><span>رزرو نوبت</span><small>تقویم فارسی</small></a>
              <a href="https://t.me/TehranAesthetics" target="_blank" rel="noopener"><span>تلگرام</span><small>@TehranAesthetics</small></a>
            </nav>
            <div class="ta-cta">
              <a class="btn btn-primary" href="'. esc_url( $reserve ) .'">رزرو سریع نوبت</a>
              <a class="btn btn-ghost" href="'. esc_url( $consult ) .'">مشاوره آنلاین</a>
            </div>
          </div>
        </div>';
});

// Footer copyright
add_action('wp_footer', function(){
  echo '<div class="footer-copy">همه حقوق و منافع این سایت متعلق به شرکت لوتوس طب فراگیر می‌باشد.</div>';
});
