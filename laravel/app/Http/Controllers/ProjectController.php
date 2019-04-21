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
	
	public function search() {
		$request = request();
		$where = [];
		if (!empty($request->phrase)) { $where[] = ['word_phrase', 'like', '%' . $request->phrase . '%']; }
		if ($request->letter != '0') { $where[] = ['word_phrase', 'like', $request->letter . '%']; }
		if ($request->article != '0') { $where[] = ['article', $request->article]; }
		if ($request->type != '0') { $where[] = ['type_id', $request->type]; }
		if ($request->group != '0') { $where[] = ['group_id', $request->group]; }
		$results = DB::table('words')
			->select('words.*', 'types.label as type', 'groups.label as group')
			->where($where)
			->leftJoin('types', 'words.type_id','=','types.id')
			->leftJoin('groups', 'words.group_id','=','groups.id')
			->orderBy('words.id', 'desc')
			->limit(30)
			->get()->toArray();
		return $results;
	} // done
	
	public function create() {
		$request = request();
		$data = [];
		$data['word_phrase'] = $request->phrase;
		$data['article'] = $request->article == 'null' ? null : $request->article;
		$data['type_id'] = $request->type;
		if ($data['type_id'] == '_create_new_') {
			$data['type_id'] = $this->create_type($request->type_new);
		}
		$data['group_id'] = $request->group;
		if ($data['group_id'] == '_create_new_') {
			$data['group_id'] = $this->create_group($request->group_new);
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
		$data['type_id'] = $request->type;
		if ($data['type_id'] == '_create_new_') {
			$data['type_id'] = $this->create_type($request->type_new);
		}
		$data['group_id'] = $request->group;
		if ($data['group_id'] == '_create_new_') {
			$data['group_id'] = $this->create_group($request->group_new);
		}
		$data['meaning'] = $request->meaning;
		$data['examples'] = $request->examples;
		DB::table('words')->where('id', $request->id)->update($data);
		return $data;
	} // done
	
	// ==================================================
	
	public function create_type($type) {
		$check = DB::table('types')->where('label', $type)->get()->toArray();
		$check = (array) $check;
		if (count($check)) return $check[0]['id'];
		$type_id = DB::table('types')->insertGetId([
			'label' => $type
		]);
		return $type_id;
	} // done
	
	public function create_group($group) {
		$check = DB::table('groups')->where('label', $group)->get()->toArray();
		$check = (array) $check;
		if (count($check)) return $check[0]['id'];
		$group_id = DB::table('groups')->insertGetId([
			'label' => $group
		]);
		return $group_id;
	} // done
}