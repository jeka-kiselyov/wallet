<?php
define('ADODB_ASSOC_CASE', 2);

class db
{
        static function getInstance()
        {
                if (self :: $instance === NULL)
                {
                        self :: $instance = new db(); //create class instance

                        try {
                            self :: $adodb = NewADOConnection(__db_type__);
                            self :: $adodb -> Connect( __db_host__, __db_username__, __db_password__, __db_database__); //connect to database constants are taken from config
                            self :: $adodb -> SetFetchMode(ADODB_FETCH_ASSOC);
                            self :: $adodb -> SetCharSet('utf8');
                            self :: $adodb -> Execute("set names 'utf8'");
                        } catch (Exception $e)
                        {
                            die ("Can't connect to database");
                        }
                }
                return  self :: $instance;
        }

        /**
         * Insert the array into table
         *
         * @param string $tablename
         * @param array $record
         *
         * Example:
         *
         * $DB -> insert('users',array('user' => 'insert_test','name'=>'James','surname'=>'Baker','user_level' => 5));
         * $DB -> insert('users',$_POST);
         *
         */
        public function insert($tablename,$record)
        {
                $rs = self :: $adodb -> Execute( 'SELECT * FROM ' . $tablename . ' LIMIT 1');
                $insert_sql =  self :: $adodb -> GetInsertSQL($rs,$record);
                return self :: $adodb -> Execute( $insert_sql );
        }

        /**
         * Update table
         *
         * @param string $tablename
         * @param $record $array
         * @param integer $where
         * @return result
     *
         * Example:
         *
         * $DB -> update('useri',array('user' => 'update_test','name'=>'James','surname'=>'Baker','phone' => 04656454),'id=75');
         * $DB -> update('users',$_POST,'id=75');
         *
         */
        public function update($tablename,$record,$where = null,$data = null)
        {
                if ( $where !== Null )
                {
                        $rs = self :: $adodb -> Execute( 'SELECT * FROM ' . $tablename . ' WHERE ' . $where .' LIMIT 1',$data);
                } else
                {
                        $rs = self :: $adodb -> Execute( 'SELECT * FROM ' . $tablename .' LIMIT 1',$data);
                }

                $update_sql = self :: $adodb -> GetUpdateSQL( $rs, $record );
                if ( $update_sql != '')
                {
                        return self :: $adodb -> Execute( $update_sql );
                }
                return false;
        }

        /**
         * Delete record from table
         *
         * @param string $tablename
         * @param string $where
         * @return result
         *
         * Example:
         *
         * $DB -> delete('useri','id=75');
         *
         */
        public function delete( $tablename, $where, $params = null)
        {
                $result = self :: $adodb -> Execute( 'DELETE FROM '.$tablename.' WHERE '.$where , $params);
                return $result;
        }

        /**
         * Escape string to be used in SQL query. Use this instead on $db->qstr() if you don't need wraping quotes
         *
         * @param string $string
         * @return result
         *
         * Example:
         *
         * $DB -> escape("field's value");
         *
         */
        public function escape($string)
        {
            return mysqli_real_escape_string(self::$adodb->_connectionID, $string);
        }

        /**
         * Returns a prepared query . Works just like execute , only instead of running the prepared query it returns it
         *
         * @param unknown_type $str
         * @param unknown_type $arr
         * @return unknown
         */
        public function returnPreparedQuery ( $str , $arr )
        {
                $temp = explode ( '?' , $str ) ;
                $size = count ($temp) ;
                for ($x=0;$x<$size;$x++)
                {
                        if ( ($temp[$x] != '') && ( $arr[$x]) != '' )
                        {
                                $temp[$x] .=  ' ' .  self ::$instance -> qstr ($arr[$x]) ;
                        }
                }
                return implode ( ' ' , $temp ) ;
        }

        public function __call($method, $args)//call adodb methods
        {
                return call_user_func_array(array(self :: $adodb, $method),$args);
        }

        public function __get($property)
        {
                return self :: $adodb -> $property;
        }

        public function __set($property, $value)
        {
                self :: $adodb[$property] = $value;
        }

        private function __clone()//do not allow clone
        {
        }

        static private $adodb = false;
        static private $instance = NULL;
}


?>