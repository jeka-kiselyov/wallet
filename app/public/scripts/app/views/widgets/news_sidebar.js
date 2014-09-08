// news_sidebar.js
// Note that the name should be the same as XXX in 'shared/widget/XXX.tpl'
App.Views.Widgets.news_sidebar = Backbone.View.extend({

	templateName: 'news_sidebar',
	initialize: function() {
		console.log('Initializing news_sidebar widget');

		this.render();
	},
	renderLoading: function() {

	},
	renderHTML: function(data) {
		if (typeof(data) === 'undefined')
			var data = {};
		var that = this;
		App.templateManager.fetch('widgets/'+this.templateName, data, function(html) {
			that.$el.html(html);
			that.trigger('render');
			that.trigger('loaded');
		});		
	},
	render: function() {
		this.$el.html('SIDEBAR');
		this.renderHTML();
	}
});
