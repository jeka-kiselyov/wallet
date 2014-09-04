// newpassword.js
App.Views.Dialogs.NewPassword = App.Views.Abstract.Dialog.extend({

	code: '',
	hash: '',
	dialogName: 'newpassword',
	events: {
		"submit form": "onSubmit",
		"shown.bs.modal": "onShown"
	},
	initialize: function(params) {
		this.code = ''; if (params && typeof(params.code) !== 'undefined') this.code = params.code;
		this.hash = ''; if (params && typeof(params.hash) !== 'undefined') this.hash = params.hash;
		this.show({code: this.code, hash: this.hash});
	},
	onShown: function() {
		console.log('Registration dialog is shown');
		this.$('#input_password').focus();
	},
	onSubmit: function() {
		var that = this;

		this.$('.btn-primary').button('loading');

		var password = this.$('#input_password').val();
		var confirm = this.$('#input_confirm_password').val();
		var error = '';
		if (password != confirm)
			error = 'Passowrds missmatch';

		if (password.length < 6)
			error = 'Password is too short';

		if (error)
		{
			this.$('.errors-container').html(error);
			this.$('.errors-container').slideDown();

			this.$('#input_password').focus();
			this.$('.btn-primary').button('reset');
			var that = this;
			setTimeout(function() {
				that.$('.errors-container').slideUp();
			}, 2000);
		} else {
			App.currentUser.clear();
			this.listenTo(App.currentUser, 'newpassword:success', function(){
				this.$('.modal-body-default').slideUp();
				this.$('.modal-body-success').slideDown();
			});
			this.listenTo(App.currentUser, 'newpassword:error', function(){
				var html = ""; for (var k in App.currentUser.validationError) html+=App.currentUser.validationError[k].msg+"<br>";
				this.$('.errors-container').html(html);
				this.$('.errors-container').slideDown();

				this.$('#input_email').focus();
				this.$('.btn-primary').button('reset');
				var that = this;
				setTimeout(function() {
					that.$('.errors-container').slideUp();
				}, 2000);
			});

			App.currentUser.newPassword(this.code, this.hash, password);
		}

		return false;
	}
});