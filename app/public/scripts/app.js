// app.js
window.App = {

	Models: {},
	Collections: {},
	Views: {Dialogs: {}},

	router: null,

	dialog: null,

	header: null,
	footer: null,
	settings: null,

	currentUser: null,

	init: function()
	{
		var that = this;

		if(!this.currentUser)
			this.setUser();

		$('.signin_caller').on('click', function() {
			console.log('signin_caller click handler');
			App.dialog = new App.Views.Dialogs.Signin();
			App.dialog.show();
			return false;
		});
		$('.signout_caller').on('click', function() {
			console.log('signout_caller click handler');
			App.currentUser.signOut();
			return false;
		});

		Backbone.history.start();
	},
	setUser: function(data)
	{
		this.currentUser = new App.Models.User();
		this.currentUser.on('signedInStatusChanged',this.userChanged, this);
		if (typeof(data) !== 'undefined')
			this.currentUser.signInWithData(data);
	},
	userChanged: function()
	{
		console.log('User info changed');
		// You can also refresh the page here if you want to.
		this.renderLayoutBlocks();
		console.log(App.currentUser.getWallets());
	},
	renderLayoutBlocks: function()
	{
		var that = this;

		if (!this.header)
		{
			this.header = new App.Views.Header();
		}

		var renderFunc = function() {
			that.header.render();
		};

		if ($.isReady)
		{
			renderFunc();
		} else {
			$(function(){ renderFunc(); });
		}
	}

};