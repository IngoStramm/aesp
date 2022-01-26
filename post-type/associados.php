<?php

add_action('init', 'associados_video_cpt', 1);

function associados_video_cpt()
{
    $associados = new Aesp_Post_Type(
        __('Associado', 'aesp'), // Nome (Singular) do Post Type.
        'associados' // Slug do Post Type.
    );

    $associados->set_labels(
        array(
            'menu_name' => __('Associados', 'aesp'),
            'singular_name' => __('Associado', 'aesp'),
            'view_item' => __('Ver Associados', 'aesp'),
            'edit_item' => __('Editar Associados', 'aesp'),
            'search_items' => __('Pesquisar Associados', 'aesp'),
            'update_item' => __('Atualizar Associados', 'aesp'),
            'parent_item_colon' => __('Associado Pai', 'aesp'),
            'add_new' => __('Adicionar Novo', 'aesp'),
            'add_new_item' => __('Adicionar novo Associado', 'aesp'),
            'new_item' => __('Novo', 'aesp'),
            'all_items' => __('Todos Associados', 'aesp'),
            'not_found' => __('Nenhum Associado encontrado', 'aesp'),
            'not_found_in_trash' => __('Nenhum Associado encontrado na lixeira', 'aesp'),
        )
    );

    $associados->set_arguments(
        array(
            'has_archive' => true,
            'supports' => array('title', 'thumbnail'),
            'menu_icon' => 'dashicons-star-filled'
        )
    );
}
