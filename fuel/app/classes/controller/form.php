<?php


class  Controller_Form extends Controller_Template
{
	public function action_index()
	{
		$this->template->title = 'コンタクトフォーム';
		$this->template->content = View::forge('form/index');
	}
}

//検証ルールの定義
//public function action_confirm()
{
	$val= Validation::forge();

	$val->add('name','名前')
	->add_rule('trim')
	->add_rule('required')
	->add_rule('max_length',100)
	->add_rule('valid_email');

	$val->add('comment','コメント')
	->add_rule('required')
	->add_rule('max_length',400);

	return $val;
}

//public function action_confirm()
{
	$val = $this->get_validation();

	if ($val->run())
	{
		$data['input'] = $val->validation();
		$this->template->title = View::forge('form/confirm',$data);
	}
	else
	{
		$this->template->title = 'コンタクトフォーム:エラー';
		$this->template->content = View::forge('form/index');
		$this->template->content ->set_safe('html_error',$val->show_errors());
	}
}

//public function  action_send()
{
	//CSRF対策
	if (! Security::check_token())
	{
		return 'ページ遷移が正しくありません'
	}
	$val = $this->get_validation();

	if (! $val->run())
	{
		$this->template->title = 'コンタクトフォーム';
		$this->template->content = View::forge('form/index');
		$this->template->content -> set_safe('html_error',$val->show_errors());
		return
	}

	$post = $val->validation();
	$data = $this->build_mail($post);

	//メール送信
	try
	{
		$this->sendmail($data);
		$this->template->title = 'コンタクトフォーム: 送信完了';
		$this->template->content->set_safe('html_error',$val->show_errors());
		return;
	}
	catch (EmailValidationFailedException $e)
	{
		Log::error(
		'メール検証エラー:' . $e->getMessage(), __METHOD__
		);
		$html_error = '<p>メールを送信できませんでした。</p>';
	}

	$this->template->title='コンタクトフォーム: 送信エラー';
	$this->template->content=View::forge('form/index');
	$this->template->content->set_safe('html_error',$html_error);
}

//メールの作成
//public function build_mail($post)
{
	$data['form']	= $post['email'];
	$data['form_name']	= $post['name'];
	$data['to']	= 'info@example.jp';
	$data['to_name']	= '管理者';
	$data['subject']	= 'コンタクトフォーム';

	$ip = Input:: ();
	$agent = Input::user_agent();

	$data['body']	= <<< END
------------------------------------------------------------------------------------
	名前:{$post['name']}
	メールアドレス:{$post['name']}
	IPアドレス:$ip
	ブラウザ:$agent
------------------------------------------------------------------------------------
コメント:
{$post['comment']}
------------------------------------------------------------------------------------
END;
	

	return $data;
}