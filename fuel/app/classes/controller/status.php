<?php

class Controller_Status extends Controller
{
	public function action_index()
	{
		// Statusモデルから結果を取得する
		$results = Model_Status::find_body_by_username('foo');

		// $resultsをダンプして確認する Debug::dump($results);
		return '';// 返り値がないとエラーになるため
	}
}