// wallets.js
App.Views.Pages.Wallets = App.Views.Abstract.Page.extend({

	templateName: 'pages/wallets/index',
	title: 'Your Wallets',
	url: 'wallets',
	events: {
		"mouseenter .item": "moreWalletDetails",
		"mouseleave .item": "lessWalletDetails",
		"click .item_button_remove": "removeItem",
	},
	moreWalletDetails: function(ev) {
		$(ev.currentTarget).find(".item_buttons").show();
	},
	lessWalletDetails: function(ev) {
		$(ev.currentTarget).find(".item_buttons").hide();
	},
	removeItem: function(ev) {
		var id = $(ev.currentTarget).parents('.item').data('id');
		App.showDialog('HideWallet', {item: this.items.get(id)});

		return false;
	},
	render: function() {
		this.renderHTML({items: this.items.toJSON()});
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
			that.listenTo(that.items, 'reset', that.render);
			that.listenTo(that.items, 'remove', that.render);
		});
	}

});