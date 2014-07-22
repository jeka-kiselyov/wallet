<?php
 
 class checker_test extends PHPUnit_Framework_0F
 {
 	public function setUp()
    {
    }

    /**
     * Test checker class /includes/checker.class.php
     * @return [type]
     */
    public function test_checker()
    {
        $checker = new checker;

        // Initial parameters
        $this->assertEquals($checker->get_errors_as_html(), "");
        $this->assertEquals($checker->is_good(), true); // synonims
        $this->assertEquals($checker->is_passed(), true); // synonims
        $this->assertEquals($checker->has_errors(), false); // synonims

        $wrong_value = "input value";
        /// Pass set of wrong values
        $checker->check($wrong_value, checker_rules::MIN_LENGTH(12), "Error message 1");
        $this->assertEquals($checker->get_errors(), array(array("message"=>"Error message 1", "input_id"=>"0")) );

        $checker->check($wrong_value, checker_rules::MAX_LENGTH(1), "Error message 2");
        $this->assertEquals($checker->get_errors_as_html(), "Error message 1<br>Error message 2"); /// Two strings in errors array now

        $checker->check($wrong_value, checker_rules::EMAIL(), "Error message 3");
        $this->assertEquals(count($checker->get_errors()), 3); // Now there're 3

        $checker->check($wrong_value, checker_rules::EQUAL('not_equal_to_value'), "Error message 4");
        $this->assertEquals($checker->is_good(), false);

        $good_value = "Good value ".rand(0,1000000)."_".md5(rand(0,100000));
        $checker->clear(); // Clean up errors
        $checker->check($good_value, checker_rules::EQUAL($good_value), "Error message 5");
        $this->assertEquals(count($checker->get_errors()), 0); // No errors, all is fine

        $checker->clear();
        $checker->check($good_value, checker_rules::MIN_LENGTH(10), "Error message 6");
        $this->assertEquals(count($checker->get_errors()), 0);

        $checker->clear();
        $checker->check('email'.md5(time()).'@example.com', checker_rules::EMAIL(), "Error message 7");
        $this->assertEquals($checker->is_good(), true); // still good

        $checker->clear();
        $checker->check($good_value, checker_rules::UNIQUE_IN_DB('users', 'email'), "Error message 8");
        $this->assertEquals(count($checker->get_errors()), 0); /// there's no such email in users database

        $checker->clear();
        $checker->check($good_value, checker_rules::PREG('/^[a-zA-Z0-9_ ]+$/'), "Error message 9");
        $this->assertEquals($checker->is_good(), true); // still good

        $checker->clear();
        $checker->check_post('not_existent_post_value_name', checker_rules::EMAIL(), "Error message 10");
        $this->assertEquals($checker->is_good(), false); // And it's bad now - $_POST[not_existent_post_value_name] is not defined
        $checker->clear();

        $_POST['testpost'] = 'testing13';

        /// Now try to get value from $_POST
        $form_input_value = $checker->check_post('testpost', checker_rules::MIN_LENGTH(3), 'Minimum length of testpost is 3 chars');
        $this->assertEquals($checker->is_good(), true); // Good, as strlen(testing13) > 3
        $this->assertEquals($form_input_value, 'testing13'); // Gotcha!

        /// And now try to see what is with error
        $form_input_value_2 = $checker->check_post('testpost', checker_rules::MIN_LENGTH(23), 'Minimum length of testpost is 23 chars now');
        $this->assertEquals($checker->is_good(), false); // Bad now
        $this->assertEquals($form_input_value_2, false); // Got false instead, as value has not passrd rule

        // get value without checking rules
        $form_input_value_3 = $checker->post('testpost');
        $this->assertEquals($form_input_value_3, 'testing13'); // Got it
    }

	public function tearDown()
	{
	}

 }
