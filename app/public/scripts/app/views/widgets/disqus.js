// disqus.js
App.Views.Widgets.Disqus = Backbone.View.extend({

	shortname: 'example',
	identifier: 'something',
	title: 'title',
	url: '',

	language: 'en',

	el: $("#comments_container"),

	initialize: function() {
		console.log('Initializing disqus widget');
		this.shortname = App.settings.disqusShortname;
		this.language = App.settings.disqusLanguage;
	},
	embedJSCode: function() {

		var that = this;
		window.disqus_config = function() {
			this.language = that.language;
			this.page.identifier = that.identifier;
			this.page.url = that.url;
			this.page.title = that.title;
			this.callbacks.onReady = [function() { 
				console.log('Disqus is ready');
			}];
		};

		var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
		dsq.src = '//' + this.shortname + '.disqus.com/embed.js';
		(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
	},
	render: function(identifier, title, url) {
		if (typeof(url) === 'undefined')
			url = document.location;

		this.identifier = identifier;
		this.title = title;
		this.url = url;

		this.el = $("#comments_container");

		if (this.$('#disqus_thread').length == 0)
			this.el.html('<div id="disqus_thread"></div>');

		if (typeof(DISQUS) !== 'undefined')
		{
			var that = this;

			DISQUS.reset({
				reload: true,
				config: function () {  
					this.page.identifier = that.identifier;  
					this.page.url = that.url;
					this.page.title = that.title;
					this.language = that.language;
				}
			});	
		} else {
			this.embedJSCode();			
		}
	}
});
