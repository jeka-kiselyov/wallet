// news_items.js
App.Collections.NewsItems = Backbone.PageableCollection.extend({
	model: App.Models.NewsItem,
	url: App.settings.apiEntryPoint+"news_items",

});



