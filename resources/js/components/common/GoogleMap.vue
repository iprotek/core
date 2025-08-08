<template>
    <div :id="'google-map-'+_uid" :style="'height:'+(height ? height: '200px;' )+';width:'+(width ? width :'200px')+';'" >
    </div>
</template>

<script>
    export default {
        props:[ "height", "width", "google_map_api_key", "coordinates", "is_multi_coordinates" ],
        $emits:[],
        watch: { 
        },
        components: { 
        },
        data: function () {
            return {
                google_map_id: 'google-map-'+this._uid,
                map:null,
                marker:null
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
                google.maps.event.addListenerOnce(vm.map, 'tilesloaded', function () {
                    vm.placeMarker(defaultLocation);
                });
            },
            placeMarker:function(location) {
                var vm = this;
                if (vm.marker) {
                    vm.marker.setPosition(location);
                } 
                else {
                    vm.marker = new google.maps.Marker({
                        position: location,
                        map: vm.map,
                    });
                }
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
