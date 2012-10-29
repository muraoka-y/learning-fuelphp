<?php

class Controller_Layout extends Controller_Template
{
public function before()
{
// 必ず親クラスの before() メソッドを実行する



￼
parent::before();

$this->current_user = 'Sawako';
}

public function action_index()
{
// ビューファイル全体に$title をセットする
$this->template->set_global('title', 'レイアウト機能のサンプル');

$data = array('user' => $this->current_user);
$this->template->content = View::forge('layout/index', $data);
$this->template->footer  = View::forge('layout/footer');
}
}