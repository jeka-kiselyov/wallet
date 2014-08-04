// router.js
App.router = new (Backbone.Router.extend({

  routes: {
    "": "index",// root
    "help": "help",// #help
    "wallets": "wallets",// #wallets
    "wallets/:id": "wallet",// #wallets/4
  },

  index: function() {
    App.showPage('Index');
  },

  help: function() {
    console.log('routing help');
  },

  wallet: function(id) {
    console.log('routing wallet');
  },

  wallets: function() {
    App.showPage('Wallets');
  },

  init: function() {
    Backbone.history.start({pushState: true});
    Backbone.history.isRoutingURL = function(fragment) {
      for (var k in this.handlers)
        if (this.handlers[k].route.test(fragment))
          return true;
      return false;
    };

    if (Backbone.history && Backbone.history._hasPushState) {
      $(document).on("click", "a", function(evt){
        if (typeof(evt.ctrlKey) !== 'undefined' && evt.ctrlKey)
          return true;
        var href = $(this).attr("href");
        var protocol = this.protocol + "//";
        href = href.split(App.settings.sitePath).join('');
        href = href.slice(-1) == '/' ? href.slice(0, -1) : href;
        console.log(href);
        // Ensure the protocol is not part of URL, meaning its relative.
        if (href.slice(protocol.length) !== protocol && Backbone.history.isRoutingURL(href))
        {
        console.log(href);
          evt.preventDefault();
          App.router.navigate(href, {trigger: true});

          return false;
        }

        console.log('should go');
        return true;
      });
    }
  }

}))();