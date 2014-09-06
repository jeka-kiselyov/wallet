// view_stack.js
App.viewStack = {

	stack: {},
	addView: function(name, params, view)
	{
		var hash = name;
		if (typeof(params) !== 'undefined')
			for (var k in params)
			{
				if (typeof(params[k].id) !== 'undefined')
					hash += '-'+k+'_id'+params[k].id;
				else
					hash += '-'+k+'_'+params[k];
			}

		this.stack[hash] = {view: view, hash: hash, added: new Date()};
		return true;
	},
	getView: function(name, params)
	{
		var hash = name;
		if (typeof(params) !== 'undefined')
			for (var k in params)
			{
				if (typeof(params[k].id) !== 'undefined')
					hash += '-'+k+'_id'+params[k].id;
				else
					hash += '-'+k+'_'+params[k];
			}
			
		if (typeof(this.stack[hash]) !== 'undefined')
			return this.stack[hash].view;
		else
			return false;
	}
};