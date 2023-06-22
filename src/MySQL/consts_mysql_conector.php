<?php

namespace Conectors;

define('CREATE_TABLE_EXAMPLE', "id INT(120) NOT NULL AUTO_INCREMENT PRIMARY KEY, nome VARCHAR(255) NOT NULL, senha VARCHAR(255) NOT NULL");

$teste_array = [
    [
        "name"=>"id",
        "type"=>"int",
        "size"=>120,
        "primary_key"=>true,
        "auto_increment"=>true,
        "not_null"=>true,
        "unique"=>true
    ],
    "nome"=>[
        "type"=>"varchar",
        "size"=>255,
        "not_null"=>true,
        "unique"=>false
    ],
    "senha"=>[
        "type"=>"varchar",
        "size"=>120,
        "not_null"=>true,
        "unique"=>true
    ]
];

?>