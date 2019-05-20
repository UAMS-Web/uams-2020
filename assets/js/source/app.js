(function($){
	$(document).ready(function(){
		$('.gallery').find('br').detach();
	});


	$(document).ready(function(){
		searchvisible = false;
		$("#toggle-search").click(function() {
			$("#header-search").toggleClass("closed");
			if (!searchvisible) {
				$("#uams-search-bar").focus();
			}
			searchvisible = !searchvisible;
			$(this).toggleClass("active");
		});

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