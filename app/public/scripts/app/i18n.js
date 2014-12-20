// i18n.js
App.i18n = {

	strings: {},
	loaded: false,
	setLanguage: function(languageCode) {
		this.languageCode = languageCode;
		this.loadStrings();
	},
	getLanguage: function(languageCode) {
		return this.languageCode;
	},
	translate: function(string) {
		if (typeof(this.strings[string]) === 'undefined' || this.strings[string] === false)
			return string;
		else
			return this.strings[string];
	},
	loadStrings: function() {

		var that = this;
		var process = function(data) {
			that.strings = data;
			that.loaded = true;
		}

		this.loaded = false;
		$.ajax({
			url: App.settings.apiEntryPoint + 'i18n/bycode/'+this.languageCode,
			data: {},
			success: process,
			dataType: 'json',
			mimeType: 'application/json',
			cache: true
		});
	}


};