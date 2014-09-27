// transaction_details.js
App.Views.Dialogs.TransactionDetails = App.Views.Abstract.Dialog.extend({

	dialogName: 'transaction_details',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown",
		"click #remove_transaction_button": "removeTransaction"
	},
	removeTransaction: function() {
		App.showDialog('RemoveTransaction', {item: this.item});
		return false;
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
		//var name = this.$('#input_name').val();
		
		//this.item.set('name', name);
		//this.item.save();

		this.hide();

		return false;
	}
});