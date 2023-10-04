<?php

$curriculo = new AESP_Post_Type('Currículo', 'curriculo');

$curriculo->set_arguments(
    array(
        'supports' => array('title', 'author')
    )
);
