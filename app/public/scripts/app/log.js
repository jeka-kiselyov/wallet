// log.js
App.log = {

	currentURL: null,
	currentTitle: null,
	currentVisitTime: null,
	setURL: function(url)
	{
		this.currentURL = url;

		if (typeof(ga) === 'function')
		{
			ga('set', 'page', '/'+url);
			ga('send', 'pageview');
		}
	},
	setTitle: function(title)
	{
		this.currentTitle = title;

		if (typeof(ga) === 'function')
		{
			ga('set', 'title', title);
		}
	},
	pageView: function()
	{
		var time = Date.now();

		if (this.currentVisitTime == null || (time - this.currentVisitTime) > 100) /// 100 microseconds
		{
			if (typeof(ga) === 'function')
			{
				ga('send', 'pageview');
			}
		}

		this.currentVisitTime = time;
	}

}