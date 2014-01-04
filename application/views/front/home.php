<?php

/*
 * Halaman utama website
 */
?>
<!-- Load Header template -->
<?php $this->load->view('front/includes/header');?>
<!-- Load Google Map API -->

<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
    
  var fusiontable_polygondesa;
  var fusiontable_polygonmoratorium;  
  var map;
  function map_initialize() {
    var latlng = new google.maps.LatLng(-1.406109,113.57666);//  center Palangka
    var myOptions = {
      zoom: 7,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    map = new google.maps.Map(document.getElementById("mainMapFrontpage"),myOptions);
    fusiontable_polygondesa = new google.maps.FusionTablesLayer({
      query: {
        select: 'geometry',
        from: '2135757'
      }
    });
    
    fusiontable_polygonmoratorium = new google.maps.FusionTablesLayer({
      query: {
        select: 'geometry',
        from: '2139312'
      }
    });    
    fusiontable_polygondesa.setMap(map);
    fusiontable_polygonmoratorium.setMap(map);

  }
  window.onload = function(){
      //map_initialize();      
  }
</script>

<script type="text/javascript">
// Yang pertama berjalan saat DOM ready/halaman sudah selesai termuat.    
$(document).ready(function(){
    // mCustomScrollbars dipanggil sasat pertama window load    
    //mCustomScrollbar();
    $("#moratorium_legend").change(function(){
        if($(this).is(':checked'))fusiontable_polygonmoratorium.setMap(map);
        else fusiontable_polygonmoratorium.setMap(null);
    });
    $("#village_legend").change(function(){
        if($(this).is(':checked'))fusiontable_polygondesa.setMap(map);
        else fusiontable_polygondesa.setMap(null);
    });
});
// functions
function mCustomScrollbar(){
	/* 
	malihu custom scrollbar function parameters: 
	1) scroll type (values: "vertical" or "horizontal")
	2) scroll easing amount (0 for no easing) 
	3) scroll easing type 
	4) extra bottom scrolling space for vertical scroll type only (minimum value: 1)
	5) scrollbar height/width adjustment (values: "auto" or "fixed")
	6) mouse-wheel support (values: "yes" or "no")
	7) scrolling via buttons support (values: "yes" or "no")
	8) buttons scrolling speed (values: 1-20, 1 being the slowest)
	*/
	$("#broadcast_message_container").mCustomScrollbar("vertical",420,"easeOutCirc",1.25,"fixed","yes","no",0); 		
        //$("#custom_message_container").mCustomScrollbar("vertical",420,"easeOutCirc",1.25,"fixed","yes","no",0); 		
        $("#microblog_conversation_container").mCustomScrollbar("vertical",420,"easeOutCirc",1.25,"fixed","yes","no",0);
        $("#news_container").mCustomScrollbar("vertical",420,"easeOutCirc",1.25,"fixed","yes","no",0);
}

/* function to fix the -10000 pixel limit of jquery.animate */
$.fx.prototype.cur = function(){
    if ( this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null) ) {
      return this.elem[ this.prop ];
    }
    var r = parseFloat( jQuery.css( this.elem, this.prop ) );
    return typeof r == 'undefined' ? 0 : r;
}

</script>
<!-- Map Container -->
<!--
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div id="mainMapFrontpageBG"></div>
        <div id="mainMapLegend">
            <h3>Legenda Peta</h3>
            <input type="checkbox" id="village_legend" name="village_legend" checked="checked"/>
            <label class="village_legend" for="village_legend">Wilayah Pedesaan</label><br/>
            <input type="checkbox" id="moratorium_legend" name="moratorium_legend" checked="checked"/>
            <label class="moratorium_legend" for="moratorium_legend">Wilayah Moratorium</label>
        </div>
        <div id="mainMapFrontpage"></div>
    </div>
</div>
-->
<!-- Petunjuk SMS -->
<?php $this->load->view('front/help_content');?>
<?php $this->load->view('front/widget/microblog');?>
<link type="text/css" rel="stylesheet" href="<?php echo $this->config->item('css_path');?>front/blogpost.css" />

<div class="row">
    <div class="col-xs-12 col-md-4">
        <div class="widget_title"><p><a href="<?=site_url('microblog/microblogging')?>#broadcast-stream"><span class="glyphicon glyphicon-bullhorn"></span> Tahukah Anda?</a></p></div>
        <div id="broadcast_message_container" class="message-container">
            <div class="loading-message">Memuat...</div> 
            <div id="broadcast_message_stream"></div>                        
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="widget_title"><p><a href="<?=site_url('microblog/microblogging')?>#conversation-stream"><span class="glyphicon glyphicon-envelope"></span> Pendapat Warga</a></p></div>
        <div id="incoming_message_container" class="message-container">
            <div class="loading-message">Memuat...</div>
            <div id="incoming_message_stream"></div>                        
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="widget_title"><p><a href="http://berita.borneoclimate.info"><span class="glyphicon glyphicon-file"></span> Kabar Kampung</a></p></div>
        <div class="news_container">
            <div id="loading"></div>
            <?php $this->load->view('front/widget/newsfeed');?>
        </div>
    </div>
</div>

<!-- Load Footer template -->
<?php $this->load->view('front/includes/footer');?>


