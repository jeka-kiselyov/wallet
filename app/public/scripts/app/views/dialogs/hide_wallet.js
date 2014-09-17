// hide_wallet.js
App.Views.Dialogs.HideWallet = App.Views.Abstract.Dialog.extend({

	dialogName: 'hide_wallet',
	events: {
		"submit form": "onSubmit"
	},
	initialize: function(params) {
		if (typeof(params.item) != 'undefined')
			this.item = params.item;
		else
			throw 'Can not initialize dialog without param.item';

		this.show({item: this.item.toJSON()});
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');
		this.item.set('status', 'hidden');
		this.item.save();
		
		this.hide();

		return false;
	}
});