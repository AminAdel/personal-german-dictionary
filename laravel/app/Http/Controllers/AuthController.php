<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\Classes\Strings;
use App\Methods\Auth2;

class AuthController extends Controller
{
	public function login_api() {
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
	
	public function logout_api() {
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
		DB::table('password_recovery')->insert([
			'user_id' => $user['id'],
			'verification_code' => $recovery_code,
			'date' => time()
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
}
