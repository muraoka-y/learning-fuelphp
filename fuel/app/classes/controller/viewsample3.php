<?php

class Controller_ViewSample3 extends Controller{
	public function action_index()
	{
		// Viewオブジェクトを生成する
		$view = View::forge('viewsample');

		// View に変数をセットする
		$view->set('title', 'ビューのサンプル 3');
		$view->set_safe('username', '<del>Azunyan</del>Azusa');
		// View オブジェクトを返す
		return $view;
	}
}