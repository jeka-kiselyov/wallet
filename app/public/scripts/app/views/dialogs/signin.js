// signin.js
App.Views.Dialogs.Signin = Backbone.View.extend({

	template: function() { return _.template( $("#templates_dialogs_signin").html() ); },

	el: $("#dialog_wrapper"),
	events: {
		"submit #signin_modal_form": "onSubmit",
		"shown.bs.modal": "onShown",
		"hidden.bs.modal": "onHidden"
	},
	render: function() {
		if (!$("#dialog_wrapper").length)
			$('body').append("<div id='dialog_wrapper'></div>");

		$("#dialog_wrapper").append(this.template());
		this.setElement($("#dialog_signin"));
		return this;
	},
	show: function(callback) {
		if (typeof(callback) === 'function')
			this.onFinished = callback;
		this.render();
		this.$el.modal();
	},
	onShown: function() {
		console.log('Sign In dialog is shown');
		this.$('#input_username').focus();
	},
	onHidden: function() {
		console.log('Sign In dialog is hidden');
		this.remove();

		// Call callback when dialog is closed
		if (typeof(this.onFinished) == 'function')
			this.onFinished();
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
			this.$el.modal('hide');
			this.$('#signin_modal_form_submit').button('reset');
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