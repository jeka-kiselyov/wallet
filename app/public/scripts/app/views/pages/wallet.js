// wallet.js
App.Views.Pages.Wallet = App.Views.Abstract.Page.extend({

	templateName: 'pages/wallets/view',
    category: 'wallets',
	events: {
		"submit #add_transaction_form": "addExpense",
		"click #add_profit_button": "addProfit",
		"click #set_total_to_button": "setTotalTo",
		"click .item": "transactionDetails"
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
	transactionDetails: function(ev) 
	{
		var data = $(ev.currentTarget).data();
		if (typeof(data.id) === 'undefined')
			return true;

		var id = parseInt(data.id, 10);
		var item = this.model.getTransactions().get(id);

		if (!item)
			return true;

		App.showDialog('TransactionDetails', {item: item});

		return false;
	},
	setTotalTo: function()
	{
		App.showDialog('SetTotalTo', {wallet: this.model});
		return false;
	},
	addProfit: function()
	{
		App.showDialog('AddProfit', {wallet: this.model});
		return false;
	},
	addExpense: function()
	{
		var description = $("#add_transaction_text").val();
		var amount = $("#add_transaction_amount").val(); // could be empty if we are getting amount from description (1st try).

		console.log('Add transaction with description: '+description);

		var numbers = description.split(",").join(".").match(/[0-9.]+/g);
		var fromDescriptionAmount = false;
		if (typeof(numbers) !== 'undefined' && numbers && typeof(numbers[0]) !== 'undefined' && numbers[0])
		{
			fromDescriptionAmount = +numbers[0];
		}

		if (fromDescriptionAmount)
		{
			this.model.addExpense(fromDescriptionAmount, description);
			this.$('#add_transaction_amount').hide();
			$("#add_transaction_text").val('').blur();
		} else {
			amount = amount.split(',').join('.');
			amount = +amount;
			if (amount > 0)
			{
				this.model.addExpense(amount, description);
				this.$('#add_transaction_amount').hide();
				this.$("#add_transaction_text").val('').blur();
			} else {
				this.$('#add_transaction_amount').show();
				this.$('#add_transaction_amount').focus();
			}	
		}

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
			that.listenTo(that.model, 'change sync destroy', that.render);
		});
	},
	reloadWallet: function() {
		var wallet_id = this.model.id;
		var that = this;
		this.requireSingedIn(function(){
			that.model = new App.Models.Wallet();
			that.model.id = wallet_id;
			
			that.listenTo(that.model, 'change sync destroy', that.render);
			
			that.model.fetch({error: function(){
				App.showPage('NotFound');
			}});	
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
				
				that.listenTo(that.model, 'change sync destroy', that.render);
				
				that.model.fetch({error: function(){
					App.showPage('NotFound');
				}});			
			} else
				throw 'id or item parameters required';

		});
	}

});