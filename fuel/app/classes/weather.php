<?php

class Controller_Weather extends Controller_Rest
{
	public function get_today()
	{
		￼
		// クエリ文字列から地名を代入
		$location = Input::get('loc');
		// 本来はモデルから地名の今日の天気を検索して代入
		$weather  = 'fine';

		// レスポンスを返す
		$this->response(array(
				'location' => $location,
				'weather'  => $weather,
		));
	}
}