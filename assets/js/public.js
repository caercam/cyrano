window.addEventListener('scroll', function() {
		var header = document.getElementById('header');
		var menu   = document.getElementById('menu');
		var limit  = header.offsetTop + header.offsetHeight;
		if ( window.scrollY > limit && ! menu.classList.contains('scroll') )
			menu.classList.add('scroll');
		else if ( window.scrollY < limit && menu.classList.contains('scroll') )
			menu.classList.remove('scroll');
	},
	false
);