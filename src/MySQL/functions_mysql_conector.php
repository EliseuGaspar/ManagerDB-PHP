<?php

namespace Conectors;

class MysqlFunctions
{
    public function __construct()
    {
        //
    }

    protected function not_ull(bool $value)
    {
        if($value)
            return "NOT NULL";
        else
            return '';
    }

    protected function primary_key(bool $value)
    {
        if($value)
            return "PRIMARY KEY";
        else
            return '';
    }

    protected function auto_increment(bool $value)
    {
        if($value)
            return "AUTO_INCREMENT";
        else
            return '';
    }

    protected function body_formate(array $body)
    {
        $body_ = '';

        for ($i = 0; $i < count($body); $i++) {
            $name = $body[$i]['name'];
            $size = $body[$i]['size'];
            $upper = strtoupper($body[$i]['type']);
            if(empty($body_)){
                $body_ = "`$name` $upper($size) {$this->not_ull($body[$i]['not_null'])} {$this->auto_increment($body[$i]['auto_increment'])} {$this->primary_key($body[$i]['primary_key'])} {$body[$i]['comment']}";
            }
            else{
                $body_ = "$body_, `$name` $upper($size) {$this->not_ull($body[$i]['not_null'])} {$this->auto_increment($body[$i]['auto_increment'])} {$this->primary_key($body[$i]['primary_key'])} {$body[$i]['comment']}";
            }
        }
        return $body_;
    }
    protected function formate_value(array $values)
    {
        $return = '?';
        for ($i = 0; $i < count($values)-1; $i ++) { 
            $return = "$return, ?";
        }

        return $return;
    }

    protected function formate_columns_name(array $values)
    {
        $names_column = array_keys($values);
        $return = '';
        for ($i = 0; $i < count($names_column); $i ++) { 
            if(empty($return))
            {
                $return = "$names_column[$i]";
            } else {
                $return = "$return, $names_column[$i]";
            }
        }
        return $return;
    }

    protected function formate_columns_values(array $values)
    {
        return array_values($values);
    }

    protected function formate_update_params(array $values)
    {
        $return = "";
        $columns = array_keys($values);
        $values_ = array_values($values);
        for ($i = 0; $i < count($values); $i++) { 
            if(empty($return))
            {
                $return = "`$columns[$i]` = '$values_[$i]'";
            } else {
                $return = "$return, `$columns[$i]` = '$values_[$i]'";
            }
        }
        return $return;
    }


}


?>