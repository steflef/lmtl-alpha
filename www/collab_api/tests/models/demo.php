<?php
/**
 * CLASS: Unit Tests for the CI PHP UNIT demonstration
 * 
 * @package default
 * @author Chad Miller <cmiller@redvel.net>
 * @link http://redvel.net
 * @since 11/28/11
 * 

 Copyright (c) 2011 Chad Miller.

 Permission is hereby granted, free of charge, to any person obtaining a copy of this 
 software and associated documentation files (the "Software"), to deal in the Software 
 without restriction, including without limitation the rights to use, copy, modify, 
 merge, publish, distribute, sublicense, and/or sell copies of the Software, and to 
 permit persons to whom the Software is furnished to do so, subject to the following 
 conditions:

 The above copyright notice and this permission notice shall be included in all 
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
 PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
 HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
 CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE 
 OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

 */

//require_once(APPPATH.'/core/MY_Controller.php');
//require_once(APPPATH.'/controllers/tb3.php');
// prevent direct loading of this file
if ( basename(__FILE__) == basename($_SERVER['PHP_SELF']) ) { die("This file cannot be loaded directly."); }

class Test_Demo extends CI_TestCase {

	private $test = "Message bidon";
	public $_CI;

	function setUp() {
		log_message('debug',  "PHPUNIT::SETUP -> Test_Demo");
		echo "PHPUNIT::SETUP -> Test_Demo";

		$this->_CI =& get_instance();
	}

	function test_database() {
		$this->_CI->load->model('gatlas_model');
		$rs = $this->_CI->gatlas_model->rao911_get_details('352253');
		
		$this->assertFalse( $rs );
	}
	
	public function testEcho(){
		echo "TEST!!!!!!";
	}
	
	function test_is_a_real_test() {
	
		// this demo test is set to not pass in order to deliberately
		// remind you to write your own tests.
		$is_a_real_test = 'No, this is not a real test.';
		$this->assertEquals( 'Yes, this is real.' , $is_a_real_test );

	}
	
	function test_is_another_test() {
		echo "PHPUNIT::test_is_another_test -> Test_Demo";
		// this demo test is set to not pass in order to deliberately
		// remind you to write your own tests.
		//$is_a_real_test = 'No, this is not a real test.';
		$this->assertEquals( 'Mon test' , 'Mon test' );

	}
	
	function tearDown() {
		
	}	
}