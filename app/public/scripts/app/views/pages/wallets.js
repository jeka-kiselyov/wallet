// wallets.js
App.Views.Pages.Wallets = App.Views.Abstract.Page.extend({

	templateName: 'pages/wallets/index',
	title: 'Your Wallets',
	url: 'wallets',
	status: 'active',
	events: {
		"mouseenter .item": "moreWalletDetails",
		"mouseleave .item": "lessWalletDetails",
		"click .item_button_remove": "removeItem",
		"click .item_button_edit": "editItem",
		"click .filter_menu": "filter"
	},
	filter: function(ev) {
		var status = $(ev.currentTarget).data('status');
		if ((status == 'active' || status == 'hidden') && status != this.status)
		{
			this.status = status;
			this.render();
		}
		// $(".filter_menu").parent().removeClass('active');
		// $(ev.currentTarget).parent().addClass('active');
		return false;
	},
	moreWalletDetails: function(ev) {
		$(ev.currentTarget).find(".item_buttons").stop().slideDown('slow');
	},
	lessWalletDetails: function(ev) {
		$(ev.currentTarget).find(".item_buttons").stop().slideUp('slow');
	},
	removeItem: function(ev) {
		var id = $(ev.currentTarget).parents('.item').data('id');
		App.showDialog('HideWallet', {item: this.items.get(id)});

		return false;
	},
	editItem: function(ev) {
		var id = $(ev.currentTarget).parents('.item').data('id');
		App.showDialog('EditWallet', {item: this.items.get(id)});

		return false;
	},
	render: function() {
		var filtered = this.items.search({status: this.status});
		this.renderHTML({items: filtered.toJSON(), status: this.status});
	},
	initialize: function() {
		console.log('wallets.js | initialize');
		this.renderLoading();
		this.items = new App.Collections.Wallets();
		/// initialize models, collections etc. Request fetching from storage

		this.listenTo(this.items, 'sync', this.render);

		var that = this;
		this.items.fetch().done(function(){

			that.listenTo(that.items, 'add', that.render);
			that.listenTo(that.items, 'change', that.render);
			that.listenTo(that.items, 'reset', that.render);
			that.listenTo(that.items, 'remove', that.render);
		});
	}

});