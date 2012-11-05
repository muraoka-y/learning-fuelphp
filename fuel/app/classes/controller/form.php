<?php
//１フォーム生成
//２入力データ検証
//3検証ok->確認ページ、error->入力ページ表示

//Contoroler template classの継承
class  Controller_Form extends Controller_Public
{
	public function action_index() //action index mehtod 追加
	{
		$this->template->title = 'コンタクトフォーム';//template.phpの中の$title $contentに対応する
		$this->template->content = View::forge('form/index');
	}


	//検証ルールの定義
	public function get_validation()
	{
		$val= Validation::forge();//validationオブジェクト生成
		//addメソッドでフィールド名とラベル名追加、add_ruleメソッドで整形ルール追加
		$val->add('name','名前')
		->add_rule('trim')
		->add_rule('required')
		->add_rule('no_tab_and_newline')
		->add_rule('max_length', 50);

		$val->add('email','メール')
		->add_rule('trim')
		->add_rule('required')
		->add_rule('max_length',100)
		->add_rule('no_tab_and_newline')
		->add_rule('valid_email');

		$val->add('comment','コメント')
		->add_rule('required')
		->add_rule('max_length',400);

		return $val;
	}

	//action_confirmメソッドをformコントローラーに追加
	public function action_confirm()
	{
		$val = $this->get_validation()->add_callable('MyValidationRules');
		/* 検証ルールを定義したvalidationオブジェクトを作成したget_validationメソッドを
		 * add_callableで取得
		 * validationのrunメソッドで実行
		 */ 
		
		if ($val->run())
		{
			$data['input'] = $val->validated();//検証後データを配列$data['input']に代入
			$this->template->title ='コンタクトフォーム: 確認';
			$this->template->content = View::forge('form/confirm',$data);
		}
		else
		{
			$this->template->title = 'コンタクトフォーム:エラー';
			$this->template->content = View::forge('form/index');
			$this->template->content ->set_safe('html_error', $val->show_errors());
			//show_errorsメソッドがhtmlでerroeメッセージを返す、set_safe()メソッドでヴューにセットする
		}
	}

	public function  action_send()
	{
		//CSRF対策
		if (! Security::check_token())
		{
			return 'ページ遷移が正しくありません';
		}
		$val = $this->get_validation();

		if (! $val->run())
		{
			$this->template->title = 'コンタクトフォーム';
			$this->template->content = View::forge('form/index');
			$this->template->content -> set_safe('html_error',$val->show_errors());
			return;
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
	public function build_mail($post)
	{
		$data['form']	= $post['email'];
		$data['form_name']	= $post['name'];
		$data['to']	= 'info@example.jp';
		$data['to_name']	= '管理者';
		$data['subject']	= 'コンタクトフォーム';

		$ip = Input::ip();
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
}