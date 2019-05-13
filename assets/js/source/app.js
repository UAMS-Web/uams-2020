(function($){
	$(document).ready(function(){
		$('.gallery').find('br').detach();
	});
})(jQuery);

jQuery(document).ready(function($) { 
	// Show/hide Search Box
		
	jQuery(".toggle-search").click(function() {
		$(".search-wrap").slideToggle('fast', function(){
			$(".toggle-search").toggleClass('active');
			$("#toggle-search .google-search input[type=text]").focus();
		});
		return false;	
	});

});