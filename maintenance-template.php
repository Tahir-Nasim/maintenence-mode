<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode</title>
    <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . 'assets/css/style.css'; ?>">
    
</head>
<body>
    <div class="maintenance-container">
        <h1>We're Under Maintenance</h1>
        <p>Our website is temporarily down for maintenance. We'll be back shortly!</p><br><p>We'll be Live in </p><br> 
        <div id="countdown"></div>
    </div>

    <!-- enqueued jQuery was not Working. So I included jQuery from a CDN in here. -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo plugin_dir_url(__FILE__) . 'assets/js/script.js'; ?>"></script>
</body>
</html>
