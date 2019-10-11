(function($){
	$(document).ready(function(){
		$('.gallery').find('br').detach();
	});


	$(document).ready(function($) { 

		$('#sidebarCollapse').on('click', function () {
			$('#sidebar').toggleClass('active');
			$('.site-container').toggleClass('active');
			$('#sidebarCollapse .fa-bars').toggleClass('active');
			$('#sidebarCollapse .fa-times').toggleClass('active');
			$('.collapse.in').toggleClass('in');
			$('#sidebarCollapse[aria-expanded=true]').attr('aria-expanded', 'false');
		});

	});

})(jQuery);