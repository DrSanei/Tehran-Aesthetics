<?php
/**
 * Plugin Name: TA AutoBuilder EX
 * Description: Creates pages & sections like the benchmark site. Persian content, sample images (SVG), grids (services, branches, offers), and consult/reserve pages.
 * Version: 2.0.0
 * Author: Healio
 */
if (!defined('ABSPATH')) exit;

class TA_AutoBuilder_EX {
  public function __construct(){
    register_activation_hook(__FILE__, [$this,'activate']);
    add_shortcode('ta_home', [$this,'home']);
    add_action('wp_enqueue_scripts', [$this,'assets']);
  }
  public function assets(){
    wp_enqueue_style('ta-autobuilder-ex', plugins_url('assets/auto.css', __FILE__), [], '2.0.0');
  }
  private function page($title,$content){
    $p = get_page_by_title($title);
    if ($p){ wp_update_post(['ID'=>$p->ID,'post_content'=>$content]); return $p->ID; }
    return wp_insert_post(['post_title'=>$title,'post_type'=>'page','post_status'=>'publish','post_content'=>$content,'post_author'=>get_current_user_id()]);
  }
  public function activate(){
    $home    = $this->page('خانه','[ta_home]');
    $services= $this->page('خدمات',$this->services());
    $branches= $this->page('شعب',$this->branches());
    $tips    = $this->page('نکات زیبایی',$this->tips());
    $offers  = $this->page('تخفیف‌ها',$this->offers());
    $reserve = $this->page('رزرو نوبت','[ta_booking]');
    $consult = $this->page('مشاوره آنلاین',$this->consult());

    update_option('show_on_front','page');
    update_option('page_on_front',$home);

    update_option('ta_nav_home', get_permalink($home));
    update_option('ta_nav_services', get_permalink($services));
    update_option('ta_nav_branches', get_permalink($branches));
    update_option('ta_nav_consult', get_permalink($consult));
    update_option('ta_nav_reserve', get_permalink($reserve));
  }
  public function home(){
    ob_start(); ?>
    <div class="ta-home" dir="rtl">
      <!-- Hero with finder -->
      <section class="hero">
        <div class="hero-text">
          <h1>کلینیک زیبایی تهران اِستتیکس</h1>
          <p>زیبایی، پوست و مو — خدمات پیشرفته با تیم متخصص</p>
        </div>
        <div class="finder">
          <form action="<?php echo esc_url( home_url('/reserve') ); ?>" method="get">
            <select name="service">
              <option>انتخاب خدمت</option>
              <option>کاشت مو</option><option>کاشت ابرو</option><option>بلفاروپلاستی</option><option>بوتاکس</option>
            </select>
            <select name="branch">
              <option>انتخاب شعبه</option>
              <option>شعبه تهران</option><option>شعبه کرج</option>
            </select>
            <button type="submit">جستجو / رزرو</button>
          </form>
        </div>
      </section>

      <!-- Services grid -->
      <section class="services">
        <h2>خدمات</h2>
        <div class="grid">
          <?php foreach(['کاشت مو','کاشت ابرو','بلفاروپلاستی','تزریق ژل','لیزر موهای زائد','کانتورینگ چهره','میکروبلیدینگ','جوانسازی پوست'] as $srv){
            echo '<a class="card" href="'. esc_url( home_url('/services') ) .'"><img src="'. plugins_url('assets/svc.svg', __FILE__) .'" alt=""><div class="t">'.$srv.'</div></a>';
          } ?>
        </div>
      </section>

      <!-- Offers teaser -->
      <section class="offers">
        <h2>پکیج‌ها و تخفیف‌ها</h2>
        <div class="grid">
          <?php for($i=1;$i<=4;$i++){ echo '<a class="card" href="'. esc_url( home_url('/offers') ) .'"><img src="'. plugins_url('assets/offer.svg', __FILE__) .'" alt=""><div class="t">پکیج ویژه '.$i.'</div></a>'; } ?>
        </div>
      </section>

      <!-- Branches teaser -->
      <section class="branches">
        <h2>شعب و آدرس‌ها</h2>
        <div class="grid">
          <a class="card" href="<?php echo esc_url( home_url('/branches') ); ?>"><img src="<?php echo plugins_url('assets/branch.svg', __FILE__); ?>" alt=""><div class="t">شعبه تهران</div></a>
          <a class="card" href="<?php echo esc_url( home_url('/branches') ); ?>"><img src="<?php echo plugins_url('assets/branch.svg', __FILE__); ?>" alt=""><div class="t">شعبه کرج</div></a>
        </div>
      </section>
    </div>
    <?php return ob_get_clean();
  }
  private function services(){
    return '<div class="ta-page" dir="rtl"><h2>خدمات زیبایی</h2><div class="grid">'. $this->cards(['کاشت مو','کاشت ابرو','بلفاروپلاستی','تزریق ژل','بوتاکس','لیزر موهای زائد','فرم‌دهی بینی بدون جراحی','لیفت صورت']).'</div></div>';
  }
  private function branches(){
    $img = plugins_url('assets/branch.svg', __FILE__);
    $html = '<div class="ta-page" dir="rtl"><h2>شعب</h2>';
    $html .= '<div class="branch"><img src="'.$img.'" alt=""><div><h3>شعبه تهران</h3><p>تهران، خیابان مثال، پلاک ۱۲</p><p>۰۲۱-۱۲۳۴۵۶۷۸</p><iframe src="https://maps.google.com" style="width:100%;height:220px;border:0;"></iframe></div></div>';
    $html .= '<div class="branch"><img src="'.$img.'" alt=""><div><h3>شعبه کرج</h3><p>کرج، بلوار نمونه، پلاک ۸</p><p>۰۲۶-۱۲۳۴۵۶۷</p><iframe src="https://maps.google.com" style="width:100%;height:220px;border:0;"></iframe></div></div>';
    $html .= '</div>';
    return $html;
  }
  private function tips(){
    return '<div class="ta-page" dir="rtl"><h2>نکات زیبایی</h2><div class="grid">'. $this->cards(['روتین مراقبت پوست','کاهش لک و تیرگی','مراقبت بعد از بوتاکس','استفاده صحیح از ضدآفتاب']).'</div></div>';
  }
  private function offers(){
    return '<div class="ta-page" dir="rtl"><h2>تخفیف‌ها و پکیج‌ها</h2><div class="grid">'. $this->cards(['پکیج جوانسازی','پکیج لیزر','پکیج بوتاکس','پکیج مراقبت مو']).'</div></div>';
  }
  private function consult(){
    return '<div class="ta-page" dir="rtl"><h2>مشاوره آنلاین</h2><p>برای مشاوره غیرهمزمان روی دکمه زیر بزنید.</p><p><a class="btn" href="'. esc_url( home_url('/consult') ) .'">شروع مشاوره</a></p><p>یا از طریق تلگرام: <a href="https://t.me/TehranAesthetics" target="_blank">@TehranAesthetics</a></p></div>';
  }
  private function cards($titles){
    $out=''; foreach($titles as $t){ $out .= '<a class="card" href="#"><img src="'. plugins_url('assets/svc.svg', __FILE__) .'" alt=""><div class="t">'. esc_html($t) .'</div></a>'; } return $out;
  }
}
new TA_AutoBuilder_EX();
