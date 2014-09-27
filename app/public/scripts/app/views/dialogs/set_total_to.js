// set_total_to.js
App.Views.Dialogs.SetTotalTo = App.Views.Abstract.Dialog.extend({

	dialogName: 'set_total_to',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown"
	},
	initialize: function(params) {
		this.wallet = params.wallet || false;
		this.show();
	},
	onShown: function() {
		this.$('#input_total').focus();
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');

		var total = this.$('#input_total').val();
		total = +total;
		//var description = this.$('#input_description').val();
		
		if (total)
		{
			this.wallet.setTotalTo(total);			
		}

		this.hide();

		return false;
	}
});