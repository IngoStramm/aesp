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

    if ($curriculo_id)
        $output .= '<pre>' . $curriculo_id . '</pre>';

    // Parei aqui
    // Exibir os valores dos meta fields no value dos inputs do form

    $output .= '<form action="" method="POST" class="form-curriculo" id="form-curriculo">';
    $output .= '';
    $output .= wp_nonce_field('aesp_validate_curriculo_form_action', 'aesp_nonce_curriculo_form', true, false);
    $output .= '<input type="text" id="nome" name="nome" placeholder="' . __('Nome', 'aesp') . '" value="' . ($user_info->first_name ? $user_info->first_name : '') . '" required />';
    $output .= '<input type="text" id="sobrenome" name="sobrenome" placeholder="' . __('Sobrenome', 'aesp') . '" value="' . ($user_info->last_name ? $user_info->last_name : '') . '" required />';
    $output .= '<input type="email" id="email" name="email" placeholder="' . __('E-mail', 'aesp') . '" value="' . ($user_info->user_email ? $user_info->user_email : '') . '" required />';
    $output .= '<input type="text" id="cep" class="cep" name="cep" placeholder="' . __('CEP', 'aesp') . '" value="04010200" required />';
    $output .= '<input type="text" id="rua" name="rua" placeholder="' . __('Rua', 'aesp') . '" value="Rua Domingos de Morais" required />';
    $output .= '<input type="number" id="numero" name="numero" placeholder="' . __('Número', 'aesp') . '" value="1" required />';
    $output .= '<input type="text" id="bairro" name="bairro" placeholder="' . __('Bairro', 'aesp') . '" value="Vila Mariana" required />';
    $output .= '<input type="text" id="complemento" name="complemento" placeholder="' . __('Complemento', 'aesp') . '" />';
    $output .= '<input type="text" id="cidade" name="cidade" placeholder="' . __('Cidade', 'aesp') . '" value="São Paulo" required />';
    $output .= '<select type="text" id="uf" name="uf" required>';
    $output .= '
    <option>' . __('Selecione um Estado', 'aesp') . '</option>
    <option value="AC">Acre</option>
    <option value="AL">Alagoas</option>
    <option value="AP">Amapá</option>
    <option value="AM">Amazonas</option>
    <option value="BA">Bahia</option>
    <option value="CE">Ceará</option>
    <option value="DF">Distrito Federal</option>
    <option value="ES">Espírito Santo</option>
    <option value="GO">Goiás</option>
    <option value="MA">Maranhão</option>
    <option value="MT">Mato Grosso</option>
    <option value="MS">Mato Grosso do Sul</option>
    <option value="MG">Minas Gerais</option>
    <option value="PA">Pará</option>
    <option value="PB">Paraíba</option>
    <option value="PR">Paraná</option>
    <option value="PE">Pernambuco</option>
    <option value="PI">Piauí</option>
    <option value="RJ">Rio de Janeiro</option>
    <option value="RN">Rio Grande do Norte</option>
    <option value="RS">Rio Grande do Sul</option>
    <option value="RO">Rondônia</option>
    <option value="RR">Roraima</option>
    <option value="SC">Santa Catarina</option>
    <option value="SP" selected>São Paulo</option>
    <option value="SE">Sergipe</option>
    <option value="TO">Tocantins</option>';
    $output .= '</select>';
    $output .= '<input type="text" class="cpf" id="cpf" name="cpf" placeholder="' . __('CPF', 'aesp') . '" value="272733991847" required />';
    $output .= '<input type="text" class="fone" id="fone" name="fone" placeholder="' . __('Telefone (com DDD)', 'aesp') . '" value="11999999999" required />';
    $output .= '<input type="text" id="rede-social" name="rede-social" placeholder="' . __('Link da sua principal Rede Social', 'aesp') . '" value="https://teste.com" required />';
    $output .= '<input type="text" id="cargo" name="cargo" placeholder="' . __('Cargo Pretendido', 'aesp') . '" value="teste" required />';
    $output .= '<input type="text" id="formacao" name="formacao" placeholder="' . __('Formação profissional', 'aesp') . '" value="teste" required />';
    $output .= '<input type="text" id="outros-cursos" name="outros-cursos" placeholder="' . __('Outros cursos de interesse', 'aesp') . '" value="teste" required />';
    $output .= '<input type="text" id="experiencia" name="experiencia" placeholder="' . __('Experiência profissional', 'aesp') . '" value="teste" required />';
    $output .= '<input type="text" id="salario" class="salario" name="salario" placeholder="' . __('Pretenção salarial', 'aesp') . '" value="10000" required />';
    $output .= '<textarea id="info" name="info" placeholder="' . __('Outras informações relevantes', 'aesp') . '" required>teste</textarea>';
    $output .= '<button>' . __('Enviar', 'aesp') . '</button>';
    $output .= '</form>';
    return $output;
}
