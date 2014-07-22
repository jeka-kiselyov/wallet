<?php

    class schema {

        static function getInstance()
        {
            if (self::$instance === NULL)
            {
                self::$instance = new schema(); //create class instance
                self::$tables = @include('settings/schema.php');
                if (!self::$tables)
                    self::$tables = array();
                self::$db = db::getInstance();
            }
            return self::$instance;
        }

        function get_tables()
        {
            return array_keys(self::$tables);
        }

        function get_tables_from_db()
        {
            $tables = self::$db->MetaTables();
            if (!$tables)
                return array();
            else
                return $tables;
        }

        function get_fields($table_name)
        {
            if (isset(self::$tables[$table_name], self::$tables[$table_name]['fields']))
                return self::$tables[$table_name]['fields'];
        }

        function get_fields_from_db($table_name)
        {
            $columns = self::$db->MetaColumns($table_name);
            $keys = self::$db->getall("SHOW INDEXES FROM  `".$table_name."`");
            $fields = array();
            if (!$columns)
                return $fields;

            foreach ($columns as $c) 
            {
                $field = array();
                $field['type'] = $this->sql_type_to_schema_type($c);
                if ($c->primary_key)
                    $field['primaryKey'] = true;
                if ($c->auto_increment)
                    $field['autoIncrement'] = true;
                if ($c->has_default)
                    $field['defaultValue'] = $c->default_value;

                /// indexes
                if (!isset($field['primaryKey']) || !$field['primaryKey'])
                foreach ($keys as $key) {
                    if (isset($key['Column_name']) && $key['Column_name'] == $c->name)
                    {
                        /// INDEX
                        if (isset($key['Non_unique']))
                        {
                            if ($key['Non_unique'])
                            {
                                $field['key'] = true;
                            }
                            else
                                $field['unique'] = true;
                        } else {
                            $field['key'] = true;                            
                        }
                    }
                }

                $fields[$c->name] = $field;
            }

            return $fields;
        }

        private function sql_type_to_schema_type($column)
        {
            $type = strtolower($column->type);
            $max_length = $column->max_length;

            $ret = '';
            switch ($type) {
                case 'varchar':
                case 'char':
                    $ret = 'STRING';
                    if ($max_length && $max_length != 255) $ret.='('.$max_length.')';
                    break;
                case 'text':
                case 'mediumtext':
                    $ret = 'TEXT';
                    break;
                case 'int':
                case 'bigint':
                case 'mediumint':
                case 'smallint':
                case 'tinyint':
                    $ret = 'INTEGER';
                    if ($max_length && $max_length != 11) $ret.='('.$max_length.')';
                    break;
                case 'decimal':
                    $ret = 'DECIMAL';
                    break;
                case 'float':
                    $ret = 'FLOAT';
                    break;
                case 'double':
                    $ret = 'DOUBLE';
                    break;
                case 'real':
                    $ret = 'REAL';
                    break;
                case 'enum':
                    $ret = 'ENUM';
                    $enums = array();
                    if (isset($column->enums) && $column->enums)
                        foreach ($column->enums as $enum)
                            $enums[] = "".$enum."";
                    if ($enums)
                        $ret.='('.implode(",", $enums).')';
                    break;              
                default:
                    # code...
                    break;
            }

            return $ret;
        }

        public function schema_type_to_sql_type($type)
        {
            $max_length = false;
            if (strpos($type, "(") !== false)
            {
                if (strpos($type, 'ENUM') === false)
                {
                    $type = explode("(", $type);
                    $max_length = (int)str_replace(")", '', $type[1]);
                    $type = $type[0];
                } else {
                    $type = str_replace("', '", "','", $type);
                }
            }

            $ret = '';
            switch ($type) {
                case 'STRING':
                    $ret = 'VARCHAR';
                    if (!$max_length) $max_length = 255;
                    break;
                case 'INTEGER':
                    $ret = 'INT';
                    if (!$max_length) $max_length = 11;
                    break;
                case 'TEXT':
                    $ret = 'TEXT';
                    break;
                default:
                    return $type;
                    break;
            }

            if ($max_length)
                return $ret."(".$max_length.")";
            else
                return $ret;
        }

        public function schema_field_to_sql_item($field_name, $field_parameters)
        {
            $item = "`".self::$db->escape($field_name)."` ".$this->schema_type_to_sql_type($field_parameters['type']);
            if (isset($field_parameters['autoIncrement']) && $field_parameters['autoIncrement'])
                $item.= " NOT NULL AUTO_INCREMENT";
            elseif (isset($field_parameters['defaultValue']))
                $item.= " NOT NULL DEFAULT  '".self::$db->escape($field_parameters['defaultValue'])."'";
            else
                $item.= " NULL DEFAULT NULL";

            return $item;
        }

        static private $db = false;
        static private $instance = NULL;
        static private $tables = array();
    }