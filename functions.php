<?php

// Verifica se o usuário está logado quando estiver acesso a área restrita
add_action('get_header', 'aesp_area_restrita_managment');

function aesp_area_restrita_managment()
{  
    if (!is_post_type_archive('documento'))
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
    if (!is_user_logged_in())
        return;

    $current_user = wp_get_current_user();
    $roles = (array) $current_user->roles;
    $show_admin_url = isset($roles[0]) && ($roles[0] === 'administrator');
    // aesp_debug($show_admin_url);
?>
    <?php
    $redirect = null;
    $aesp_login_page = aesp_get_option('aesp_login_page');
    if ($aesp_login_page) {
        $redirect = get_page_link($aesp_login_page);
    }
    ?>
    <div class="aesp-custom-admin-bar">
        <div class="aesp-custom-admin-bar-message">
            <?php echo sprintf(__('Bem vindo, %s', 'aesp'), '<strong>' . $current_user->data->display_name . '</strong>'); ?> (<a href="<?php echo wp_logout_url($redirect); ?>" target="_self" class=""><?php _e('sair', 'aesp'); ?></a>)!
            <?php if ($show_admin_url) { ?>
                <a class="aesp-btn" href="<?php echo get_admin_url(); ?>" target="_blank"><?php _e('Acessar admin', 'aesp'); ?></a>
            <?php } ?>
        </div>
    </div>
    <!-- /.aesp-custom-admin-bar -->

<?php
}


add_action('elementor/query/assoc_parc_query', 'aesp_assoc_parc_query_callback');

function aesp_assoc_parc_query_callback($query)
{
    $query->set('order', 'ASC');
    $query->set('orderby', 'title');
}

add_shortcode('aesp_search', 'aesp_search_form');

function aesp_search_form($atts)
{
    $a = shortcode_atts(array(
        'post_type' => 'associados',
    ), $atts);
    return '<form class="elementor-search-form" role="search" action="' . site_url('/') . '" method="get">
                <div class="elementor-search-form__container">
                    <input placeholder="' . __('Pesquisar', 'aesp') . '" class="elementor-search-form__input" type="search" name="s" value="">
                    <input type="hidden" name="post_type" value="' . $a['post_type'] .  '" />
                    <button class="elementor-search-form__submit" type="submit" title="' . __('Pesquisar', 'aesp') . '" aria-label="' . __('Pesquisar', 'aesp') . '" style="min-width: 50px;">
                    <i aria-hidden="true" class="fas fa-search"></i>
                    <span class="elementor-screen-only">' . __('Pesquisar', 'aesp') . '</span>
					</button>
				</div>
		    </form>';
}
