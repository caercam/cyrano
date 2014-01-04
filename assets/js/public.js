window.addEventListener('scroll', function() {
		var header = document.getElementById('header');
		var menu   = document.getElementById('nav-menu');
		var limit  = header.offsetTop + header.offsetHeight;
		if ( window.scrollY > limit && ! menu.classList.contains('scroll') )
			menu.classList.add('scroll');
		else if ( window.scrollY < limit && menu.classList.contains('scroll') )
			menu.classList.remove('scroll');
	},
	false
);

(function( $ ) {

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

	paginate.bind('mouseleave', function() {
		$(this).removeClass('active');
		$(this).css({top: 0});
	});

})(jQuery);




/*var list = document.getElementsByClassName('paginate-link');
var paginate = document.getElementById('paginate-links');
var pagenumber
Array.prototype.forEach.call(list, function(li, index, nodeList) {
	if ( li.classList.contains('selected') && 'A' == li.firstChild.nodeName ) {
		li.addEventListener('click', function(event) {
			event.preventDefault();
			if ( paginate.classList.contains('active') ) {
				paginate.classList.remove('active');
				paginate.style.left = ( -28 * index ) + 'px';
			}
			else {
				paginate.classList.add('active');
				paginate.style.left = '0px';
			}
		});
	}
});

paginate.addEventListener('blur', function(event) {
	if ( paginate.classList.contains('active') )
		paginate.classList.remove('active');
});*/