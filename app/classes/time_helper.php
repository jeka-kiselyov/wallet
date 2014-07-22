<?php

  class time_helper extends singleton_base 
  {
    public function findNextOccurenceDate($start, $year = 0, $month = 0, $day = 0, $week = 0, $weekday = 0)
    {
      $nstart = $this->ceilDate($start);
      for ($i = 0; $i < 366*10; $i++) /// @todo: Find better algo
      {
        $next_date = $nstart + 24*60*60*$i;
        if ($day != 0 && date("j", $next_date) != $day)
          continue;
        if ($weekday != 0 && date("N", $next_date) != $weekday)
          continue;
        if ($month != 0 && date("n", $next_date) != $month)
          continue;
        if ($week != 0)
        {
          $nweek = date("W", $next_date) - date("W", strtotime( date("Y-m-01", $next_date) ) ) + 1;
          if ($nweek != $week)
            continue;
        }
        if ($year != 0 && date("Y", $next_date) != $year)
          continue;

        return $next_date;
      }

      return false;
    }

    public function ceilDate($time)
    {
      if ($time == false)
        $time = time();

      $midnight = new DateTime();
      $midnight->setTimestamp($time)->modify('tomorrow')->setTime(0, 0);

      return $midnight->getTimestamp();
    }

    public function floorDate($time)
    {
      if ($time == false)
        $time = time();

      $midnight = new DateTime();
      $midnight->setTimestamp($time)->setTime(0, 0);

      return $midnight->getTimestamp();
    }

  }




