<?php

class Controller_Public extends Controller_Template
{
	public function before()
	{
		parent::before();
		$this->response->set_header('X-FRAME-OPTTIONS','SAMEORGIN');
	}
}