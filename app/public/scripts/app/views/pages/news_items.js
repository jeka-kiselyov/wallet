// news_items.js
App.Views.Pages.NewsItems = App.Views.Abstract.Page.extend({

	page: 1,
	perPage: 25,
	templateName: 'pages/news/recent',
	title: function() { return 'News, page: '+this.page; },
	events: {
		"click #go_to_prev": "prevPage",
		"click #go_to_next": "nextPage",
		"click .to_news_item": "toNewsItem"
	},
	toNewsItem: function(ev) {
		var data = $(ev.currentTarget).data();
		if (typeof(data.newsItemId) === 'undefined')
			return true;

		var newsItemId = parseInt(data.newsItemId, 10);
		var item = this.items.get(newsItemId);

		if (!item)
			return true;

		App.router.setUrl('news/view/'+item.get('slug')+'.html');
		App.showPage('NewsItem', {item: item});

		return false;
	},
	prevPage: function() {
		if (this.page == 1)
			return false;

		console.log("Navigating to prev page");

		this.page = this.page - 1;
		this.getUpdatedPage();

		return false;
	},
	nextPage: function() {
		if (this.items.length < this.perPage)
			return false;
		
		console.log("Navigating to next page");
		this.page = this.page + 1;
		this.getUpdatedPage();

		return false;
	},
	getUpdatedPage: function() {
		this.items.getPage(this.page);
		App.router.setUrl('news/recent/'+this.page);
	},
	render: function() {
		console.log("Rendering news items");
		this.renderHTML({items: this.items.toJSON(), page: this.page, perPage: this.perPage});
		
		if (!this.items.hasNextPage())
			this.$('#go_to_next').parent().addClass('disabled');
		else
			this.$('#go_to_next').parent().removeClass('disabled');
		
		if (!this.items.hasPreviousPage())
			this.$('#go_to_prev').parent().addClass('disabled');
		else
			this.$('#go_to_prev').parent().removeClass('disabled');

	},
	initialize: function(params) {

		/// initialize models, collections etc. Request fetching from storage
		this.items = new App.Collections.NewsItems();
		this.items.setPageSize(this.perPage);

		if (typeof(params.page) !== 'undefined')
		{
			this.page = parseInt(params.page, 10);
		}

		this.renderLoading();		
		this.listenTo(this.items, 'add', this.render);
		this.listenTo(this.items, 'reset', this.render);
		this.listenTo(this.items, 'remove', this.render);

		this.items.getPage(this.page);
	}

});