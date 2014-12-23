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
			$('.image-link').magnificPopup({
				type: 'image',
				gallery: { enabled: true },
				image: {
					titleSrc: function(item) {
						return $('#'+item.el.attr('id')+'-title').text() + '<small>'+$('#'+item.el.attr('id')+'-description').text()+'</small>';
					}
				}
			});		

			if ($(window).width() > 800 && $('#footer').offset().top > $('#screenshots_header').offset().top + 180)
			{
				var margin = $('#footer').offset().top - ($('#screenshots_header').offset().top + 180);
				margin = Math.round(margin);
				$('#screenshots_header').css('margin-top', margin+'px');
			}	
		});

		

	}

});