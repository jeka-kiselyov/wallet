// add_wallet.js
App.Views.Dialogs.AddWallet = App.Views.Abstract.Dialog.extend({

	dialogName: 'add_wallet',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown"
	},
	initialize: function() {
		this.show();
	},
	onShown: function() {
		this.$('#input_name').focus();
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');
		var name = this.$('#input_name').val();
		var item = new App.Models.Wallet();
		item.set('name', name);
		item.set('total', 0);
		item.save();

		if (typeof(App.page) !== 'undefined' && App.page && typeof(App.page.items) !== 'undefined' && App.page.items.model == App.Models.Wallet)
		{
			App.page.items.add(item);
		}

		this.hide();

		return false;
	}
});