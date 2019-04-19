<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProjectController extends Controller {
	
	public function get_types() {
		$types = DB::table('types')
			->orderBy('id', 'asc')
			->get()->toArray();
		return $types;
	} // done
	
	public function get_groups() {
		$groups = DB::table('groups')
			->orderBy('label', 'asc')
			->get()->toArray();
		return $groups;
	} // done
	
	public function get_latest() {
		$latest = DB::table('words')
			->select('words.*', 'types.label as type')
			->leftJoin('types', 'words.type_id','=','types.id')
			->orderBy('words.id', 'desc')
			->limit(30)
			->get()->toArray();
		return $latest;
	} // done
	
	public function create() {
		$request = request();
		$data = [];
		$data['word_phrase'] = $request->phrase;
		$data['article'] = $request->article == 'null' ? null : $request->article;
		$data['group_id'] = $request->type;
		if ($data['group_id'] == '_create_new_') {
			$data['group_id'] = DB::table('types')->insertGetId([
				'label' => $request->type_new
			]);
		}
		$data['group_id'] = $request->group;
		if ($data['group_id'] == '_create_new_') {
			$data['group_id'] = DB::table('groups')->insertGetId([
				'label' => $request->group_new
			]);
		}
		$data['meaning'] = $request->meaning;
		$data['examples'] = $request->examples;
		DB::table('words')->insert($data);
		return $data;
	} // done
	
	public function edit() {
		$request = request();
		$data = [];
		$data['word_phrase'] = $request->phrase;
		$data['article'] = $request->article == 'null' ? null : $request->article;
		$data['group_id'] = $request->type;
		if ($data['group_id'] == '_create_new_') {
			$data['group_id'] = DB::table('types')->insertGetId([
				'label' => $request->type_new
			]);
		}
		$data['group_id'] = $request->group;
		if ($data['group_id'] == '_create_new_') {
			$data['group_id'] = DB::table('groups')->insertGetId([
				'label' => $request->group_new
			]);
		}
		$data['meaning'] = $request->meaning;
		$data['examples'] = $request->examples;
		DB::table('words')->insert($data);
		return $data;
	}
	
	public function search() {
		
	}
}