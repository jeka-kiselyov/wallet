// router.js
App.router = new (Backbone.Router.extend({

  setUrl: function(path) {
    this.navigate(path);
  },

  routes: {
    "": "index",// root
    "help": "help",// #help
    "wallets(/)": "wallets",// #wallets
    "wallets/:id": "wallet",// #wallets/4
    "static/view/:id": "static",// #wallets/4
    "news/recent/:page(/)": "newsItems",// #news/recent/3
    "news/recent(/)": "newsItems",// #news/recent
    "news/view/:slug.html": "newsItem",// #news/view/someslug.html
  },

  dialogs: {
    "user/signin": "Signin",
    "user/registration": "Registration"
  },

  index: function() {
    App.showPage('Index');
  },

  static: function(id) {
    App.showPage('Static', {slug: id});
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

  newsItems: function(page) {
    if (typeof(page) === 'undefined') 
      page = 1;
    App.showPage('NewsItems', {page: page});
  },

  newsItem: function(slug) {
    App.showPage('NewsItem', {slug: slug});
  },

  initialize: function() {
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
        href = href.slice(0,1) == '/' ? href.slice(1) : href;

        // Ensure the protocol is not part of URL, meaning its relative.
        if (href.slice(protocol.length) !== protocol && Backbone.history.isRoutingURL(href))
        {
          console.log('Navigating to "'+href+'"');
          evt.preventDefault();
          App.router.navigate(href, {trigger: true});

          return false;
        }

        return true;
      });
    }
  }

}))();