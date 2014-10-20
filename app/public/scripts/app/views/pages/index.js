// index.js
App.Views.Pages.Index = App.Views.Abstract.Page.extend({

	templateName: 'pages/index/index',
    category: 'home',
	title: function() {
		return 'Homepage';
	},
	render: function() {
		this.renderHTML({});
	},
	initialize: function() {
		this.renderLoading();		

		/// initialize models, collections etc. Request fetching from storage
		
		this.render();
	}

});