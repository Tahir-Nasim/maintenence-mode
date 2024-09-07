<?php
function maintenance_mode_menu() {
    add_menu_page(
        'Maintenance Mode Settings',
        'Maintenance Mode',
        'manage_options',
        'maintenance-mode',
        'maintenance_mode_settings_page',
        'dashicons-hammer',
        90
    );
}
add_action('admin_menu', 'maintenance_mode_menu');


// Function to generate private access link key
function generate_private_access_key() {
    $key = wp_generate_password(10, false);
    update_option('private_access_key', $key);
    return $key;
}

// Hook this into your settings save action
if (isset($_POST['enable_maintenance_mode'])) {
    if ($_POST['enable_maintenance_mode'] == '1') {
        // Maintenance mode is enabled
        $private_access_key = generate_private_access_key();
    }
}

function maintenance_mode_settings_page() {
    if (isset($_POST['maintenance_mode_status'])) {
        update_option('maintenance_mode_active', $_POST['maintenance_mode_status']);
    }

/* The code snippet */
    $maintenance_status = get_option('maintenance_mode_active', 'disabled');
    $private_link = wp_generate_password(10, false);

    ?>
    <div class="wrap">
        <h1>Maintenance Mode Settings</h1>
        <form method="POST">
            <label>
                <input type="radio" name="maintenance_mode_status" value="enabled" <?php checked($maintenance_status, 'enabled'); ?>> Enable
            </label>
            <label>
                <input type="radio" name="maintenance_mode_status" value="disabled" <?php checked($maintenance_status, 'disabled'); ?>> Disable
            </label>
            <br><br>
            <input type="submit" value="Save Changes" class="button button-primary">
        </form>
        <br>
        <h2>Private Access Link:</h2>
        <p>Use the following link to access the site while in maintenance mode:</p>
        <p><strong><?php echo site_url("?access=$private_link"); ?></strong></p>
    </div>
    <?php
}
