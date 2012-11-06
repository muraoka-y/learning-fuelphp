<?php
//１フォーム生成
//２入力データ検証
//3検証ok->確認ページ、error->入力ページ表示

//Contoroler template classの継承
class  Controller_Form extends Controller_Public
{
	public function action_index() //action index mehtod 追加
	{
		$form = $this->get_form();

		if (Input::method() === 'POST')
		{
			$form->repopulate();
		}


		$this->template->title = 'コンタクトフォーム';
		$this->template->content = View::forge('form/index');
		$this->template->content->set_safe('html_form', $form->build('form/confirm'));
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

		$val->add('email','メールアドレス')
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
		$form = $this->get_form();
		$val = $form->validation()->add_callable('MyValidationRules');
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
			$form->repopulate();
			$this->template->title = 'コンタクトフォーム:エラー';
			$this->template->content = View::forge('form/index');
			$this->template->content->set_safe('html_error', $val->show_errors());
			$this->template->content->set_safe('html_form', $form->build('form/confirm'));
		}
	}

	public function  action_send()
	{
		//CSRF対策
		if (! Security::check_token())
		{
			return 'ページ遷移が正しくありません';
		}
		$val = $this->get_validation()->add_callable('MyValidationRules');

		if (! $val->run())
		{
			$this->template->title = 'コンタクトフォーム';
			$this->template->content = View::forge('form/index');
			$this->template->content->set_safe('html_error',$val->show_errors());
			return;
		}

		$post = $val->validation();
		$data = $this->build_mail($post);

		//メール送信
		try
		{
			$form->repopulate();
			$this->sendmail($data);
			$this->template->title = 'コンタクトフォーム: 送信完了';
			$this->template->content->set_safe('html_error',$val->show_errors());
			$this->template->content->set_safe('html_form', $form->build('form/confirm'));
			return;
		}
		catch (EmailValidationFailedException $e)
		{
			Log::error(
			'メール検証エラー:' . $e->getMessage(), __METHOD__
			);
			$html_error = '<p>メールを送信できませんでした。</p>';
		}
		$form->repopulate();
		$this->template->title='コンタクトフォーム: 送信エラー';
		$this->template->content=View::forge('form/index');
		$this->template->content->set_safe('html_error',$html_error);
		$this->template->content->set_safe('html_form', $form->build('form/confirm'));

	}

	// フォームの定義
	public function get_form()
	{
		$form = Fieldset::forge();
		
		$form->add('name', '名前')
			->add_rule('trim')
			->add_rule('required')
			->add_rule('no_tab_and_newline')
			->add_rule('max_length', 50);

		$form->add('email', 'メールアドレス')
			->add_rule('trim')
			->add_rule('required')
			->add_rule('no_tab_and_newline')
			->add_rule('max_length', 100)
			->add_rule('valid_email');

		$form->add('comment', 'コメント',
				array('type' => 'textarea', 'cols' => 70, 'rows' => 6))
			->add_rule('required')
			->add_rule('max_length', 400);

		$form->add('submit', '', array('type'=>'submit', 'value' => '確認'));
		return $form;
	}
}