(function () {
	"use strict";

	if(sessionStorage.getItem('sidebar') === null){
		$('.sidebar').removeClass('toggled');
	}else{
		$('.sidebar').addClass('toggled');
	}
	
	$('.sidebar-toggle').click(function(event) {
		if($(".sidebar").hasClass("toggled")){
			sessionStorage.clear();			
		}else{
            sessionStorage.setItem('sidebar', 'expanded');
		}
		// $(".main").width($("body").width() - $(".sidebar").width())
	});

})();
