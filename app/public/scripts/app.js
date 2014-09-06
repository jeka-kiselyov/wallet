// app.js
window.App = {

	Models: {},
	Collections: {},
	Views: {Abstract: {}, Dialogs: {}, Pages: {}, Widgets: {}},

	router: null,

	dialog: null,
	page: null,

	header: null,
	footer: null,
	settings: null,

	currentUser: null,

	init: function()
	{
		var that = this;
		
		if(!this.currentUser)
			this.setUser();

		App.localStorage.invalidate(App.settings.version);
		App.router.init();
	},
	showDialog: function(dialogName, params) {
		if (typeof(App.Views.Dialogs[dialogName]) === 'undefined') /// this page is already current
			return false;

		if (App.dialog && App.dialog.isVisible)
		{
			App.dialog.once('hidden', function() {
				console.log('Ready to show another dialog');
				App.dialog = new App.Views.Dialogs[dialogName](params);	
			}, this);
			App.dialog.hide();
		} else {
			App.dialog = new App.Views.Dialogs[dialogName](params);			
		}


		return true;
	},
	showPage: function(pageName, params) {

		console.log('Showing page: '+pageName);

		if (typeof(params) === 'undefined')
			params = {};

		if (typeof(App.Views.Pages[pageName]) === 'undefined')
		{
			console.error("There is no view class defined");
			return false;
		}

		if (typeof(App.page) !== 'undefined' && App.page) /// undelegate events from previous page
		{
			App.page.undelegateEvents();
		}

		/// Trying to get view from stack
		var fromStack = this.viewStack.getView(pageName, params);

		if (fromStack !== false)
		{
			/// Console log wake up page from stack
			console.log('Showing page from stack');
			App.page = fromStack;
			App.page.wakeUp();
		} else {
			/// or create new one
			App.page = new App.Views.Pages[pageName](params);
			this.viewStack.addView(pageName, params, App.page);
		}

		return true;
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