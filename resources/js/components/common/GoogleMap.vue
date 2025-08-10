<template>
    <div :id="'google-map-'+_uid" :style="'height:'+(height ? height: '200px;' )+';width:'+(width ? width :'200px')+';'" >
    </div>
</template>

<script> 
    export default {
        /*
            Market: htmlIcon, htmlContent
            //HTML ICON
            customize the icon

            //HTML Content
            customize the content info of the icon is clicked

            CONTENT TEMPLATE
            <div style="text-align:center; font-size:12px;">
            <img src="https://media.istockphoto.com/id/1973365581/vector/sample-ink-rubber-stamp.jpg?s=612x612&w=0&k=20&c=_m6hNbFtLdulg3LK5LRjJiH6boCb_gcxPvRLytIz0Ws=" style="width:50px;height:50px;border-radius:50%;" />
            <div><strong>Sample </strong></div>
            <div>This is a sample Text</div>
            </div>
        */
        props:[ "height", "width", "google_map_api_key", "google_map_api_id", "is_multi_coordinates", "is_select_map" ],
        $emits:["selected_location", "clicked_marker"],
        watch: { 
            is_select_map:function(newVal){
                this.is_select_map = newVal;
            }
        },
        components: { 
        },
        data: function () {
            return {
                google_map_id: 'google-map-'+this._uid,
                map:null,
                markers:[],
                promiseExec:null
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
                mapId: vm.google_map_api_id
                });

                
                //MAP LOADED
                google.maps.event.addListenerOnce(vm.map, 'tilesloaded', function () {

                    if(!vm.is_select_map){
                        vm.placeMarker({
                            latitude: defaultLocation.lat,
                            longitude: defaultLocation.lng
                        });
                    }
                    //
                    vm.promiseExec(
                        vm.map,
                        {
                            latitude: defaultLocation.lat,
                            longitude: defaultLocation.lng
                        }
                    );

                });
                
                if(vm.is_select_map){                    
                    vm.map.addListener("click", (e) => {

                        if(!vm.is_select_map) return;

                        var location = {
                           latitude: e.latLng.lat(),
                           longitude: e.latLng.lng()
                        }
                        vm.$emit('selected_location', location);
                        vm.placeMarker(location);
                    });
                }

                //Triggers when map has been loaded.
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            },
            setMarker:function(location, is_center = false){
            
                this.placeMarker(location);
                
                if(is_center){
                    this.recenterMap(location.latitude, location.longitude);
                }
            
            },            
            // Recenter function
            recenterMap:function(lat, lng) {
                var vm = this;
                setTimeout(()=>{
                    const newCenter = new google.maps.LatLng(lat, lng);
                    vm.map.setCenter(newCenter);
                }, 500);
            },
            placeMarker:function(location, is_new=false, dataInfo) {
                var vm = this;
                //SING COORDINATES
                if(!vm.is_multi_coordinates && is_new === false){
                    var marker = vm.markers[0];
                    if (marker) {
                        marker.position ={
                            lat: location.latitude,
                            lng: location.longitude
                        };
                    } 
                    else {
                        vm.createMarker(location, dataInfo);
                    }
                }
                else if(vm.is_multi_coordinates === true && is_new === true){                    
                    //TODO:: FOR MULTI LOCATION / ORDINATES, NOT EMPLEMENTED
                    vm.createMarker(location, dataInfo);
                }


            },
            //dataInfo fields: title, htmlContent, htmlIcon
            createMarker:function(location, dataInfo){
                var vm = this;
                var htmlElement = document.createElement("div");
                if(dataInfo && dataInfo.htmlIcon){
                    htmlElement.innerHTML = dataInfo.htmlIcon;
                }
                var marker = new google.maps.marker.AdvancedMarkerElement({
                    position: {
                        lat: location.latitude,
                        lng: location.longitude,
                    },
                    map: vm.map,
                    title: dataInfo && dataInfo.title ? dataInfo.title : '',
                    content: dataInfo && dataInfo.htmlIcon ? htmlElement : null
                });

                var infoWindow = null;
                if(dataInfo && dataInfo.htmlContent){

                    
                    infoWindow = new google.maps.InfoWindow({
                        content: dataInfo.htmlContent
                    });
                    
                    //Manipulate style inside of infowindow
                    infoWindow.addListener("domready", () => {
                        // Get the default container for the InfoWindow
                        const iwOuter = document.querySelector('.gm-style-iw');
                        if(iwOuter){
                            var closeButton = iwOuter.querySelector('button.gm-ui-hover-effect');
                            if(closeButton){
                                closeButton.style.right = 0;
                                closeButton.style.position = 'absolute';
                            }
                        }
                    });
                }


                marker.addListener("click", (evt) => {

                    if(infoWindow){
                        infoWindow.open(vm.map, marker);
                    }

                    vm.$emit('clicked_marker', marker, dataInfo, location, evt);

                });

                vm.markers.push(marker);   
            },
            clearMarkers:function(){
                var vm = this;
                vm.markers.forEach(m => m.setMap(null));
                vm.markers = [];
            },
            loadCoordinates(coordinates=[{latitude: 10.3157, longitude: 123.8854}]){
                return this.initMap(coordinates);
            }


        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
