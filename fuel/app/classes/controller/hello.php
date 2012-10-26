<?php
class Controller_Hello extends Controller
{
	public function action_index()
	{
	 	// 文字列を返す
		//return 'Hello World!';
		//view オブジェクトを返す*view fileはcontrollerから呼び出される
		return View::forge('hello');
	}
}
