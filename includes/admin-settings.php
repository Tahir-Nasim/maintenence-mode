<?php
// Admin Settings Code
if (isset($_POST['enable_maintenance_mode'])) {
    if ($_POST['enable_maintenance_mode'] == '1') {
        update_option('enable_maintenance_mode', '1');
        $private_access_key = generate_private_access_key();
    } else {
        update_option('enable_maintenance_mode', '0');
        delete_option('private_access_key');
    }
}
