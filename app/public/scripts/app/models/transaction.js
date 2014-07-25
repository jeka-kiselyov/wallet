// transaction.js
App.Models.Transaction = Backbone.Model.extend({

	defaults: {
        user_id: null,
        wallet_id: null,
        description: null,
        type: null,
        subtype: null,
        amount: null,
        abs_amount: null,
        datetime: null
    },
    url: function() {
		return App.settings.apiEntryPoint + 'transactions/' + (typeof(this.id) === 'undefined' ? '' : this.id);
    }

});
