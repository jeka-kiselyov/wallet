// router.js
var router = Backbone.Router.extend({

  routes: {
    "help": "help",// #help
    "wallets": "wallets",// #wallets
    "wallets/:id": "wallet",// #wallets/4
  },

  help: function() {
    console.log('routing help');
  },

  wallet: function(id) {
    console.log('routing wallet');
  },

  wallets: function() {
    
  }

});

App.router = new router();