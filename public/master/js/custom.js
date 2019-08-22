(function () {
	"use strict";

	if(sessionStorage.getItem('sidebar') === null){
		$('.sidebar').removeClass('toggled');
		$(".main").width("calc(100% - 255px)")
	}else{
		$('.sidebar').addClass('toggled');
		$(".main").width("100%")
	}
	
	$('.sidebar-toggle').click(function(event) {
		if($(".sidebar").hasClass("toggled")){
			sessionStorage.clear();	
			$(".main").width("calc(100% - 255px)")
		}else{
			sessionStorage.setItem('sidebar', 'expanded');
			$(".main").width("100%")
		}
	});

})();
