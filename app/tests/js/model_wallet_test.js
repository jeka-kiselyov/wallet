describe("Wallet model", function() {
	describe("without server", function() {

		var wallet;
		beforeEach(function() {
			wallet = new App.Models.Wallet();
		});

		it('should exist', function() {
			expect(wallet).toBeDefined();
		});

		it('should be a Backbone.Model instance', function() {
			expect(wallet instanceof Backbone.Model).toBe(true);
		});

		it("default fields are null", function() {
			expect(wallet.get("name")).toBeNull();
			expect(wallet.get("type")).toBeNull();
			expect(wallet.get("total")).toBeNull();
		});

		it("nonexisting fields are undefined", function() {
			expect(wallet.get("somerandomfieldname123")).toBeUndefined();
		});


	});
	describe("Interacting with server", function() {

		var wallet;
		beforeEach(function() {
			wallet = new App.Models.Wallet();
			jasmine.Ajax.install();
		});
		afterEach(function() {
			jasmine.Ajax.uninstall();
		});

		it("interacting with server", function() {
			wallet.set('name', 'Backbone wallet name');
			wallet.set('type', 'user');
			wallet.save();

			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'wallets/');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 200,
				"contentType": 'application/json',
				"responseText": JSON.stringify({id: 999, user_id: 999, name: 'Backbone wallet name', type: 'default', total: '0'})
			});

			expect(wallet.id).toBe(999);
			expect(wallet.get('name')).toBe('Backbone wallet name');
			expect(wallet.getTotal()).toBe(0);

			wallet.destroy();
			expect(jasmine.Ajax.requests.mostRecent().url).toBe(App.settings.apiEntryPoint+'wallets/999');
			jasmine.Ajax.requests.mostRecent().response({
				"status": 200,
				"contentType": 'application/json',
				"responseText": 'null'
			});
		});

	});
});


