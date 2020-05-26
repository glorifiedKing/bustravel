(function($) {

	"use strict";

	var fullHeight = function() {

		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

	$('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active');
  });

	$('#sidebar .list-unstyled li').click(function(){
    $('#sidebar .list-unstyled li.active').removeClass('active');
    $(this).parent().addClass('active');
	});

})(jQuery);
