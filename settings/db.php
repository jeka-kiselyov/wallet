<?php

   if (ENVIRONMENT_SERVER == 'localhost')
   {
      define("__db_type__", "mysqli");
      define("__db_host__", "localhost");
      define("__db_username__", "root");
      define("__db_password__", "");
      define("__db_database__", "wallet");
   }
   else 
   {
      define("__db_type__", "mysqli");
      define("__db_host__", "localhost");
      define("__db_username__", "root");
      define("__db_password__", "");
      define("__db_database__", "wallet");
   }
