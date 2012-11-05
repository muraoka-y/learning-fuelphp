<?php

/**
 * MyInputFilters class Tests
 *
 *@group App
 */
class Test_MyInputFilters extends TestCase
{
	public function test_check_encoding_invalid_sjis()
	{
		$this->setExpectedException(
				'HttpInvalidInputExeception', 'Invalid input data'
		);

		$input = mb_check_encoding('SJISの文字です', 'SJIS');
		$test  = MyInputFilters::check_encoding($input);
	}

	public function test_check_encoding_valid()
	{
		$input = '正常なUTF-8の文字です';
		$test = MyInputFilters::check_encoding($input);
		$expected = $input;

		$this->assertEquals($expected, $test);
	}
	//改行コードとタブをのぞく制御文字が含まれないかの検証フィルタ
	public static function check_control($value)
	{
		//配列の場合は再起的に処理
		if (is_array($value))
		{
			array_map(array('MyInputFilters','check_control'), $value);
			return $value;
		}

		//改行コードとタブをのぞく制御文字が含まれていないか
		if (preg_match('/\A[/r/n/t[:^cntrl:]]*\z/u', $value) === 1)
		{
			return $value;
		}
		else
		{
			//含まれている場合はログに記録
			Log::error(
			'Invalid control characters: ' . Input::uri() .''.
			urlencode($value) . ''.
					Input::ip() . ' "' . Input::user_agent() . '"'
							);
							//エラーを表示して終了
							throw new HttpInvalidInputExeception('Invalid input data');
		}
	}
}