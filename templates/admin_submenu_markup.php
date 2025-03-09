<?php

if (!defined('ABSPATH')) {
    exit;
}

?>

<div class="wrap">
    <h1>Mini Cart Shortcode Styles</h1>
    <p>Use this shortcode &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong> [cmcw_mini_cart] </strong></p>
    <form method="post" action="options.php">
        <?php
        settings_fields('cmcw_options_group');
        do_settings_sections('cmcw_shortcode');
        submit_button();
        ?>
    </form>
</div>