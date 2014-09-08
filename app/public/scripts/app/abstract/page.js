// page.js
App.Views.Abstract.Page = Backbone.View.extend({

	isReady: false,
	widgets: [],
	setURL: function(url) {
		if (typeof(url) === 'undefined')
		{
			var url = '';
			if (typeof(this.url) === 'function')
				url = this.url();
			else if (typeof(this.url) === 'string')
				url = this.url;
		}

		if (url)
			App.router.setUrl(url);
	},
	setTitle: function(title) {
		if (typeof(title) === 'undefined')
		{
			var title = '';
			if (typeof(this.title) === 'function')
				title = this.title();
			else if (typeof(this.title) === 'string')
				title = this.title;
		}
		
		if (typeof(App.settings.title) == 'function')
			title = App.settings.title(title);

		console.log("Document title changed to '"+title+"'");
		$(document).attr('title', title);
	},
	wakeUp: function() {
		this.holderReady = false;
		this.render();
	},
	proccessWidgets: function() {
		var that = this;
		this.$('.client-side-widget').each(function(){
			var data = $(this).data();
			if (typeof(data.widgetName) === 'undefined' || !data.widgetName)
				return false;

			if (typeof(App.Views.Widgets[data.widgetName]) === 'undefined')
			{
				console.error('Widget class for '+data.widgetName+' is not defined');
				return false;
			}

			var widgetView = new App.Views.Widgets[data.widgetName]({  
			  el: $(this)
			});

			that.widgets.push(widgetView);
		});
	},
	renderHTML: function(data) {

		if (typeof(this.templateName) === 'undefined' || !this.templateName)
			throw 'templateName is undefined';

		if (typeof(data) === 'undefined')
			data = {};

		this.switchBuffers();

		var that = this;
		App.templateManager.fetch(this.templateName, data, function(html) {
			that.$el.html('<div class="page">'+html+'</div>');
			$('.page', "#page_holder_"+App.currentHolder).removeClass('page_loading');

			that.trigger('render');
			that.trigger('loaded');
		});
		this.setTitle();
		this.setURL();
		this.isReady = true;

		var that = this;
		setTimeout(function(){
			that.proccessWidgets();
		}, 10);
		
		return this;
	},
	switchBuffers: function() {
		if (typeof(this.holderReady) !== 'undefined' && this.holderReady === true)
			return true;
		console.log('Switching buffers');
		var holderToRenderTo = 2;
		if (typeof(App.currentHolder) !== 'undefined' && App.currentHolder == 2)
			holderToRenderTo = 1;

		var holderToFadeOut = (holderToRenderTo == 1) ? 2 : 1;

		$("#page_holder_"+holderToFadeOut).hide();
		$("#page_holder_"+holderToFadeOut).html('');
		$("#page_holder_"+holderToRenderTo).show();

		this.setElement($("#page_holder_"+holderToRenderTo));

		App.currentHolder = holderToRenderTo;

		this.holderReady = true;
	},
	renderLoading: function() {
		/// ask templateManager to prepare template
		App.templateManager.fetch(this.templateName, {});

		this.switchBuffers();

		this.$el.html('<div class="page page_loading"></div>');
		
		this.setTitle();
		this.setURL();

		console.log('Displaying loading');
		this.trigger('loading');
	}

});