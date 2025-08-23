<template>
    <div :style="'width:'+(width ? width :'200px')+';'">
        <span v-if="isMapReady">
            <span v-if="group_id && target_name && target_id && is_start_select_paths == false && series_paths.coordinates.length <= 0" class="mr-2">
                <button v-if="set_view === false" class="btn btn-outline-primary btn-sm my-1" @click="set_view=true" >
                    <span class="fa fa-map"></span>  SET DEFAULT VIEW
                </button>
                <div v-else>
                    <web-submit :action="save_map_settings" el_class="btn btn-outline-primary btn-sm my-1 mr-2" icon_class="fa fa-save" :label="'SAVE'" :timeout="3000" />
                    <button class="btn btn-default btn-sm my-1" @click="set_view=false">
                        <span class="fa fa-times"></span> CANCEL
                    </button>
                </div>
            </span>
            <span v-if="is_select_paths && select_source && select_target && set_view == false">
                <span v-if="is_start_select_paths === false" >
                    <button class="btn btn-outline-secondary btn-sm my-1 mr-2" @click="is_start_select_paths=true" >
                        <span class="fa fa-code-fork"></span> SELECT PATH/S
                    </button>
                    <web-submit v-if="series_paths.coordinates.length > 0" :action="save_paths" :el_class="'btn btn-outline-primary btn-sm my-1 mr-2'" :icon_class="' fa fa-save'" :label="'SAVE'" :timeout="3000" />
                    <button v-if="series_paths.coordinates.length > 0" class="btn btn-default btn-sm my-1" @click="cancelSelectPaths();is_start_select_paths=false" >
                        <span class="fa fa-times"></span> CANCEL
                    </button>
                </span>
                <span v-else>
                    <button class="btn btn-outline-success btn-sm my-1 mr-2" @click="doneSelecting();">
                        <span class="fa fa-check"></span> DONE
                    </button>
                    <button class="btn btn-outline-warning btn-sm my-1" @click="undoSelectedPath();" >
                        <span class="fa fa-undo"></span> UNDO
                    </button>
                    <button class="btn btn-default btn-sm my-1" @click="cancelSelectPaths();is_start_select_paths=false" >
                        <span class="fa fa-times"></span> CANCEL
                    </button>

                </span>

            </span>
        </span>
        <div :id="'google-map-'+_uid" :style="'height:'+(height ? height: '200px;' )+';width:'+(width ? width :'200px')+';'" ></div>
        <div>
            <button class="btn btn-outline-primary btn-sm" @click="set_info_window_once = !set_info_window_once">
                <span v-if="set_info_window_once">
                    TOGGLE MULTIPLE INFOWINDOW
                </span>
                <span v-else>
                    TOGGLE ONCE INFOWINDOW
                </span>
            </button>
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
            "is_select_map", //Activate to click single mark

            //For Custom View
            "target_id", 
            "target_name", 
            "group_id",

            //
            "is_select_paths", //Active to select multi paths
            "select_source",
            "select_target",
            "action_saving_paths",

            "info_window_once" //Open info and closes the previously opened.
        ],
        $emits:["selected_location", "clicked_marker", "clicked_path"],
        watch: { 
            is_select_map:function(newVal){
                this.is_select_map = newVal;
            },
            select_source:function(newVal){
                var vm = this;
                vm.series_paths.source = newVal;
                vm.series_paths.target = null;
                if(vm.series_paths.lineEl){
                    vm.clearLineElement(vm.series_paths.lineEl);
                    vm.series_paths.lineEl = null;
                }
                if(vm.series_paths.previewLineEl){
                    vm.clearLineElement(vm.series_paths.previewLineEl);
                    vm.series_paths.previewLineEl = null;
                }
                vm.is_start_select_paths = false;

                vm.clearSelectedPaths();

                vm.selectedCentered(newVal);
            },
            select_target:function(newVal){
                var vm = this;
                vm.series_paths.target = newVal;
                
                if(vm.series_paths.lineEl){
                    vm.clearLineElement(vm.series_paths.lineEl);
                    vm.series_paths.lineEl = null;
                }
                if(vm.series_paths.previewLineEl){
                    vm.clearLineElement(vm.series_paths.previewLineEl);
                    vm.series_paths.previewLineEl = null;
                }
                vm.is_start_select_paths = false;

                vm.clearSelectedPaths();
                
                vm.selectedCentered(newVal);
            }


        },
        components: {
            "web-submit":WebSubmitVue
        },
        data: function () {
            return {
                set_info_window_once: this.info_window_once !== false ? true :false,
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
                    coordinates:[],
                    lineEl:null,
                    previewLineEl:null
                },

                is_start_select_paths:false,
                currentInfoWindow:null
            }
        },
        methods: { 

            save_paths:function(){
                var vm = this;
                if(this.action_saving_paths){
                    
                   return this.action_saving_paths(this.series_paths);
                
                }

            },

            clearLineElement:function(lineEl){
                var vm = this;
                lineEl.path.setMap(null);
                lineEl.path = null;
                if(lineEl.interval_callback){
                    clearInterval( lineEl.interval_callback );
                    lineEl.interval_callback = null;
                }
                vm.paths = vm.paths.filter(a=> a != lineEl);
                if(lineEl.marker){
                    lineEl.marker.setMap(null);
                    lineEl.marker = null;
                }
            },

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
                
                if(vm.is_select_map || vm.is_select_paths){                    
                    vm.map.addListener("click", (e) => {

                        if(vm.is_select_paths){
                            if(vm.is_start_select_paths){
                                vm.clickStartSelectPaths(e);
                            }
                            return;
                        }

                        if(!vm.is_select_map) return;

                        var location = {
                           latitude: e.latLng.lat(),
                           longitude: e.latLng.lng()
                        }
                        vm.$emit('selected_location', location);
                        vm.placeMarker(location);
                    });
                }

                //MOUSE OVER
                let lastUpdate = 0;
                let prepareReadyInterval = null;

                vm.map.addListener("mousemove", (e) => {
                    
                    if(!vm.series_paths.previewLineEl) return;

                    var previewLine = vm.series_paths.previewLineEl.path
                    if(vm.is_start_select_paths && vm.is_select_paths  && previewLine){
                        const now = Date.now();
                        if (now - lastUpdate > 100) { // update every 30ms
                            //previewLine.setPath([pointA, e.latLng.toJSON(), pointB]);
                            lastUpdate = now;
                            //console.log("Trigger", lastUpdate);
                            if(prepareReadyInterval !== null){
                                clearInterval(prepareReadyInterval);
                                prepareReadyInterval = null;
                            }

                            prepareReadyInterval = setTimeout(()=>{
                                //console.log("Passed");
                                vm.setPathsPreviewLine(e, previewLine);
                                clearInterval(prepareReadyInterval);
                                prepareReadyInterval = null;  
                            }, 200);
                            //console.log("Trigger", lastUpdate);
                            //vm.setPathsPreviewLine(previewLine);

                        }
                    }
                });

                //
                vm.map.getDiv().addEventListener("keydown", function (event) {
                    
                    if (event.key === "Enter") {
                        //console.log("Enter key pressed on the map!");
                        vm.doneSelecting();
                    }
                    else if (event.key === 'Escape') {
                        vm.undoSelectedPath();
                    }
                });

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
            //MAP ELEMENT TRIGGERS LIKE CLICK, ONLOAD, ETC...
            mapElementTrigger:function(mapEl, action){
                google.maps.event.trigger(mapEl, action);
            },
            // Recenter function
            recenterMap:function(lat, lng, zoom=null, is_animate = false) {
                var vm = this;
                let Resolve = null;
                let promise = new Promise((resolve)=>{
                    Resolve = resolve;
                });
                setTimeout(()=>{

                    const newCenter = new google.maps.LatLng(lat, lng);
                    
                    if(is_animate === false){
                        vm.map.setCenter(newCenter);
                        Resolve({status:"completed"});
                    }
                    else{
                        vm.smoothPanTo({ latitude: lat, longitude: lng }).then(data=>{
                            if(zoom){
                                vm.map.setZoom(zoom);
                                Resolve({status:"completed"});
                            }else{
                                Resolve({status:"completed"});
                            }
                        });
                    }
                }, 300);
                return promise;
            },

            smoothPanTo:function(location, duration = 1000) {
                var vm = this;
                const startLatLng = vm.map.getCenter();
                const startTime = performance.now();
                let Resolve = null;
                let promise = new Promise((resolve)=>{
                    Resolve = resolve;
                });

                function animate(time) {
                    const elapsed = time - startTime;
                    const t = Math.min(1, elapsed / duration); // progress from 0 to 1
                    const easedT = t * (2 - t); // ease-out

                    const lat = startLatLng.lat() + ( ( location.latitude * 1 )- startLatLng.lat()) * easedT;
                    const lng = startLatLng.lng() + ( ( location.longitude * 1 ) - startLatLng.lng()) * easedT;

                    vm.map.setCenter({ lat, lng });

                    if (t < 1) {
                       requestAnimationFrame(animate);
                    }
                    else{
                        //console.log("animation completed");
                        Resolve({status:"completed"});
                    }
                }

                requestAnimationFrame(animate);
                return promise;
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

                    if( infoWindow && vm.set_info_window_once && vm.currentInfoWindow){
                        if(vm.currentInfoWindow != infoWindow){
                            vm.currentInfoWindow.close();
                        }
                    }

                    if(infoWindow){
                        infoWindow.open(vm.map, marker);
                        if(vm.currentInfoWindow != infoWindow)
                            vm.currentInfoWindow = infoWindow;
                    }

                    vm.$emit('clicked_marker', marker, dataInfo, location, evt);

                });

                vm.markers.push(marker);
                return marker;
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
                    p.path = null;
                    if(p.interval_callback !== null){
                        window.clearInterval(p.interval_callback);
                        p.interval_callback = null;
                    }
                    if(p.marker){
                        p.marker.setMap(null);
                        p.marker = null;
                    }
                });
                vm.paths = [];
            },

            loadCoordinates(coordinates=[{latitude: 10.3157, longitude: 123.8854}], set_marker=true){
                return this.initMap(coordinates, set_marker);
            },

            createPath:function(coordinates, hex_color="#FF0000", is_dash=false, is_moving=false, speed=250, htmlContent = null, isClickable = false){
                var vm = this;
                var coordinatePoints = [];
                if(Array.isArray(coordinates)){
                    coordinatePoints = coordinates;
                }
                else if(typeof coordinates === "object" && coordinates !== null){
                    const pointA = { lat: coordinates.from.latitude * 1, lng: coordinates.from.longitude * 1 }; 
                    const pointB = { lat: coordinates.to.latitude * 1, lng: coordinates.to.longitude * 1 }; 
                    coordinatePoints = [pointA, pointB];
                }
                
                
                var line = null;
                var pathData = null;
                
                //ONLY LINE
                if(is_dash === false){
                    line = new google.maps.Polyline({
                        path: coordinatePoints, // Points in order
                        geodesic: true,         // Makes the line follow the curve of the Earth
                        strokeColor: hex_color, // Line color
                        strokeOpacity: 1.0,     // Fully visible
                        strokeWeight: 3,         // Thickness
                        clickable: isClickable 
                    });

                    line.setMap(vm.map);

                   pathData = {
                        path:line,
                        default_stroke_weight:3,
                        data:coordinates,
                        is_dash:false,
                        interval_callback:null,
                        marker:null,
                        setColor:function(color){
                            line.setOptions({ strokeColor: color });
                        },
                        setMarker:function(icon_class, infoHTML){
                            if(pathData.marker){
                                pathData.marker.setMap(null);
                                pathData.marker = null;
                            }

                            let midPoint = vm.getPolylineMidpoint(line);
                            const faMarker = new google.maps.marker.AdvancedMarkerElement({
                                map: vm.map,
                                position: {
                                    lat: midPoint.latitude,
                                    lng: midPoint.longitude
                                },
                                content: document.createElement("div")
                            });
                            faMarker.content.innerHTML = `<i class="${icon_class}" style="font-size:24px;"></i>`;
                            pathData.marker = faMarker;


                            //INFO WINDOW
                            if(infoHTML){
                                let infoWindow = new google.maps.InfoWindow({
                                    content: infoHTML
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
                                pathData.marker.addListener("click", (evt) => {

                                    if( infoWindow && vm.set_info_window_once && vm.currentInfoWindow){
                                        if(vm.currentInfoWindow != infoWindow){
                                            vm.currentInfoWindow.close();
                                        }
                                    }

                                    if(infoWindow){ 
                                        infoWindow.open(vm.map, pathData.marker);
                                        if(vm.currentInfoWindow != infoWindow)
                                            vm.currentInfoWindow = infoWindow;
                                    }
                                });
                            }
                        },
                        setStrokeWeight:function(strokeWeight){
                            line.setOptions({ strokeWeight: strokeWeight });
                        }
                        
                    };

                    vm.paths.push(pathData);

                }
                else{

                    // Create a dashed polyline either moving or not.
                    line = new google.maps.Polyline({
                        path: coordinatePoints,
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
                        map: vm.map,
                        clickable: isClickable 
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
                        default_stroke_weight:2,
                        data:coordinates,
                        is_dash:true,
                        interval_callback:interval_callback,
                        marker:null,
                        setColor:function(color){
                            let icons = line.get("icons");
                            icons[0].icon.strokeColor = color; 
                            // force redraw without recreating the whole polyline
                            line.set("icons", icons);
                        },
                        setStrokeWeight:function(strokeWeight){
                            let icons = line.get("icons");
                            icons[0].icon.scale = strokeWeight; 
                            // force redraw without recreating the whole polyline
                            line.set("icons", icons);
                        },
                        setMarker:function(icon_class, infoHTML=null){
                            if(pathData.marker){
                                pathData.marker.setMap(null);
                                pathData.marker = null;
                            }
                            let midPoint = vm.getPolylineMidpoint(line);
                            const faMarker = new google.maps.marker.AdvancedMarkerElement({
                                map: vm.map,
                                position: {
                                    lat: midPoint.latitude,
                                    lng: midPoint.longitude
                                },
                                content: document.createElement("div")
                            });
                            faMarker.content.innerHTML = `<i class="${icon_class}" style="font-size:20px;"></i>`;
                            pathData.marker = faMarker;
                            
                            //INFO WINDOW
                            if(infoHTML && infoHTML != null){
                                let infoWindow = new google.maps.InfoWindow({
                                    content: infoHTML
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
                                pathData.marker.addListener("click", (evt) => {
                                    if( infoWindow && vm.set_info_window_once && vm.currentInfoWindow){
                                        if(vm.currentInfoWindow != infoWindow){
                                            vm.currentInfoWindow.close();
                                        }
                                    }

                                    if(infoWindow){
                                        infoWindow.open(vm.map, pathData.marker);
                                        if(vm.currentInfoWindow != infoWindow)
                                            vm.currentInfoWindow = infoWindow;
                                    }
                                });
                            }
                        }
                    };

                    vm.paths.push(pathData);

                }

                line.addListener("click", (event) => {
                    vm.$emit('clicked_path', pathData );
                });

                
                // Mouse over
                line.addListener("mouseover", () => {
                    //polyline.setOptions({ strokeColor: "#00FF00", strokeWeight: 4 }); // highlight
                    //console.log("Mouse over");
                    pathData.setColor('#0000FF');
                    if(pathData.is_dash){
                        pathData.setStrokeWeight(3);
                    }
                    else{
                        pathData.setStrokeWeight(4);
                    }
                });

                // Mouse leave
                line.addListener("mouseout", () => {
                    //polyline.setOptions({ strokeColor: "#FF0000", strokeWeight: 2 }); // restore
                    pathData.setColor(pathData.default_color);
                    pathData.setStrokeWeight(pathData.default_stroke_weight);
                });

                pathData.default_color = hex_color;

                ///return line;
                return pathData;
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
            },

            getPolylineMidpoint:function(polyline) {
                const path = polyline.getPath().getArray();
                let totalDist = 0;

                // compute total length
                for (let i = 0; i < path.length - 1; i++) {
                    totalDist += google.maps.geometry.spherical.computeDistanceBetween(path[i], path[i+1]);
                }

                let halfDist = totalDist / 2;
                let travelled = 0;

                for (let i = 0; i < path.length - 1; i++) {
                    const segDist = google.maps.geometry.spherical.computeDistanceBetween(path[i], path[i+1]);

                    if (travelled + segDist >= halfDist) {
                        const ratio = (halfDist - travelled) / segDist;
                        const lat = path[i].lat() + (path[i+1].lat() - path[i].lat()) * ratio;
                        const lng = path[i].lng() + (path[i+1].lng() - path[i].lng()) * ratio;
                        //return new google.maps.LatLng(lat, lng);
                        return {
                            latitude: lat,
                            longitude: lng
                        };
                    }

                    travelled += segDist;
                }

                return {
                    latitude: path[0].lat,
                    longitude: path[0].lng
                };// path[0]; // fallback
            },

            /** PATH SELECTION HERE.. */
            clickStartSelectPaths:function(e){
                
                //console.log(e);
                var vm = this;

                if( vm.is_start_select_paths && vm.series_paths.source && vm.series_paths.target){
                    vm.series_paths.coordinates.push({
                        lat: e.latLng.lat(),
                        lng: e.latLng.lng()
                    });
                    //console.log(e);
                    vm.setSelectedPaths();

                }
            },
            setSelectedPaths(){
                var vm = this;
                var pointA = vm.series_paths.source;
                var pointB = vm.series_paths.target;

                pointA = {
                    lat: pointA.latitude,
                    lng: pointA.longitude
                }
                pointB = {
                    lat: pointB.latitude,
                    lng: pointB.longitude
                }

                let collect = [];
                
                collect.push(pointA);
                
                vm.series_paths.coordinates.forEach(line=>{
                    collect.push(line);
                });
                
                collect.push(pointB);

                vm.series_paths.lineEl.path.setPath(collect);

            },
            clearSelectedPaths:function(){

                this.series_paths.coordinates = [];

                //TODO:: reset path of two sources.. to the solid single line 

            },
            selectedCentered:function(val){
                var vm = this;
                setTimeout(()=>{
                    if(val){
                        if(vm.series_paths.source && vm.series_paths.target){
                            //TODO: center in between
                            var pointA = vm.series_paths.source;
                            var pointB = vm.series_paths.target;
                            
                            const bounds = new google.maps.LatLngBounds();
                            bounds.extend({
                                lat: pointA.latitude,
                                lng: pointA.longitude
                            });
                            bounds.extend({
                                lat: pointB.latitude,
                                lng: pointB.longitude
                            });

                            // Fit map to bounds
                            vm.map.fitBounds(bounds);

                            if(this.series_paths.lineEl == null){
                                this.series_paths.lineEl = vm.createPath( { from: pointA, to: pointB }, "#0000FF", true, true, 250,  null, false);
                            }

                            if(this.series_paths.previewLineEl == null){
                                this.series_paths.previewLineEl = vm.createPath( { from: pointA, to: pointB }, "#008000", true, true, 250, null, false);
                            }

                            /*
                            const centerPoint = {
                                latitude: ( (pointA.latitude * 1) + (pointB.latitude * 1 ) ) / 2,
                                longitude: ( ( pointA.longitude * 1) + ( pointB.longitude * 1) ) / 2
                            };
                            vm.recenterMap(centerPoint.latitude, centerPoint.longitude, null, true);
                            */

                        }
                        else {
                            //TODO: center the current
                            vm.recenterMap(val.latitude, val.longitude, 20, true);
                        }
                    }
                }, 50);
            },
            setPathsPreviewLine:function(e,previewLine, onlyAB = false){

                var vm = this;

                var pointA = vm.series_paths.source;
                var pointB = vm.series_paths.target;

                pointA = {
                    lat: pointA.latitude,
                    lng: pointA.longitude
                }
                pointB = {
                    lat: pointB.latitude,
                    lng: pointB.longitude
                }

                if(vm.series_paths.coordinates.length > 0){
                    pointA = vm.series_paths.coordinates[vm.series_paths.coordinates.length - 1];
                }

                if(!pointA) return;
                if(!pointB) return;

                if(onlyAB){
                    previewLine.setPath([pointA, pointB]);
                    return;
                }

                 previewLine.setPath([pointA, e.latLng.toJSON(), pointB]);

            },
            cancelSelectPaths:function(){
                var vm = this;
                vm.series_paths.coordinates = [];
                vm.setSelectedPaths();
                vm.setPathsPreviewLine(null, vm.series_paths.previewLineEl.path, true);

            },
            undoSelectedPath:function(){
                var vm = this;
                vm.series_paths.coordinates.pop();
                vm.setSelectedPaths();
                vm.setPathsPreviewLine(null, vm.series_paths.previewLineEl.path, true);

            },
            doneSelecting:function(){
                var vm = this;
                if(vm.is_start_select_paths && vm.is_select_paths  && vm.series_paths.previewLineEl){
                    vm.is_start_select_paths = false;
                    vm.setPathsPreviewLine(null, vm.series_paths.previewLineEl.path, true);
                }
            }



        },
        mounted:function(){
        },
        updated:function(){

        }
    }
</script>
