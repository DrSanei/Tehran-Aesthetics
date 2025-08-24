
<?php
/**
 * Plugin Name: TA AutoBuilder
 * Description: Auto-creates Tehran-Aesthetics pages and content with RTL sections, services, branches, and booking integration.
 * Version: 1.0.0
 * Author: Healio
 */

if (!defined('ABSPATH')) exit;

class TA_AutoBuilder {
  public function __construct(){
    register_activation_hook(__FILE__, [$this, 'activate']);
    add_shortcode('ta_home', [$this, 'home_shortcode']);
    add_action('wp_enqueue_scripts', [$this, 'assets']);
  }

  public function assets(){
    wp_enqueue_style('ta-autobuilder', plugins_url('assets/autobuilder.css', __FILE__), [], '1.0.0');
  }

  public function activate(){
    $home_id   = $this->ensure_page('خانه', '[ta_home]');
    $services_id = $this->ensure_page('خدمات', '<h2>لیست خدمات</h2>');
    $branches_id = $this->ensure_page('شعب', '<h2>لیست شعب</h2>');
    $reserve_id  = $this->ensure_page('رزرو نوبت', '[ta_booking]');
    $consult_id  = $this->ensure_page('مشاوره آنلاین', '<h2>مشاوره آنلاین</h2><p><a href="/step1">شروع مشاوره</a></p>');

    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_id);
    update_option('ta_nav_home', get_permalink($home_id));
    update_option('ta_nav_services', get_permalink($services_id));
    update_option('ta_nav_consult', get_permalink($consult_id));
    update_option('ta_nav_reserve', get_permalink($reserve_id));
    update_option('ta_nav_branches', get_permalink($branches_id));
  }

  private function ensure_page($title, $content){
    $page = get_page_by_title($title);
    if ($page) {
      wp_update_post([ 'ID' => $page->ID, 'post_content' => $content ]);
      return $page->ID;
    }
    return wp_insert_post([
      'post_title'   => $title,
      'post_type'    => 'page',
      'post_status'  => 'publish',
      'post_content' => $content,
      'post_author'  => get_current_user_id()
    ]);
  }

  public function home_shortcode(){
    ob_start(); ?>
    <div class="ta-home" dir="rtl">
      <section class="doctor-intro">
        <div class="ph">[تصویر پزشک]</div>
        <h2>دکتر محمد صانعی</h2>
        <p>تحت نظارت MD, PhD زیبایی، پوست، مو</p>
        <a href="/step1" class="btn">مشاوره آنلاین</a>
      </section>
      <section class="offers">
        <div class="banner">[بنر پیشنهادها]</div>
      </section>
      <section class="services">
        <h3>خدمات</h3>
        <div class="cards">
          <div class="card">کاشت مو</div>
          <div class="card">کاشت ابرو</div>
          <div class="card">بلفاروپلاستی</div>
          <div class="card">تزریق ژل</div>
          <div class="card">بوتاکس</div>
        </div>
      </section>
      <section class="branches">
        <h3>شعب</h3>
        <div class="cards">
          <div class="card">شعبه تهران - آدرس و تلفن</div>
          <div class="card">شعبه کرج - آدرس و تلفن</div>
        </div>
      </section>
    </div>
    <?php
    return ob_get_clean();
  }
}
new TA_AutoBuilder();
