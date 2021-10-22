<?php

add_action('cmb2_admin_init', 'aesp_register_options_metabox');

function aesp_register_options_metabox()
{
    global $aesp_pages_array, $aesp_moeda;
    /**
     * Registers options page menu item and form.
     */
    $cmb_options = new_cmb2_box(array(
        'id'           => 'aesp_option_metabox',
        'title'        => esc_html__('Configurações AESP', 'aesp'),
        'object_types' => array('options-page'),
        'option_key'      => 'aesp_options', // The option key and admin menu page slug.
        'icon_url'        => 'dashicons-admin-generic', // Menu icon. Only applicable if 'parent_slug' is left empty.
        'menu_title'      => esc_html__('AESP', 'aesp'), // Falls back to 'title' (above).
        // 'parent_slug'     => 'themes.php', // Make options page a submenu item of the themes menu.
        // 'capability'      => 'manage_options', // Cap required to view options-page.
        // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        // 'save_button'     => esc_html__( 'Save Theme Options', 'aesp' ), // The text for the options-page save button. Defaults to 'Save'.
    ));

    $cmb_options->add_field(array(
        'name'    => __('Página de login', 'aesp'),
        'id'      => 'aesp_login_page',
        'type'    => 'select',
        'show_option_none' => true,
        'options_cb'   => function () {
            $pages = get_pages();
            $pages_array = [];
            foreach ($pages as $page) {
                $pages_array[$page->ID] = $page->post_title;
            }
            return $pages_array;
        }
    ));
}

function aesp_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('aesp_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('aesp_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}
