<?php
 
 class openexchangerates_test extends PHPUnit_Framework_0F
 {
    public function test_openexchangerates()
    {
    	$UAH = $this->openexchangerates->USDtoUAH(100);
        $USD = $this->openexchangerates->UAHtoUSD($UAH);

        $this->assertEquals(round($USD), 100);

        $EUR = $this->openexchangerates->UAHtoEUR(10);
        $UAH = $this->openexchangerates->EURtoUAH($EUR);

        $this->assertEquals(round($UAH), 10);

        /// 3 way
        $EUR = $this->openexchangerates->UAHtoEUR(99);
        $RUB = $this->openexchangerates->EURtoRUB($EUR);
        $UAH = $this->openexchangerates->RUBtoUAH($RUB);

        $this->assertEquals(round($UAH), 99);
        
    }

 }
