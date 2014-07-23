<?php

class openexchangerates extends singleton_base 
{
  protected $api_key = '';
  protected $_currencies_codes = array("AED","AFN","ALL","AMD","ANG","AOA","ARS","AUD","AWG","AZN","BAM","BBD","BDT","BGN","BHD","BIF","BMD","BND","BOB","BRL","BSD","BTC","BTN","BWP","BYR","BZD","CAD","CDF","CHF","CLF","CLP","CNY","COP","CRC","CUP","CVE","CZK","DJF","DKK","DOP","DZD","EEK","EGP","ERN","ETB","EUR","FJD","FKP","GBP","GEL","GGP","GHS","GIP","GMD","GNF","GTQ","GYD","HKD","HNL","HRK","HTG","HUF","IDR","ILS","IMP","INR","IQD","IRR","ISK","JEP","JMD","JOD","JPY","KES","KGS","KHR","KMF","KPW","KRW","KWD","KYD","KZT","LAK","LBP","LKR","LRD","LSL","LTL","LVL","LYD","MAD","MDL","MGA","MKD","MMK","MNT","MOP","MRO","MTL","MUR","MVR","MWK","MXN","MYR","MZN","NAD","NGN","NIO","NOK","NPR","NZD","OMR","PAB","PEN","PGK","PHP","PKR","PLN","PYG","QAR","RON","RSD","RUB","RWF","SAR","SBD","SCR","SDG","SEK","SGD","SHP","SLL","SOS","SRD","STD","SVC","SYP","SZL","THB","TJS","TMT","TND","TOP","TRY","TTD","TWD","TZS","UAH","UGX","USD","UYU","UZS","VEF","VND","VUV","WST","XAF","XAG","XAU","XCD","XDR","XOF","XPF","YER","ZAR","ZMK","ZMW","ZWL");
  protected $_data;


  function __construct() 
  {
    parent::__construct();
    if (!$this->registry->settings->openexchangerates_api_key)
      throw new Exception("openexchangerates_api_key setting is missed");
      
    $this->api_key = $this->registry->settings->openexchangerates_api_key;
    $this->_data = false;
  }

  public function __call($method, $args)
  {
    // format $this->USDtoUAH(100);
    if (strpos($method, 'to') !== false)
    {
      $method = explode('to', $method);
      $from = $method[0];
      $to = $method[1];

      if (in_array($from, $this->_currencies_codes) && in_array($to, $this->_currencies_codes))
      {
        $data = $this->getData();
        if (!isset($data['USD']) || !isset($data[$from]) || !isset($data[$to]))
          return false;
        // 1st step, convert to USD first.
        $value = ($args[0]/$data[$from]) * $data[$to];

        return $value;
      }
    }

    return false;
  }

  function getCodes()
  {
    return $this->_currencies_codes;
  }

  function getData()
  {
    if ($this->_data)
      return $this->_data;

    $this->_data = $this->cache->get('openexchangerates');
    if ($this->_data)
      return $this->_data;

    $data = @file_get_contents("http://openexchangerates.org/api/latest.json?app_id=".$this->api_key);
    $data = @json_decode($data);

    $this->_data = get_object_vars($data->rates);

    $this->cache->set($this->_data, 'openexchangerates', array('openexchangerates'), 2679); // 2679 to fit 1000 requests per month for Free account type

    return $this->_data;
  }

}




