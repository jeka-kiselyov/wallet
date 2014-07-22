// user.js
App.Models.User = Backbone.Model.extend({

	defaults: {
        auth_code: null,
        email: null,
        password: null,
        login: null,
    },
    url: function() {
		return App.settings.apiEntryPoint + 'users' + (typeof(this.id) === 'undefined' ? '' : '/'+this.id);
    },

    signedIn: false,
    signInError: null,

	isSignedIn: function() {
		return this.signedIn;
	},

	isAdmin: function() {
		return false;
	},

    signInWithData: function(data) {
		var tmpSignedIn = this.signedIn;
		if (typeof(data) !== 'undefined' && typeof(data.auth_code) !== 'undefined' && data.auth_code)
			this.signedIn = true;
		else
			this.signedIn = false;

		if (typeof(data) !== 'undefined')
			this.set(data);

		if (tmpSignedIn != this.signedIn)
			this.trigger('signedInStatusChanged');
    },
	signIn: function(callback) {
		var that = this;
		var username = '';
		var url = App.settings.apiEntryPoint+'users/signin';

		if (this.isSignedIn())
		{
			if (typeof(callback) === 'function')
				callback(that);
			return false;
		}

		if ((this.get('email') === null && this.get('login') === null) || this.get('password') === null) {
			throw ("Can't sign in without parameters");
		}

		if (this.get('login'))
			username = this.get('login');
		else if (this.get('email'))
			username = this.get('username');

		$.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            data: {username: username, password: this.get('password')},
            success: function (data) {
				if (typeof(data.auth_code) != 'undefined' && data.auth_code)
				{
					console.log('Logged in successfully');
					that.signInWithData(data);
					if (typeof(callback) === 'function')
						callback(that);
				}
            },
            error: function(data) {
				console.log('Cannot log in');
				that.signInWithData();
				if (typeof(data.responseJSON) != 'undefined' && typeof(data.responseJSON.code) != 'undefined' && typeof(data.responseJSON.message) != 'undefined')
					that.signInError = data.responseJSON.message;
				if (typeof(callback) === 'function')
					callback(that);
            }
        });

        return true;
	},
	signOut: function(callback) {
		var that = this;
		var url = App.settings.apiEntryPoint+'users/signout';

		if (!this.isSignedIn())
		{
			if (typeof(callback) === 'function')
				callback(that);
			return false;
		}

		$.ajax({
            url: url,
            type: 'POST',
            dataType: "json",
            success: function (data) {
				console.log('Signed out');
				that.signInWithData();
				if (typeof(callback) === 'function')
					callback(that);
            },
            error: function(data) {
				console.error('Error signing out');
				if (typeof(callback) === 'function')
					callback(that);
            }
        });

        return true;
	}

});
