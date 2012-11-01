<?php
class Controller_ViewSample2 extends Controller
{
	public function action_index()
	{
		// Viewオブジェクトを生成する
		$view = View::forge('viewsample');
		// View に変数をセットする
		$view->set('title', 'ビューのサンプル 2');
		$view->set('username', 'Mugi');
		// View オブジェクトを返す
		return $view;
	}
}