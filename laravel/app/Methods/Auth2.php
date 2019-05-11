<?php

namespace App\Methods;

use Illuminate\Support\Facades\DB;

class Auth2
{
	public static function make_password_hash($password) {
		$string = env('APP_PASSWORD_PREPHRASE') . $password . env('APP_PASSWORD_POSTPHRASE');
		return md5($string);
	} //done
	
	public static function login($user_id) {
		$login_hash = md5($user_id . uniqid());
		DB::table('login_hash')
			->insert([
				'user_id' => $user_id,
				'hash' => $login_hash,
				'time' => time()
			]);
		return [
			'status' => 'ok',
			'login_hash' => $login_hash
		];
	} // done
	
	public static function logout($login_hash) {
		DB::table('login_hash')->where('hash', $login_hash)->delete();
		return [
			'status' => 'ok'
		];
	} // done
	
	public static function update_last_signin($user_id) {
		DB::table('users')->where([
			['id', $user_id]
		])->update([
			'last_signin' => time()
		]);
		return [
			'status' => 'ok'
		];
	} // done
	
}