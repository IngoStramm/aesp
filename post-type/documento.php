<?php

add_action('init', 'documento_video_cpt', 1);

function documento_video_cpt()
{
    $documento = new Aesp_Post_Type(
        __('Documento', 'aesp'), // Nome (Singular) do Post Type.
        'documento' // Slug do Post Type.
    );

    $documento->set_labels(
        array(
            'menu_name' => __('Documentos da Área Restrita', 'aesp'),
            'singular_name' => __('Documento', 'aesp'),
            'view_item' => __('Ver Documentos', 'aesp'),
            'edit_item' => __('Editar Documentos', 'aesp'),
            'search_items' => __('Pesquisar Documentos', 'aesp'),
            'update_item' => __('Atualizar Documentos', 'aesp'),
            'parent_item_colon' => __('Documento Pai', 'aesp'),
            'add_new' => __('Adicionar Novo', 'aesp'),
            'add_new_item' => __('Adicionar novo Documento', 'aesp'),
            'new_item' => __('Novo', 'aesp'),
            'all_items' => __('Todos Documentos', 'aesp'),
            'not_found' => __('Nenhum Documento encontrado', 'aesp'),
            'not_found_in_trash' => __('Nenhum Documento encontrado na lixeira', 'aesp'),
        )
    );

    $documento->set_arguments(
        array(
            'has_archive' => true,
            'supports' => array('title'),
            'menu_icon' => 'dashicons-media-document'
        )
    );
}
