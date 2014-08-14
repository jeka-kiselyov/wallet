// wallets.js
App.Views.Pages.Wallets = App.Views.Abstract.Page.extend({

	templateName: 'wallets',
	render: function() {
		var that = this;
		//that.renderHTML({});
		setTimeout(function(){ that.renderHTML({}); }, 3000);
	},
	initialize: function() {
		this.renderLoading();

		/// initialize models, collections etc. Request fetching from storage
		this.render();
	}

});