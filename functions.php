<?php

function aesp_redirect_to_login_page()
{
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

// Verifica se o usuário está logado quando estiver acesso a área restrita
add_action('get_header', 'aesp_area_restrita_managment');

function aesp_area_restrita_managment()
{
    if (!is_post_type_archive('documento'))
        return;

    if (is_user_logged_in())
        return;

    aesp_redirect_to_login_page();
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

function get_user_curriculo_id($create_new = true)
{
    $curriculos_id = [];
    $user_info = wp_get_current_user();

    if (!$user_info->exists())
        return null;

    $get_posts_args = [
        'author'            => $user_info->ID,
        'post_type'         => 'curriculo',
        'orderby'           =>  'post_date',
        'order'             =>  'DESC',
        'fields'            => 'ids',
        'post_status'       => array(
            'publish',
            'future',
            'draft',
            'pending',
            'private',
            // 'trash',
            'auto-draft',
            'inherit',
        )
        // 'posts_per_page' => 1
    ];

    $curriculos_id = get_posts($get_posts_args);
    wp_reset_postdata();

    if (!$curriculos_id && $create_new) { // Se não existir nenhum currículo
        // Cria um novo currículo e armazena o seu ID
        $wp_insert_post_args = [
            'post_author' => $user_info->ID,
            'post_title' => __('Currículo de', 'aesp') . ': ' . $user_info->display_name,
            'post_type' => 'curriculo',
            'post_status' => 'pending'
        ];
        $curriculos_id[] = wp_insert_post($wp_insert_post_args);
    }
    return $curriculos_id;
}

function aesp_post_curriculo_form()
{
    $retorno = [];
    $error_messages = [];

    if (!isset($_POST['aesp_nonce_curriculo_form']) || !wp_verify_nonce($_POST['aesp_nonce_curriculo_form'], 'aesp_validate_curriculo_form_action')) {
        $error_messages['message'] = __('Não possível validar o formulário.', 'aesp');
        $retorno['error_messages'] = $error_messages;
        wp_send_json($retorno);
        wp_die();
    }

    $nome = isset($_POST['nome']) && $_POST['nome'] ? $_POST['nome'] : null;
    if (!$nome) $error_messages['nome'] = __('Campo nome é obrigatório.', 'aesp');

    $sobrenome = isset($_POST['sobrenome']) && $_POST['sobrenome'] ? $_POST['sobrenome'] : null;
    if (!$sobrenome) $error_messages['sobrenome'] = __('Campo sobrenome é obrigatório.', 'aesp');

    $email = isset($_POST['email']) && $_POST['email'] ? $_POST['email'] : null;
    if (!$email) $error_messages['email'] = __('Campo email é obrigatório.', 'aesp');

    $cep = isset($_POST['cep']) && $_POST['cep'] ? $_POST['cep'] : null;
    if (!$cep) $error_messages['cep'] = __('Campo cep é obrigatório.', 'aesp');

    $rua = isset($_POST['rua']) && $_POST['rua'] ? $_POST['rua'] : null;
    if (!$rua) $error_messages['rua'] = __('Campo rua é obrigatório.', 'aesp');

    $numero = isset($_POST['numero']) && $_POST['numero'] ? $_POST['numero'] : null;
    if (!$numero) $error_messages['numero'] = __('Campo numero é obrigatório.', 'aesp');

    $bairro = isset($_POST['bairro']) && $_POST['bairro'] ? $_POST['bairro'] : null;
    if (!$bairro) $error_messages['bairro'] = __('Campo bairro é obrigatório.', 'aesp');

    $complemento = isset($_POST['complemento']) && $_POST['complemento'] ? $_POST['complemento'] : null;

    $cidade = isset($_POST['cidade']) && $_POST['cidade'] ? $_POST['cidade'] : null;
    if (!$cidade) $error_messages['cidade'] = __('Campo cidade é obrigatório.', 'aesp');

    $uf = isset($_POST['uf']) && $_POST['uf'] ? $_POST['uf'] : null;
    if (!$uf) $error_messages['uf'] = __('Campo uf é obrigatório.', 'aesp');

    $cpf = isset($_POST['cpf']) && $_POST['cpf'] ? $_POST['cpf'] : null;
    if (!$cpf) $error_messages['cpf'] = __('Campo cpf é obrigatório.', 'aesp');

    $fone = isset($_POST['fone']) && $_POST['fone'] ? $_POST['fone'] : null;
    if (!$fone) $error_messages['fone'] = __('Campo fone é obrigatório.', 'aesp');

    $rede_social = isset($_POST['rede-social']) && $_POST['rede-social'] ? $_POST['rede-social'] : null;
    if (!$rede_social) $error_messages['rede_social'] = __('Campo rede_social é obrigatório.', 'aesp');

    $cargo = isset($_POST['cargo']) && $_POST['cargo'] ? $_POST['cargo'] : null;
    if (!$cargo) $error_messages['cargo'] = __('Campo cargo é obrigatório.', 'aesp');

    $formacao = isset($_POST['formacao']) && $_POST['formacao'] ? $_POST['formacao'] : null;
    if (!$formacao) $error_messages['formacao'] = __('Campo formacao é obrigatório.', 'aesp');

    $outros_cursos = isset($_POST['outros-cursos']) && $_POST['outros-cursos'] ? $_POST['outros-cursos'] : null;
    if (!$outros_cursos) $error_messages['outros_cursos'] = __('Campo outros_cursos é obrigatório.', 'aesp');

    $experiencia = isset($_POST['experiencia']) && $_POST['experiencia'] ? $_POST['experiencia'] : null;
    if (!$experiencia) $error_messages['experiencia'] = __('Campo experiencia é obrigatório.', 'aesp');

    $salario = isset($_POST['salario']) && $_POST['salario'] ? $_POST['salario'] : null;
    if (!$salario) $error_messages['salario'] = __('Campo salario é obrigatório.', 'aesp');

    $info = isset($_POST['info']) && $_POST['info'] ? $_POST['info'] : null;
    if (!$info) $error_messages['info'] = __('Campo info é obrigatório.', 'aesp');

    $user_info = wp_get_current_user();
    if (!$user_info->exists()) $error_messages['user'] = __('É preciso estar logado para enviar o currículo.', 'aesp');

    $curriculos_id = get_user_curriculo_id();

    if (count($curriculos_id) > 1) {
        $retorno['success'] = false;
        $retorno['error_messages'] = __('Mais de um currículo encontrado para este usuário.');
        $retorno['count_curriculos_id'] = count($curriculos_id);
        foreach ($curriculos_id as $id) {
            $retorno['curriculos_id'][] = $id;
        }
        wp_send_json($retorno);
        wp_die();
    }

    $curriculo_id = $curriculos_id[0];

    if (!$curriculo_id) {
        $error_messages['error_messages'] = __('Não foi possível salvar/atualizar o currículo.', 'aesp');
    }

    if (count($error_messages) > 0) {
        $retorno['success'] = false;
        foreach ($curriculos_id as $id) {
            $retorno['curriculos_id'][] = $id;
        }
        $retorno['error_messages'] = $error_messages;
        wp_send_json($retorno);
        wp_die();
    }

    $warning_messages = [];

    $update_nome = update_post_meta($curriculo_id, 'aesp_curriculo_nome', $nome);
    if (!$update_nome) $warning_messages['update_nome'] = __('Não foi possível salvar/atualizar o campo "Nome".', 'aesp');

    $update_sobrenome = update_post_meta($curriculo_id, 'aesp_curriculo_sobrenome', $sobrenome);
    if (!$update_sobrenome) $warning_messages['update_sobrenome'] = __('Não foi possível salvar/atualizar o campo "Sobrenome".', 'aesp');

    $update_email = update_post_meta($curriculo_id, 'aesp_curriculo_email', $email);
    if (!$update_email) $warning_messages['update_email'] = __('Não foi possível salvar/atualizar o campo "E-mail".', 'aesp');

    $update_cep = update_post_meta($curriculo_id, 'aesp_curriculo_cep', $cep);
    if (!$update_cep) $warning_messages['update_cep'] = __('Não foi possível salvar/atualizar o campo "CEP".', 'aesp');

    $update_rua = update_post_meta($curriculo_id, 'aesp_curriculo_rua', $rua);
    if (!$update_rua) $warning_messages['update_rua'] = __('Não foi possível salvar/atualizar o campo "Rua".', 'aesp');

    $update_numero = update_post_meta($curriculo_id, 'aesp_curriculo_numero', $numero);
    if (!$update_numero) $warning_messages['update_numero'] = __('Não foi possível salvar/atualizar o campo "Número".', 'aesp');

    $update_bairro = update_post_meta($curriculo_id, 'aesp_curriculo_bairro', $bairro);
    if (!$update_bairro) $warning_messages['update_bairro'] = __('Não foi possível salvar/atualizar o campo "Bairro".', 'aesp');

    $update_complemento = update_post_meta($curriculo_id, 'aesp_curriculo_complemento', $complemento);
    if (!$update_complemento) $warning_messages['update_complemento'] = __('Não foi possível salvar/atualizar o campo "Complemento".', 'aesp');

    $update_cidade = update_post_meta($curriculo_id, 'aesp_curriculo_cidade', $cidade);
    if (!$update_cidade) $warning_messages['update_cidade'] = __('Não foi possível salvar/atualizar o campo "Cidade".', 'aesp');

    $update_uf = update_post_meta($curriculo_id, 'aesp_curriculo_uf', $uf);
    if (!$update_uf) $warning_messages['update_uf'] = __('Não foi possível salvar/atualizar o campo "Selecione um Estado".', 'aesp');

    $update_cpf = update_post_meta($curriculo_id, 'aesp_curriculo_cpf', $cpf);
    if (!$update_cpf) $warning_messages['update_cpf'] = __('Não foi possível salvar/atualizar o campo "CPF".', 'aesp');

    $update_fone = update_post_meta($curriculo_id, 'aesp_curriculo_fone', $fone);
    if (!$update_fone) $warning_messages['update_fone'] = __('Não foi possível salvar/atualizar o campo "Telefone (com DDD)".', 'aesp');

    $update_rede_social = update_post_meta($curriculo_id, 'aesp_curriculo_rede_social', $rede_social);
    if (!$update_rede_social) $warning_messages['update_rede_social'] = __('Não foi possível salvar/atualizar o campo "Link da sua principal Rede Social".', 'aesp');

    $update_cargo = update_post_meta($curriculo_id, 'aesp_curriculo_cargo', $cargo);
    if (!$update_cargo) $warning_messages['update_cargo'] = __('Não foi possível salvar/atualizar o campo "Cargo Pretendido".', 'aesp');

    $update_formacao = update_post_meta($curriculo_id, 'aesp_curriculo_formacao', $formacao);
    if (!$update_formacao) $warning_messages['update_formacao'] = __('Não foi possível salvar/atualizar o campo "Formação profissional".', 'aesp');

    $update_outros_cursos = update_post_meta($curriculo_id, 'aesp_curriculo_outros_cursos', $outros_cursos);
    if (!$update_outros_cursos) $warning_messages['update_outros_cursos'] = __('Não foi possível salvar/atualizar o campo "Outros cursos de interesse".', 'aesp');

    $update_experiencia = update_post_meta($curriculo_id, 'aesp_curriculo_experiencia', $experiencia);
    if (!$update_experiencia) $warning_messages['update_experiencia'] = __('Não foi possível salvar/atualizar o campo "Experiência profissional".', 'aesp');

    $update_salario = update_post_meta($curriculo_id, 'aesp_curriculo_salario', $salario);
    if (!$update_salario) $warning_messages['update_salario'] = __('Não foi possível salvar/atualizar o campo "Pretenção salarial".', 'aesp');

    $update_info = update_post_meta($curriculo_id, 'aesp_curriculo_info', $info);
    if (!$update_info) $warning_messages['update_info'] = __('Não foi possível salvar/atualizar o campo "Outras informações relevantes".', 'aesp');

    if (count($warning_messages) > 0) {
        $retorno['success'] = true;
        $retorno['warning_messages'] = $warning_messages;
    } else {
        $retorno['success'] = true;
        $retorno['success_messages'] = __('Todos os campos foram atualizados.', 'aesp');
    }

    $retorno['curriculo_id'] = $curriculo_id;

    wp_send_json($retorno);
    wp_die();
}

add_action('wp_ajax_aesp_post_curriculo_form', 'aesp_post_curriculo_form');
add_action('wp_ajax_nopriv_aesp_post_curriculo_form', 'aesp_post_curriculo_form');

function aesp_return_ufs()
{
    $ufs = [
        '' => __('Selecione um Estado!', 'aesp'),
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    ];
    return $ufs;
}

// add_action('wp_head', 'aesp_teste');
function aesp_teste()
{
    aesp_debug(get_user_curriculo_id());
}
