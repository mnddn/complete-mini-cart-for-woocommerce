<div class="wrap">
    <h1>Mini Cart Shortcode Styles</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('cmcw_settings');
        do_settings_sections('cmcw_shortcode');
        submit_button();
        ?>
    </form>
</div>