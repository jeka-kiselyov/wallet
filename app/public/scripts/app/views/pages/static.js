// index.js
App.Views.Pages.Static = App.Views.Abstract.Page.extend({

	templateName: 'pages/static/view',
	render: function() {
		this.renderHTML({static_page: this.model.attributes});
	},
	initialize: function(params) {
		this.renderLoading();

		/// initialize models, collections etc. Request fetching from storage
		this.model = new App.Models.StaticPage();
		this.model.set('slug', params.slug);
		
		this.listenTo(this.model, 'change', this.render);
		this.model.fetch();
	}

});