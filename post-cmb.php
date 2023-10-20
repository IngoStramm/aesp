<?php

add_action('cmb2_admin_init', 'aesp_documento_cmb');

function aesp_documento_cmb()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'aesp_documento_metabox',
        'title'         => esc_html__('Opções', 'aesp'),
        'object_types'  => array('documento'), // Post type
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Descrição', 'aesp'),
        'id'      => 'aesp_documento_desc',
        'type'    => 'textarea',
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Documentos para download', 'aesp'),
        'id'      => 'aesp_documento_url',
        'type' => 'file',
    ));
}

add_action('cmb2_admin_init', 'aesp_associados_parceiros_cmb');

function aesp_associados_parceiros_cmb()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'aesp_associados_metabox',
        'title'         => esc_html__('Opções', 'aesp'),
        'object_types'  => array('associados', 'parcerias'), // Post type
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Link para o site', 'aesp'),
        'id'      => 'aesp_associados_parceiros_url',
        'type' => 'text_url',
        'attributes' => array(
            'placeholder' => 'https://',
            'required' => true
        ),
    ));
}

add_action('cmb2_admin_init', 'aesp_curriculo_cmb');

function aesp_curriculo_cmb()
{
    $cmb = new_cmb2_box(array(
        'id'            => 'aesp_curriculo_metabox',
        'title'         => esc_html__('Dados', 'aesp'),
        'object_types'  => array('curriculo'), // Post type
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Nome', 'aesp'),
        'id'      => 'aesp_curriculo_nome',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Sobrenome', 'aesp'),
        'id'      => 'aesp_curriculo_sobrenome',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('E-mail', 'aesp'),
        'id'      => 'aesp_curriculo_email',
        'type' => 'text_email',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('CEP', 'aesp'),
        'id'      => 'aesp_curriculo_cep',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Rua', 'aesp'),
        'id'      => 'aesp_curriculo_rua',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Número', 'aesp'),
        'id'      => 'aesp_curriculo_numero',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Bairro', 'aesp'),
        'id'      => 'aesp_curriculo_bairro',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Complemento', 'aesp'),
        'id'      => 'aesp_curriculo_complemento',
        'type' => 'text',
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Cidade', 'aesp'),
        'id'      => 'aesp_curriculo_cidade',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('UF', 'aesp'),
        'id'      => 'aesp_curriculo_uf',
        'type' => 'select',
        'attributes' => array(
            'required' => true
        ),
        'options' => 'aesp_return_ufs'
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('CPF', 'aesp'),
        'id'      => 'aesp_curriculo_cpf',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Telefone (com DDD)', 'aesp'),
        'id'      => 'aesp_curriculo_fone',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Link da sua principal Rede Social', 'aesp'),
        'id'      => 'aesp_curriculo_rede_social',
        'type' => 'text_url',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Cargo Pretendido', 'aesp'),
        'id'      => 'aesp_curriculo_cargo',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Formação profissional', 'aesp'),
        'id'      => 'aesp_curriculo_formacao',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Outros cursos de interesse', 'aesp'),
        'id'      => 'aesp_curriculo_outros_cursos',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Experiência profissional', 'aesp'),
        'id'      => 'aesp_curriculo_experiencia',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Pretenção salarial', 'aesp'),
        'id'      => 'aesp_curriculo_salario',
        'type' => 'text',
        'attributes' => array(
            'required' => true
        ),
    ));

    $cmb->add_field(array(
        'name'    => esc_html__('Outras informações relevantes', 'aesp'),
        'id'      => 'aesp_curriculo_info',
        'type' => 'textarea',
        'attributes' => array(
            'required' => true
        ),
    ));

}
