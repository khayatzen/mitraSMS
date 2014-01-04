// SEBARAN STREAM (MAP)
function get_streams(){
	TWITTER_POST_LOADED = 0;
	var a = [];
	var url =  document.base_url + 'api/petatwit/?callback=?';
	document.twitter_post = [];
	$.jsonp({
		url: url,
		success: function(json)
		{
			TWITTER_POST_LOADED = 1;
			if(json.length > 0)
			{
				for(i=0; i<json.length; i++) {
					a = [ 
						json[i].user, 
						json[i].url, 
						json[i].picurl, 
						json[i].text,
						json[i].content,
						json[i].timestamp,
						json[i].city, 
						json[i].latitude, 
						json[i].longitude,
					];
					document.twitter_post.push(a);
				}
			}
		},
		error: function(b,c)
		{
		
		}
	});
}

var TWITTER_POST_LOADED = 1;

$(document).ready(function(){

	// $('body').onload(function(e){
		
		var marker;
		var initialmarker;
		var map;
		var ib;
	
		/* GMAP */
		var Indonesia = new google.maps.LatLng(-3,120);// new google.maps.LatLng(13,92);
		var mapOptions = {
			zoom: 5,
			center: Indonesia,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			disableDefaultUI: true,
			disableDoubleClickZoom: true,
			draggable: false,
			scrollwheel: false
		};
		var map = new google.maps.Map(document.getElementById("peta"),mapOptions);	
		var image = new google.maps.MarkerImage(
			document.image_url + 'tutiwit_img.png',
			new google.maps.Size(16,16),
			new google.maps.Point(0,0),
			new google.maps.Point(8,8)
		);
		var shadow = new google.maps.MarkerImage(
			document.image_url + 'tutiwit_shadow.png',
			new google.maps.Size(28,16),
			new google.maps.Point(0,0),
			new google.maps.Point(8,8)
		);
		var shape = {
			coord: [6,0,7,1,7,2,9,3,14,4,15,5,15,6,15,7,15,8,15,9,15,10,15,11,15,12,15,13,14,14,9,15,5,15,3,14,2,13,2,12,1,11,1,10,1,9,1,8,1,7,1,6,1,5,1,4,1,3,1,2,2,1,3,0,6,0],
			type: 'poly'
		};
		
		
		var trns = new google.maps.MarkerImage(
			document.image_url + 'transparan.png',
			new google.maps.Size(8,8),
			new google.maps.Point(0,0),
			new google.maps.Point(4,4)
		);
		/* END GMAP */
		
		$('#load_sebaran_twit_terkini').remove();	
		
		// initial
		initialmarker = new google.maps.Marker({
			draggable: false,
			raiseOnDrag: false,
			icon: trns,
			shadow: trns,
			//shape: shape,
			map: map,
			position: Indonesia,
		});
		initialmarker.setMap(map);
		var ib = new InfoBox({ 
			isHidden: false,
			content: '<div class="loading">Sedang jalan-jalan dari Sabang sampai Merauke mengumpulkan data...</div>',
			disableAutoPan: false,
			pixelOffset: new google.maps.Size(-340,-170),
			zIndex: 5,
			closeBoxURL: '',
			enableEventPropagation: false,
			boxStyle: { 
				width: "400px",
				padding: '5px 10px'
			}
		});		
		ib.open(map, initialmarker);
		
		if (TWITTER_POST_LOADED)
		{
			 get_twitter();
		}
		$('#peta').everyTime(6000, function(){
			//alert('hore');
			if(document.twitter_post.length > 0)
			{
				var sekarang = document.twitter_post.pop();
				var info = '<div class="twitholder"><div class="twitnow"><img src="'+sekarang[2]+'"><a href="http://twitter.com/'+sekarang[0]+'" target="blank">'+sekarang[0]+'</a><p>'+ $.url.decode(sekarang[3]) +'</p><small>di '+sekarang[6]+'</small></div></div>';
				ib.close();			
				if (initialmarker) initialmarker.setMap(null);
				if (marker) marker.setMap(null);
				marker = new google.maps.Marker({
					draggable: false,
					raiseOnDrag: false,
					icon: image,
					shadow: shadow,
					shape: shape,
					map: map,
					position: new google.maps.LatLng( sekarang[7],sekarang[8] ),
					title: 'from '+sekarang[6],
				});
				marker.setMap(map);
				var infoboxOptions = {
					content: info,
					disableAutoPan: false,
					maxWidth: '400px',
					pixelOffset: new google.maps.Size(10, -25),
					zIndex: 5,
					boxStyle: { 
						//background: "url("+document.image_url+"images/panahpeta.png) no-repeat left top",
						opacity: 1,
						width: "400px",
						//height: "100px",
						//padding: '0px 0px 0px 15px',
						//border: '0px solid #CD5C5C',
					},
					closeBoxURL: '',//"http://www.google.com/intl/en_us/mapfiles/close.gif",
					infoBoxClearance: new google.maps.Size(1, 1),
					isHidden: false,
					pane: "floatPane",
					enableEventPropagation: false
				};
				ib = new InfoBox(infoboxOptions);
				ib.open(map, marker);
				
			} 
			else 
			{
				if(TWITTER_POST_LOADED)
				{
					 get_twitter();
				}
			}
		});
		
	// });

});