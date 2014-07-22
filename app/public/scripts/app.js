// app.js
window.App = {

	Models: {},
	Collections: {},
	Views: {Dialogs: {}},

	dialog: null,

	header: null,
	footer: null,
	settings: null,
	
	apiEntryPoint: null,

	currentUser: null,

	init: function()
	{
		var that = this;

		if (typeof(site_path) !== 'undefined')
			this.apiEntryPoint = site_path+'/api/';
		else
			this.apiEntryPoint = '//'+document.domain+'/api/';


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