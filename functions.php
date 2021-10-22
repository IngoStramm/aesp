<?php

// Verifica se o usuário está logado quando estiver acesso a área restrita
add_action('get_header', 'aesp_area_restrita_managment');

function aesp_area_restrita_managment()
{
    if (!is_archive('documento') && !is_tax('categoria-documento'))
        return;

    if (is_user_logged_in())
        return;
    $aesp_login_page = aesp_get_option('aesp_login_page');

    if (!$aesp_login_page) {
        wp_safe_redirect(get_site_url());
        exit;
    } else {
        // aesp_debug($aesp_login_page);
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
        $curr_url = urlencode($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

        // return aesp_debug(esc_url($curr_url));
        wp_safe_redirect(esc_url(get_page_link($aesp_login_page) . '?redirect=' . $curr_url));
        exit;
    }
}

// Shortcode para pegar o redirectionamento passado na query string
add_shortcode('get-redirect', 'aesp_get_redirect');
function aesp_get_redirect()
{
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;
    // if (!$redirect)
    //     return;
    return $redirect;
}

// Remove o admin bar para todos os usuários que não sejam administradores
add_action('after_setup_theme', 'aesp_remove_admin_bar');

function aesp_remove_admin_bar()
{
    show_admin_bar(false);
}

add_action('get_header', 'aesp_custom_admin_bar');

function aesp_custom_admin_bar()
{
    $current_user = wp_get_current_user();
    // $roles = (array) $current_user->roles;

    // if (isset($roles[0]) && ($roles[0] !== 'administrator' && $roles[0] !== 'editor')) {
?>
    <?php
    $redirect = null;
    $aesp_login_page = aesp_get_option('aesp_login_page');
    if ($aesp_login_page) {
        $redirect = get_page_link($aesp_login_page);
    }
    //get_page_link($aesp_login_page)
    ?>
    <div class="aesp-custom-admin-bar">
        <div class="aesp-custom-admin-bar-message"><?php echo sprintf(__('Bem vindo, %s', 'aesp'), '<strong>' . $current_user->data->display_name . '</strong>'); ?> (<a href="<?php echo wp_logout_url($redirect); ?>" target="_self" class=""><?php _e('sair', 'aesp'); ?></a>)!</div>
    </div>
    <!-- /.aesp-custom-admin-bar -->

<?php // }
}
