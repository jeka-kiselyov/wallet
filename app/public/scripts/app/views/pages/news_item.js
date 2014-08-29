// news_item.js
App.Views.Pages.NewsItem = App.Views.Abstract.Page.extend({

	templateName: 'pages/news/view',
	title: function() {
		if (typeof(this.model) != 'undefined' && this.model.get('title'))
			return this.model.get('title');
	},
	render: function() {
		this.renderHTML({item: this.model.attributes});
	},
	initialize: function(params) {

		/// initialize models, collections etc. Request fetching from storage
		this.model = new App.Models.NewsItem();
		this.model.set('slug', params.slug);
		
		this.listenTo(this.model, 'change', this.render);

		this.renderLoading();
		
		this.model.fetch({error: function(){
			App.showPage('NotFound');
		}});
	}

});