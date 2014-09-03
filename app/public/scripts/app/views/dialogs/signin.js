// signin.js
App.Views.Dialogs.Signin = App.Views.Abstract.Dialog.extend({

	dialogName: 'signin',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown"
	},
	initialize: function() {
		this.show();
	},
	onShown: function() {
		console.log('Sign In dialog is shown');
		this.$('#input_username').focus();
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');

		var username = this.$('#input_username').val();
		var password = this.$('#input_password').val();

		App.currentUser.set('login', username);
		App.currentUser.set('password', password);
		App.currentUser.on('signedin', function(){
			that.$('.btn-primary').button('reset');
			that.hide();
		});
		App.currentUser.on('invalid', function(){
			that.$('.btn-primary').button('reset');
			that.$('.errors-container').slideDown();
			that.$('#input_username').focus();
			
			setTimeout(function() {
				that.$('.errors-container').slideUp();
			}, 2000);
		});
		App.currentUser.signIn(username, password);

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