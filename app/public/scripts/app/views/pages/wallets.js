// wallets.js
App.Views.Pages.Wallets = Backbone.View.extend({

	templateName: 'wallets',
	render: function() {
		var that = this;

		var holderToRenderTo = 2;
		if (typeof(App.currentHolder) !== 'undefined' && App.currentHolder == 2)
			holderToRenderTo = 1;

		var holderToFadeOut = (holderToRenderTo == 1) ? 2 : 1;

		this.setElement($("#page_holder_"+holderToRenderTo));


		App.templateManager.fetch(this.templateName, { greetings: 'Something' }, function(html) {
			that.$el.html(html);
		});


		$("#page_holder_"+holderToRenderTo).fadeIn(100);
		$("#page_holder_"+holderToFadeOut).fadeOut(100);

		App.currentHolder = holderToRenderTo;
		return this;
	}

});