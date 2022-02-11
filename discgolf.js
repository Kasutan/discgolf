jQuery(function ($) {
	$(document).ready(function () {
		if($(window).width() < 960) {
			console.log('mobile');
			var topbar = $('.topbar').outerHeight();
			$('.site').css('margin-top',topbar);
			$('.mobile-sidebar').css('top',topbar);
		}
	});
});