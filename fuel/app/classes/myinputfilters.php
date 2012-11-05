<?php
//okなら入力値をそのまま返し不正な場合はエラー表示
class MyInputFilters
{
	//文字エンコーディングの検証フィルター
	public static function check_encoding($value)
	{
		//引数が配列の場合は再起的に処理
		if (is_array($value))
		{
			array_map(array('MyInputFilters', 'check_encoding'),$value);
			return $value;
		}
		
		//文字エンコーディングを検証、$encodingにはutf8が入っている
		if (mb_check_encoding($value,Fuel::$encoding))
		{
			return $value;
		}
		else 
		{
			//エラーの場合はログに記録
			Log::error(
				'Invalid character encoding: ' . Input::uri() . ' ' .
				urlencode($value) . ' ' .
				Input::ip() . ' "' . Input::user_agent() . '"'
			);
			//エラーを表示して終了
			throw new HttpInvalidInputException('Invalid input data');
		}
	}
}