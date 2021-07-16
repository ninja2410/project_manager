Vue.config.debug = true;
Vue.config.devtools = true;

new Vue({
	el: '#app',
	components: {
		VueBootstrapTable: VueBootstrapTable
	},
	data: {
		logging: [],
		showFilter: true,
		showPicker: true,
		paginated: true,
		columns: [
		{
			title:"No.",
			visible: true,
			editable: false,
		},
		{
			title:"Nombre",
			visible: true,
			editable: true,
		},
		{
			title:"Edad",
			visible: true,
			editable: true,
		},
		{
			title:"country",
			visible: true,
			editable: true,
		}
		],
		values: [
		{
			"No.": 1,
			"Nombre": "John",
			"country": "UK",
			"age": 25,
		},
		{
			"No.": 2,
			"Nombre": "Mary",
			"country": "France",
			"age": 30,
		},
		{
			"No.": 3,
			"Nombre": "Ana",
			"country": "Portugal",
			"age": 20,
		}
		]
	},
	ready: function () {
	},
	methods: {
		addItem: function() {
			var self = this;
			var item = {
				"id" : this.values.length + 1,
				"name": "name " + (this.values.length+1),
				"country": "Portugal",
				"age": 26,
			};
			this.values.push(item);
		},
		toggleFilter: function() {
			this.showFilter = !this.showFilter;
		},
		togglePicker: function() {
			this.showPicker = !this.showPicker;
		},
		togglePagination: function () {
			this.paginated = !this.paginated;
		}
	},
	events: {
		cellDataModifiedEvent: function( originalValue, newValue, columnTitle, entry) {
			this.logging.push("Original Value : " + originalValue +
				" | New Value : " + newValue +
				" | Column : " + columnTitle +
				" | Complete Entry : " +  entry );
		},
	},
});