<style>
    #header{margin:0 auto;}
    #wrap{width:100%;margin:0 auto;}
    #main{margin:0 auto;}
    #footer{margin:0 auto;}
</style>

<script type="text/javascript" language="javascript">
var stream_map;
var village_markers = [];
var display_village_markers = false;
var village_latlng = [];
var infoWindow = [];
var kml_polygon_desa;
var fusiontable_polygondesa;
var fusiontable_polygonmoratorium;
var infoboxOptions = {
        disableAutoPan: false,
        maxWidth: "500px",
        pixelOffset: new google.maps.Size(2, -25),
        zIndex: 10000,
        boxStyle: { 
                opacity: 0.9,
                width: "400px"
        },
        closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
        infoBoxClearance: new google.maps.Size(1, 1),
        isHidden: false,
        pane: "floatPane",
        enableEventPropagation: false
    };
var infobox = new InfoBox(infoboxOptions);

$(document).ready(function(){
    initialize_stream_map();  
    // Kontrol map collapse dengan menyesuaikan posisi peta dan navigasi
    $("#show_hide_panel").toggle(
    function(){        
       $("#panelContent").hide();
       $("#mapPanel").css('width','10px');
       $("#mainMap").css('width','99%');
       $("#show_hide_panel span").removeClass('show_panel'); 
       $("#show_hide_panel span").addClass('hide_panel');
       $("#show_hide_panel").attr('title','Tampilkan panel navigasi');
       //initialize_stream_map();
       google.maps.event.trigger(stream_map, "resize");
    },function(){
       $("#panelContent").show(); 
       $("#mapPanel").css('width','20%');
       $("#mainMap").css('width','79.6%');
       $("#show_hide_panel span").removeClass('hide_panel'); 
       $("#show_hide_panel span").addClass('show_panel'); 
       $("#show_hide_panel").attr('title','Sembunyikan panel navigasi');
       google.maps.event.trigger(stream_map, "resize");
    });
    // Ubah style CSS tombol/link collapse pada saat panel navigasi tersembunyi atau tertampil
    $("#show_hide_panel").hover(
        function(){
           $("#show_hide_panel span").removeClass('panel_active'); 
           $("#show_hide_panel span").addClass('panel_hover');        
        },
        function(){
           $("#show_hide_panel span").removeClass('panel_hover'); 
           $("#show_hide_panel span").addClass('panel_active');        
        }
    );
    //Map Navigation
    $("#poly_moratorium").change(function(){
        if($(this).is(':checked'))fusiontable_polygonmoratorium.setMap(stream_map);
        else fusiontable_polygonmoratorium.setMap(null);
    });
    $("#poly_desa").change(function(){
        if($(this).is(':checked'))fusiontable_polygondesa.setMap(stream_map);
        else fusiontable_polygondesa.setMap(null);
    });
    $("#mark_desa").change(function(){
        if($(this).is(':checked')){            
            for(var village_id in village_markers) {
                var marker = village_markers[village_id];		
                marker.setMap(stream_map);
            }
        }
        else {
            for(var village_id in village_markers) {
                var marker = village_markers[village_id];		
                marker.setMap(null);
            }
        }
    });
    get_village_list(0);
    $("#village_list a.village_nav").click(function(){
        display_village_SMS($(this).attr('rel'));
    });
    $("#village_pagination a.pagination").click(function(){
        get_village_list($(this).attr('rel'));
    });
    $("#village_search_input").val('Cari desa . . .');
    $("#village_search_input").focus(function(){
        $(this).val('');
    });
    $("#village_search_input").blur(function(){
        $(this).val('Cari desa . . .');
    });
    $("#village_search_input").keypress(function(){
        if($(this).val() != '')search_village($(this).val());
        else get_village_list(0);
    });
});
//Create Map SMS
function initialize_stream_map(){
    var latlng = new google.maps.LatLng(-1.986419,113.040878);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.TERRAIN
    };
    stream_map = new google.maps.Map(document.getElementById("mainMap"),myOptions);    
    
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
    fusiontable_polygondesa.setMap(stream_map);
    fusiontable_polygonmoratorium.setMap(stream_map);
    get_villages_data();    
}
//Get Villages
function get_villages_data(){
    $.getJSON("<?=site_url('phonebook/get_villages')?>",{is_ajax: 'true'}, function(data){
              $.each(data, function(key, val) {
                    latlong = val.village_coordinate_location;
                    latlong = latlong.toString();
                    latlong = latlong.split(','); 
                    var village_id = parseInt(val.village_id);
                    
                    //alert(latlong[0]);
                    //alert(latlong[1]);
                    var lat = parseFloat(latlong[1]);
                    var lng = parseFloat(latlong[0]);
                    //markerlatlng[village_id] = new google.maps.LatLng(parseFloat(latlong[0]),parseFloat(latlong[1]));                        
                    village_latlng[village_id] = new google.maps.LatLng(lat,lng);//new google.maps.LatLng(-1.296276,113.686523);//new google.maps.LatLng(lat,lng);
                    var image = new google.maps.MarkerImage('http://labs.google.com/ridefinder/images/mm_20_green.png',
                      new google.maps.Size(20, 34),
                      new google.maps.Point(0, 0),
                      new google.maps.Point(10, 34));
                    village_markers[village_id] = new google.maps.Marker({
                          position: village_latlng[village_id],//new google.maps.LatLng(parseFloat(latlong[0]),parseFloat(latlong[1])), 
                          map: stream_map, 
                          icon: image,
                          title:val.village_name
                    });
                    if(display_village_markers == false){
                        village_markers[village_id].setMap(null);
                    }
              })              
          });
}
function view_marker_village(village_id){
    village_markers[village_id].setMap(stream_map);
}
/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
function get_village_list(paging){
            $.ajax({
                    url: '<?=site_url('phonebook/get_village_list')?>/'+paging,
                    type: "GET",
                    dataType: 'json',  //Required
                    //beforeSend: function() {$("#custom_message_container .loading").show();},
                    //complete: function() { $("#custom_message_container .loading").hide();},
                    success: function(data){                                
                                //each village list
                                var villages='';
                                villages += '<ul>';
                                $.each(data.villages, function(key, val) {
                                    villages +='<li><a class="village_nav" href="#" rel="'+val.village_id+'">'+val.village_name+'</a></li>';
                                });
                                villages += '</ul>';                                
                                if(villages !== ''){
                                    $("#village_list").html(villages);   
                                    $("#village_list a.village_nav").click(function(){
                                        display_village_SMS($(this).attr('rel'));
                                    });
                                }
                                //each pagination                                
                                var pagination='';                                
                                $.each(data.pagination, function(key, val) { 
                                    if(val.prev < 0)val.prev=0;
                                    pagination += '<a class="pagination" href="#" rel="'+val.prev+'">&laquo;</a>';
                                    var pagination_viewed = 5;
                                    var pagination_limit = paging + pagination_viewed;
                                    for(var i=0;i<pagination_viewed;i++){
                                        var page_anchor = parseInt(paging)+parseInt(i)+1;
                                        var rel = parseInt(paging)+parseInt(i);
                                        pagination += '<a class="pagination" href="#" rel="'+rel+'">'+page_anchor+'</a>';
                                    }
                                    pagination += '<a class="pagination" href="#" rel="'+val.next+'">&raquo;</a>';
                                });
                                if(pagination !== ''){
                                    $("#village_pagination").html(pagination); 
                                    $("#village_pagination a.pagination").click(function(){
                                        get_village_list($(this).attr('rel'));
                                    });
                                }                                
                             }
            }); 
}
function search_village(query,paging){
            if(!paging)paging=0;
            $.ajax({
                    url: '<?=site_url('phonebook/search_village')?>/'+query+'/'+paging+'/1',
                    type: "GET",
                    dataType: 'json',  //Required
                    //beforeSend: function() {$("#custom_message_container .loading").show();},
                    //complete: function() { $("#custom_message_container .loading").hide();},
                    success: function(data){
                                //each village list
                                var villages='';
                                villages += '<ul>';
                                $.each(data.villages, function(key, val) {
                                    villages +='<li><a class="village_nav" href="#" rel="'+val.village_id+'">'+val.village_name+'</a></li>';
                                });
                                villages += '</ul>';                                
                                if(villages !== ''){
                                    $("#village_list").html(villages);                                    
                                }
                                $("#village_list a.village_nav").click(function(){
                                        display_village_SMS($(this).attr('rel'));
                                });
                                //each pagination                                
                                var pagination='';                                
                                $.each(data.pagination, function(key, val) { 
                                    if(val.prev < 0)val.prev=0;
                                    pagination += '<a class="pagination" href="#" rel="'+val.prev+'">&laquo;</a>';
                                    var pagination_viewed = 5;
                                    var pagination_limit = paging + pagination_viewed;
                                    for(var i=0;i<pagination_viewed;i++){
                                        var page_anchor = parseInt(paging)+parseInt(i)+1;
                                        var rel = parseInt(paging)+parseInt(i);
                                        pagination += '<a class="pagination" href="#" rel="'+rel+'">'+page_anchor+'</a>';
                                    }
                                    pagination += '<a class="pagination" href="#" rel="'+val.next+'">&raquo;</a>';
                                });
                                if(pagination !== ''){
                                    $("#village_pagination").html(pagination); 
                                    $("#village_pagination a.pagination").click(function(){
                                        search_village(query,$(this).attr('rel'));
                                    });
                                }
                             }
            }); 
}
/*
* Display Village SMS
 */
 function display_village_SMS(village_id,paging){
        village_id = parseInt(village_id);
        $.ajax({
            url: '<?=site_url('microblog/village_microblog')?>/'+village_id+'/'+paging+'/1',
            type: "GET",
            dataType: 'json',  //Required
            //beforeSend: function() {$("#custom_message_container .loading").show();},
            //complete: function() { $("#custom_message_container .loading").hide();},
            success: function(data){
                            village_markers[village_id].setMap(stream_map);
                            

                            contentString = data.messages;
                            infoWindow[village_id] = new google.maps.InfoWindow({
                                content: contentString,
                                pixelOffset: new google.maps.Size(0,10)                           

                            });
                            infoWindow[village_id].open(stream_map,village_markers[village_id]);
                            
                            
                            var latlong;
                            $.each(data.village, function(key, val) {
                                latlong  = val.village_coordinate_location.split(',');
                            });
                            var latitude = parseFloat(latlong[1]);
                            var longitude = parseFloat(latlong[0]);
//                            infobox.setPosition(new google.maps.LatLng(latitude,longitude));
//                            infobox.setContent(contentString);
//                            infobox.open(stream_map);
                                        
                            google.maps.event.addListener(village_markers[village_id], 'click', function() {                            
                                infoWindow[village_id].open(stream_map,village_markers[village_id]);
                            }); 
                            google.maps.event.addListener(infoWindow[village_id], 'closeclick', function() {                            
                                infoWindow[village_id].close();
                                village_markers[village_id].setMap(null);
                            });
                            //if($("div.customScrollBox").size() > 0) $("#village_conversation_container").mCustomScrollbar("vertical",420,"easeOutCirc",1.25,"fixed","yes","no",0);
                        
            }
        });
 }

</script>