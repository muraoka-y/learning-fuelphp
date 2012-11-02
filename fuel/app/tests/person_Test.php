<?php

require 'person.php';

//test case class はPHPUnit_Framework_TestCaseを継承する
class Person_Test extends PHPUnit_Framework_TestCase
{
	//テストメソッド名はtestではじめる
	public function test_get_gender()
	{
		$person = new Person('Rintaro', 'male', '1988/12/14');
		
		$test =$person->get_gender();
		$expected = 'male';
		
		$this->assertEquals($expected,$test);
	}
}