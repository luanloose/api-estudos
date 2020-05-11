<?php

namespace App\Api;

class ApiMessages
{

	public static function getSucess(string $message)
	{
		return [
			'Sucess' => [
				'message' => $message
			]
		];
	}

	public static function getError(string $message)
	{
		return [
			'Error' => [
				'message' => $message
			]
		];
	}
}
