// header.js
App.Views.Header = Backbone.View.extend({

	el: $("#header"),
	events: {
	},

	render: function() {
		this.setElement($("#header"));
		if (App.currentUser.isSignedIn())
		{
			console.log('Rendering for signed in user');

			this.$('.header_is_not_signed_in').hide();
			this.$('.header_is_signed_in').show();
		} else {
			console.log('Rendering for not signed in user');
			this.$('.header_is_not_signed_in').show();
			this.$('.header_is_signed_in').hide();
		}


		console.log('Header rendered');
		return this;
	}
});