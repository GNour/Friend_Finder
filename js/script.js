"use strict";

//Preloader
var preloader = $("#spinner-wrapper");
$(window).on("load", function () {
  var preloaderFadeOutTime = 500;

  function hidePreloader() {
    preloader.fadeOut(preloaderFadeOutTime);
  }
  hidePreloader();
});

jQuery(document).ready(function ($) {
  //Initiate Scroll Styling
  if ($.isFunction($.fn.scrollbar)) $(".scrollbar-wrapper").scrollbar();

  //Fire Scroll and Resize Event
  $(window).trigger("scroll");
  $(window).trigger("resize");
});

/**
 * function for attaching sticky feature
 **/

function attachSticky() {
  // Sticky Right Sidebar
  $("#sticky-sidebar").stick_in_parent({
    parent: "#page-contents",
    offset_top: 70,
  });
}

// Disable Sticky Feature in Mobile
$(window).on("resize", function () {
  if ($.isFunction($.fn.stick_in_parent)) {
    // Check if Screen wWdth is Less Than or Equal to 992px, Disable Sticky Feature
    if ($(this).width() <= 992) {
      $("#chat-block").trigger("sticky_kit:detach");
      $("#sticky-sidebar").trigger("sticky_kit:detach");

      return;
    } else {
      // Enabling Sticky Feature for Width Greater than 992px
      attachSticky();
    }

    // Firing Sticky Recalculate on Screen Resize
    return function (e) {
      return $(document.body).trigger("sticky_kit:recalc");
    };
  }
});
