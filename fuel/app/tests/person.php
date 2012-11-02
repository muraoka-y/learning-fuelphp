<?php

class Person
{
	//プロパティ
	public  $name;   //名前
	private $_gender;//性別
	private $_birthday;//生年月日
	
	//constructer
	public function __construct($name, $gender, $birthday){
		$this->name			= $name;
		$this->_gender		= $gender;
		$this->_birthday	=$birthday;
	}
	
	//get gender method
	public function  get_gender(){
		return $this->_gender;
	}
}