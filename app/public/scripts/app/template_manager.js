// template_manager.js
App.templateManager = {

	_cache: {},
	_templates: {},
	_loadingStates: {},
	_initialized: false,

	initialize: function()
	{
	    jSmart.prototype.getTemplate = function(name)
	    {
	    	if (name.indexOf('shared/widgets/') === 0)
	    	{
	    		/// It's a widget!
	    		var widgetName = name.split('shared/widgets/').join('').split('.tpl').join('');
	    		console.log('template_manager.js | Including widget "'+widgetName+'"');

	    		return '<div class="widget client-side-widget client-side-widget-'+widgetName+'" id="widget_'+(Math.random()+'').split('0.').join('')+'" data-widget-name="'+widgetName+'"></div>';
	    	}

	    	if (typeof(App.templateManager._templates[name]) !== 'undefined')
	    	{
	    		return App.templateManager._cache[name];
	    	} else {
		        throw new Error('Template ' + name + ' is not yet loaded');	    		
	    	}
	    }

	    this._initialized = true;
	},
	commonData: function()
	{
		return {
			settings: {
				site_path: App.settings.sitePath,
				client_side: true
			}
		};
	},
	fetch: function(name, data, success) {

		if (!this._initialized)
			this.initialize();

		var data = _.extend(data, this.commonData());

		if (typeof(this._templates[name]) !== 'undefined' || this.tryToLoadFromStorage(name))
		{
			var res = this._templates[name].fetch(data);
			if (typeof(success) === 'function')
				success(res);
			return res;
		}

		var that = this;
		this._loadingStates[name] = 'loading';

		if (typeof(success) === 'function')
		{
			this.loadFromServer(name, function(tpl) {
				success(tpl.fetch(data));
			});
		} else {
			this.loadFromServer(name);
		}

		return false;
	},
	tryToLoadFromStorage: function(name)
	{
		if (!App.settings.enableTemplatesCache)
		{
			console.log('Templates cache is disabled');
			return false;
		}

		if (!App.localStorage.isSupported())
		{
			console.log('Local storage is disabled');
			return false;
		}

		var data = App.localStorage.get('app_temapltes_'+name);
		if (data)
		{
			this._cache[name] = data;
			this._templates[name] = new jSmart(data);
			this._loadingStates[name] = 'ready';

			return true;
		}

		return false;
	},
	loadFromServer: function(name, callback) {
		var that = this;
		var templateName = name;
		var process = function(data)
		{
			if (data)
			{
				App.localStorage.set('app_temapltes_'+templateName, data);
				that._cache[templateName] = data;
				that._templates[templateName] = new jSmart(data);
				that._loadingStates[templateName] = 'ready';

				if (typeof(callback) === 'function')
					callback(that._templates[templateName]);
			}
		};

		var use_cache = true;
		if (!App.settings.enableTemplatesCache)
			use_cache = false;

		$.ajax({
			url: App.settings.templatePath + name,
			data: {},
			success: process,
			cache: use_cache
		});
	}


};