import { Component, OnInit, ViewEncapsulation } from '@angular/core';

@Component({
	selector: 'app-root',
	templateUrl: './app.component.html',
	styleUrls: ['./app.component.scss'],
	encapsulation: ViewEncapsulation.None
})
export class AppComponent implements OnInit {
	
	showCopied = false;
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
		},
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
		},
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
		},
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
		},
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
		},
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
		},
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
		},
	];
	form = 'create'; // create || edit
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
	types = [
		{value: 'name', label: 'Name'},
		{value: 'verb', label: 'Verb'},
		{value: 'adjective', label: 'Adjective'},
		{value: 'number', label: 'Number'},
	];
	groups = [
		{value: 'basic_1', label: 'Basic 1'},
		{value: 'basic_2', label: 'Basic 2'},
		{value: 'basic_3', label: 'Basic 3'},
		{value: 'basic_4', label: 'Basic 4'},
		{value: 'basic_5', label: 'Basic 5'},
		{value: 'intermediate_1', label: 'Intermediate 1'},
		{value: 'intermediate_2', label: 'Intermediate 2'},
		{value: 'intermediate_3', label: 'Intermediate 3'},
		{value: 'intermediate_4', label: 'Intermediate 4'},
		{value: 'advanced_1', label: 'Advanced 1'},
		{value: 'advanced_2', label: 'Advanced 2'},
		{value: 'advanced_3', label: 'Advanced 3'},
	];
	// ==================================================
	
	constructor() {}
	
	ngOnInit(): void {
		
	}
	
	// ==================================================
	
	onResultClick(li_index) {
		console.log(li_index);
		this.form = 'edit';
	}
	
	onSearch() {
		console.log('search');
		this.form = 'create';
	}
	
	onCreate() {
		console.log('create');
	}
	
	onEdit() {
		console.log('edit');
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
	}
}
