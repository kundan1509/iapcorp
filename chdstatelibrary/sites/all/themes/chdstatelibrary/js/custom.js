jQuery(document).ready(function(){
	jQuery('input[name="name"]').attr('readonly', 'true');
	jQuery('input[name="name"]').attr('onfocus', "this.removeAttribute('readonly');");
	jQuery('input[name="pass"]').attr('readonly', 'true');
	jQuery('input[name="pass"]').attr('onfocus', "this.removeAttribute('readonly');");


  jQuery( ".block-styleswitcher" ).wrap( "<div class='switcher'> <span class='switcher-span'>color-change</span></div>" );
jQuery( "<p>Colour change options</p>" ).insertBefore( ".block-styleswitcher > ul" );
jQuery(".switcher-span").prepend("<div><i class='fa fa-cog fa-spin'></i></div>");

jQuery( ".switcher span").click(function() {
  jQuery(".switcher").toggleClass( "slide");

});

jQuery( "span.glyphicon-search" ).text( "Search" );
jQuery('input').attr('autocomplete', 'off');
jQuery('span.input-group-btn').attr('title', 'Search');
jQuery( "#search-block-form #edit-actions" ).remove();

jQuery('#edit-submitted-your-name').attr('title', 'Enter Your Name');
jQuery('#edit-submitted-your-email-id').attr('title', 'Enter Your Email');
jQuery('#edit-submitted-phone').attr('title', 'Enter Your Phone');
jQuery('#edit-submitted-feedback').attr('title', 'Enter Your Feedback Message');
jQuery('#edit-captcha-response').attr('title', 'Enter Correct Captcha');


 jQuery(window).scroll(function () {
            if (jQuery(this).scrollTop() >300) {
                jQuery('#back-to-top').fadeIn();
            } else {
                jQuery('#back-to-top').fadeOut();
            }
        });


		
		
	// validation for mobile number

 jQuery("#edit-submitted-your-phone").on('keyup keydown', function () {
	 
					if (isNaN((jQuery(this).val()))) {
          	jQuery(this).val(jQuery(this).val().substring(0, (jQuery(this).val().length - 1)));
          }
          if (jQuery(this).val().length > 10 && jQuery(this).val().indexOf('.') == -1) {
          	jQuery(this).val(jQuery(this).val().substring(0, 10));            
          }
          if (jQuery(this).val().indexOf('.') !== -1 && jQuery(this).val().length > 9) {
          jQuery(this).val(jQuery(this).val().substring(0, 9));
          }
          if ((jQuery(this).val().indexOf('.') !== -1)) {
          	var decimal = jQuery(this).val().split('.');
            if (decimal[1].length > 2) { 
            	jQuery(this).val(jQuery(this).val().substring(0, (jQuery(this).val().length - 1)));
            }
          }
        });	
		
		
		
		
		
jQuery('#back-to-top').click(function(){
      jQuery('#back-to-top').tooltip('hide');
        jQuery('html,body').animate({scrollTop:0},'800');
        return false;
    });


// this code for use externel link alert message
jQuery('a[target="_blank"]').click(function( event ) {
event.preventDefault(); 
//var url = window.location.hostname;
var url = location.protocol + "//" + location.host;
if(this.href.indexOf(url)<0){
//They have clicked an external domain
var language_selected = jQuery('html').attr('lang');
var msg = '';
if(language_selected=='hi'){
	msg = 'यह लिंक आपको एक बाहरी वेबसाइट पर ले जाएगा |';
}else if(language_selected=='pa'){
	msg = 'ਇਹ ਲਿੰਕ ਤੁਹਾਨੂੰ ਇੱਕ ਬਾਹਰੀ ਵੈਬਸਾਈਟ ਤੇ ਲੈ ਜਾਵੇਗਾ |';
}else{
	msg = 'This link will take you to an external website.';
}
var yesno = confirm(msg);
if (yesno) window.open(jQuery(this).attr('href'));
 }   
 else{

window.open(jQuery(this).attr('href'));
}
    
});

 });

jQuery('#back-to-top').tooltip('hide');
