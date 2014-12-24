// balance.js
App.Views.Charts.Balance = Backbone.View.extend({

	events: {
	},
	chart: false,
	aTransactions: [],
	_data: {},
	dataReady: false,
	dataFetched: false,

	initialize: function() {
		if (!this.model || !this.id)
			console.error('views/charts/balance.js | model(wallet) and id of canvas should be provided for this view');
	},
	fetchTransactions: function() {
		if (this.aTransactions.length)
		{
			this.trigger('transactionsReady');
			return this.aTransactions;
		}

		var transactions = new App.Collections.Transactions();
		this.aTransactions = [];
        transactions.setWalletId(this.model.id);

        var that = this;
        transactions.once('sync',function(){
        	console.log('Synced current month');
        	transactions.forEach(function(t){
        		that.aTransactions.push(t);
        	});

        	transactions.once('sync',function(){
        	console.log('Synced prev month');
	        	transactions.forEach(function(t){
	        		that.aTransactions.push(t);
	        	});

	        	that.trigger('transactionsReady');
        	});
        	transactions.gotoPrev();
        });

        transactions.fetch();
	},
	fetchData: function() {
		if (this.dataFetched)
			return false;
		if (this.dataReady)
		{
			return this._data;
		}

		this.dataFetched = true;

		var that = this;
		this.once('transactionsReady',function(){
			_.each(this.aTransactions, function(item){
				console.log(item.get('amount'));
			});
			//// first step. Filter expenses
			var a = _.filter(this.aTransactions, function(t){ return t.get('amount') < 0; });

			//// second step. For each day
			var days = {};
			var curTime = new Date();
			var curDay = curTime.getDate();
			var curMonth = curTime.getMonth();
			curTime = curTime.getTime();
			for (var i = 0; i <= 60; i++)
			{
				var d = new Date(curTime - 24*60*60*1000*i);
				var day = d.getDate();
				var month = d.getMonth();
				var dayTotal = 0;
				_.each(a, function(t){
					var tDate = new Date(t.get('datetime')*1000);
					if (tDate.getDate() == day && tDate.getMonth() == month)
						dayTotal += Math.abs(t.get('amount'));
				});
				days[day+'-'+month] = {day: day, month: month, total: dayTotal};
			}
			/// third step. Find first day from a set at which transaction occurs.
			var firstDay = 0;
			var firstMonth = 0;
			_.each(days, function(day){
				firstDay = day.day;
				firstMonth = day.month;
			});

			/// forth step. Fill days without transactions by values from next days started from firstDay
			_.each(days, function(d){
				if (firstMonth < d.month || (firstDay < d.day && firstMonth == d.month))
				{
					if (d.total == 0 && typeof(d.filledMissed) === 'undefined')
					{
						var foundTotal = 0;
						var foundEmpty = [];
						var iDay = d.day + 1;
						var iMonth = d.month;
						foundEmpty.push(d.day+'-'+d.month);

						while ((foundTotal == 0) && ((curMonth == iMonth && curDay >= iDay) || (curMonth > iMonth)) )
						if (typeof(days[iDay+'-'+iMonth]) !== 'undefined')
						{

							if (days[iDay+'-'+iMonth].total == 0)
							{
								/// another empty day
								foundEmpty.push(iDay+'-'+iMonth);
							} else {
								/// day with total set
								foundEmpty.push(iDay+'-'+iMonth);
								foundTotal = days[iDay+'-'+iMonth].total;
							}

							iDay++;

						} else {
							/// next month
							iMonth++;
							iDay = 1;
						}

						//// fill missed sum
						foundEmpty = _.uniq(foundEmpty);
						if (foundTotal > 0 && foundEmpty.length > 0)
						{
							var total = foundTotal / foundEmpty.length;
							_.each(foundEmpty, function(found){
								days[found].total = total;
								days[found].filledMissed = true;
							});
						}
					}
				}
			});

			/// fifth step. Filter days with total set and truncate to 30 max
			//days = _.filter(days, function(d){ return d.total > 0; });
			//days = _.last(days, 30);

			/// generate labels
			//days.reverse();

			var labels = [];
			_.each(days, function(day, key){
				labels.push(day.day+'/'+(day.month+1));
			});

			/// generate series
			var series = [[]];
			_.each(days, function(day, key){
				series[0].push(day.total);
			});

			labels.reverse(); series[0].reverse();
			labels =  _.last(labels, 30);
			series[0] =  _.last(series[0], 30);

			that._data = {labels: labels, series: series};
			that.dataFetched = false;

			that.dataReady = true;
        	that.trigger('dataReady');
		});
		this.fetchTransactions();
	},
	data: function() {
		this.fetchData();
		return  {
			labels: ['11/12', '12/12', '13/12', '14/12', '15/12','11/12', '12/12', '13/12', '14/12', '15/12'],
			series: [
				[560,560,500,450.55,500,560,560,500,450.55,100]
			]
		};
	},
	wakeUp: function() {
		this.render();
	},
	render: function() {
		console.log('Rendering chart');
		console.log(this.id);
		console.log($('#'+this.id));
		if (!$('#'+this.id).length)
			return;

		this.once('dataReady', function(){
			this.chart = new Chartist.Bar('#'+this.id, this._data, {
				low: 0,
				chartPadding: 0,
				fullWidth: false
			});
		});
		this.fetchData();
	}
});
