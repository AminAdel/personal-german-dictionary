<?php
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST');
//header("Access-Control-Allow-Headers: X-Requested-With");

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
	header('Access-Control-Allow-Headers: token, Content-Type');
	header('Access-Control-Max-Age: 1728000');
	header('Content-Length: 0');
	header('Content-Type: text/plain');
	die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

Route::get('/type', 'ProjectController@get_types');
Route::get('/group', 'ProjectController@get_groups');
Route::post('/search', 'ProjectController@search');

Route::post('/create', 'ProjectController@create');
Route::post('/edit', 'ProjectController@edit');

Route::prefix('auth')->group(function() {
	Route::post('/register', 'Auth2APIController@register');
	Route::post('/login', 'Auth2APIController@login');
	Route::post('/logout', 'Auth2APIController@logout');
	Route::post('/recover', 'Auth2APIController@recover');
	Route::post('/recover_verify', 'Auth2APIController@recover_verify');
	Route::post('/recover_change_password', 'Auth2APIController@recover_change_password');
});