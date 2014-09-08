// news_items.js
App.Collections.NewsItems = Backbone.PageableCollection.extend({
	model: App.Models.NewsItem,
	newsCategoryId: false,
	initialize: function(models, options) {
		if (typeof(options) !== 'undefined' && typeof(options.newsCategoryId) !== 'undefined' && options.newsCategoryId)
			this.newsCategoryId = options.newsCategoryId;
	},
	url: function() {
		if (typeof(this.newsCategoryId) !== 'undefined' && this.newsCategoryId)
			return App.settings.apiEntryPoint+"news_categories/"+this.newsCategoryId+"/news_items";
		else
			return App.settings.apiEntryPoint+"news_items";
	}

});



