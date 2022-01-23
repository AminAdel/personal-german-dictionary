<?php

namespace App\Helpers\Classes;

class Numbers {
	
	public static function addZero($number, $length, $default = 'error') {
		// example : $number = 8; $length = 2 : returns -> 08;
		if (strlen($number) < $length) return self::addZero('0' . $number, $length, $default);
		elseif (strlen($number) == $length) return $number;
		else return $default;
	}
}