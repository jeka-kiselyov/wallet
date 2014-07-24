// wallet.js
App.Models.Wallet = Backbone.Model.extend({

	defaults: {
        name: null,
        type: null,
        total: null
    },
    getTotal: function() {
		return parseFloat(this.get('total'),10);
    },
    url: function() {
		return App.settings.apiEntryPoint + 'wallets/' + (typeof(this.id) === 'undefined' ? '' : this.id);
    }

});
