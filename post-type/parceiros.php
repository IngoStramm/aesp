<?php

add_action('init', 'parceiros_video_cpt', 1);

function parceiros_video_cpt()
{
    $parceiros = new Aesp_Post_Type(
        __('Parceiro', 'aesp'), // Nome (Singular) do Post Type.
        'parceiros' // Slug do Post Type.
    );

    $parceiros->set_labels(
        array(
            'menu_name' => __('Parceiros', 'aesp'),
            'singular_name' => __('Parceiro', 'aesp'),
            'view_item' => __('Ver Parceiros', 'aesp'),
            'edit_item' => __('Editar Parceiros', 'aesp'),
            'search_items' => __('Pesquisar Parceiros', 'aesp'),
            'update_item' => __('Atualizar Parceiros', 'aesp'),
            'parent_item_colon' => __('Parceiro Pai', 'aesp'),
            'add_new' => __('Adicionar Novo', 'aesp'),
            'add_new_item' => __('Adicionar novo Parceiro', 'aesp'),
            'new_item' => __('Novo', 'aesp'),
            'all_items' => __('Todos Parceiros', 'aesp'),
            'not_found' => __('Nenhum Parceiro encontrado', 'aesp'),
            'not_found_in_trash' => __('Nenhum Parceiro encontrado na lixeira', 'aesp'),
        )
    );

    $parceiros->set_arguments(
        array(
            'has_archive' => true,
            'supports' => array('title', 'thumbnail'),
            'menu_icon' => 'dashicons-businessman'
        )
    );
}
