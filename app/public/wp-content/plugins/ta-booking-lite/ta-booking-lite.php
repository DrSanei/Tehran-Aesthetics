<?php
/**
 * Plugin Name: TA Booking Lite
 * Description: 4-step RTL booking with Jalali (Persian) calendar. Sends entries to Google Sheets via webhook and CSV fallback. Payment redirect URL configurable.
 * Version: 2.0.0
 * Author: Healio
 */
if (!defined('ABSPATH')) exit;

class TA_Booking_Lite {
  public function __construct(){
    add_shortcode('ta_booking', [$this,'shortcode']);
    add_action('wp_enqueue_scripts', [$this,'assets']);
    add_action('admin_menu', [$this,'menu']);
    add_action('admin_init', [$this,'settings']);
  }
  public function assets(){
    wp_register_style('ta-booking', plugins_url('assets/booking.css', __FILE__), [], '2.0.0');
    wp_register_script('ta-jalali', plugins_url('assets/jalali.js', __FILE__), [], '2.0.0', true);
    wp_register_script('ta-booking', plugins_url('assets/booking.js', __FILE__), ['ta-jalali'], '2.0.0', true);
  }
  public function settings(){
    register_setting('ta_booking','ta_booking_clinics');
    register_setting('ta_booking','ta_booking_services');
    register_setting('ta_booking','ta_booking_webhook');
    register_setting('ta_booking','ta_booking_payment_url');
    register_setting('ta_booking','ta_booking_amount');
  }
  public function menu(){
    add_options_page('TA Booking Lite','TA Booking Lite','manage_options','ta-booking-lite',[$this,'page']);
  }
  public function page(){ ?>
    <div class="wrap" dir="rtl">
      <h1>تنظیمات رزرو نوبت</h1>
      <form method="post" action="options.php">
        <?php settings_fields('ta_booking'); do_settings_sections('ta_booking'); ?>
        <table class="form-table">
          <tr><th>شعب</th><td><input type="text" name="ta_booking_clinics" value="<?php echo esc_attr(get_option('ta_booking_clinics','شعبه تهران، شعبه کرج'));?>" class="regular-text"></td></tr>
          <tr><th>خدمات</th><td><input type="text" name="ta_booking_services" value="<?php echo esc_attr(get_option('ta_booking_services','کاشت مو، کاشت ابرو، بلفاروپلاستی, بوتاکس'));?>" class="regular-text"></td></tr>
          <tr><th>Webhook گوگل شیت</th><td><input type="url" name="ta_booking_webhook" value="<?php echo esc_attr(get_option('ta_booking_webhook',''));?>" class="regular-text"></td></tr>
          <tr><th>آدرس پرداخت</th><td><input type="url" name="ta_booking_payment_url" value="<?php echo esc_attr(get_option('ta_booking_payment_url','https://example.com/payment'));?>" class="regular-text"></td></tr>
          <tr><th>مبلغ (تومان)</th><td><input type="number" name="ta_booking_amount" value="<?php echo esc_attr(get_option('ta_booking_amount','380000'));?>" class="small-text"></td></tr>
        </table>
        <?php submit_button(); ?>
      </form>
    </div>
  <?php }
  private function csv_path(){
    $upload_dir = wp_upload_dir();
    return trailingslashit($upload_dir['basedir']).'ta-bookings.csv';
  }
  private function append_csv($row){
    $path = $this->csv_path();
    if (!file_exists($path)) {
      $fp = fopen($path, 'w'); fputcsv($fp, ['datetime','name','mobile','clinic','service','date','time']); fclose($fp);
    }
    $fp = fopen($path, 'a'); fputcsv($fp, $row); fclose($fp);
  }
  public function shortcode($atts){
    wp_enqueue_style('ta-booking');
    wp_enqueue_script('ta-booking');
    $step = isset($_GET['step']) ? intval($_GET['step']) : 1;
    $clinics = array_map('trim', explode(',', get_option('ta_booking_clinics','شعبه تهران، شعبه کرج')));
    $services = array_map('trim', explode(',', get_option('ta_booking_services','کاشت مو، کاشت ابرو، بلفاروپلاستی, بوتاکس')));
    $webhook = esc_url_raw(get_option('ta_booking_webhook',''));
    $payment_url = esc_url_raw(get_option('ta_booking_payment_url','https://example.com/payment'));
    $amount = intval(get_option('ta_booking_amount','380000'));

    if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['ta_booking_nonce']) && wp_verify_nonce($_POST['ta_booking_nonce'], 'ta_booking')){
      $name = sanitize_text_field($_POST['name'] ?? '');
      $mobile = sanitize_text_field($_POST['mobile'] ?? '');
      $clinic = sanitize_text_field($_POST['clinic'] ?? '');
      $service = sanitize_text_field($_POST['service'] ?? '');
      $date = sanitize_text_field($_POST['date'] ?? '');
      $time = sanitize_text_field($_POST['time'] ?? '');

      $this->append_csv([ current_time('mysql'), $name, $mobile, $clinic, $service, $date, $time ]);
      if ($webhook){
        wp_remote_post($webhook, [
          'headers'=>['Content-Type'=>'application/json; charset=utf-8'],
          'body'=> wp_json_encode([
            'datetime'=> current_time('mysql'),
            'name'=>$name,'mobile'=>$mobile,'clinic'=>$clinic,'service'=>$service,'date'=>$date,'time'=>$time,'amount'=>$amount
          ]),
          'timeout'=>8
        ]);
      }
      wp_redirect($payment_url); exit;
    }

    ob_start(); ?>
    <div class="ta-wizard" dir="rtl">
      <div class="ta-steps">
        <div class="dot <?php echo $step==1?'active':''; ?>">۱</div>
        <div class="dot <?php echo $step==2?'active':''; ?>">۲</div>
        <div class="dot <?php echo $step==3?'active':''; ?>">۳</div>
        <div class="dot <?php echo $step==4?'active':''; ?>">۴</div>
      </div>

      <?php if ($step===1): ?>
        <form method="get" class="ta-form">
          <input type="hidden" name="step" value="2" />
          <label>نام و نام خانوادگی</label>
          <input type="text" name="name" required placeholder="مثال: علی رضایی" />

          <label>شماره همراه</label>
          <input type="tel" name="mobile" required placeholder="09xxxxxxxxx" pattern="^09\d{9}$" />

          <label>انتخاب شعبه</label>
          <select name="clinic" required>
            <option value="">انتخاب کنید</option>
            <?php foreach($clinics as $c): ?><option value="<?php echo esc_attr($c); ?>"><?php echo esc_html($c); ?></option><?php endforeach; ?>
          </select>

          <label>انتخاب خدمت</label>
          <select name="service" required>
            <option value="">انتخاب کنید</option>
            <?php foreach($services as $s): ?><option value="<?php echo esc_attr($s); ?>"><?php echo esc_html($s); ?></option><?php endforeach; ?>
          </select>

          <button class="btn next" type="submit">انتخاب تاریخ و ساعت</button>
        </form>
      <?php elseif ($step===2): ?>
        <form method="get" class="ta-form">
          <input type="hidden" name="step" value="3" />
          <label>تاریخ (تقویم فارسی)</label>
          <input type="text" id="ta-jdate" name="date" required placeholder="مثال: 1404/06/02" readonly />
          <div id="ta-calendar"></div>

          <label>ساعت</label>
          <input type="time" name="time" required />

          <button class="btn next" type="submit">ادامه</button>
        </form>
      <?php elseif ($step===3): ?>
        <form method="post" class="ta-form">
          <?php wp_nonce_field('ta_booking','ta_booking_nonce'); ?>
          <input type="hidden" name="name" value="<?php echo esc_attr($_GET['name'] ?? ''); ?>" />
          <input type="hidden" name="mobile" value="<?php echo esc_attr($_GET['mobile'] ?? ''); ?>" />
          <input type="hidden" name="clinic" value="<?php echo esc_attr($_GET['clinic'] ?? ''); ?>" />
          <input type="hidden" name="service" value="<?php echo esc_attr($_GET['service'] ?? ''); ?>" />
          <input type="hidden" name="date" value="<?php echo esc_attr($_GET['date'] ?? ''); ?>" />
          <input type="hidden" name="time" value="<?php echo esc_attr($_GET['time'] ?? ''); ?>" />

          <div class="summary">مبلغ قابل پرداخت: <strong><?php echo number_format($amount); ?></strong> تومان</div>
          <button class="btn pay" type="submit">پرداخت آنلاین</button>
        </form>
      <?php else: ?>
        <div class="confirm">
          <div class="ok">✔</div>
          <p>نوبت شما با موفقیت ثبت شد.</p>
          <p class="sub">جزئیات نوبت به شماره همراه شما ارسال شد.</p>
        </div>
      <?php endif; ?>
    </div>
    <?php return ob_get_clean();
  }
}
new TA_Booking_Lite();
