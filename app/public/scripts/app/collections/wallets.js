//users.js
App.Collections.Wallets = Backbone.Collection.extend({
	model: App.Models.Wallet,
    url: function() {
		return App.settings.apiEntryPoint + 'wallets';
    },
});



