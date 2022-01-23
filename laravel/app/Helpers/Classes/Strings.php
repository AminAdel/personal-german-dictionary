<?php

namespace App\Helpers\Classes;

class Strings {
	
	public static function isEmail($str) {
		if (filter_var($str, FILTER_VALIDATE_EMAIL) == false) return false;
		return true;
	}
	
	public static function isMobile($str) {
		$mobile = self::mobileCleanup($str);
		if ($mobile == false) return false;
		return true;
	}
	
	public static function mobileCleanup($mobile) {
		// task : remove extra chars; return cleaned-up mobile number;
		$mobile = preg_replace('/[^0-9]/', '', $mobile);
		$mobile = ltrim($mobile, '0');
		
		if (strlen($mobile) < 10 || strlen($mobile) > 13) return false;
		
		return $mobile;
	}
	
	public static function mobileExtract($mobile) {
		// task : separate CountryCode and MobileNumber;
		$mobile = self::mobileCleanup($mobile); if ($mobile == false) return false;
		$mobile = str_split(strrev($mobile), 10);
		$mobile[0] = strrev($mobile[0]);
		$mobile[1] = (isset($mobile[1]) ? strrev($mobile[1]) : '');
		return $mobile; // as array [mobileNumber, countryCode]
	}
	
	public static function emailOrMobile($str) {
		if (self::isEmail($str)) return 'email';
		if (self::isMobile($str)) return 'mobile';
		return false;
	}
	
	public static function removeNumbers($string) {
		$string = self::en_fa_numbers($string, 'en');
		$string = preg_replace('/[0-9]/', '', $string);
		return trim($string);
	}
	
	public static function removeEnglishChars($string) {
		$string = preg_replace('/[a-zA-Z]/', '', $string);
		return trim($string);
	}
	
	public static function removeFarsiChars($string) {
		$string = str_ireplace([
			'ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش',
			'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 
			'ئ', 'ؤ', 'أ', 'ء', 'آ', 'ۀ', 'ة', 'ي', ''
		], '', $string);
		return trim($string);
	}
	
	//==================================================
	
	public static function arabic2farsi($string) {
		/* change arabic chars with farsi chars */
		$string = str_ireplace('ك', 'ک', $string);
		$string = str_ireplace('ي', 'ی', $string);
		$string = str_ireplace('ة', 'ه', $string);
		
		return $string;
	}
	
	public static function en_fa_numbers($string, $to = 'fa') {
		$numbers_fa = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
		$numbers_en = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
		if ($to == 'fa') {
			$string = str_replace($numbers_en, $numbers_fa, $string);
		}
		elseif ($to == 'en') {
			$string = str_replace($numbers_fa, $numbers_en, $string);
		}
		return $string;
	}
	
	//==================================================
	
	public static function preg_match_replace($pattern, $replacement, $subject) {
		// version 1.0.0    ->  1397.03.30
		preg_match_all($pattern, $subject, $matches, PREG_SET_ORDER); //zdd($matches);
		foreach ($matches as $index => $match) {
			$subject = str_replace($match[0], str_replace($match[1], $replacement, $match[0]), $subject);
		}
		return $subject;
	} //done
}