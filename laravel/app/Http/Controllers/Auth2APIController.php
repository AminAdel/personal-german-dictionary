<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Helpers\Classes\Strings;
use App\Methods\Auth2;

class Auth2APIController extends Controller
{
	public function login() {
		$request = request();
		$email = $request->filled('email') ? $request->input('email') : false;
		$password = $request->filled('password') ? $request->input('password') : false;
		//==================================================
		if (!$email || !$password) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'empty',
					'text' => 'لطفا ایمیل و رمز عبور خود را کامل وارد نمایید'
				]
			];
		}
		//==================================================
		if (!Strings::isEmail($email)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'email_format',
					'text' => 'فرمت ایمیل وارد شده صحیح نیست'
				]
			];
		}
		//==================================================
		if (strlen($password) < 8) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'password_short',
					'text' => 'رمز عبور باید حداقل 8 کاراکتر باشد'
				]
			];
		}
		//==================================================
		$password_hash = Auth2::make_password_hash($password);
		//==================================================
		$user = DB::table('users')->where([
			['email', $email],
			['password_hash', $password_hash]
		])->get()->toArray();
		$user = objectToArray($user);
		if (empty($user)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'not_found',
					'text' => 'کاربری با این اطلاعات پیدا نشد'
				]
			];
		}
		
		// else :
		$login_hash = Auth2::login($user[0]['id']);
		return [
			'status' => 'success',
			'login_hash'=> $login_hash
		];
	} // done
	
	public function logout() {
		$request = request();
		$login_hash = $request->filled('login_hash') ? $request->input('login_hash') : false;
		Auth2::logout($login_hash);
	} // done
	
	public function register() {
		$request = request();
		$email = $request->filled('email') ? $request->input('email') : false;
		$password = $request->filled('password') ? $request->input('password') : false;
		$password2 = $request->filled('password2') ? $request->input('password2') : false;
		//==================================================
		if (!$email || !$password || !$password2) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'empty',
					'text' => 'لطفا ایمیل و رمز عبور خود را کامل وارد نمایید'
				]
			];
		}
		//==================================================
		if (!Strings::isEmail($email)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'email_format',
					'text' => 'فرمت ایمیل وارد شده صحیح نیست'
				]
			];
		}
		//==================================================
		if (strlen($password) < 8) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'password_short',
					'text' => 'رمز عبور باید حداقل 8 کاراکتر باشد'
				]
			];
		}
		//==================================================
		if ($password != $password2) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'passwords_not_equal',
					'text' => 'رمزهای عبور با یکدیگر برابر نیستند'
				]
			];
		}
		//==================================================
		$password_hash = Auth2::make_password_hash($password);
		//==================================================
		$user_info = [
			'email' => $email,
			'mobile' => null,
			'password_hash' => $password_hash,
			'signup_date_y' => jdate('Y'),
			'signup_date_m' => jdate('m'),
			'signup_date_d' => jdate('d'),
		];
		$user_id = DB::table('users')->insertGetId($user_info);
		$login_hash = Auth2::login($user_id);
		return [
			'status' => 'success',
			'login_hash' => $login_hash
		];
	} // done
	
	public function recover() {
		$request = request();
		$email = $request->filled('email') ? $request->input('email') : false;
		//==================================================
		if (!$email) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'empty',
					'text' => 'لطفا ایمیل خود را کامل وارد نمایید'
				]
			];
		}
		//==================================================
		if (!Strings::isEmail($email)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'email_format',
					'text' => 'فرمت ایمیل وارد شده صحیح نیست'
				]
			];
		}
		//==================================================
		$user = DB::table('users')->where([
			['email', $email],
		])->get()->toArray();
		$user = objectToArray($user);
		if (empty($user)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'not_found',
					'text' => 'کاربری با این ایمیل پیدا نشد'
				]
			];
		}
		$user = $user[0];
		//==================================================
		// generate a random number :
		$recovery_code = rand(100000, 999999);
		DB::table('security_strings')->insert([
			'user_id' => $user['id'],
			'type' => 'password_recovery',
			'sec_string' => $recovery_code,
			'time' => time()
		]);
		if (env('APP_ENV', 'local') == 'production') {
			Mail::send(
				'حسابدار شخصی',
				'noreply@akanta.ir',
				$user['email'],
				'بازیابی رمز عبور',
				'بازیابی رمز عبور',
				'<p>کد تایید برای بازیابی رمز عبور :</p><h1>'.$recovery_code.'</h1>'
			);
		}
		//==================================================
		return [
			'status' => 'success',
			'success' => [
				'name' => 'recovery_code_sent',
				'text' => 'کد تایید ارسال شده به ایمیل خود را اینجا وارد نمایید.'
			]
		];
	} // done
	
	public function recover_verify() {
		$request = request();
		$email = $request->filled('email') ? $request->input('email') : false;
		$verification_code = $request->filled('verification_code') ? $request->input('verification_code') : false;
		//==================================================
		if (!$email) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'empty',
					'text' => 'خطا: لطفا یک بار دیگر مراحل بازیابی را طی کنید.'
				]
			];
		}
		//==================================================
		if (!$verification_code) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'empty',
					'text' => 'لطفا کد تایید ارسال شده به ایمیل خود را اینجا وارد نمایید'
				]
			];
		}
		//==================================================
		if (!Strings::isEmail($email)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'email_format',
					'text' => 'فرمت ایمیل وارد شده صحیح نیست'
				]
			];
		}
		//==================================================
		$user = DB::table('users')->where([
			['email', $email],
		])->get()->toArray();
		$user = objectToArray($user);
		if (empty($user)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'not_found',
					'text' => 'کاربری با این ایمیل پیدا نشد'
				]
			];
		}
		$user = $user[0];
		//==================================================
		$verification_code_check = DB::table('security_strings')->where([
			['user_id', $user['id']],
			['type', 'password_recovery'],
			['sec_string', $verification_code],
		])->get()->toArray();
		$verification_code_check = objectToArray($verification_code_check);
		if (empty($verification_code_check)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'wrong_verification_code',
					'text' => 'کد تایید وارد شده صحیح نیست. در صورتیکه بیش از 24 ساعت گذشته باشد مراحل بازیابی را از ابتدا طی کنید.'
				]
			];
		}
		//==================================================
		$change_password = Auth2::makeRecord_changePassword($user['id']);
		return [
			'status' => 'success',
			'success' => [
				'name' => 'change_password',
				'text' => 'لطفا یک رمز عبور جدید برای حساب کاربری خود ثبت نمایید.'
			],
			'change_password' => $change_password
		];
	} // done
	
	public function recover_change_password() {
		$request = request();
		$email = $request->filled('email') ? $request->input('email') : false;
		$password = $request->filled('password') ? $request->input('password') : false;
		$sec_string = $request->filled('sec_string') ? $request->input('sec_string') : false;
		
		//==================================================
		// check sec_string :
		$info = Auth2::getInfo_secString('changing_password', $sec_string);
		if (!$info || time() - $info['time'] > 600) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'timeout',
					'text' => 'فرصت شما برای ثبت رمز عبور جدید به پایان رسیده است. 
					لطفا یک بار دیگر مراحل بازیابی رمز عبور را از ابتدا طی کنید.'
				]
			];
		}
		//==================================================
		if (!$email || !Strings::isEmail($email)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'empty',
					'text' => 'خطا: لطفا یک بار دیگر مراحل بازیابی را طی کنید.'
				]
			];
		}
		//==================================================
		if (!$password || strlen($password) < 8) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'password_short',
					'text' => 'رمز عبور باید حداقل 8 کاراکتر باشد'
				]
			];
		}
		//==================================================
		$user = DB::table('users')->where([
			['email', $email],
		])->get()->toArray();
		$user = objectToArray($user);
		if (empty($user)) {
			return [
				'status' => 'error',
				'error' => [
					'name' => 'not_found',
					'text' => 'کاربری با این ایمیل پیدا نشد'
				]
			];
		}
		$user = $user[0];
		//==================================================
		$password_hash = Auth2::make_password_hash($password);
		//==================================================
		DB::table('users')->where([
			['id', $user['id']],
		])->update([
			'password_hash' => $password_hash
		]);
		//==================================================
		$login_info = Auth2::login($user);
		return $login_info;
	} // done
}
