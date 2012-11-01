
<?php

class Model_Status extends Model
{
	public static function find_body_by_username($username)
	{
	   $data = array(
		array(
				// 本来は、例えば、データベースを検索して結果を返す
				'date' => '2012/04/08 12:33',
				'body' => 'イースターなう',
				),
				array(
						'date' => '2012/04/08 07:52',
						'body' => '花祭りなう',
						),
			);
		return $data;
	}
}