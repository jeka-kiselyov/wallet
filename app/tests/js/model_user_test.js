describe("User model", function() {
	describe("User model default values", function() {

		beforeEach(function() {
			this.user = new App.Models.User();
		});

		it("default fields are null", function() {
			expect(this.user.get("email")).toBeNull();
			expect(this.user.get("login")).toBeNull();
			expect(this.user.get("auth_code")).toBeNull();
			expect(this.user.get("password")).toBeNull();
		});

		it("nonexisting fields are undefined", function() {
			expect(this.user.get("somerandomfieldname123")).toBeUndefined();
		});

		it(".signedIn is false and .signInError is null", function() {
			expect(this.user.signedIn).toBe(false);
			expect(this.user.signInError).toBeNull();
		});

		it("isSignedIn() returns false", function() {
			expect(this.user.isSignedIn()).toBe(false);
		});

		it("isAdmin() returns false", function() {
			expect(this.user.isAdmin()).toBe(false);
		});

	});

	describe("Logging in", function() {
		beforeEach(function() {
			jasmine.Ajax.install();
			this.user = new App.Models.User();
		});

		afterEach(function() {
			jasmine.Ajax.uninstall();
		});

		it("should throw an error when signing in without parameters", function() {
			expect(this.user.signIn).toThrow();
		});

		it("should sign in with login", function() {
			var login = 'someusername';
			var password = 'somepassword';
			this.user.set('login', login);
			this.user.set('password', password);

			expect(this.user.get('login')).toBe(login);
			expect(this.user.get('password')).toBe(password);

			spyOn(this.user, 'signInWithData').and.callThrough();

			this.user.signIn();
			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'users/signin');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 200,
				"contentType": 'application/json',
				"responseText": '{"id":"999","login":"someusername","auth_code":"somecode"}'
			});

			/// Should be logged in now
			expect(this.user.isSignedIn()).toBe(true);
			expect(this.user.signInWithData).toHaveBeenCalled();
		});

		it("should sign in with email too", function() {
			var email = 'someusername@example.com';
			var password = 'somepassword';
			this.user.set('email', email);
			this.user.set('password', password);

			expect(this.user.get('email')).toBe(email);
			expect(this.user.get('password')).toBe(password);

			spyOn(this.user, 'signInWithData').and.callThrough();

			this.user.signIn();
			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'users/signin');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 200,
				"contentType": 'application/json',
				"responseText": '{"id":"999","login":"someusername","auth_code":"somecode"}'
			});

			/// Should be logged in now
			expect(this.user.isSignedIn()).toBe(true);
			expect(this.user.signInWithData).toHaveBeenCalled();
		});

		it("should not signin with wrong credentials", function() {
			var email = 'someusername@example.com';
			var password = 'somepassword';
			this.user.set('email', email);
			this.user.set('password', password);

			spyOn(this.user, 'signInWithData').and.callThrough();
			
			this.user.signIn();
			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'users/signin');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 400,
				"contentType": 'application/json',
				"responseText": '{"code":"011","message":"Invalid credentials"}'
			});

			/// Should NOT be logged in now
			expect(this.user.isSignedIn()).toBe(false);
			expect(this.user.signInError).toBe("Invalid credentials");
			expect(this.user.signInWithData).toHaveBeenCalled();
		});
	});

	describe("Logging out", function() {

		beforeEach(function() {
			jasmine.Ajax.install();
			this.user = new App.Models.User();
		});

		afterEach(function() {
			jasmine.Ajax.uninstall();
		});

		it("should sign out ok", function() {
			var email = 'someusername@example.com';
			var password = 'somepassword';
			this.user.set('email', email);
			this.user.set('password', password);

			this.user.signIn();
			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'users/signin');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 200,
				"contentType": 'application/json',
				"responseText": '{"id":"999","login":"someusername","auth_code":"somecode"}'
			});

			expect(this.user.isSignedIn()).toBe(true);

			this.user.signOut();
			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'users/signout');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 200,
				"contentType": 'application/json',
				"responseText": 'null'
			});

			expect(this.user.isSignedIn()).toBe(false);
		});

	});
});