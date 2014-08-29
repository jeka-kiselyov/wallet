// news_items.js
App.Views.Pages.NewsItems = App.Views.Abstract.Page.extend({

	page: 1,
	perPage: 25,
	templateName: 'pages/news/recent',
	title: function() { return 'News, page: '+this.page; },
	events: {
		"click #go_to_prev": "prevPage",
		"click #go_to_next": "nextPage"
	},
	prevPage: function() {
		if (this.page == 1)
			return false;

		console.log("Navigating to prev page");

		this.page = this.page - 1;
		this.getUpdatedPage();
	},
	nextPage: function() {
		console.log("Navigating to next page");
		this.page = this.page + 1;
		this.getUpdatedPage();
	},
	getUpdatedPage: function() {
		this.items.getPage(this.page);
		App.router.setUrl('news/recent/'+this.page);
	},
	render: function() {
		console.log("Rendering news items");
		if (this.items.length == 0)
		{
			console.log('No more items');
			// No more items. 
			this.$('#go_to_next').parent().addClass('disabled');
			return;
		} else {
			this.$('#go_to_next').parent().removeClass('disabled');
		}
		this.renderHTML({items: this.items.toJSON(), page: this.page, perPage: this.perPage});
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