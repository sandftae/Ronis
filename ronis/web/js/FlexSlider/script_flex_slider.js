$(document).ready(function() {
	$('.flexslider').flexslider({
		
		//namespace:"my_str-"
		//selector:".slides > p"
		animation:"fade",
		//easing:"easeOutElastic",
		//reverse:true,
		animationLoop:true,
		startAt:5,
		slideshow:true,
		slideshowSpeed:2000,
		animationSpeed:1000,
		//initDelay:3000,
		randomize:true,
		//pauseOnHover:true,
		pauseOnAction:true,
		//controlNav:'thumbnails',
		directionNav:true,
		prevText:"Previous",
		nextText:"Next",
		keyboard:false,
		//pausePlay:true,
		//playText:"Play",
		//pausetext:"Pause"
		
	});
});