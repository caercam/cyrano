jQuery(window).load(function() {

	$ = jQuery;

	menu     = $('#nav-menu');
	header   = $('#header');
	list     = $('.paginate-link');
	paginate = $('#paginate-links');
	res_menu = $('#nav-menu-res');

	var responsive_menu = function() {
		if ( window.innerWidth <= 480 && ! menu.hasClass('responsive') )
			menu.addClass('responsive');
		else if ( window.innerWidth > 480 && menu.hasClass('responsive') )
			menu.removeClass('responsive');
	};

	var scrolled_menu = function() {

		var limit  = header.offset().top + header.height();
		var _class = 'scroll';

		if ( $('#wpadminbar').length )
			var _class = 'scroll scroll28';

		if ( window.scrollY > limit  ) {
			if ( ! menu.hasClass(_class) )
				menu.addClass(_class);
			menu.width(header.outerWidth() + 128);
		}
		else if ( window.scrollY < limit ) {
			if ( menu.hasClass(_class) )
				menu.removeClass(_class);
			menu.width(header.outerWidth());
		}
	};

	responsive_menu();
	scrolled_menu();

	$(window).resize(function() {
		responsive_menu();
		scrolled_menu();
	});

	$(window).scroll(function() {
		scrolled_menu();
	});

	$('.menu-nav a').click(function(event) {
		event.preventDefault();
		$('body, html').animate({scrollTop: 0}, 500);
	});

	res_menu.change(function() {
		document.location.href=this.options[ this.selectedIndex ].value;
	});

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

	paginate.bind('mouseleave', function() {
		$(this).removeClass('active');
		$(this).css({top: 0});
	});

});