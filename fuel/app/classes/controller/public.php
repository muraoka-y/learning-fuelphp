<?php

class Controller_Public extends Controller_Template
{
	public function before()
	{
		parent::before();//beforeメソッドはactionの前に実行される
		$this->response = Response::forge();
		$this->response->set_header('X-FRAME-OPTIONS', 'SAMEORIGIN');
	}
}