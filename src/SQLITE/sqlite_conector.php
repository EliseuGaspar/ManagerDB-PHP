<?php

namespace Conectors;

include_once('functions_sqlite_conector.php');

class Sqlite extends SqliteFunctions
{
    private static $connection;
    public function __construct(string $path)
    {
        try {
            self::$connection = new \PDO('sqlite:'.$path);
        } catch (\Throwable $th) {
            echo "erro ao conectar-se a base de dados";
        }
    }

    public function create_table(string $table_name = "TesteTable", array $body_table)
    {
        $body = self::body_formate($body_table);
        try {
            $sql = "CREATE TABLE IF NOT EXISTS '$table_name'($body)";
            $sql = self::$connection->prepare($sql);
            $sql->execute();
            return self::$connection->commit();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function select_all(string $table_name, string $reference_column = '*')
    {
        try {
            $sql = self::$connection->prepare("SELECT $reference_column FROM '$table_name'");
            $response = $sql->execute();
            return $sql->fetchAll();
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function select_for_reference(string $table_name, string $reference_column = '*', string $reference_value, string $column)
    {
        try {
            $sql = self::$connection->prepare("SELECT $reference_column FROM '$table_name' WHERE $column = $reference_value");
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
            $sql = self::$connection->prepare("INSERT INTO '$table_name'($columns_name) VALUES($value_columns_number)");
            $response = $sql->execute($columns_values);
            self::$connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function update(string $table_name, array $update_param, string $reference_column, string $reference_value)
    {
        $sets_values = self::formate_update_params($update_param);
        if(is_string($reference_value))
            $sql = "UPDATE '$table_name' SET $sets_values WHERE $reference_column = '$reference_value'";
        else if(is_integer($reference_value))
            $sql = "UPDATE '$table_name' SET $sets_values WHERE $reference_column = $reference_value";
        else if(is_float($reference_value))
            $sql = "UPDATE '$table_name' SET $sets_values WHERE $reference_column = $reference_value";
        else
            return false;
        try {
            $sql = self::$connection->prepare($sql);
            $response = $sql->execute();
            self::$connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete_table(string $table_name)
    {
        try {
            $sql = self::$connection->prepare("DROP TABLE '$table_name'");
            $response = $sql->execute();
            self::$connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function delete_table_value(string $table_name, string $reference_column, string $reference_value)
    {
        if(is_string($reference_value))
            $sql = "DELETE FROM '$table_name' WHERE $reference_column like '$reference_value'";
        else if(is_integer($reference_value))
            $sql = "DELETE FROM '$table_name' WHERE $reference_column like $reference_value";
        else if(is_float($reference_value))
            $sql = "DELETE FROM '$table_name' WHERE $reference_column like $reference_value";
        else
            return false;
        try {
            $sql = self::$connection->prepare($sql);
            $response = $sql->execute();
            self::$connection->commit();
            return $response;
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function create_body_table(
        array $column_name,
        array $column_type,
        array $column_primary_key,
        array $column_auto_increment,
        array $column_not_ull,
        array $column_unique
        )
    {
        $array_of_return = [];

        for ($i = 0; $i < count($column_name); $i++) { 
            $array_of_return[$i] = [
                "name"=>$column_name[$i],
                "type"=>$column_type[$i],
                "primary_key"=>$column_primary_key[$i],
                "auto_increment"=>$column_auto_increment[$i],
                "not_null"=>$column_not_ull[$i],
                "unique"=>$column_unique[$i],
            ];
        }

        return $array_of_return;
    }
    

}




?>