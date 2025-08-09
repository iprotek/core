<template>
    <div :id="'google-map-'+_uid" :style="'height:'+(height ? height: '200px;' )+';width:'+(width ? width :'200px')+';'" >
    </div>
</template>

<script>
    export default {
        props:[ "height", "width", "google_map_api_key", "is_multi_coordinates", "is_select_map" ],
        $emits:["selected_location"],
        watch: { 
        },
        components: { 
        },
        data: function () {
            return {
                google_map_id: 'google-map-'+this._uid,
                map:null,
                markers:[]
            }
        },
        methods: { 
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            
            initMap:function(coordinates=[{latitude: 10.3157, longitude: 123.8854}]) { // Default Cebu, Philippines

                var vm = this;
                if(!vm.google_map_api_key) return;

                const defaultLocation = { lat: coordinates[0].latitude, lng: coordinates[0].longitude }; 

                vm.map = new google.maps.Map(document.getElementById(vm.google_map_id), {
                center: defaultLocation,
                zoom: 10,
                });

                //vm.map.addListener("click", (e) => {
                if(!vm.is_select_map){
                    google.maps.event.addListenerOnce(vm.map, 'tilesloaded', function () {
                        vm.placeMarker(defaultLocation);
                    });
                }
                else{                    
                    vm.map.addListener("click", (e) => {
                        vm.$emit('selected_location', e.latLng);
                        vm.placeMarker(e.latLng);
                    });
                }
            },
            setMarker:function(location, is_center = false){
            
                this.placeMarker(location);
                
                if(is_center){
                    this.recenterMap(location.latitude, location.longitude);
                }
            
            },            
            // Recenter function
            recenterMap:function(lat, lng) {
                //const newCenter = new google.maps.LatLng(lat, lng);
                vm.map.setCenter(newCenter);
            },
            placeMarker:function(location, is_new=false) {
                var vm = this;
                //SING COORDINATES
                if(!vm.is_multi_coordinates){
                    var marker = vm.markers[0];
                    if (marker) {
                        marker.setPosition(location);
                    } 
                    else {
                        marker = new google.maps.Marker({
                            position: location,
                            map: vm.map,
                        });
                        vm.markers.push(marker);
                    }
                }
                else{

                    //TODO:: FOR MULTI LOCATION / ORDINATES, NOT EMPLEMENTED

                }


            },
            clearMarkers:function(){
                var vm = this;
                vm.markers.forEach(m => m.setMap(null));
                vm.markers = [];
            },
            loadCoordinates(coordinates=[{latitude: 10.3157, longitude: 123.8854}]){
                this.initMap(coordinates);
            }


        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
