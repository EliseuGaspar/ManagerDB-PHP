<?php

namespace Conectors;

include_once 'consts_mysql_conector.php';
include_once 'functions_mysql_conector.php';

use PDO;
use Conectors\MysqlFunctions;

class Mysql extends MysqlFunctions
{
    private $connection, $db;

    public function __construct(string $host = 'localhost', string $user = 'root', string $pwd = '', string $db_name = '')
    {
        try {
            if(!empty($db_name))
            {
                $string_connection = "mysql:host=$host;dbname=$db_name;charset=UTF8";
                $this->db = $db_name;
            }
            else
                $string_connection = "mysql:host=$host;charset=UTF8";
            $this->connection = new PDO($string_connection,$user,$pwd) or die('Falha na instância da class PDO');
        } catch (\PDOException $e) {
            echo 'Não foi possível conectar-se ao servidor';
        }
    }

    public function create_database(string $db_name)
    {
        try {
            $sql = $this->connection->prepare("CREATE DATABASE IF NOT EXISTS $db_name;");
            $this->db = $db_name;
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function create_table(string $table_name = 'TesteTable', array $body_table)
    {
        $body = self::body_formate($body_table);
        try {
            $sql = "CREATE TABLE IF NOT EXISTS `$this->db`.`$table_name`($body) ENGINE = InnoDB;";
            echo $sql;
            $sql = $this->connection->prepare($sql);
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function select_all(string $table_name, string $reference_column = '*')
    {
        try {
            $sql = $this->connection->prepare("SELECT $reference_column FROM `$this->db`.`$table_name`");
            $response = $sql->execute();
            return $sql->fetchAll();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function select_for_reference(string $table_name, string $reference_column = '*', string $reference_value, string $column)
    {
        try {
            $sql = $this->connection->prepare("SELECT $reference_column FROM `$this->db`.`$table_name` WHERE $column = $reference_value");
            $response = $sql->execute();
            return $sql->fetchAll();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function insert(string $table_name, array $values)
    {
        $value_columns_number = self::formate_value($values);
        $columns_name = self::formate_columns_name($values);
        $columns_values = self::formate_columns_values($values);
        try {
            $sql = $this->connection->prepare("INSERT INTO `$this->db`.`$table_name`($columns_name) VALUES($value_columns_number)");
            $response = $sql->execute($columns_values);
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update(string $table, array $update_param, string $reference_column, string $reference_value)
    {
        $sets_values = self::formate_update_params($update_param);
        echo "UPDATE `$this->db`.`$table` SET $sets_values WHERE $reference_column = $reference_value";
        try {
            $sql = $this->connection->prepare("UPDATE `$this->db`.`$table` SET $sets_values WHERE $reference_column = $reference_value");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete_table(string $table)
    {
        try {
            $sql = $this->connection->prepare("DROP TABLE `$this->db`.`$table`");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete_database(string $database = '')
    {
        try {
            if(empty($database))
                $sql = $this->connection->prepare("DROP DATABASE `$this->db`");
            else
                $sql = $this->connection->prepare("DROP DATABASE `$database`");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete_table_value(string $table, string $reference_column, string $reference_value)
    {
        try {
            $sql = $this->connection->prepare("DELETE FROM `$this->db`.`$table` WHERE $reference_column = $reference_value");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function change_column_for_unique(string $table, string $column_name)
    {
        try {
            $sql = $this->connection->prepare("ALTER TABLE `$this->db`.`$table` ADD UNIQUE(`$column_name`);");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function change_unique_for_column_normal(string $table, string $column_name)
    {
        try {
            $sql = $this->connection->prepare("ALTER TABLE `$this->db`.`$table` DROP INDEX `$column_name`;");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function order_column(string $table, string $column_name)
    {
        try {
            $sql = $this->connection->prepare("SELECT $column_name FROM `$this->db`.`$table` ORDER BY `$column_name`;");
            $response = $sql->execute();
            $this->connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function SQL(string $sql, bool $return = false)
    {
        if(!$return)
        {
            try {
                $sql = $this->connection->prepare($sql);
                $response = $sql->execute();
                $this->connection->commit();
                return $response;
            } catch (\PDOException $e) {
                return false;
            }
        } else {
            try {
                $sql = $this->connection->prepare($sql);
                $response = $sql->execute();
                return $sql->fetchAll();
            } catch (\PDOException $e) {
                return false;
            }
        }
        
    }

    public function create_body_table(
        array $column_name,
        array $column_type,
        array $column_size,
        array $column_primary_key,
        array $column_auto_increment,
        array $column_not_ull,
        array $column_unique,
        array $column_comment
        )
    {
        $array_of_return = [];

        for ($i = 0; $i < count($column_name); $i++) { 
            $array_of_return[$i] = [
                "name"=>$column_name[$i],
                "type"=>$column_type[$i],
                "size"=>$column_size[$i],
                "primary_key"=>$column_primary_key[$i],
                "auto_increment"=>$column_auto_increment[$i],
                "not_null"=>$column_not_ull[$i],
                "unique"=>$column_unique[$i],
                "comment"=>$column_comment[$i]
            ];
        }

        return $array_of_return;
    }
    

}




?>