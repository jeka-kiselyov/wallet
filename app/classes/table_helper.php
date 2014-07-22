<?php

  class table_helper extends singleton_base 
  {
    function proccess_order_parameters($session_prefix)
    {
      if (!session_id()) session_start();
      $order_by = "id"; $order_dir = "DESC";

      if (isset($_POST['order_by']))
      {
        if (isset($_SESSION[$session_prefix.'order_by']) && $_POST['order_by'] == $_SESSION[$session_prefix.'order_by'])
         if (isset($_SESSION[$session_prefix.'order_dir']) && $_SESSION[$session_prefix.'order_dir'] == "ASC")
          $_SESSION[$session_prefix.'order_dir'] = "DESC";
           else
            $_SESSION[$session_prefix.'order_dir'] = "ASC";

        $_SESSION[$session_prefix.'order_by'] = $this->regexpes->filter_non_adup($_POST['order_by']);
      }

      if (isset($_SESSION[$session_prefix.'order_by'])) $order_by = $_SESSION[$session_prefix.'order_by'];
      if (isset($_SESSION[$session_prefix.'order_dir'])) $order_dir = $_SESSION[$session_prefix.'order_dir'];

      return array("by"=>$order_by, "dir"=>$order_dir);
    }

    function proccess_search_parameters($session_prefix)
    {
      if (!session_id()) session_start();
      $search = "";
      if (isset($_POST['q'])) $_SESSION[$session_prefix.'q'] = $_POST['q'];
      if (isset($_SESSION[$session_prefix.'q'])) $search = $_SESSION[$session_prefix.'q'];
      return $search;
    }

    function proccess_paging_parameters($count, $per_page = 10)
    {
      if (!session_id()) session_start();
      $page = 0;
      if (isset($_GET['page_n'])) $page = $_GET['page_n'];

      if ($count < $page*$per_page) $page = 0;

      $total_pages = ceil($count/$per_page);

      $ret = array();

      for ($i=0; ($i<$total_pages && $i<3); $i++)
      if ($i == $page)
       $ret[] = array("page"=>$i, "text"=>($i+1), "selected"=>true);
        else
         $ret[] = array("page"=>$i, "text"=>($i+1), "selected"=>false);

      if (isset($ret[count($ret)-1]) && $ret[count($ret)-1]["page"] < $page-3)
         $ret[] = array("page"=>"...", "text"=>"...", "selected"=>false);

      for ($i=$page-2; $i<$page+3; $i++)
      if ($i>2 && $i<$total_pages-3)
      if ($i == $page)
       $ret[] = array("page"=>$i, "text"=>($i+1), "selected"=>true);
        else
         $ret[] = array("page"=>$i, "text"=>($i+1), "selected"=>false);

      if (isset($ret[count($ret)-1]) &&  $ret[count($ret)-1]["page"] < $total_pages-4 && $ret[count($ret)-1]["page"] != "..." )
         $ret[] = array("page"=>"...", "text"=>"...", "selected"=>false);

      for ($i=$total_pages-3; $i<$total_pages; $i++)
      if ($i > 2)
      if ($i == $page)
       $ret[] = array("page"=>$i, "text"=>($i+1), "selected"=>true);
        else
         $ret[] = array("page"=>$i, "text"=>($i+1), "selected"=>false);


      return array("pages"=>$ret, "cur_page"=>$page, "cur_offset"=>$page*$per_page, "total"=>$count, "total_pages"=>$total_pages);
    }

    function get_count($table_name, $search = "", $search_fields = array(), $joins = array() )
    {
      $table_name = $this->regexpes->filter_non_adup($table_name);
      $fields = $this->get_joins_as_fields_array($joins);
      $q = "SELECT COUNT(`".$table_name."`.id) as cnt";

      $q.=" FROM `".$table_name."` ";

      $q.=$this->get_joins_as_join_string($table_name, $joins);
      $q.=$this->get_searches_as_string($table_name, $search, $search_fields, $joins);

      return $this->db->getone($q);
    }

    function get_items($table_name, $order, $limit, $count, $search = "", $search_fields = array(), $joins = array() )
    {
      $table_name = $this->regexpes->filter_non_adup($table_name);
      $q = "SELECT ";

      $fields = $this->get_joins_as_fields_array($joins);
      $fields[] = "`".$table_name."`.*";
      $q.=implode(",", $fields);

      $q.=" FROM `".$table_name."` ";

      $q.=$this->get_joins_as_join_string($table_name, $joins);
      $q.=$this->get_searches_as_string($table_name, $search, $search_fields, $joins);

      $q.=" ORDER BY ".$this->regexpes->filter_non_adup($order['by'])." ".$this->regexpes->filter_non_adup($order['dir']);
      $q.=" LIMIT ".(int)$limit.", ".(int)$count;
      
      return $this->db->getall($q);
    }

    function get_searches_as_string($table_name, $search, $search_fields, $joins)
    {
      $q = " ";
      if ($search)
      {
        $current_table_columns = $this->db->getall("SHOW COLUMNS FROM $table_name"); 
        $columns = array();
        foreach ($current_table_columns as $current_table_column) {
         $columns[$current_table_column['Field']] = true;
        }

        $q.=" WHERE ";
        $search_clauses = array();
        foreach ($search_fields as $key=>$v)
        {
          if (isset($columns[$v]))
          {
            $search_clauses[] = " `".$table_name."`.`".$this->regexpes->filter_non_adup($v)."` LIKE ".$this->db->qstr("%".$search."%")." "; 
          } else {
            $found_in_others = false;
            foreach ($joins as &$j)
            if (isset($j['fields']) && isset($j['alias']))
            foreach ($j['fields'] as $f)
            if ($f == $v)
            {
              $search_clauses[] = " `".$j['alias']."`.`".$this->regexpes->filter_non_adup($v)."` LIKE ".$this->db->qstr("%".$search."%")." ";
              $found_in_others = true;
            }

            if (!$found_in_others)
              $search_clauses[] = " `".$table_name."`.`".$this->regexpes->filter_non_adup($v)."` LIKE ".$this->db->qstr("%".$search."%")." ";            
          }
        }
        $q.=implode(" OR ", $search_clauses);
      }

      return $q;
    }

    function get_joins_as_join_string($table_name, $joins)
    {
      $q = " ";
      if ($joins)
      {
        foreach ($joins as &$j)
        if (isset($j['table']) && isset($j['field']))
        {
          $join_table = $this->regexpes->filter_non_adup($j['table']);
          $join_field = $this->regexpes->filter_non_adup($j['field']);
          $join_table_as = $join_field."_".$join_table;
          $q.=" LEFT JOIN `".$join_table."` as ".$join_table_as." ON `".$join_table_as."`.id =  `".$table_name."`.".$join_field." ";
        }
      }
      return $q;
    }

    function get_joins_as_fields_array(&$joins)
    {
      $fields = array();
      if ($joins)
      {
        foreach ($joins as &$j)
        if (isset($j['table']) && isset($j['field']))
        {
          $join_table = $this->regexpes->filter_non_adup($j['table']);
          $join_field = $this->regexpes->filter_non_adup($j['field']);
          $join_table_as = $join_field."_".$join_table;
          $j['alias'] = $join_table_as;
          $j['fields'] = array();

          $cols = $this->db->getall("SHOW COLUMNS FROM $join_table");
          foreach ($cols as $c)
          {
            $fields[] =" `".$join_table_as."`.`".$c['Field']."` as ".$join_table_as."_".$c['Field'];
            $j['fields'][] = $c['Field'];
          }
        }
      }

      return $fields;
    }


  }



