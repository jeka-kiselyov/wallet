// signin.js
App.Views.Dialogs.Registration = App.Views.Abstract.Dialog.extend({

	dialogName: 'registration',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown"
	},
	initialize: function() {
		this.show();
	},
	onShown: function() {
		console.log('Registration dialog is shown');
		this.$('#input_login').focus();
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');

		var login = this.$('#input_login').val();
		var password = this.$('#input_password').val();
		var email = this.$('#input_email').val();

		App.currentUser.clear();
		App.currentUser.on("invalid", function(){
			var html = ""; for (var k in App.currentUser.validationError) html+=App.currentUser.validationError[k].msg+"<br>";
			that.$('.errors-container').html(html);
			that.$('.errors-container').slideDown();

			that.$('#input_login').focus();	/// @todo: focus to input with error
			that.$('.btn-primary').button('reset');

			setTimeout(function() {
				that.$('.errors-container').slideUp();
			}, 2000);
		});
		App.currentUser.on("signedInStatusChanged", function(){
			App.userChanged();
			that.hide();
		});
		App.currentUser.register(login, email, password);

		return false;
	}
});