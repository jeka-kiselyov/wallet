// wallet.js
App.Views.Pages.Wallet = App.Views.Abstract.Page.extend({

	templateName: 'pages/wallets/index',
	title: 'Your Wallets',
	url: 'wallets',
	render: function() {
		this.renderHTML({items: this.items.toJSON()});
	},
	initialize: function() {
		console.log('wallets.js | initialize');
		this.renderLoading();
		this.items = new App.Collections.Wallets();
		/// initialize models, collections etc. Request fetching from storage

		this.listenTo(this.items, 'add', this.render);
		this.listenTo(this.items, 'reset', this.render);
		this.listenTo(this.items, 'remove', this.render);
		this.listenTo(this.items, 'sync', this.render);

		var that = this;
		this.items.fetch().done(function(){
			that.render();
		});
	}

});