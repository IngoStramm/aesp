<?php

/**
 * Plugin Name: AESP
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do AESP.
 * Version: 0.0.20
 * Author: Ingo Stramm
 * Text Domain: aesp
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('AESP_DIR', plugin_dir_path(__FILE__));
define('AESP_URL', plugin_dir_url(__FILE__));

function aesp_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

require_once 'tgm/tgm.php';
require_once 'classes/classes.php';
require_once 'scripts.php';
require_once 'post-type/post-type.php';
require_once 'taxonomy/taxonomy.php';
require_once 'post-cmb.php';
require_once 'user-cmb.php';
require_once 'settings.php';
require_once 'post-type.php';
require_once 'shortcode.php';
require_once 'functions.php';

require_once 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/aesp/master/info.json',
    __FILE__,
    'aesp'
);
