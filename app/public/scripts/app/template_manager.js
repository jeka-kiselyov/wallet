// template_manager.js
App.templateManager = {

	_cache: {},
	_templates: {},
	_loadingStates: {},

	fetch: function(name, data, success) {
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
			return false;
		
		if (!App.localStorage.isSupported())
			return false;
		
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
		$.get(App.settings.templatePath + name, function( data ) {
			if (data)
			{
				App.localStorage.set('app_temapltes_'+templateName, data);
				that._cache[templateName] = data;
				that._templates[templateName] = new jSmart(data);
				that._loadingStates[templateName] = 'ready';

				if (typeof(callback) === 'function')
					callback(that._templates[templateName]);
			}
		});
	}


};