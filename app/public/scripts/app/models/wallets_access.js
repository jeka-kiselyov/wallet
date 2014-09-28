// wallets_access.js
App.Models.WalletsAccess = Backbone.Model.extend({	

    defaults: {
        wallet_id: null,
        to_user_id: null,
        original_user_id: null,
        to_email: null
    },
    url: function() {
        if (!this.get('wallet_id'))
            return App.settings.apiEntryPoint + 'wallets_accesses/' + (typeof(this.id) === 'undefined' ? '' : this.id);
        else
            return App.settings.apiEntryPoint + 'wallets/'+this.get('wallet_id')+'/accesses/' + (typeof(this.id) === 'undefined' ? '' : this.id);
    }
});
