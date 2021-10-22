<?php

add_action('init', 'aesp_arquivo_taxonomy', 1);

function aesp_arquivo_taxonomy()
{
    $categoria = new Aesp_Taxonomy(
        __('Categoria de Documento', 'aesp'), // Nome (Singular) da nova Taxonomia.
        'categoria-documento', // Slug do Taxonomia.
        'documento' // Nome do tipo de conteúdo que a taxonomia irá fazer parte.
    );

    $categoria->set_labels(
        array(
            'singular_name' => __('Categoria de Documento', 'aesp'),
            'add_or_remove_items' => __('Adicionar ou remover Categorias', 'aesp'),
            'view_item' => __('Ver Categoria', 'aesp'),
            'edit_item' => __('Editar Categoria', 'aesp'),
            'search_items' => __('Pesquisar Categorias', 'aesp'),
            'update_item' => __('Atualizar Categoria', 'aesp'),
            'parent_item' => __('Categoria Pai', 'aesp'),
            'parent_item_colon' => __('Categoria Pai', 'aesp'),
            'menu_name' => __('Categorias de Documento', 'aesp'),
            'add_new_item' => __('Adicionar Nova', 'aesp'),
            'new_item_name' => __('Nova Categoria', 'aesp'),
            'all_items' => __('Todas Categorias', 'aesp'),
            'separate_items_with_commas' => __('Separar Categorias por vírgula', 'aesp'),
            'choose_from_most_used' => __('Escolher Categorias mais usadas', 'aesp'),
        )
    );

    $categoria->set_arguments(
        array(
            'hierarchical' => true
        )
    );
}