// index.js
App.Views.Pages.Index = App.Views.Abstract.Page.extend({

	templateName: 'pages/index/index',
    category: 'home',
	events: {
		"click #demo_signup": "demoSignUp"
	},
	demoSignUp: function() {
		console.log('Sign up for the demo');
		this.renderLoading();
		this.listenTo(App.currentUser, 'signedInStatusChanged', function(){
			App.router.redirect('/wallets/');
		});
		App.currentUser.demoRegister();
	},
	title: function() {
		return 'Homepage';
	},
	render: function() {
		this.renderHTML({});
	},
	wakeUp: function() {
		if (typeof(App.currentUser) !== 'undefined' && App.currentUser && App.currentUser.isSignedIn())
			App.router.redirect('/wallets/');
		else {
			this.holderReady = false;
			this.render();
		}
	},
	initialize: function() {
		this.renderLoading();		
		if (typeof(App.currentUser) !== 'undefined' && App.currentUser && App.currentUser.isSignedIn())
			App.router.redirect('/wallets/');
		/// initialize models, collections etc. Request fetching from storage
		this.render();

		this.on('render', function(){
			$('#demo_signup').clickonmouseover();			
		});

		

	}

});