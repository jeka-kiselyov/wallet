// news_items.js
App.Views.Pages.NewsItems = App.Views.Abstract.Page.extend({

	templateName: 'pages/news/recent',
	title: 'News',
	render: function() {
		this.renderHTML({items: this.items.toJSON()});
	},
	initialize: function(params) {

		/// initialize models, collections etc. Request fetching from storage
		this.items = new App.Collections.NewsItems();

		this.renderLoading();
		var that = this;
		this.items.getFirstPage().done(function(){
			that.render();
		});
	}

});