// wallets.js
App.Views.Pages.Wallets = App.Views.Abstract.Page.extend({

	templateName: 'wallets',
	render: function() {
		this.renderLoading();
		var that = this;
		//that.renderHTML({});
		setTimeout(function(){ that.renderHTML({}); }, 3000);
	}

});