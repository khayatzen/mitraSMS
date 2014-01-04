jQuery(document).ready(function() {
    jQuery('head').append('<link href="http://smsburuhmigran.infest.or.id/extensions/microblog/microblog.css" rel="stylesheet" type="text/css"/>');
    microbloging();
});

var microblog_sms_server_URL = 'http://smsburuhmigran.infest.or.id/index.php/microblog/microbloging/callback=?';

function microbloging(){
jQuery.ajax({
      url: microblog_sms_server_URL,
      type: "GET",
      dataType: 'jsonp',  //Required
      success: function(data){
		  jQuery("#tabs").html(data.content);
		  
                    jQuery('#tabs .divtab').hide(); // Hide all divs
                    jQuery('#tabs .divtab:first').show(); // Show the first div
                    jQuery('#tabs ul li:first').addClass('active'); // Set the class of the first link to active
                    jQuery('#tabs ul li a').click(function(){ //When any link is clicked
                    jQuery('#tabs ul li').removeClass('active'); // Remove active class from all links
                    jQuery(this).parent().addClass('active'); //Set clicked link class to active
                    var currentTab = jQuery(this).attr('href'); // Set variable currentTab to value of href attribute of clicked link
                    jQuery('#tabs .divtab:visible').fadeOut('slow',function(){ //fade out visible div
                        jQuery(currentTab).fadeIn('slow') //fade in target div
                    });
                    return false;

                    });
               }
      });

}