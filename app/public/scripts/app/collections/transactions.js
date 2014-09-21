//transactions.js
App.Collections.Transactions = Backbone.Collection.extend({

	model: App.Models.Transaction,
	wallet_id: false,
	comparator: function(item) {
		return -item.get('datetime'); // Note the minus!
	},
    url: function() {
		if (this.wallet_id)
			return App.settings.apiEntryPoint + 'wallets/' + this.wallet_id + '/transactions';
		else
			return App.settings.apiEntryPoint + 'transactions';
    },
    setWalletId: function(wallet_id) {
		this.wallet_id = wallet_id;
    }
});



