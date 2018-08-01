/**
 * global variable for ajax request
 */
var requestPage;

/**
 * global variable to load script or js file once
 */
LoadedScripts = [];
LoadedStyle = [];

$(function() {

   
  
  // Bind an event to window.onhashchange that, when the hash changes, gets the
  // hash and adds the class "selected" to any matching nav link.
  $(window).on('hashchange', function(e) {
    callback = (location.hash.replace(/^#/, ''))

    if(callback && !requestPage){
      // create object with required data
      var data = {};
      data.callback = callback;
      data.form_data = {};

      // call the ajax function to show the page content
      page_ajaxify(data);

      // activate the menu
      $(".left-menu .acc-menu li").removeClass('active');
      $(".left-menu .acc-menu li[data-callback='" + callback + "']").addClass('active');
    }

  }).trigger('hashchange');

});

jQuery(document).ready(function($v) {

  /**
   * this will trigger when left menu clicked
   */
  $v(document).on('click', ".hash_ajaxify", function(e) {
    // stop the event or navigation
    e.preventDefault();
    e.stopPropagation();

    // create object with required data
    var data = {};
    data.callback = $v.trim($v(this).attr('data-callback'));
    data.form_data = {};

    window.location.hash = '';
    window.location.hash = data.callback;

    // call the ajax function to show the page content
    // page_ajaxify(data);

    // make the menu active which is just clicked from left Menus
    menu_active($v(this));

  })

  /**
   * this will trigger when left menu clicked
   */
  $v(document).on('click', ".page_ajaxify", function(e) {
    // stop the event or navigation
    e.preventDefault();
    e.stopPropagation();

    // create object with required data
    var data = {};
    data.callback = $v.trim($v(this).attr('data-callback'));
    data.form_data = {};

    // call the ajax function to show the page content
    page_ajaxify(data);

    // make the menu active which is just clicked from left Menus
    menu_active($v(this));

  })
})

/**
 * function to render middle content of the page
 * 
 * @param obj
 */
function page_ajaxify(obj) {
  // show process is going on via loader
  show_waitMe(jQuery('body'));

  // if already ajax request is started then stop this ajax and start new
  if(requestPage){
    requestPage.abort();
  }

  // call ajax request to get the page content
  requestPage = jQuery.ajax({
    url : SITEROOT+obj.callback,
    method : 'POST',
    data : obj.form_data,
    success : function(result) {
      jQuery(".region-content").html(result);
      hide_waitMe();
      requestPage = null;
    },
    error : function(error) {
      // hide_waitMe();
      requestPage = null

    }
  });

  return true;
}


/**
 * function to make menu active which is just clicked
 * 
 * @param this_active
 */
function menu_active(this_active) {
  $(".left-menu .acc-menu li").removeClass('active');
  this_active.addClass('active');

  var menu = this_active.attr('data-callback')
  $(".left-menu .acc-menu li[data-callback='" + menu + "']").addClass('active');

  var attr = this_active.attr('parent-callback');
  if(typeof attr !== typeof undefined && attr !== false){
    var main_menu = this_active.attr('parent-callback')
    $(".left-menu .acc-menu li[data-callback='" + main_menu + "']").addClass('active');
  }
}

/**
 * display loader for body
 * 
 * @param this_active
 */
function show_waitMe(field) {
  /* Provide the container name which should be hide */
  field.waitMe({
    effect : 'win8',
    text : 'Please wait...',
    bg : 'rgba(255,255,255,0.7)',
    color : '#000',
    sizeW : '40px',
    sizeH : '40px'
  });
}

/**
 * hide loader for body
 * 
 * @param this_active
 */
function hide_waitMe() {
  jQuery(".waitMe").hide();
}
