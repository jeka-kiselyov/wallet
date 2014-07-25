//transactions.js
App.Collections.Transactions = Backbone.Collection.extend({
	model: App.Models.Transaction,
    url: function() {
		return App.settings.apiEntryPoint + 'transactions';
    },
});



