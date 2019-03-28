$(document).ready(function(){
	  var height_d = [$('.top').offset().top, $('.img01').offset().top, $('.lx').offset().top, $('.qa').offset().top];
	  var index = 0;
	  var height_m = parseInt($($('.nav').get(0)).css('height'));
	  function scroll_monitor() {
		var h = $(document).scrollTop() + height_m;
		var i = 0;
		if (h < height_d[1]) {
		  i = 0;
		} else if (h < height_d[2]) {
		  i = 1;
		} 
		else if(h < height_d[3]){
		  i=2;
		}
		else {
		  i = 3;
		}

		if (i != index) {
		  index = i;
		  $('.nav ul li').removeClass('current');
		  $($('.nav ul li').get(i)).addClass('current');
		}
	  }
		$(document).scroll(function() {
		  scroll_monitor();
		});
		scroll_monitor();
		$('.nav-scroll').click(function() {
		  var height_s = $($(this).attr('href')).offset().top - height_m;
		  var css = (height_s < 0) ? '0px' : height_s + 'px';
		  $("html, body").animate({scrollTop: css});
		  return false;
		});
})