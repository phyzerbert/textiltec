(function () {
	"use strict";

	function isMobile() { return ('ontouchstart' in document.documentElement); }

	// console.log(isMobile())

	if(sessionStorage.getItem('sidebar') === null){
		$('.sidebar').removeClass('toggled');
		if(isMobile()){
			$(".main").width("100%")
		}else{
			$(".main").width("calc(100% - 255px)")
		}
	}else{
		$('.sidebar').addClass('toggled');
		if(isMobile()){
			$(".main").width("calc(100% - 255px)")
		}else{
			$(".main").width("100%")
		}
	}
	
	$('.sidebar-toggle').click(function(event) {
		if($(".sidebar").hasClass("toggled")){
			sessionStorage.clear();	
			if(isMobile()){
				$(".main").width("100%")
			}else{
				$(".main").width("calc(100% - 255px)")
			}
		}else{
			sessionStorage.setItem('sidebar', 'expanded');
			if(isMobile()){
				$(".main").width("calc(100% - 255px)")
			}else{
				$(".main").width("100%")
			}	
		}
	});



})();
