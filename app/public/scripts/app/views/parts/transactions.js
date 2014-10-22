// transactions.js
App.Views.Parts.Transactions = Backbone.View.extend({

	templateName: 'parts/transactions',
	el: $("#comments_container"),
	events: {
		"click .item": "transactionDetails"
	},
	transactionDetails: function(ev) 
	{
		console.log('views/parts/transactions.js | Show transactions details');
		var data = $(ev.currentTarget).data();
		if (typeof(data.id) === 'undefined')
			return true;

		var id = parseInt(data.id, 10);
		var item = this.collection.get(id);

		if (!item)
			return true;

		App.showDialog('TransactionDetails', {item: item});

		return false;
	},

	initialize: function() {
		console.log('views/parts/transactions.js | Initializing Transactions view');
		if (!this.model || !this.collection)
			console.error('views/parts/transactions.js | model && collection && id should be provided for this view');

		this.listenTo(this.collection, 'fetch sync', this.render);
	},
	wakeUp: function() {
		console.error('views/parts/transactions.js | Waking up');
		this.listenTo(this.collection, 'fetch sync', this.render);		
	},
	render: function() {
		console.log('views/parts/transactions.js | Rendering, state = '+this.collection.state);
		this.setElement($('#'+this.id));

		var data = {state: this.collection.state, transactions: this.collection.sort().toJSON(), item: this.model.toJSON()};

		var that = this;
		App.templateManager.fetch(this.templateName, data, function(html) {
			that.$el.html(html);
			that.trigger('render');
			that.trigger('loaded');
		});		
	}
});
