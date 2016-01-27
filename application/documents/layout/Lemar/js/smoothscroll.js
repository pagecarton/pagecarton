$(document).ready(function() {

    // Click event for any anchor tag that's href starts with #
    $('a[href^="#"]').click(function(event) {

        // The id of the section we want to go to.
        var id = $(this).attr("href");

        // An offset to push the content down from the top.
        var offset = 60;

        // Our scroll target : the top position of the
        // section that has the id referenced by our href.
        var target = $(id).offset().top - offset;

        // The magic...smooth scrollin' goodness.
        $('html, body').animate({scrollTop:target}, 1000);

        //prevent the page from jumping down to our section.
        event.preventDefault();
    });
});

jQuery(document).ready(function(){
		var pxShow = 300;//height on which the button will show
		var fadeInTime = 1000;//how slow/fast you want the button to show
		var fadeOutTime = 1000;//how slow/fast you want the button to hide
		var scrollSpeed = 1000;//how slow/fast you want the button to scroll to top. can be a value, 'slow', 'normal' or 'fast'
		jQuery(window).scroll(function(){
			if(jQuery(window).scrollTop() >= pxShow){
				jQuery(".back-top").fadeIn(fadeInTime);
			}else{
				jQuery(".back-top").fadeOut(fadeOutTime);
			}
		});		 
	});