<template>
    <div :style="'width:'+(width ? width :'200px')+';'">
        <div v-if="group_id && target_name && target_id">
            <button v-if="set_view === false" class="btn btn-outline-primary btn-sm my-1" @click="set_view=true" >
                <span class="fa fa-map"></span>  SET DEFAULT VIEW
            </button>
            <div v-else>
                <web-submit :action="save_map_settings" el_class="btn btn-outline-primary btn-sm my-1 mr-2" icon_class="fa fa-save" :label="'SAVE'" :timeout="3000" />
                <button class="btn btn-default btn-sm my-1" @click="set_view=false">
                    <span class="fa fa-times"></span> CANCEL
                </button>

            </div>
        </div>
        <div :id="'google-map-'+_uid" :style="'height:'+(height ? height: '200px;' )+';width:'+(width ? width :'200px')+';'" >
    </div>
    </div>
</template>

<script> 
    import WebSubmitVue from './WebSubmit.vue';
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
        props:[ 
            "height", 
            "width", 
            "google_map_api_key", 
            "google_map_api_id", 
            "is_multi_coordinates", 
            "is_select_map", 

            //For Custom View
            "target_id", 
            "target_name", 
            "group_id",

            //
            "is_select_paths",
            "select_source",
            "select_target"
        ],
        $emits:["selected_location", "clicked_marker", "clicked_path"],
        watch: { 
            is_select_map:function(newVal){
                this.is_select_map = newVal;
            },
            select_from:function(newVal){
                this.series_paths.source = newVal;
                this.series_paths.lines = [];
            },
            select_target:function(newVal){
                this.series_paths.target = newVal;
                this.series_paths.lines = [];
            }


        },
        components: {
            "web-submit":WebSubmitVue
        },
        data: function () {
            return {
                google_map_id: 'google-map-'+this._uid,
                map:null,
                markers:[],
                paths:[],
                isMapReady:false,
                promiseExec:null,
                set_view:false,
                series_paths:{
                    source:null,
                    target:null,
                    lines:[]
                },
                
            }
        },
        methods: { 
            save_map_settings:function(){
                var vm = this;

                // Assuming you already created the map:
                const center = vm.map.getCenter(); // returns a LatLng object
                const zoom = vm.map.getZoom();     // returns a number

                var request = {
                    latitude : center.lat(),
                    longitude : center.lng(),
                    target_id: this.target_id,
                    target_name: this.target_name,
                    zoom: zoom
                };

                
                return WebRequest2('POST', '/api/group/'+vm.group_id+'/map/settings', JSON.stringify(request) ).then(resp=>{
                    return resp.json().then(data=>{
                        setTimeout(()=>{
                            vm.set_view = false;
                        }, 4000);

                        return data;
                    
                    });
                });
            },
            get_center_info:function(){
                var vm = this;
                if(vm.map){

                    // Assuming you already created the map:
                    const center = vm.map.getCenter(); // returns a LatLng object
                    const zoom = vm.map.getZoom();     // returns a number
                    

                }
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },

            loadMapUI:function(coordinates=[{latitude: 10.3157, longitude: 123.8854}], zoom=15, set_marker=true){
                
                var vm = this;

                if(!vm.google_map_api_key) return;

                const defaultLocation = { lat: coordinates[0].latitude, lng: coordinates[0].longitude }; 

                vm.map = new google.maps.Map(document.getElementById(vm.google_map_id), {
                    center: defaultLocation,
                    zoom: zoom,
                    mapId: vm.google_map_api_id
                });

                
                //MAP LOADED
                google.maps.event.addListenerOnce(vm.map, 'tilesloaded', function () {

                    //TODO:: SET THE MAP FOR CUSTOM FOCUS HERE

                    if(!vm.is_select_map && set_marker){
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
                    vm.isMapReady = true;
                    //This configuration is when you have group_id, target_id and target_name
                    //vm.loadMapSettings();

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
            },
            
            initMap:function(coordinates=[{latitude: 10.3157, longitude: 123.8854}], set_marker=true) { // Default Cebu, Philippines
                
                var vm = this;

                //This is to activating the default settings in a map
                if(vm.group_id && vm.target_id && vm.target_name){
                    vm.loadMapSettings().then(data=>{
                        if(data){
                            vm.loadMapUI([data], data.zoom, set_marker);
                        }
                        else{
                            //Load the default view
                            vm.loadMapUI(coordinates, 11, set_marker);
                        }
                    });
                }else{
                    //Load the default view
                    vm.loadMapUI(coordinates, 11, set_marker);
                }

                //Triggers when map has been loaded.
                return new Promise((promiseExec)=>{
                    vm.promiseExec = promiseExec;
                });
            },
            queryString:function(params={}){ 
                var queryString = Object.keys(params).map(function(key) {
                    return key + '=' + params[key]
                }).join('&');
                return queryString;
            },
            loadMapSettings:function(){

                var vm = this;
                
                return WebRequest2('GET', '/api/group/'+vm.group_id+'/map/settings?'+vm.queryString({
                    target_id : vm.target_id,
                    target_name : vm.target_name
                })).then(resp=>{
                    if(resp.ok){
                        return resp.json().then(data=>{
                            if(data.status == 1 && data.data ){
                                var jsonMap = JSON.parse(data.data);
                                if(jsonMap){
                                    return jsonMap;
                                }
                            }
                            return null;
                        });
                    }
                    return null;
                })

            },
            setMarker:function(location, is_center = false){
            
                this.placeMarker(location);
                
                if(is_center){
                    this.recenterMap(location.latitude, location.longitude);
                }
            
            },            
            // Recenter function
            recenterMap:function(lat, lng, zoom=null) {
                var vm = this;
                setTimeout(()=>{

                    const newCenter = new google.maps.LatLng(lat, lng);
                    
                    vm.map.setCenter(newCenter);

                    if(zoom)
                        vm.map.setZoom(zoom);

                }, 300);
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
            clearPaths:function(){
                var vm = this;
                vm.paths.forEach(p=>{ 
                    p.path.setMap(null);
                    if(p.interval_callback !== null){
                        window.clearInterval(p.interval_callback);
                    }
                });
                vm.paths = [];
            },
            loadCoordinates(coordinates=[{latitude: 10.3157, longitude: 123.8854}], set_marker=true){
                return this.initMap(coordinates, set_marker);
            },

            createPath:function(PointAB, hex_color="#FF0000", is_dash=false, is_moving=false, speed=250, htmlContent = null){
                var vm = this;
                const pointA = { lat: PointAB.from.latitude * 1, lng: PointAB.from.longitude * 1 }; // Cebu City
                const pointB = { lat: PointAB.to.latitude * 1, lng: PointAB.to.longitude * 1 }; // Nearby point
                
                var line = null;
                var pathData = null;
                
                //ONLY LINE
                if(is_dash === false){
                    line = new google.maps.Polyline({
                        path: [pointA, pointB], // Points in order
                        geodesic: true,         // Makes the line follow the curve of the Earth
                        strokeColor: hex_color, // Line color
                        strokeOpacity: 1.0,     // Fully visible
                        strokeWeight: 3         // Thickness
                    });

                    line.setMap(vm.map);

                    pathData = {
                        path:line,
                        data:PointAB,
                        interval_callback:null
                    };

                    vm.paths.push(pathData);

                }
                else{

                    // Create a dashed polyline either moving or not.
                    line = new google.maps.Polyline({
                        path: [pointA, pointB],
                        geodesic: true,
                        strokeOpacity: 0, // Hide the solid line
                        icons: [
                            {
                                icon: {
                                    path: "M 0,-1 0,1", // short vertical dash
                                    strokeOpacity: 1,
                                    strokeColor: hex_color, // <-- set dash color here
                                    scale: 2,
                                },
                                offset: "0",
                                repeat: "10px", // space between dashes
                            }
                        ],
                        map: vm.map
                    });

                    // Animate the dashes moving
                    var interval_callback = null;
                    if(is_moving){
                        let count = 0;
                        interval_callback = window.setInterval(() => {
                            count = (count + 1) % 200;

                            const icons = line.get("icons");
                            icons[0].offset = count + "px";
                            line.set("icons", icons);
                        }, speed);
                    }
                    
                    pathData = {
                        path:line,
                        data:PointAB,
                        interval_callback:interval_callback
                    };

                    vm.paths.push(pathData);

                }

                line.addListener("click", (event) => {
                    if(htmlContent){
                        infoWindow.setContent(htmlContent);
                        infoWindow.setPosition(event.latLng); // Show at clicked point
                        infoWindow.open(vm.map);
                    }
                    vm.$emit('clicked_path', pathData );
                });

            },
            defaultSvgIcon:function(icon_class, bgColor="white", stroke="silver"){
                if(icon_class){
                    return `
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="50" viewBox="0 0 40 50">
                            <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                <feDropShadow dx="0" dy="1" stdDeviation="2" flood-color="rgba(0,0,0,0.3)" />
                            </filter>

                            <!-- Teardrop shape -->
                            <path d="
                            M20 2
                            a18 18 0 0 1 18 18
                            c0 9 -8.5 17 -17.5 27
                            C11 37 2 29 2 20
                            a18 18 0 0 1 18 -18z
                            " fill="${bgColor}" stroke="${stroke}" stroke-width="2" filter="url(#shadow)" />

                            <!-- Font Awesome icon -->
                            <foreignObject x="0" y="0" width="40" height="40">
                                <div xmlns="http://www.w3.org/1999/xhtml" 
                                    style="display:flex;justify-content:center;align-items:center;height:40px;">
                                <i class="${icon_class}" style="font-size:18px;"></i>
                                </div>
                            </foreignObject>
                        </svg>
                    `;
                }
                return null;
            }


        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
