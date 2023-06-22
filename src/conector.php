<?php

namespace Conectors;

include_once('MySQL/mysql_conector.php');
include_once('PostgreSQL/postgresql_conector.php');
include_once('SQLITE/sqlite_conector.php');

use Conectors\Mysql;
use Conectors\Postgresql;
use Conectors\Sqlite;

class Conector
{
    public function __construct()
    {
        //
    }
    public function Mysql(string $host, string $user, string $pwd, string $db_name)
    {
        return new Mysql($host, $user, $pwd, $db_name);
    }

    public function Postgresql(string $host, string $user, string $pwd, string $db_name)
    {
        return new Postgresql($host, $user, $pwd, $db_name);
    }

    public function Sqlite(string $path)
    {
        return new Sqlite($path);
    }


}


?>