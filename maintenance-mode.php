<?php
/**
 * Plugin Name: Website Maintenance Mode
 * Description: Set your site to Maintenance Mode to take it offline temporarily (status code 503), or to Coming Soon mode (status code 200), taking it offline until it is ready to be launched.
 * Author: Tahir Nasim
 * Author URI: https://TahirNasim.com
 * Version: 1.0
 */


 
// Enqueue Styles and Scripts
function mm_enqueue_scripts() {
    // Check if maintenance mode is enabled and the user is not logged in
    if (get_option('enable_maintenance_mode') == '1' && !is_user_logged_in()) {
        
        // Enqueue jQuery (WordPress includes jQuery by default, no need to download it externally)
        // wp_enqueue_script('jquery');
        
        // Enqueue your script.js for the countdown functionality, make sure it's dependent on jQuery
        // wp_enqueue_script('countdown-script', plugin_dir_url(__FILE__) . 'script.js', array('jquery'), null, true);

        // Enqueue the CSS file for styling (if necessary)
        wp_enqueue_style('maintenance-style', plugin_dir_url(__FILE__) . 'style.css');
    }
}
add_action('wp_enqueue_scripts', 'mm_enqueue_scripts');



// Function to check if maintenance mode is enabled
function maintenance_mode() {
    // Check if maintenance mode is enabled
    if (get_option('enable_maintenance_mode') == '1') {
        // Check if the private access key is present in the URL
        if (isset($_GET['access_key']) && $_GET['access_key'] == get_option('private_access_key')) {
            // Valid private access key, set a session cookie to grant access
            setcookie('private_access_granted', '1', time() + 3600, '/');
            return; // Bypass maintenance mode
        }

        // If the user doesn't have the private access cookie, show the maintenance page
        if (!isset($_COOKIE['private_access_granted'])) {
            // Include the maintenance mode template file
            include plugin_dir_path(__FILE__) . 'maintenance-template.php';
            exit; // Stop further execution to prevent loading the rest of the site
        }
    }
}
// Hook into WordPress template redirect to check for maintenance mode
add_action('template_redirect', 'maintenance_mode');

// Create settings menu in the dashboard
function mm_add_admin_menu() {
    add_menu_page('Maintenance Mode', 'Maintenance Mode', 'manage_options', 'maintenance-mode', 'mm_settings_page', 'dashicons-admin-tools');
}
add_action('admin_menu', 'mm_add_admin_menu');





// Admin settings page
function mm_settings_page() {
    // Handle form submission
    $message = '';

    // Check if the form was submitted
    if (isset($_POST['submit'])) {
        // If the checkbox is checked, enable maintenance mode
        if (isset($_POST['enable_maintenance_mode']) && $_POST['enable_maintenance_mode'] == '1') {
            update_option('enable_maintenance_mode', '1');
            $private_access_key = generate_private_access_key();
            $message = 'Maintenance Mode is Activated';
        } else {
            // If the checkbox is not checked, disable maintenance mode
            update_option('enable_maintenance_mode', '0');
            delete_option('private_access_key');
            $message = 'Maintenance Mode is Deactivated';
        }

        // Set a transient to store the message
        set_transient('mm_mode_status', $message, 5); // 5 seconds expiry for the transient
    }

    // Check if there's a transient message to display
    if ($message = get_transient('mm_mode_status')) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
        delete_transient('mm_mode_status'); // Remove the transient after displaying it
    }

    // Show the private access link if maintenance mode is enabled
    ?>
    <div class="wrap">
        <h1>Maintenance Mode Settings</h1>
        <p>Set your site to Maintenance Mode to take it offline temporarily (status code 503), or to Coming Soon mode (status code 200), until it's ready to launch. </p>
        <form method="post" action="">
            <table class="form-table">
                <tr>
                    <th scope="row">Enable Maintenance Mode</th>
                    <td>
                        <input type="checkbox" name="enable_maintenance_mode" value="1" <?php checked(get_option('enable_maintenance_mode'), '1'); ?> />
                    </td>
                </tr>
            </table>
            <?php submit_button('Save Changes', 'primary', 'submit'); ?>
        </form>

        <?php if (get_option('enable_maintenance_mode') == '1') : ?>
            <h2>Private Access Link</h2>
            <p>
                Share this link with people who need access to the site while it is in maintenance mode:<br>
                <a href="<?php echo home_url() . '/?access_key=' . get_option('private_access_key'); ?>">
                    <?php echo home_url() . '/?access_key=' . get_option('private_access_key'); ?>
                </a>
            </p>
        <?php endif; ?>
    </div>
    <?php
}





// Generate a private access key
function generate_private_access_key() {
    $key = wp_generate_password(10, false); // Generate a random string
    update_option('private_access_key', $key); // Save the key in the database
    return $key;
}

