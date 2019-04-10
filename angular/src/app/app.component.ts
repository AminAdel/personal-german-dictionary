import { Component, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
	selector: 'app-root',
	templateUrl: './app.component.html',
	styleUrls: ['./app.component.scss'],
	encapsulation: ViewEncapsulation.None
})
export class AppComponent implements OnInit {
	
	phrase = '';
	results = [
		{
			id: 1,
			phrase: 'some phrase',
			type: 'name',
			group: 'A1',
			meaning: 'meaning',
			examples: 'dasdads'
		},
		{
			id: 2,
			phrase: 'some phrase2',
			type: 'name2',
			group: 'A2',
			meaning: 'meaning2',
			examples: 'dasdads2'
		}
	];
	form = 'create'; // create || edit
	
	// ==================================================
	
	constructor() {}
	
	ngOnInit(): void {
		
	}
	
	// ==================================================
	
	on_add_char(char) {
		this.phrase += char;
	}
	
	on_result_click(li_index) {
		console.log(li_index);
	}
	
	onSearch() {
		console.log('search');
	}
	
	onCreate() {
		console.log('create');
	}
	
	onEdit() {
		console.log('edit');
	}
}
