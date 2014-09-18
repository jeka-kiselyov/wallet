// edit_wallet.js
App.Views.Dialogs.EditWallet = App.Views.Abstract.Dialog.extend({

	dialogName: 'edit_wallet',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown"
	},
	initialize: function(params) {
		if (typeof(params.item) != 'undefined')
			this.item = params.item;
		else
			throw 'Can not initialize dialog without param.item';

		this.show({item: this.item.toJSON()});
	},
	onShown: function() {
		this.$('#input_name').focus().select();
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');
		var name = this.$('#input_name').val();
		
		this.item.set('name', name);
		this.item.save();

		this.hide();

		return false;
	}
});