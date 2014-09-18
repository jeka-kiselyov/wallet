//wallets.js
App.Collections.Wallets = Backbone.Collection.extend({
	model: App.Models.Wallet,
    url: function() {
		return App.settings.apiEntryPoint + 'wallets';
    },
    search: function(opts) {
        var result = this.where(opts);
        var resultCollection = new App.Collections.Wallets(result);

        return resultCollection;
    }
});



