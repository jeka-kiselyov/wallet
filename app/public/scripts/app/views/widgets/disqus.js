// disqus.js
App.Views.Widgets.Disqus = Backbone.View.extend({

	shortname: 'example',
	identifier: 'something',
	title: 'title',
	url: '',

	el: $("#disqus_thread"),

	initialize: function() {
		console.log('Initializing disqus widget');
	},
	embedJSCode: function() {

		var that = this;
		window.disqus_config = function() {
			this.page.identifier = that.identifier;
			this.page.url = that.url;
			this.callbacks.onReady = [function() { 
				console.log('Disqus is ready');
			}];
		};

		var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		dsq.src = '//' + this.shortname + '.disqus.com/embed.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	},
	setURL: function(url)
	{
		if (typeof(url) === 'undefined')
			url = document.location;

		this.url = url;

		return this;
	},
	setShortName: function(shortname)
	{
		this.shortname = shortname;
		return this;
	},
	setTitle: function(title)
	{
		this.title = title;
		return this;
	},
	setIdentifier: function(identifier)
	{
		this.identifier = identifier;
		return this;
	},
	reset: function() {
		if (typeof(DISQUS) !== 'undefined')
		{
			var that = this;

			DISQUS.reset({
				reload: true,
				config: function () {  
					this.page.identifier = that.identifier;  
					this.page.url = that.url;
				}
			});	
		} else {
			this.embedJSCode();			
		}
	}
});
