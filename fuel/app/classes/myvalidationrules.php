<?php

class MyValidationRules
{
	//改行コードにタブが含まれていないかの検証ルール
	public static function _validation_no_tab_and_newline($value)
	{
		//改行コードやタブが含まれていないか
		if (preg_match('/\A[^\r\n\t]*\z/u',$value) === 1)
		{
			//含まれていない場合はtrue
			return true;
		}
		else 
		{
			//含まれている場合はfalseを返す
			return false;
		}
	}
}