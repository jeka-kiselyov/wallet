// dialog.js
App.Views.Abstract.Dialog = Backbone.View.extend({

	el: $("#dialog_wrapper"),
	show: function(data)
	{
		if (!$("#dialog_wrapper").length)
			$('body').append("<div id='dialog_wrapper'></div>");

		this.setElement($("#dialog_wrapper"));
		
		if (typeof(data) === 'undefined')
			data = {};

		this.$el.html('<div id="dialog_'+this.dialogName+'" class="modal fade dialog_'+this.dialogName+'" role="dialog" aria-labelledby="dialog_label">'+
				'<div class="modal-dialog"><div class="modal-content">Loading</div></div></div>');
		this.$el.children().modal();

		var that = this;

		App.templateManager.fetch('dialogs/'+this.dialogName, data, function(html) {
		 	console.log('Dialog '+that.dialogName+' rendering');
		 	that.$(".modal").html(html);
		 	console.log('Dialog '+that.dialogName+' rendered');
		});
	},
	hide: function()
	{
		console.log("Hide dialog");
		this.$el.children().modal('hide');
	}

});