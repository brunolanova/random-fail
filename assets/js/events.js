(function(){

	/* Shows floater header menu if scroll top > 70 */
	$(window).scroll(function(){
		if ($(this).scrollTop() > 70) {
			$('.main-menu header').addClass('active');
		} else {
			$('.main-menu header').removeClass('active');
		}
	});

	/* Keybord keyups events */
	$(document).keyup(function(event){
		
		/* Close full-menu if its opened / active */
		if (event.keyCode === 27) {
			
			$('body').css('overflow', 'initial')
			.find('.active').removeClass('active');	
		};
	});

})();