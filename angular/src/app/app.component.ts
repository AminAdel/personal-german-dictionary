import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Component({
	selector: 'app-root',
	templateUrl: './app.component.html',
	styleUrls: ['./app.component.scss'],
	encapsulation: ViewEncapsulation.None
})
export class AppComponent implements OnInit {
	
	// api_base_url = 'http://127.0.0.1/_AminAdel/personal-dictionary-german/laravel/public/api/'; // work
	api_base_url = 'http://localhost/_github/personal-dictionary-german/laravel/public/api/'; // home
	
	// ==================================================
	
	showCopied = false;
	letters = [
		{value: 'a', label: 'A'},
		{value: 'b', label: 'B'},
		{value: 'c', label: 'C'},
		{value: 'd', label: 'D'},
		{value: 'e', label: 'E'},
		{value: 'f', label: 'F'},
		{value: 'g', label: 'G'},
		{value: 'h', label: 'H'},
		{value: 'i', label: 'I'},
		{value: 'j', label: 'J'},
		{value: 'k', label: 'K'},
		{value: 'l', label: 'L'},
		{value: 'm', label: 'M'},
		{value: 'n', label: 'N'},
		{value: 'o', label: 'O'},
		{value: 'p', label: 'P'},
		{value: 'q', label: 'Q'},
		{value: 'r', label: 'R'},
		{value: 's', label: 'S'},
		{value: 't', label: 'T'},
		{value: 'u', label: 'U'},
		{value: 'v', label: 'V'},
		{value: 'w', label: 'W'},
		{value: 'x', label: 'X'},
		{value: 'y', label: 'Y'},
		{value: 'z', label: 'Z'},
		
		{value: 'ä', label: 'Ä'},
		{value: 'ö', label: 'Ö'},
		{value: 'ü', label: 'Ü'},
		{value: 'ß', label: 'ß'},
	];
	results = [];
	types = [];
	groups = [];
	
	// ==================================================
	
	search_phrase = '';
	search_letter = '0';
	search_article = '0';
	search_type = '0';
	search_group = '0';
	
	create_phrase = '';
	create_article = 'null';
	create_type = '';
	create_type_new = '';
	create_group = '';
	create_group_new = '';
	create_meaning = '';
	create_examples = '';
	
	edit_id: 0;
	edit_phrase = '';
	edit_article = 'null';
	edit_type = '';
	edit_type_new = '';
	edit_group = '';
	edit_group_new = '';
	edit_meaning = '';
	edit_examples = '';
	
	// ==================================================
	
	constructor(private http: HttpClient) {}
	
	ngOnInit(): void {
		this.load_types();
		this.load_groups();
		this.load_latest();
	}
	
	// ==================================================
	
	load_types() {
		this.http.get(this.api_base_url + 'type').subscribe(
			(data: {}[]) => {
				this.types = data;
			},
			(error) => { console.log(error); }
		);
	} // done
	
	load_groups() {
		this.http.get(this.api_base_url + 'group').subscribe(
			(data: {}[]) => {
				this.groups = data;
			},
			(error) => { console.log(error); }
		);
	} // done
	
	load_latest() {
		this.http.get(this.api_base_url + 'latest').subscribe(
			(data: {}[]) => {
				// console.log(data);
				this.results = data;
			},
			(error) => { console.log(error); }
		);
	}
	
	// ==================================================
	
	onResultClick(li_index) {
		console.log(li_index);
		// todo set edit_id
	}
	
	onSearch() {
		console.log('search');
	}
	
	onCreate() {
		let data = {
			phrase: this.create_phrase,
			article: this.create_article,
			type: this.create_type,
			type_new: this.create_type_new,
			group: this.create_group,
			group_new: this.create_group_new,
			meaning: this.create_meaning,
			examples: this.create_examples
		};
		
		this.http.post(this.api_base_url + 'create', data).subscribe(
			(resp) => {
				if (data.type === '_create_new_') { this.load_types(); }
				if (data.group === '_create_new_') { this.load_groups(); }
				this.load_latest();
			},
			(error) => {
				console.log(error);
			},
			() => {},
		);
	}
	
	onEdit() {
		console.log('edit');
		let data = {
			id: this.edit_id,
			phrase: this.edit_phrase,
			type: this.edit_type,
			group: this.edit_group,
			meaning: this.edit_meaning,
			examples: this.edit_examples
		};
		this.http.post(this.api_base_url + 'edit', data).subscribe(
			(resp) => {
				console.log(resp);
			},
			(error) => {
				console.log(error);
			},
			() => {
				console.log('complete');
				this.load_types();
				this.load_groups();
			},
		);
	}
	
	// ==================================================
	
	copyToClipboard(item) {
		document.addEventListener('copy', (e: ClipboardEvent) => {
			e.clipboardData.setData('text/plain', (item));
			e.preventDefault();
			document.removeEventListener('copy', null);
		});
		document.execCommand('copy');
		
		this.showCopied = true;
		setTimeout(() => {
			this.showCopied = false;
		}, 2000);
	} // done
}
