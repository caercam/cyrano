(function( $ ) {

	$(window).scroll(function() {
		var header = $('#header');
		var menu   = $('#nav-menu');
		var limit  = header.offset().top + header.height();
		var _class = 'scroll';

		if ( $('#wpadminbar').length )
			var _class = 'scroll scroll28';

		if ( window.scrollY > limit && ! menu.hasClass(_class) ) {
			menu.addClass(_class);
			menu.width(header.outerWidth() + 128);
		}
		else if ( window.scrollY < limit && menu.hasClass(_class) ) {
			menu.removeClass(_class);
			menu.width(header.outerWidth());
		}

		
	});

	var list = $('.paginate-link');
	var paginate = $('#paginate-links');

	$('.paginate-link.selected').click(function(event) {
		event.preventDefault();
		var index = list.index( this );
		if ( paginate.hasClass('active') ) {
			paginate.removeClass('active');
			paginate.css({top: 0});
		}
		else {
			paginate.addClass('active');
			paginate.css({top: ( -28 * index )});
		}
	});

	$('.menu-nav a').click(function(event) {
		event.preventDefault();
		$('body, html').animate({scrollTop: 0}, 500);
	});

	paginate.bind('mouseleave', function() {
		$(this).removeClass('active');
		$(this).css({top: 0});
	});

})(jQuery);