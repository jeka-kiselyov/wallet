// wallet.js
App.Views.Pages.Wallet = App.Views.Abstract.Page.extend({

	templateName: 'pages/wallets/view',
	title: function() {
		if (typeof(this.model) != 'undefined' && this.model.get('name'))
			return this.model.get('name');
		else
			return 'Wallet';
	},
	url: function() {
		if (typeof(this.model) != 'undefined' && this.model.id)
			return 'wallets/'+this.model.id;
	},
	render: function() {
		this.renderHTML({item: this.model.toJSON()});
	},
	wakeUp: function() {
		this.holderReady = false;
		var that = this;
		this.requireSingedIn(function(){
			that.render();
			that.listenTo(that.model, 'change', that.render);
		});
	},
	initialize: function(params) {
		console.log('wallet.js | initialize');
		this.renderLoading();

		var that = this;
		this.requireSingedIn(function(){

			/// initialize models, collections etc. Request fetching from storage
			if (typeof(params.item) !== 'undefined')
			{
				that.model = params.item;
				that.render();
				that.listenTo(that.model, 'change', that.render);
			} else if (typeof(params.id) !== 'undefined') 
			{
				that.model = new App.Models.Wallet();
				that.model.id = params.id;
				
				that.listenTo(that.model, 'change', that.render);
				
				that.model.fetch({error: function(){
					App.showPage('NotFound');
				}});			
			} else
				throw 'id or item parameters required';

		});
	}

});