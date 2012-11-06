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
			static ::log_error('Invalid character encoding',$value);
			//エラーを表示して終了
			throw new HttpInvalidInputException('Invalid input data');	
		}
	}
	
	//改行コードとタブをのぞく文字が含まれないかの検証フィルター
	public static function check_control($value)
	{
		//配列の場合は再起的に処理
		if (is_array($value))
		{
			array_map(array('MyInputFilters', 'check_control'), $value);
			return $value;
		}

		//改行コードとタブをのぞく制御文字が含まれていないか
		if (preg_match('/\A[\r\n\t[:^cntrl:]]*\z/u', $value) === 1)
		{
			return $value;
		}
		else 
		{
			//含まれている場合はログに記録
			static ::log_error('Invalid control characters' ,$value);
			//エラー表示して終了
			throw new HttpInvalidInputExeception('Invalid input data');
		}
	}
	
	//エラーをログに記録
	public static function log_error($msg,$value)
	{
		Log::error(
			$msg .':' . Input::user_agent() . '"'
		);
	}
}