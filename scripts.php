<?php

add_action('wp_enqueue_scripts', 'aesp_frontend_scripts');

function aesp_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1', '10.0.0.3'))) ? '' : '.min';

    if (empty($min)) :
        wp_enqueue_script('aesp-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    wp_register_script('aesp-script', AESP_URL . 'assets/js/aesp' . $min . '.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('aesp-script');

    wp_localize_script('aesp-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('aesp-style', AESP_URL . 'assets/css/aesp.css', array(), filemtime(AESP_DIR . '/assets/css/aesp.css'), 'all');
}
