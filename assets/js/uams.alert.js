//     UAMS.js 0.1
//     uw.edu/marketing/web/
//     A UAMS JavaScript library that implements various web components to any site
//     Includes hard dependencies jQuery (v2.1.1), Backbone (1.1.2), and Underscore (1.6.0)

jQuery(document).ready(function($){
// Baseline setup
// --------------

// Establish the root object `window`.
var root = this

// Create a safe reference to the UAMS object which will be used to establish the global UAMS object.
var UAMS = function(obj)
{
    if ( obj instanceof UAMS ) return obj

    if ( ! ( this instanceof UAMS )) return new UAMS(obj)

    this._wrapped = obj
};

// Establish the global UAMS object `window.UAMS`
root.UAMS = UAMS


// Current version
UAMS.VERSION = '0.1'

;// List out the classes that each component searches for
UAMS.elements = {

  alert      : '#header-sticker-wrapper',
/*
  accordion  : '.uams-accordion',
  dropdowns  : '#reddiedrops',
  images     : 'a > img',
  mobilemenu : '#mobile-relative',
  radio      : ':radio',
  checkbox   : ':checkbox',
  search     : '#uamssearcharea',
  select     : '.uams-select',
  quicklinks : '.uams-quicklinks',
  slideshow  : '.uams-slideshow',
  social     : '.uams-social',
  vimeo      : '.uams-vimeo',
  youtube    : '.uams-youtube'
*/

}

UAMS.getBaseUrl = function() {
  var site = _.first( _.compact( Backbone.history.location.pathname.split('/') ) )
  var url = ''

  if (!Backbone.history.location.origin) {
    Backbone.history.location.origin = Backbone.history.location.protocol + "//" + Backbone.history.location.hostname + (Backbone.history.location.port ? ':' + Backbone.history.location.port: '');
  }

  if (Backbone.history.location.origin.indexOf('www.uams.edu') != -1) {
    url = Backbone.history.location.origin + ( site ? '/' + site : '' ) + '/';
  } else if (Backbone.history.location.origin.indexOf('uamsweb.com') != -1) {
    url = Backbone.history.location.origin + ( site ? '/' + site : '' ) + '/';
  } else {
    url = Backbone.history.location.origin + '/';
  }
  return url
}

UAMS.wpinstance = function(){
  return Backbone.history.location.pathname ? Backbone.history.location.pathname : "";
}

UAMS.sources = {
  // Note: style_dir is a variable created by the Wordpress' wp_localize_script in class.uams-scripts.php
/*
  quicklinks : typeof(style_dir) !== 'undefined' ? style_dir + '/wp-admin/admin-ajax.php?action=quicklinks' : UAMS.getBaseUrl() + 'wp-admin/admin-ajax.php?action=quicklinks',
  search     : UAMS.getBaseUrl() + 'wp-admin/admin-ajax.php'
*/
}

// Initialize all components when the DOM is ready
UAMS.initialize = function( $ )
{
  // Cache common elements that each javascript module calls
  UAMS.$body      = $('body');
  UAMS.$window    = $( window );
  UAMS.baseUrl    = UAMS.getBaseUrl()

  // UAMS Utilities
/*
  UAMS.dropdowns  = _.map( $( UAMS.elements.dropdowns ),     function( element ) { return new UAMS.Dropdowns({ el : element }) } )
  UAMS.mobilemenu = _.map( $( UAMS.elements.mobilemenu ),     function( element ) { return new UAMS.MobileMenu({ el : element }) } )
  UAMS.quicklinks = _.map( $( UAMS.elements.quicklinks ),    function( element ) { return new UAMS.QuickLinks( { el : element, url : UAMS.sources.quicklinks }) } )
  UAMS.search     = _.map( $( UAMS.elements.search ),    function( element ) { return new UAMS.Search( { el : element } ) } )
  UAMS.images     = _.map( $( UAMS.elements.images ),    function( element ) { return new UAMS.Image({ el : element }) } )

  // UAMS Modules
  UAMS.slideshows = _.map( $( UAMS.elements.slideshow ), function( element ) { return new UAMS.Slideshow( { el : element }) } )
  UAMS.social     = _.map( $( UAMS.elements.social ),    function( element ) { return new UAMS.Social({ el : element }) } )
  UAMS.vimeo      = _.map( $( UAMS.elements.vimeo ),     function( element ) { return new UAMS.Vimeo({ el : element }) } )
  UAMS.youtube    = _.map( $( UAMS.elements.youtube ),   function( element ) { return new UAMS.YouTube.Collection({ el: element})})


  // UAMS Components - These need to render after all other javascript elements are rendered on page
  UAMS.accordion  = _.map( $( UAMS.elements.accordion ), function( element ) { return new UAMS.Accordion( { el : element }) } )
  UAMS.radio      = _.map( $( UAMS.elements.radio ),     function( element ) { return new UAMS.Radio({ el : element }) } )
  UAMS.checkbox   = _.map( $( UAMS.elements.checkbox ),     function( element ) { return new UAMS.Radio({ el : element }) } )
  UAMS.select     = _.map( $( UAMS.elements.select ),    function( element ) { return new UAMS.Select({ el : element }) } )
*/

  UAMS.alert = new UAMS.Alert({ after: UAMS.elements.alert, model: new UAMS.Alert.Model() });

  // todo: add to separate file
  $('table').addClass('table table-striped')

  $('pre').addClass('prettyprint')

  if ( window.prettyPrint ) prettyPrint()

}

jQuery(document).ready(function(){
  // switching to anonymous function so UAMS.initialize can be extended before running
  UAMS.initialize(jQuery);
})


// Basic UAMS Components
// --------------
;UAMS.Alert = Backbone.View.extend({

  alert : '#uamsalert-alert-message',

  events : {
    'click .close' : 'hide'
  },

  template : '<div id="uamsalert-alert-message" class="<% _.each( categories, function( category ) { %> <%= category.slug %> <% }) %>"><div class="container"><span class="close">Close</span><h1><%= title %></h1><p><%= excerpt %><br/><a class="more" href="http://uamshealth.com/weather" title="<%= title %>">More info</a></p></div></div>',

  initialize  : function( options )
  {
    _.bindAll( this, 'render', 'hide' )
    this.options = _.extend( {}, options )
    this.model.on( 'sync', this.render )
  },

  render : function()
  {
    if ( this.model.get('title'))
     {
      $(this.options.after).after( _.template( this.template )( this.model.toJSON() ) )
      this.setElement( $( this.alert ) )
    }
  },

  hide : function()
  {
    this.$el.remove()
  }

})

UAMS.Alert.Model = Backbone.Model.extend({

  alerts :  [
    'urgent-inside',
    'alert-inside',
    'fyi-inside',
  ],

  data  : {
    c : '?',
    test : true,
    number:1,
    type:'post',
    status:'publish',
    dataType: 'json'
  },

  url :  'https://public-api.wordpress.com/rest/v1/sites/uamsalert.wordpress.com/posts/',

  initialize : function()
  {
    this.fetch( { data : this.data })
  },

  parse: function(data)
  {
    var post = _.first( data.posts )
    _.extend( post.categories, {alert: { slug : window.location.hash.replace('#','') } } )
    if ( _.intersection( _.pluck(  post.categories, 'slug' ), this.alerts ).length || post.categories.alert.slug.indexOf( 'uamsalert' ) !== -1 )
      return post
  }

});
});