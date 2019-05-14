(function($){
	$(document).ready(function(){
		$('.gallery').find('br').detach();
	});


	$(document).ready(function($) { 
	// Show/hide Search Box
		
		$(".toggle-search").click(function() {
				$(".search-wrap").slideToggle('fast', function(){
					$(".toggle-search").toggleClass('active');
					$("#toggle-search .google-search input[type=text]").focus();
				});
				return false;
			
		});

		$('#sidebarCollapse').on('click', function () {
			$('#sidebar').toggleClass('active');
			$('.site-container').toggleClass('active');
			$('.collapse.in').toggleClass('in');
			$('#sidebarCollapse[aria-expanded=true]').attr('aria-expanded', 'false');
		});

	});

})(jQuery);