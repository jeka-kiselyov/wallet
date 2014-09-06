// view_stack.js
App.viewStack = {

	stack: {},
	addView: function(hash, view)
	{
		this.stack[hash] = {view: view, hash: hash, added: new Date()};
		return true;
	},
	getView: function(hash)
	{
		if (typeof(this.stack[hash]) !== 'undefined')
			return this.stack[hash].view;
		else
			return false;
	}
};