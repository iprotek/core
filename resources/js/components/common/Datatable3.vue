<template>
    <table :id="table_name" >
        <thead>
            <tr>
                <th>#</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody> 
        </tbody>
        <tfoot>
            <tr >
                <th>#</th>
                <th>Status</th>
            </tr>
        </tfoot>
    </table> 
</template>
<script>

    export default {
        props:[ ],
        components: { 
        },
        data: function () {
            return {   
                table_name:""
            }
        },
        methods: {
            show: function () {
               // this.$refs.modal.show();
            }, 
            save: function () { 
            },
            load_languages(){ 
                //console.log(vm.languages);
            }
        },
        mounted:function(){   
            this.table_name = "datatable-"+this._uuid;
            /**
             *  
            ajax: function ( data, callback, settings ) {
                $.ajax({
                    url: "ajax_url?" + $("my-form").serialize(),
                    data: { start: data.start, count: data.length }
                });
            },
             * 
             */

            //SUCCEED
            /*
             $('#mytable').DataTable({
                ajax:  "/ticket-requests/get-tickets",
                columns:[
                    { data: 'account_id' },
                    { data: 'account_no' },
                ]
            });

           return;
            let table1 = new Datatable('#mytable',{
                //Sample data format for json
                //ajax:"/ticket-requests/get-tickets",
                //Sample columns
                ajax: function ( data, callback, settings ) {
                    return $.ajax({
                        url: "/ticket-requests/get-tickets",
                        data: { start: 0, count: 1 }
                    }).then(res=>{
                        console.log(res);
                        //this.data = res;
                    });

                },
                columns:[
                    { data: 'account_id' },
                    { data: 'account_no' },
                ]
            });

            */
        $.fn.dataTable.pipeline = function (opts) {
            // Configuration options
            var conf = $.extend(
                {
                    pages: 5, // number of pages to cache
                    url: '', // script url
                    data: null, // function or object with parameters to send to the server
                    // matching how `ajax.data` works in DataTables
                    method: 'GET', // Ajax HTTP method
                },
                opts
            );
        
            // Private variables for storing the cache
            var cacheLower = -1;
            var cacheUpper = null;
            var cacheLastRequest = null;
            var cacheLastJson = null;
        
            return function (request, drawCallback, settings) {
                var ajax = false;
                var requestStart = request.start;
                var drawStart = request.start;
                var requestLength = request.length;
                var requestEnd = requestStart + requestLength;
        
                if (settings.clearCache) {
                    // API requested that the cache be cleared
                    ajax = true;
                    settings.clearCache = false;
                } else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
                    // outside cached data - need to make a request
                    ajax = true;
                } else if (
                    JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
                    JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
                    JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
                ) {
                    // properties changed (ordering, columns, searching)
                    ajax = true;
                }
        
                // Store the request for checking next time around
                cacheLastRequest = $.extend(true, {}, request);
        
                if (ajax) {
                    // Need data from the server
                    if (requestStart < cacheLower) {
                        requestStart = requestStart - requestLength * (conf.pages - 1);
        
                        if (requestStart < 0) {
                            requestStart = 0;
                        }
                    }
        
                    cacheLower = requestStart;
                    cacheUpper = requestStart + requestLength * conf.pages;
        
                    request.start = requestStart;
                    request.length = requestLength * conf.pages;
        
                    // Provide the same `data` options as DataTables.
                    if (typeof conf.data === 'function') {
                        // As a function it is executed with the data object as an arg
                        // for manipulation. If an object is returned, it is used as the
                        // data object to submit
                        var d = conf.data(request);
                        if (d) {
                            $.extend(request, d);
                        }
                    } else if ($.isPlainObject(conf.data)) {
                        // As an object, the data given extends the default
                        $.extend(request, conf.data);
                    }
        
                    return $.ajax({
                        type: conf.method,
                        url: conf.url,
                        data: request,
                        dataType: 'json',
                        cache: false,
                        success: function (json) {
                            cacheLastJson = $.extend(true, {}, json);
        
                            if (cacheLower != drawStart) {
                                json.data.splice(0, drawStart - cacheLower);
                            }
                            if (requestLength >= -1) {
                                json.data.splice(requestLength, json.data.length);
                            }
        
                            drawCallback(json);
                        },
                    });
                } else {
                    json = $.extend(true, {}, cacheLastJson);
                    json.draw = request.draw; // Update the echo for each response
                    json.data.splice(0, requestStart - cacheLower);
                    json.data.splice(requestLength, json.data.length);
        
                    drawCallback(json);
                }
            };
        };
        
        // Register an API method that will empty the pipelined data, forcing an Ajax
        // fetch on the next draw (i.e. `table.clearPipeline().draw()`)
        $.fn.dataTable.Api.register('clearPipeline()', function () {
            return this.iterator('table', function (settings) {
                settings.clearCache = true;
            });
        });


        //
        // DataTables initialisation
        //
        $('#'+this.table_name).DataTable({
            //Sample data format for json
            //ajax:"/ticket-requests/get-tickets",
            //Sample columns
            processing:true,
            serverSide:true,
            ajax: $.fn.dataTable.pipeline({
                url: "/ticket-requests/get-tickets",
                pages: 5, // number of pages to cache
            }),
            columns:[
                { data: 'account_id' },
                { data: 'account_no' },
            ]
        });



        }
    }

</script>