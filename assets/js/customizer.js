jQuery( document ).ready( function( $ ) {
	wp.customize('cyrano_logo', function(value) {
		value.bind(function(to) {
			$('.site-logo img').attr('src', to);
		});
	});
});