// abstract.js
App.Views.Abstract.Page = Backbone.View.extend({

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
		});

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
		console.log('Displaying loading');
	}

});