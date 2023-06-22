<?php

namespace Conectors;

class SqliteFunctions
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
            return "AUTOINCREMENT";
        else
            return '';
    }

    protected function unique(bool $value)
    {
        if($value)
            return "UNIQUE";
        else
            return '';
    }

    protected function body_formate(array $body)
    {
        $body_ = '';
        $primary_key_value = '';
        $primary_key_index = 0;
        $auto_increment_value = '';
        $auto_increment_index = 0;

        for ($i = 0; $i < count($body); $i++) {
            $name = $body[$i]['name'];
            $upper = strtoupper($body[$i]['type']);
            if(empty($body_)){
                $body_ = "'$name' $upper {$this->not_ull($body[$i]['not_null'])} {$this->unique($body[$i]['unique'])}";
            }
            else{
                $body_ = "$body_, '$name' $upper {$this->not_ull($body[$i]['not_null'])} {$this->unique($body[$i]['unique'])}";
            }
        }

        for ($i = 0; $i < count($body); $i++) {
            if($body[$i]['primary_key'])
            {
                $primary_key_value = $body[$i]['name'];
                $primary_key_index = $i;
            }
            if($body[$i]['auto_increment'])
            {
                $auto_increment_value = $body[$i]['auto_increment'];
                $auto_increment_index = $i;
            }
        }

        if($auto_increment_index == $primary_key_index)
            $body_ = "$body_, PRIMARY KEY($primary_key_value {$this->auto_increment($auto_increment_value)})";
        else
            $body_ = "$body_, PRIMARY KEY($primary_key_value)";

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