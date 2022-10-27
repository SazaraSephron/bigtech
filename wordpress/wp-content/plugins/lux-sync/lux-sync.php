<?php

/**
 * Plugin Name: Lux Sync
 * description: A plugin to sync the wordpress websites data to the mobile apps data
 * Version: 1.0
 * Author: Rhys Slater, Rahul Pratap, Jacob Dawe, Andrew Cui
 */

use ParagonIE\Sodium\Core\Curve25519\Ge\P2;

session_start();


include("database.php");
include("convert_data.php");
include("sync_data.php");
include("booking_page.php");
include("external_connection.php");




function add_cron_interval_five_minutes($schedules)
{
  $schedules['five_minutes'] = array(
    'interval' => 300,
    'display'  => esc_html__('Every Five Minutes'),
  );
  return $schedules;
}
add_filter('cron_schedules', 'add_cron_interval_five_minutes');

// Create a hook to call every five minutes
function schedule_my_cron_events()
{
  if (!wp_next_scheduled('ls_convert_sync')) {
    wp_schedule_event(time(), 'five_minutes', 'ls_convert_sync');
  }
  // add_action('ls_convert_sync', 'conv_and_sync');
}
add_action('ls_convert_sync', 'conv_and_sync');

add_action('init', 'schedule_my_cron_events');


/**
 * Activation hook
 */
function ls_activation()
{

  
  wp_schedule_event(current_time('timestamp'), 'five_minutes', 'ls_convert_sync');
}
register_deactivation_hook(__FILE__, 'ls_activation');




/**
 * Deactivation hook.
 */
function ls_deactivation()
{
  
  // Unshedule the task to run every five minutes
  wp_clear_scheduled_hook('cron_every_5_seconds');
  $timestamp = wp_next_scheduled('ls_cron_hook_convert');
  wp_unschedule_event($timestamp, 'ls_convert_sync');
}
register_deactivation_hook(__FILE__, 'ls_deactivation');


add_action('show_user_profile', 'extra_user_profile_fields');
add_action('edit_user_profile', 'extra_user_profile_fields');

function extra_user_profile_fields($user)
{ ?>
  <h3><?php _e("Extra profile information", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="phone"><?php _e("Phone"); ?></label></th>
      <td>
        <input type="text" name="phone" id="phone" value="<?php echo esc_attr(get_user_meta('phone', $user->ID)); ?>" class="regular-text" /><br />
        <span class="description"><?php _e("Please enter your phone number."); ?></span>
      </td>
    </tr>
  </table>
  <?php }

add_action('personal_options_update', 'save_extra_user_profile_fields');
add_action('edit_user_profile_update', 'save_extra_user_profile_fields');

function save_extra_user_profile_fields($user_id)
{
  if (empty($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_' . $user_id)) {
    return;
  }

  if (!current_user_can('edit_user', $user_id)) {
    return false;
  }
  update_user_meta($user_id, 'phone', $_POST['phone']);
}

add_shortcode('lux-instant-booking', 'instant_booking');

function instant_booking()
{
  $result = '';

  if (is_user_logged_in()) {
  ?>
    <style>
      #instant_book {
        background-color: #EFA81E;
        border: none;
        color: white;
        padding: 16px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        transition-duration: 0.4s;
        cursor: pointer;
        width: 40%;

      }

      #instant_book {
        background-color: white;
        color: black;
        border: 2px solid #EFA81E;
      }

      #instant_book:hover {
        background-color: #EFA81E;
        color: white;
    </style>
<?php
    $result = $result . '<div style="text-align:center;"><form action="" method="post">
  <button type="submit" id="instant_book" name="instant_book">Instant Booking</button>
</form><p>This will create a booking for right now.</p></div>';
  }
  return $result;
}

if (isset($_POST["instant_book"])) {
  $current_user_id = get_current_user_id();
  date_default_timezone_set("Australia/Sydney");
  $date = date("Y-m-d");
  $time = date("h:iA");
  $ls_sql = "INSERT INTO `booking` (`user_id`, `technician_id`, `date_booked`, `time_booked`, `status`) VALUES ";
  $ls_sql = $ls_sql . "('$current_user_id', '0', '$date', '$time', 'Pending');";
  if ($ls_conn->query($ls_sql) === TRUE) {
  } else {
    echo "Error: " . $ls_sql . "<br>" . $ls_conn->error;
  }
}
