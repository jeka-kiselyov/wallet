// wallet_accesses.js
App.Views.Dialogs.WalletAccesses = App.Views.Abstract.Dialog.extend({

	status: 'loading',
	dialogName: 'wallet_accesses',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown",
	},
	initialize: function(params) {
		if (typeof(params.item) != 'undefined')
			this.item = params.item;
		else
			throw 'Can not initialize dialog without param.item';

        this.accesses = new App.Collections.WalletsAccesses();
        this.accesses.setWalletId(this.item.id);
        this.listenTo(this.accesses, 'sync', this.loaded);

        this.accesses.fetch();

        this.show({item: this.item.toJSON(), accesses: this.accesses.toJSON(), status: this.status });
	},
	loaded: function() {
		this.status = 'ready';
		this.render();
	},
	render: function() {
		this.renderHTML({item: this.item.toJSON(), accesses: this.accesses.toJSON(), status: this.status });
		this.$('#input_email').focus();
	},
	onShown: function() {
		this.$('#input_email').focus();
	},
	onSubmit: function() {
		var that = this;

		var email = this.$('#input_email').val();
		if (!email)
			return false;

		var already = this.accesses.where({to_email: email});
		if (already && already.length)
		{
			$("#emails_with_access_"+already[0].id).animate({ opacity: 0.5 }, 500 ).animate({ opacity: 1 }, 500 );

			return false;
		}

		this.$('.btn-primary').button('loading');

		var access = new App.Models.WalletsAccess();
		access.set('to_email', email);
		access.set('wallet_id', this.item.id);
		access.save();
		this.accesses.add(access);
		//this.item.set('name', name);
		//this.item.save();

		//this.hide();

		return false;
	}
});