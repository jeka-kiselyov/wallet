// signin.js
App.Views.Dialogs.Signin = App.Views.Abstract.Dialog.extend({

	dialogName: 'signin',
	events: {
		"submit #signin_modal_form": "onSubmit",
		"shown.bs.modal": "onShown",
		"hidden.bs.modal": "onHidden"
	},
	initialize: function() {
		this.show();
	},
	onShown: function() {
		console.log('Sign In dialog is shown');
		this.$('#input_username').focus();
	},
	onHidden: function() {
		console.log('Sign In dialog is hidden');
		this.hide();
	},
	onSubmit: function() {
		var that = this;

		this.$('#signin_modal_form_submit').button('loading');

		var username = this.$('#input_username').val();
		var password = this.$('#input_password').val();

		App.currentUser.set('login', username);
		App.currentUser.set('password', password);
		App.currentUser.signIn(function(user) { return that.onResponse(user); });

		return false;
	},
	onResponse: function(user) {
		var that = this;
		if (user.isSignedIn())
		{
			this.$('#signin_modal_form_submit').button('reset');
			this.hide();
		} else {
			this.$('#signin_invalid_password_alert').slideDown();
			this.$('#signin_modal_form_submit').button('reset');
			this.$('#input_username').focus();

			setTimeout(function() {
				that.$('#signin_invalid_password_alert').slideUp();
			}, 1000);
		}
	}
});