<?php

return [
    'controller' => [
        'path' => 'controllers',       /* Diretório das controllers */
        'index' => 'index'             /* arquivo default controller */
    ],
    'model' => [
        'path' => 'models'             /* Diretório de models */
    ],    
    'view' => [
        'path' => 'views',             /* Diretório das views */
        'file' => 'default/index',        /* arquivo default view */
        'block' => null                 /* Caso não vá usar todo o views busca-se o bloco a ser utilizado. */
    ],
    'theme' => [
        'path'   => 'themes',           /* Diretório onde ficaram o(s) theme(s) */
        'theme'  => 'default',          /* Diretorio do thema */
        'file'   => 'index',            /* Arquivo index do theme, pode acontecer de ter mais arquivos no diretorio do theme e ou ser alterado o index do theme */
        'view'    => 'div[class="view"]', /* Bloco onde vai ser inserido a view. */
        'block'   => null               /* Caso não vá usar todo o theme busca-se o bloco a ser utilizado. */
    ]
];

