<?php

/**
 * MyValidationRules extends TestCase
 * 
 * @group App
 */
class Test_MyValidationRules extends TestCase
{
	public function test_validation_no_tab_and_newline_valid()
	{
		$input = 'タブも開業も含まない文字列です';
		$test = MyValidationRules::_validation_no_tab_and_newline($input);
		$expected = true;
		
		$this->assertEquals($expected, $test);
	}
	
	/*
	 * @dataProvider invalid_data_provider
	 */
	public function test_validation_no_tab_and_newline_invalid($input)
	{
		$test = MyValidationRules::_validation_no_tab_and_newline($input);
		$excepted = false;
		
		$this->assertEquals($expected, $test);
	}
	
	public function invalid_data_provider()
	{
		return array(
		array("改行コードを含む\n文字列です。"),
		array("改行を含む\r文字列です"),
		array("改行を含む\r\n文字列です"),
		array("タブを含む\t文字列です"),
		array("改行と\r\タブを含む\t文字列\nです"),
		);
	}
}