// wallet.js
App.Views.Pages.Wallet = App.Views.Abstract.Page.extend({

	templateName: 'pages/wallets/view',
    category: 'wallets',
	events: {
		"submit #add_transaction_form": "addExpense",
		"click #add_profit_button": "addProfit",
		"click #set_total_to_button": "setTotalTo"
	},
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
	setTotalTo: function()
	{
		App.showDialog('setTotalTo', {wallet: this.model});
		return false;
	},
	addProfit: function()
	{
		App.showDialog('addProfit', {wallet: this.model});
		return false;
	},
	addExpense: function()
	{
		var description = $("#add_transaction_text").val();
		console.log('Add transaction with description: '+description);

		var numbers = description.split(",").join(".").match(/[0-9.]+/g);
		if (typeof(numbers) !== 'undefined' && numbers && typeof(numbers[0]) !== 'undefined')
		{
			var amount = +numbers[0];
			this.model.addExpense(amount, description);
		}

		$("#add_transaction_text").val('').blur();
		return false;
	},
	render: function() {
		this.renderHTML({item: this.model.toJSON(), transactions: this.model.getTransactions().sort().toJSON() });
	},
	wakeUp: function() {
		this.holderReady = false;
		var that = this;
		this.requireSingedIn(function(){
			that.render();
			that.listenTo(that.model, 'change sync', that.render);
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
				that.listenTo(that.model, 'change sync', that.render);
			} else if (typeof(params.id) !== 'undefined') 
			{
				that.model = new App.Models.Wallet();
				that.model.id = params.id;
				
				that.listenTo(that.model, 'change sync', that.render);
				
				that.model.fetch({error: function(){
					App.showPage('NotFound');
				}});			
			} else
				throw 'id or item parameters required';

		});
	}

});