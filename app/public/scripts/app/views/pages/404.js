// 404.js
App.Views.Pages.NotFound = App.Views.Abstract.Page.extend({

	templateName: '404',
	render: function() {
		this.renderHTML();
	},
	initialize: function(params) {
		this.render();
	}

});