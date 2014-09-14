// csfr_protector.js
App.csfrProtector = {
	tokens: [],
	maximumTokensToGetUpdate: 10,
	initialized: false,
	isLoadingTokens: false,
	initialize: function() {

		var that = this;
		jQuery.ajaxPrefilter(function(options, _, xhr) {
			console.log('csfr_protector.js | ajaxPrefilter for URL '+options.url);
			if (!xhr.crossDomain && (typeof(options.hasCSRFToken) === 'undefined' || !options.hasCSRFToken))
			{
				xhr.setRequestHeader('X-CSRF-Token', that.nextToken());
				options.hasCSRFToken = true;
			}
		});

		this.initialized = true;
	},
	addToken: function(token) {
		if (!this.initialized)
			this.initialize();

		if (typeof(token) === 'string')
			token = [].concat(token);
		for (var k in token)
			this.tokens.push(token[k]);

		console.log('csfr_protector.js | Added '+token.length+' tokens. Total: '+this.leftTokens());

		return true;
	},
	nextToken: function() {
		var token = this.tokens.shift();
		console.log('csfr_protector.js | Next token');

		if (this.leftTokens() < this.maximumTokensToGetUpdate && !this.isLoadingTokens)
		{
			this.isLoadingTokens = true;
			var that = this;
			setTimeout(function(){that.getMoreTokens()}, 1);
		}

		if (!token)
			console.error('csfr_protector.js | Tokens stack is empty');
		return token;
	},
	leftTokens: function() {
		return this.tokens.length;
	},
	getMoreTokens: function() {
		this.isLoadingTokens = true;
		var that = this;
		$.get(App.settings.apiEntryPoint+'tokens', {}, function(data){
			if(Object.prototype.toString.call(data) === '[object Array]')
				that.addToken(data);
		}).always(function() {
			that.isLoadingTokens = false;
		});
	}

};