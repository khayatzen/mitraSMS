
var geocoder;
var map;
var marker;

var workgeocoder;
var workmap;
var markerArray=[];
var jumlahWilayahKerja;

function setByLatLongChecked(){
    $("#byLatLong").attr('checked','checked');
}
function initAlamat() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-2.226667,113.944167);// lat long -> Palangkaraya
    var myOptions = {
      zoom: 6,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("gMap"), myOptions);   

}

function petaAlamat(status) {
    var latlong = '';
    //var markerBy='byLatLong';
    initAlamat();
    var address = jQuery("#alamatLembaga").val();
    var byaddress = jQuery("#byAddress").attr('checked');
    var bylatlong = jQuery("#byLatLong").attr('checked');
    var lat = jQuery("#alamatLat").val();
    var lng = jQuery("#alamatLong").val();
    if(byaddress){
        markerBy = 'byAddress';
    }else if(bylatlong){
        if(lat && lng)markerBy = 'byLatLong';
        else alert("Silahkan masukkan Latitude dan Longitude terlebih dahulu !");
    }else markerBy = 'byLatLong';
    //alert(markerBy);
    if(markerBy == 'byAddress'){        
        if(address !== ''){
            
            geocoder.geocode( {'address': address}, function(results, status) {
              if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                latlong = results[0].geometry.location.lat()+','+results[0].geometry.location.lng();
                jQuery("#alamatLatLong").val(latlong);
                jQuery("#label_titik_alamat").html('Latitude-Longitude : '+latlong);
                var contentString = '<h3>'+address+'</h3>pada :'+latlong;
                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });

                var marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    title: 'Detail',
                    draggable:true,
                    animation: google.maps.Animation.BOUNCE

                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });
                google.maps.event.addListener(marker, 'click', toggleBounce);
                google.maps.event.addListener(marker, 'position_changed', function() {
                    //alert(marker.getPosition().lng());
                    latlong = marker.getPosition().lat()+','+marker.getPosition().lng();
                    $("#alamatLatLong").val(latlong);
                    $("#label_titik_alamat").html('Latitude-Longitude : '+latlong);
                });


              } else {
                alert("Maaf, Alamat tidak tersedia : " + status);
              }
            });

        }else{
            alert('Masukkan alamat Lembaga Mitra ! ');
        }
    }else if(markerBy=='byLatLong'){
        if(status=='new'){
            //alert(lat+','+lng);
                //var address1 = jQuery("#alamatLembaga").val();
                //var contentString = '<h3>'+address1+'</h3>pada :'+lat+','+lng;
                var contentString = address;
                var infowindow = new google.maps.InfoWindow({
                    content: contentString
                });
                var markerPosition = new google.maps.LatLng(parseFloat(lat),parseFloat(lng));
                var marker = new google.maps.Marker({
                    map: map,
                    position: markerPosition,
                    title: 'Alamat Lembaga',
                    draggable:true,
                    animation: google.maps.Animation.BOUNCE
                });
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });
                google.maps.event.addListener(marker, 'position_changed', function() {
                    latlong = marker.getPosition().lat()+','+marker.getPosition().lng();
                    jQuery("#alamatLatLong").val(latlong);
                    jQuery("#label_titik_alamat").html('Latitude-Longitude : '+latlong);
                });
                map.setCenter(markerPosition);
                latlong = marker.getPosition().lat()+','+marker.getPosition().lng();
                jQuery("#alamatLatLong").val(latlong);
                jQuery("#label_titik_alamat").html('Latitude-Longitude : '+latlong);
        }else if(status=='edit'){
                latLongAlamat = jQuery("#alamatLatLong").val().toString();
                if(latLongAlamat){
                        var address1 = jQuery("#alamatLembaga").val();
                        var contentString = '<h3>'+address1+'</h3>pada :'+latLongAlamat;
                        var infowindow = new google.maps.InfoWindow({
                            content: contentString
                        });
                        mark = latLongAlamat.split(',');
                        var markerPosition = new google.maps.LatLng(parseFloat(mark[0]),parseFloat(mark[1]));
                        var marker = new google.maps.Marker({
                            map: map,
                            position: markerPosition,
                            title: 'Alamat Lembaga',
                            draggable:true

                        });
                        google.maps.event.addListener(marker, 'click', function() {
                            infowindow.open(map,marker);
                        });
                        google.maps.event.addListener(marker, 'position_changed', function() {
                            latlong = marker.getPosition().lat()+','+marker.getPosition().lng();
                            jQuery("#alamatLatLong").val(latlong);
                            jQuery("#label_titik_alamat").html('Latitude-Longitude : '+latlong);
                        });
                        map.setCenter(markerPosition);
                }
        }
                    
        
    }
    map.setZoom(12);
}


function toggleBounce() {

  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

function getFusionTableMap(){
    var embedURL = jQuery("#fusionMap").val();    
    if(embedURL){
        var frame = '<iframe style="width:700px;height:320px;" scrolling="no" border"0" src="'+embedURL+'">Loading Map . . .</iframe>';
        jQuery("#petawilayahkerja").html(frame);
    }
}
