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
