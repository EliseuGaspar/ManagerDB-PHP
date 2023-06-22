<?php

include_once('src/conector.php');

use Conectors\Conector;

use function PHPSTORM_META\type;

$conn = new Conector();

$mysql = $conn->Sqlite('teste.db');

#$mysql->delete_table_value('table','senha','009988709');

$response = $mysql->select_all('table');

print_r(is_string($response[0]['nome']));

?>