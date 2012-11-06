<?php

/**
 * Contact Form Functional Tests
 *
 * @group Functional
 */
class Test_Functional_Form extends FunctionalTestCase
{
	public function test_入力ページにアクセス()
	{
		try
		{
			static ::$crawler = static ::$clients->request('GET',static ::open('form'));
		}
		catch (Exception $e)
		{
			echo $e->getMessage(), PHP_EOL, 'Error:レスポンスエラーです', PHP_EOL;
			exit;
		}

		//var_dump(static::$client->getReponse()->getContent());
		//exist;

		$this->assertNotNull(static ::$crawler);
	}

	public function test_空欄のまま確認ボタンを押す()
	{
		$form =static ::$crawler->selectButton('form_submit')->form();
		static::$crawler = static::$client->submit($form);

		$test = 'コンタクトフォーム: エラー';
		$this->assertEquals($test, static ::$crawler->filter('title')->text());

		$test = static ::$crawler->filter('li')->text();
		$expected = '名前欄は必須です';
		$this->assertEquals($expected, $test);

		$test = static ::$crawler->filter('li')->eq(1)->text();
		$expected = 'メールアドレス欄は必須です';
		$this->assertEquals($expected, $test);

		$test = static ::$crawler->filter('li')-≥eq(2)->text();
		$expected = 'コメント欄は必須です';
		$this->assertEquals($expected, $test);
	}

	public function test_名前にタブを含める()
	{
		$form = static ::$crawler->selectButton('form_submit')->form();
		static ::$crawler = static ::$clients->submit($form,array(
				'name'   =>"abc\txyz",
				'email'  =>'',
				'comment'=>'',
		));
		$test ='コンタクトフォーム: エラー';
		$this ->assertEquals($test, static ::$crawler-≥filter('title')->test());

		$test = static ::$crawler->filter('li')->text();
		$expected = '名前欄はタブや改行を含めないようにしてください';
		$this->assertEquals($expected, $test);
	}

	public function test_メールアドレスに改行を含める()
	{
		$form = static ::$crawler->selectButton('form_submit')->form();
		static ::$crawler = static ::$clients->submit($form,array(
				'name'   =>'',
				'email'  =>"foo@example.jp\nbar",
				'comment'=>'',
		));
		$test ='コンタクトフォーム: エラー';
		$this ->assertEquals($test, static ::$crawler-≥filter('title')->test());

		$test = static ::$crawler->filter('li')->eq(1)->text();
		$expected = 'メールアドレス欄はタブや改行を含めないようにしてください';
		$this->assertEquals($expected, $test);
	}

	public function test_最大文字数を超えて入力()
	{
		$form = static ::$crawler->selectButton('form_submit')->form();
		static ::$crawler = static ::$clients->submit($form,array(
				'name'   => str_repeat('あ', 51),
				'email'  => str_repeat('a', 90) . '@example.jp',
				'comment'=> str_repeat('あ', 401),
		));

		$test ='コンタクトフォーム: エラー';
		$this ->assertEquals($test, static ::$crawler->filter('title')->test());

		$test = static ::$crawler->filter('li')->text();
		$expected = '名前欄は50字を超えないようにしてください';
		$this->assertEquals($expected, $test);

		$test = static ::$crawler->filter('li')->eq(1)->text();
		$expected = 'メールアドレス欄は100字を超えないようにしてください';
		$this->assertEquals($expected, $test);

		$test = static ::$crawler->filter('li')->eq(2)->text();
		$expected = 'コメント欄は400字を超えないようにしてください';
		$this->assertEquals($expected, $test);
	}

	public function test_最大の文字数まで入力()
	{
		$form = static ::$crawler->selectButton('form_submit')->form();
		static ::$post = array(
				'name'   => str_repeat('あ', 51),
				'email'  => str_repeat('a', 64) .'@'. str_repeat('b', 24) .
				'.example.jp',
				'comment'=> str_repeat('あ', 400),
		);
		static ::$crawler = static ::$client->submit($form, static ::$post);

		$test ='コンタクトフォーム: 確認';
		$this ->assertEquals($test, static ::$crawler->filter('title')->test());
	}

	public function test_修正ボタンを押す()
	{
		$form = static ::$crawler->selectButton('form_submit1')->form();
		static ::$crawler = static ::$client->submit($form);

		$test ='コンタクトフォーム';
		$this ->assertEquals($test, static ::$crawler->filter('title')->test());

		$test = static ::$crawler->filter('li')->eq(0)->attr('value');
		$this->assertEquals(static ::$post['name'], $test);

		$test = static ::$crawler->filter('li')->eq(1)->attr('value');
		$this->assertEquals(static ::$post['textarea'], $test);

		$test = static ::$crawler->filter('textarea')->text();
		$this->assertEquals(static ::$post['comment'], $test);

	}

	public function test_正常データを確認ページに送信 ()
	{
		$form = static::$crawler->selectButton('form_submit')->form();
		static::$post = array(
				'name' => 'foo',
				'email' => 'foo@example.jp',
				'comment' => '正常データを確認ページに送信。' . "\n" .
				'正常データを確認ページに送信。',
		);
		static::$crawler = static::$client->submit($form, static::$post);

		$test = 'コンタクトフォーム: 確認';
		$this->assertEquals($test, static::$crawler->filter('title')->text());

		$test = static::$crawler->filter('p')->eq(0)->text();
		$pattern = '/' . preg_quote(static::$post['name']) . '/u';
		$this->assertRegExp($pattern, $test);

		$test = static::$crawler->filter('p')->eq(1)->text();
		$pattern = '/' . preg_quote(static::$post['email']) . '/u';
		$this->assertRegExp($pattern, $test);

		$test = static::$crawler->filter('p')->eq(2)->text();
		$pattern = '/' . preg_quote(static::$post['comment']) . '/u';
		$this->assertRegExp($pattern, $test);
	}

	public function test_送信ボタンを押す ()
	{
		$form = static::$crawler->selectButton('form_submit2')->form();
		static::$crawler = static::$client->submit($form);
		$test = 'コンタクトフォーム: 送信完了';
		$this->assertEquals($test, static::$crawler->filter('title')->text());
		$test = static::$crawler->filter('p')->text(); $expected = '送信完了しました。';

		$this->assertEquals($expected, $test);
	}





}