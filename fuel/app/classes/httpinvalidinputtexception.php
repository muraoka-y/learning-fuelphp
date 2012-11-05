<?php

class HttpInvalidInputExeception extends HttpException
{
	public function response()
	{
		$response = Request::forge('error/invalid')->execute()->response();
		return $response;
	}
}