<?php
/**
 * Plugin Name: TA Bottom Nav
 * Description: Fixed bottom navigation (mobile-first, RTL). Includes a Menu button that opens the site offcanvas menu.
 * Version: 2.0.0
 * Author: Healio
 * Text Domain: ta-bottom-nav
 */
if (!defined('ABSPATH')) exit;

class TA_Bottom_Nav {
  public function __construct(){
    add_action('wp_enqueue_scripts', [$this,'assets']);
    add_action('wp_footer', [$this,'render'], 1);
    add_action('admin_menu', [$this,'menu']);
    add_action('admin_init', [$this,'settings']);
  }
  public function assets(){
    wp_enqueue_style('ta-bottom-nav', plugins_url('assets/nav.css', __FILE__), [], '2.0.0');
    wp_enqueue_script('ta-bottom-nav', plugins_url('assets/nav.js', __FILE__), [], '2.0.0', true);
  }
  public function settings(){
    register_setting('ta_bottom_nav','ta_nav_home');
    register_setting('ta_bottom_nav','ta_nav_services');
    register_setting('ta_bottom_nav','ta_nav_consult');
    register_setting('ta_bottom_nav','ta_nav_reserve');
    register_setting('ta_bottom_nav','ta_nav_branches');
    register_setting('ta_bottom_nav','ta_nav_telegram');
  }
  public function menu(){
    add_options_page('TA Bottom Nav','TA Bottom Nav','manage_options','ta-bottom-nav',[$this,'page']);
  }
  public function page(){ ?>
    <div class="wrap" dir="rtl"><h1>تنظیمات منوی پایین</h1>
      <form method="post" action="options.php">
        <?php settings_fields('ta_bottom_nav'); do_settings_sections('ta_bottom_nav'); ?>
        <table class="form-table">
          <tr><th>خانه</th><td><input type="text" name="ta_nav_home" value="<?php echo esc_attr(get_option('ta_nav_home', home_url('/'))); ?>" class="regular-text"></td></tr>
          <tr><th>خدمات</th><td><input type="text" name="ta_nav_services" value="<?php echo esc_attr(get_option('ta_nav_services', home_url('/services'))); ?>" class="regular-text"></td></tr>
          <tr><th>مشاوره آنلاین</th><td><input type="text" name="ta_nav_consult" value="<?php echo esc_attr(get_option('ta_nav_consult', home_url('/consult'))); ?>" class="regular-text"></td></tr>
          <tr><th>رزرو نوبت</th><td><input type="text" name="ta_nav_reserve" value="<?php echo esc_attr(get_option('ta_nav_reserve', home_url('/reserve'))); ?>" class="regular-text"></td></tr>
          <tr><th>شعب</th><td><input type="text" name="ta_nav_branches" value="<?php echo esc_attr(get_option('ta_nav_branches', home_url('/branches'))); ?>" class="regular-text"></td></tr>
          <tr><th>تلگرام</th><td><input type="text" name="ta_nav_telegram" value="<?php echo esc_attr(get_option('ta_nav_telegram', 'https://t.me/TehranAesthetics')); ?>" class="regular-text"></td></tr>
        </table>
        <?php submit_button(); ?>
      </form></div>
  <?php }
  public function render(){
    if (is_admin()) return;
    $links = [
      'home' => esc_url( get_option('ta_nav_home', home_url('/')) ),
      'services' => esc_url( get_option('ta_nav_services', home_url('/services')) ),
      'consult' => esc_url( get_option('ta_nav_consult', home_url('/consult')) ),
      'reserve' => esc_url( get_option('ta_nav_reserve', home_url('/reserve')) ),
      'branches' => esc_url( get_option('ta_nav_branches', home_url('/branches')) ),
    ];
    echo '<nav class="ta-bottom-nav" dir="rtl" aria-label="Bottom Navigation">';
    echo '  <button class="btn" type="button" data-ta-menu-open><span class="ico">☰</span><span>منو</span></button>';
    echo '  <a class="btn" href="'. $links['services'] .'"><span class="ico">💉</span><span>خدمات</span></a>';
    echo '  <a class="btn prominent" href="'. $links['consult'] .'"><span class="ico">💬</span><span>مشاوره آنلاین</span></a>';
    echo '  <a class="btn" href="'. $links['reserve'] .'"><span class="ico">🗓️</span><span>رزرو نوبت</span></a>';
    echo '  <a class="btn" href="'. $links['branches'] .'"><span class="ico">📍</span><span>شعب</span></a>';
    echo '</nav>';
  }
}
new TA_Bottom_Nav();
