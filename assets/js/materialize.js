//Custom Materialize JS
jQuery(document).ready(function ($) {
	


      $('.parallax').parallax();
    $('.scrollspy').scrollSpy(); 
      

  $(window).bind('scroll', function() {
    var navHeight = $("#site-navigation").height(); // custom nav height
    ($(window).scrollTop() > navHeight ) ? $('.col.hide-on-small-only.m3.l2').addClass('navbar-fixed-top') : $('.col.hide-on-small-only.m3.l2').removeClass('navbar-fixed-top');
  });
  
    $('.button-collapse').sideNav({
      menuWidth: 280, // Default is 240
      edge: 'left', // Choose the horizontal origin
      closeOnClick: true // Closes side-nav on <a> clicks, useful for Angular/Meteor
    }
  );
    
$('ol.tree li').addClass('waves-effect waves-light'); 
});