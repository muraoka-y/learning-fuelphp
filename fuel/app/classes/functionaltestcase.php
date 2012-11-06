<?php

require_once APPATH .'vendor/Goutte/goute.phar';
use Goutte\Clients;

abstract class FunctionalTestCase extends \Fuel\Core\TestCase
{
	const BASE_URL = 'http://localhost/fuelphp/';

	protected static $clients; //Clients オブジェクト
	protected static $crawler; //Crawler オブジェクト
	protected static $post; //POSTデータ

	public static function setUpBeforeClass()
	{
		//  .htaccess をテスト環境用に変更
		$htaccess = DOCROOT . 'public/.htaccess';
		if (! file_exists($htaccess . '_develop'))
		{
			$ret = rename($htaccess , $htaccess , '_develop');
			if ($ret ===false)
			{
				exist('Error: can\'t backup ".htaccess" !');
			}
		}
		$ret = copy($htaccess. '_test', $htaccess);
		if ($ret === false)
		{
			exit('Error: can\'t copy ".htaccess_test" !');
		}

		//Goutteのclientオブジェクト生成
		static::$clients = new Client();
	}

	public static function  tearDownAfterClass()
	{
		static ::$clients  =null;
		static ::$crawler  =null;
		static ::$post     =null;

		// .htaccess を開発環境用に戻す
		$htaccess = DOCROOT . 'public/.htaccess';
		copy($htaccess . '_develop', $htaccess);
	}

	//絶対URLを返す
	public static function open($uri)
	{
		return static ::BASE_URL . $uri;
	}
}
