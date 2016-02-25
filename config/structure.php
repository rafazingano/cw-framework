<?php

return [
    'controller' => [
        'path' => 'controllers',        /* Diretório das controllers */
        'index' => 'index'             /* arquivo default controller */
    ],
    'view' => [
        'path' => 'themes',             /* Diretório das views */
        'file' => 'default/index',      /* arquivo default view */
        'block' => 'div[class="view"]' /* Caso não vá usar todo o views busca-se o bloco a ser utilizado. */
    ],
    'model' => [
        'path' => 'models'              /* Diretório de models */
    ],
    'theme' => [
        'path'   => 'themes',           /* Diretório onde ficaram o(s) theme(s) */
        'theme'  => 'default',          /* Diretorio do thema */
        'file'   => 'index',            /* Arquivo index do theme, pode acontecer de ter mais arquivos no diretorio do theme e ou ser alterado o index do theme */
        'view'    => 'div[class="view"]', /* Bloco onde vai ser inserido a view. */
        'block'   => null               /* Caso não vá usar todo o theme busca-se o bloco a ser utilizado. */
    ],
    'library' => [
        'path' => 'framework/library'   /* Caminho da pasta de bibliotecas do framework. */
    ],
    'core' => [
        'path' => 'framework/core'      /* Caminho da pasta core do framework. */
    ]
];

