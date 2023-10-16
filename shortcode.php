<?php

// Shortcode para pegar o redirectionamento passado na query string
add_shortcode('get-redirect', 'aesp_get_redirect');
function aesp_get_redirect()
{
    $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : null;
    // if (!$redirect)
    //     return;
    return $redirect;
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

add_shortcode('form-curriculo', 'aesp_form_curriculo');

function aesp_form_curriculo()
{
    $user_info = wp_get_current_user();

    if (!$user_info->exists())
        return aesp_redirect_to_login_page();

    $curriculos_id = get_user_curriculo_id(false);

    if (count($curriculos_id) > 1) {
        return __('Mais de um currículo encontrado para este usuário.', 'aesp');
    }

    $curriculo_id = $curriculos_id ? $curriculos_id[0] : null;

    $output = '';

    $data_fields = [
        'aesp_curriculo_nome',
        'aesp_curriculo_sobrenome',
        'aesp_curriculo_email',
        'aesp_curriculo_cep',
        'aesp_curriculo_rua',
        'aesp_curriculo_numero',
        'aesp_curriculo_bairro',
        'aesp_curriculo_complemento',
        'aesp_curriculo_cidade',
        'aesp_curriculo_uf',
        'aesp_curriculo_cpf',
        'aesp_curriculo_fone',
        'aesp_curriculo_rede_social',
        'aesp_curriculo_cargo',
        'aesp_curriculo_formacao',
        'aesp_curriculo_outros_cursos',
        'aesp_curriculo_experiencia',
        'aesp_curriculo_salario',
        'aesp_curriculo_info'
    ];

    $data_form = [];

    if ($curriculo_id) {

        foreach ($data_fields as $field) {
            $post_meta = get_post_meta($curriculo_id, $field, true);
            if ($post_meta)
                $data_form[$field] = $post_meta;
        }
    }

    $output .= '<form action="" method="POST" class="form-curriculo" id="form-curriculo">';
    $output .= '<div class="form-load-wrapper"><div class="form-load"><div></div><div></div></div></div>';
    $output .= wp_nonce_field('aesp_validate_curriculo_form_action', 'aesp_nonce_curriculo_form', true, false);

    $nome = '';
    if (isset($data_form['aesp_curriculo_nome']) && $data_form['aesp_curriculo_nome']) {
        $nome = $data_form['aesp_curriculo_nome'];
    } elseif ($user_info->first_name) {
        $nome = $user_info->first_name;
    }

    $output .= '<label for="nome" class="required">' . __('Nome', 'aesp') . '</label>';
    $output .= '<input type="text" id="nome" name="nome" placeholder="' . __('Nome', 'aesp') . '" value="' . $nome . '" required />';

    $sobrenome = '';
    if (isset($data_form['aesp_curriculo_sobrenome']) && $data_form['aesp_curriculo_sobrenome']) {
        $sobrenome = $data_form['aesp_curriculo_sobrenome'];
    } elseif ($user_info->last_name) {
        $sobrenome = $user_info->last_name;
    }

    $output .= '<label for="sobrenome" class="required">' . __('Sobrenome', 'aesp') . '</label>';
    $output .= '<input type="text" id="sobrenome" name="sobrenome" placeholder="' . __('Sobrenome', 'aesp') . '" value="' . $sobrenome . '" required />';


    $email = '';
    if (isset($data_form['aesp_curriculo_email']) && $data_form['aesp_curriculo_email']) {
        $email = $data_form['aesp_curriculo_email'];
    } elseif ($user_info->user_email) {
        $email = $user_info->user_email;
    }

    $output .= '<label for="email" class="required">' . __('E-mail', 'aesp') . '</label>';
    $output .= '<input type="email" id="email" name="email" placeholder="' . __('E-mail', 'aesp') . '" value="' . $email . '" required />';

    $cep = '';
    if (isset($data_form['aesp_curriculo_cep']) && $data_form['aesp_curriculo_cep']) {
        $cep = $data_form['aesp_curriculo_cep'];
    }

    $output .= '<label for="cep" class="required">' . __('CEP', 'aesp') . '</label>';
    $output .= '<input type="text" id="cep" class="cep" name="cep" placeholder="' . __('CEP', 'aesp') . '" value="' . $cep . '" required />';


    $rua = '';
    if (isset($data_form['aesp_curriculo_rua']) && $data_form['aesp_curriculo_rua']) {
        $rua = $data_form['aesp_curriculo_rua'];
    }

    $output .= '<label for="rua" class="required">' . __('Rua', 'aesp') . '</label>';
    $output .= '<input type="text" id="rua" name="rua" placeholder="' . __('Rua', 'aesp') . '" value="' . $rua . '" required />';

    $numero = '';
    if (isset($data_form['aesp_curriculo_numero']) && $data_form['aesp_curriculo_numero']) {
        $numero = $data_form['aesp_curriculo_numero'];
    }

    $output .= '<label for="numero" class="required">' . __('Número', 'aesp') . '</label>';
    $output .= '<input type="number" id="numero" name="numero" placeholder="' . __('Número', 'aesp') . '" value="' . $numero . '" required />';

    $bairro = '';
    if (isset($data_form['aesp_curriculo_bairro']) && $data_form['aesp_curriculo_bairro']) {
        $bairro = $data_form['aesp_curriculo_bairro'];
    }

    $output .= '<label for="bairro" class="required">' . __('Bairro', 'aesp') . '</label>';
    $output .= '<input type="text" id="bairro" name="bairro" placeholder="' . __('Bairro', 'aesp') . '" value="' . $bairro . '" required />';

    $complemento = '';
    if (isset($data_form['aesp_curriculo_complemento']) && $data_form['aesp_curriculo_complemento']) {
        $complemento = $data_form['aesp_curriculo_complemento'];
    }

    $output .= '<label for="complemento">' . __('Complemento', 'aesp') . '</label>';
    $output .= '<input type="text" id="complemento" name="complemento" placeholder="' . __('Complemento', 'aesp') . '" value="' . $complemento . '" />';

    $cidade = '';
    if (isset($data_form['aesp_curriculo_cidade']) && $data_form['aesp_curriculo_cidade']) {
        $cidade = $data_form['aesp_curriculo_cidade'];
    }

    $output .= '<label for="cidade" class="required">' . __('Cidade', 'aesp') . '</label>';
    $output .= '<input type="text" id="cidade" name="cidade" placeholder="' . __('Cidade', 'aesp') . '" value="' . $cidade . '" required />';

    $uf = '';
    if (isset($data_form['aesp_curriculo_uf']) && $data_form['aesp_curriculo_uf']) {
        $uf = $data_form['aesp_curriculo_uf'];
    }

    $output .= '<label for="uf" class="required">' . __('Estado', 'aesp') . '</label>';
    $output .= '<select type="text" id="uf" name="uf" required>';
    $estados = aesp_return_ufs();
    foreach ($estados as $value => $estado) {
        $output .= '<option value="' . $value . '"';
        $output .= $uf === $value ? ' selected>' : '>';
        $output .= $estado . '</option>';
    }
    $output .= '</select>';

    $cpf = '';
    if (isset($data_form['aesp_curriculo_cpf']) && $data_form['aesp_curriculo_cpf']) {
        $cpf = $data_form['aesp_curriculo_cpf'];
    }

    $output .= '<label for="cpf" class="required">' . __('CPF', 'aesp') . '</label>';
    $output .= '<input type="text" class="cpf" id="cpf" name="cpf" placeholder="' . __('CPF', 'aesp') . '" value="' . $cpf . '" required />';

    $fone = '';
    if (isset($data_form['aesp_curriculo_fone']) && $data_form['aesp_curriculo_fone']) {
        $fone = $data_form['aesp_curriculo_fone'];
    }

    $output .= '<label for="fone" class="required">' . __('Telefone (com DDD)', 'aesp') . '</label>';
    $output .= '<input type="text" class="fone" id="fone" name="fone" placeholder="' . __('Telefone (com DDD)', 'aesp') . '" value="' . $fone . '" required />';

    $rede_social = '';
    if (isset($data_form['aesp_curriculo_rede_social']) && $data_form['aesp_curriculo_rede_social']) {
        $rede_social = $data_form['aesp_curriculo_rede_social'];
    }

    $output .= '<label for="rede-social" class="required">' . __('Link da sua principal Rede Social', 'aesp') . '</label>';
    $output .= '<input type="text" id="rede-social" name="rede-social" placeholder="' . __('Link da sua principal Rede Social', 'aesp') . '" value="' . $rede_social . '" required />';

    $cargo = '';
    if (isset($data_form['aesp_curriculo_cargo']) && $data_form['aesp_curriculo_cargo']) {
        $cargo = $data_form['aesp_curriculo_cargo'];
    }

    $output .= '<label for="cargo" class="required">' . __('Cargo Pretendido', 'aesp') . '</label>';
    $output .= '<input type="text" id="cargo" name="cargo" placeholder="' . __('Cargo Pretendido', 'aesp') . '" value="' . $cargo . '" required />';

    $formacao = '';
    if (isset($data_form['aesp_curriculo_formacao']) && $data_form['aesp_curriculo_formacao']) {
        $formacao = $data_form['aesp_curriculo_formacao'];
    }

    $output .= '<label for="formacao" class="required">' . __('Formação profissional', 'aesp') . '</label>';
    $output .= '<input type="text" id="formacao" name="formacao" placeholder="' . __('Formação profissional', 'aesp') . '" value="' . $formacao . '" required />';

    $outros_cursos = '';
    if (isset($data_form['aesp_curriculo_outros_cursos']) && $data_form['aesp_curriculo_outros_cursos']) {
        $outros_cursos = $data_form['aesp_curriculo_outros_cursos'];
    }

    $output .= '<label for="outros-cursos" class="required">' . __('Outros cursos de interesse', 'aesp') . '</label>';
    $output .= '<input type="text" id="outros-cursos" name="outros-cursos" placeholder="' . __('Outros cursos de interesse', 'aesp') . '" value="' . $outros_cursos . '" required />';

    $experiencia = '';
    if (isset($data_form['aesp_curriculo_experiencia']) && $data_form['aesp_curriculo_experiencia']) {
        $experiencia = $data_form['aesp_curriculo_experiencia'];
    }

    $output .= '<label for="experiencia" class="required">' . __('Experiência profissional', 'aesp') . '</label>';
    $output .= '<input type="text" id="experiencia" name="experiencia" placeholder="' . __('Experiência profissional', 'aesp') . '" value="' . $experiencia . '" required />';

    $salario = '';
    if (isset($data_form['aesp_curriculo_salario']) && $data_form['aesp_curriculo_salario']) {
        $salario = $data_form['aesp_curriculo_salario'];
    }

    $output .= '<label for="salario" class="required">' . __('Pretenção salarial', 'aesp') . '</label>';
    $output .= '<input type="text" id="salario" class="salario" name="salario" placeholder="' . __('Pretenção salarial', 'aesp') . '" value="' . $salario . '" required />';

    $info = '';
    if (isset($data_form['aesp_curriculo_info']) && $data_form['aesp_curriculo_info']) {
        $info = $data_form['aesp_curriculo_info'];
    }

    $output .= '<label for="info" class="required">' . __('Outras informações relevantes', 'aesp') . '</label>';
    $output .= '<textarea id="info" name="info" placeholder="' . __('Outras informações relevantes', 'aesp') . '" required>' . $info . '</textarea>';

    $output .= '<button>' . __('Enviar', 'aesp') . '</button>';
    $output .= '</form>';

    return $output;
}
