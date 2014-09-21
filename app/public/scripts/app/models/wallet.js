// wallet.js
App.Models.Wallet = Backbone.Model.extend({

	defaults: {
        name: null,
        type: null,
        status: 'active',
        total: null
    },
    getTotal: function() {
		return parseFloat(this.get('total'),10);
    },
    url: function() {
		return App.settings.apiEntryPoint + 'wallets/' + (typeof(this.id) === 'undefined' ? '' : this.id);
    },
    hide: function() {
        if (this.get('status') == 'active')
        {
            this.set('status', 'hidden');
            this.save();
        }
        else if (this.get('status') == 'hidden')
        {
            this.destroy();
        }
    },
    getTransactions: function() {
        if (typeof(this.transactions) === 'undefined')
        {
            this.transactions = new App.Collections.Transactions();
            this.transactions.setWalletId(this.id);
            this.listenTo(this.transactions, 'sync', function(){this.trigger('sync');});
            this.transactions.fetch();
        }
        this.transactions.setWalletId(this.id);
        return this.transactions;
    },
    addProfit: function(amount, description) {

        var profit = new App.Models.Transaction();
        var amountValue = Math.abs(parseFloat(amount, 10));

        profit.set('description', description);
        profit.set('amount', amountValue);
        profit.set('wallet_id', this.id);

        profit.save();
        this.set('total', this.getTotal()+amountValue);

        this.getTransactions().add(profit);
        this.trigger('change');
    },
    setTotalTo: function(total) {
        var transaction = new App.Models.Transaction();
        var totalValue = Math.abs(parseFloat(total, 10));

        transaction.set('amount', totalValue);
        transaction.set('subtype', 'setup');
        transaction.set('wallet_id', this.id);

        transaction.save();
        this.set('total', totalValue);
        this.getTransactions().add(transaction);
        this.trigger('change');
    },
    addExpense: function(amount, description) {

        var expense = new App.Models.Transaction();
        var amountValue = -Math.abs(parseFloat(amount, 10));

        expense.set('description', description);
        expense.set('amount', amountValue);
        expense.set('wallet_id', this.id);

        expense.save();
        this.set('total', this.getTotal()+amountValue);

        this.getTransactions().add(expense);
        this.trigger('change');
    }

});
