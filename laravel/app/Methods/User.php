<?php

namespace App\Methods;

use Illuminate\Support\Facades\DB;

class User {
	
	public static function get_userInfo_byId($user_id) {
		$user_info = DB::table('users')->where('id', $user_id)->get()->toArray();
		$user_info = objectToArray($user_info);
		if (empty($user_info)) {
			return [
				'status' => 'error',
				'error' => 'not_found',
			];
		}
		return [
			'status' => 'ok',
			'user_info' => $user_info[0]
		];
	} // done
	
	public static function get_userInfo_byLoginHash($login_hash) {
		$user_id = DB::table('login_hash')->where('hash', $login_hash)->get()->toArray();
		$user_id = objectToArray($user_id);
		if (empty($user_id)) {
			return [
				'status' => 'error',
				'error' => 'not_found'
			];
		}
		return self::get_userInfo_byId($user_id);
	} // done
}