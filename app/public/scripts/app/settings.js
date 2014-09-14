// settings.js
App.settings = {

	sitePath: window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: ''),
	apiEntryPoint: this.site_path+'/api/',
	templatePath: this.site_path+'/jstemplates/',
	version: (typeof(app_version) !== 'undefined') ? app_version : '',
	title: function(title) {
		return title+' | '+'Wallet';
	},
	disqusShortname: 'wasabiventuresacademy',
	disqusLanguage: 'ru',

	enableTemplatesCache: false,
	enablePagesStack: true,
	pagesStackMaxLength: 25,

	history: {
		pushState: true,
		startSilent: true
	}

};