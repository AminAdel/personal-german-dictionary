<?php

namespace App\Methods;

use Illuminate\Support\Facades\DB;

class Auth2
{
	/*
	 * tb: security_strings -> types => 'login_hash', 'changing_password', 'password_recovery', 
	 */
	public static function make_password_hash($password) {
		$string = env('APP_PASSWORD_PREPHRASE') . $password . env('APP_PASSWORD_POSTPHRASE');
		return md5($string);
	} //done
	
	public static function login($user_id) {
		$login_hash = md5($user_id . uniqid());
		DB::table('security_strings')
			->insert([
				'user_id' => $user_id,
				'type' => 'login_hash',
				'sec_string' => $login_hash,
				'time' => time()
			]);
		return [
			'status' => 'success',
			'login_hash' => $login_hash
		];
	} // done
	
	public static function logout($login_hash) {
		DB::table('security_strings')->where('sec_string', $login_hash)->delete();
		return [
			'status' => 'success'
		];
	} // done
	
	public static function update_last_signin($user_id) {
		DB::table('users')->where([
			['id', $user_id]
		])->update([
			'last_signin' => time()
		]);
		return [
			'status' => 'success'
		];
	} // done
	
	public static function makeRecord_changePassword($user_id) {
		$hash_string = md5($user_id . uniqid());
		$time = time();
		DB::table('security_strings')->insert([
			'user_id' => $user_id,
			'type' => 'changing_password',
			'sec_string' => $hash_string,
			'time' => $time
		]);
		return [
			'sec_string' => $hash_string,
			'time' => $time
		];
	} // done
	
	public static function getInfo_secString($type, $sec_string) {
		$info = DB::table('security_strings')
			->where([
				'type' => $type,
				'sec_string' => $sec_string
			])->get()->toArray();
		$info = objectToArray($info);
		if (empty($info)) {
			return false;
		}
		$info['user'] = User::get_userInfo_byId($info['user_id']);
		return $info;
	} // done
	
}