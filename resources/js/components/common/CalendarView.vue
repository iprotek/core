<template>    
    <div class="card card-primary p-0">
        <div class="card-body p-0">
            <div :id="calendarId"></div>
        </div>
    </div>
</template>

<script>
     export default {
        props:["allow_drag_event", "allow_add_event", "allow_select_previous"],
        components: {  
        },
        data: function () {
            return {  
                calendar:null,
                calendarId: 'calendar'+this._uid
            }
        },
        methods: { 
            initCalendar:function(gotoDate = null){

                var vm = this;

                /* initialize the calendar
                -----------------------------------------------------------------*/
                //Date for the calendar events (dummy data)
                var date = new Date()
                var d    = date.getDate(),
                    m    = date.getMonth(),
                    y    = date.getFullYear() 
 
                var calendarEl = document.getElementById(vm.calendarId);
                    
                var updateEvents = function(_calendar ,newEvents=[]) {
                    // Clear existing event sources
                    newEvents.push({
                        title          : 'Today',
                        start          : new Date(y, m, d),
                        backgroundColor: 'rgb(70, 123, 6)', //red
                        borderColor    : 'rgb(70, 123, 6)', //red
                        allDay         : true
                        }
                    );

                    _calendar.removeAllEventSources();

                    // Add the new events as a new event source
                    _calendar.addEventSource(newEvents);

                    // Refetch and render the events
                    _calendar.refetchEvents();
                }
                var updateMonthView = function(){
                        //CALENDAR PAST SETTINGS
                        document.querySelectorAll('.fc .fc-day-past .fc-daygrid-day-number').forEach(el=>{
                            el.style.color = 'red';
                        });

                        //CALENDAR TODAY SETTINGS
                        document.querySelectorAll('.fc .fc-day-today .fc-daygrid-day-number').forEach(el=>{
                            if(!el.hasToday){
                                el.hasToday = true;
                                el.prepend('Today ');
                            }
                            el.style.color = 'green';
                        });

                        //CALENDAR FUTURE SETTINGS
                        document.querySelectorAll('.fc .fc-day-future .fc-daygrid-day-number').forEach(el=>{
                            el.style.color = 'blue';
                        });

                }
                var currentMonth = null;
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        //left  : 'prev,next today',
                        left: 'prev,next',
                        center: 'title',
                        right: ''
                        //right : 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    themeSystem: 'bootstrap',
                    //Random default events
                    events: [ /*
                        {
                        title          : 'All Day Event',
                        start          : new Date(y, m, 1),
                        backgroundColor: '#f56954', //red
                        borderColor    : '#f56954', //red
                        allDay         : true
                        },
                        {
                        title          : 'Long Event',
                        start          : new Date(y, m, d - 5),
                        end            : new Date(y, m, d - 2),
                        backgroundColor: '#f39c12', //yellow
                        borderColor    : '#f39c12' //yellow
                        },
                        {
                        title          : 'Meeting',
                        start          : new Date(y, m, d, 10, 30),
                        allDay         : false,
                        backgroundColor: '#0073b7', //Blue
                        borderColor    : '#0073b7' //Blue
                        },
                        {
                        title          : 'Lunch',
                        start          : new Date(y, m, d, 12, 0),
                        end            : new Date(y, m, d, 14, 0),
                        allDay         : false,
                        backgroundColor: '#00c0ef', //Info (aqua)
                        borderColor    : '#00c0ef' //Info (aqua)
                        },
                        {
                        title          : 'Birthday Party',
                        start          : new Date(y, m, d + 1, 19, 0),
                        end            : new Date(y, m, d + 1, 22, 30),
                        allDay         : false,
                        backgroundColor: '#00a65a', //Success (green)
                        borderColor    : '#00a65a' //Success (green)
                        },
                        {
                        title          : 'Click for Google',
                        start          : new Date(y, m, 28),
                        end            : new Date(y, m, 29),
                        url            : 'https://www.google.com/',
                        backgroundColor: '#3c8dbc', //Primary (light-blue)
                        borderColor    : '#3c8dbc' //Primary (light-blue)
                        }*/
                    ],
                    editable  : false, //allowing users to update
                    droppable : false, // this allows things to be dropped onto the calendar !!!
                    drop      : function(info) {
                        if(vm.allow_drag_event){
                            // is the "remove after drop" checkbox checked?
                            if (checkbox.checked) {
                            // if so, remove the element from the "Draggable Events" list
                            info.draggedEl.parentNode.removeChild(info.draggedEl);
                            }
                        }
                    },
                    //SELECTION
                    selectable: true,
                    select: function(daterange, param2, jsEvent, view) {
                        // start contains the date you have selected
                        // end contains the end date. 
                        // Caution: the end date is exclusive (new since v2).
                        //console.log(start, end, view);
                        setTimeout(()=>{
                            $(".fc-highlight").css("background", "#95d295")
                        }, 100);

                        var end = daterange.end;
                        if(end){
                            end.setSeconds(end.getSeconds() - 1);
                        }
                        
                        vm.$emit('select', calendar, daterange, param2, jsEvent, view);
                        /*
                        var allDay = !start.hasTime() && !end.hasTime();
                        console.log(["Event Start date: " + moment(start).format(),
                                "Event End date: " + moment(end).format(),
                                "AllDay: " + allDay].join("\n"));
                        */
                    }, 
                    selectAllow: function(selectInfo) {
                        if(vm.allow_select_previous !== false)
                            return true;
                        var now = new Date();
                        var start = selectInfo.start;
                        if(start.getFullYear() == now.getFullYear() && start.getMonth() == now.getMonth() && start.getDate() == now.getDate()){
                            return true;
                        }
                        return moment().diff(selectInfo.start) <= 0; // Disable past dates for selection
                    },
                    viewDidMount:function(info) {
                        //console.log(info);
                        updateMonthView();
                    },
                    dateClick: function(info) {
                        var clickedMonth = info.date.getMonth();

                        if (clickedMonth !== currentMonth) {
                            currentMonth = clickedMonth;

                            // Trigger your custom event here
                            // Pass the clicked month or any other relevant information
                            //$(document).trigger('monthChanged', clickedMonth);
                            updateMonthView();
                            //updateEvents(calendar);
                            vm.$emit('dateClick', calendar, info);

                        }
                    },
                    datesSet:function(view) {
                        //console.log(view);return;
                        var displayedMonth = view.start.getMonth();
                        if (displayedMonth !== currentMonth) {
                            currentMonth = displayedMonth;

                            // Trigger your custom event here
                            // Pass the displayed month or any other relevant information
                            //$(document).trigger('monthChanged', displayedMonth);
                            updateMonthView();
                            //updateEvents(calendar); //samples
                            setTimeout(()=>{
                            vm.$emit('datesSet', calendar, view);
                            }, 100);
                        }
                    },
                    eventClick: function(event, jsEvent, view) {
                        // Your function to handle the event click
                        //alert('Event clicked: ' + event.title);
                        //console.log(event);
                        vm.$emit("selectedEvent", event);
                    }
                });
                calendar.render();
                vm.calendar = calendar;
                if(gotoDate){
                    calendar.gotoDate(gotoDate);
                    calendar.select(gotoDate);
                }
                else{
                    calendar.select(new Date());
                }
                //Select the current date 
                // $('#calendar').fullCalendar() 
            },
            updateEvents:function(newEvents=[]){
                var vm = this;
                var _calendar = this.calendar;
                //console.log("CALENDAR",vm);
                //return;
                /*
                var date = new Date()
                var d    = date.getDate(),
                    m    = date.getMonth(),
                    y    = date.getFullYear() ;
                
                // Clear existing event sources
                newEvents.push({
                    title          : 'Today',
                    start          : new Date(y, m, d),
                    //backgroundColor: 'rgb(70, 123, 6)',
                    //borderColor    : 'rgb(70, 123, 6)',
                    allDay         : true,
                    color: 'success'
                    }
                );
                */
                
                newEvents.forEach((ev)=>{
                    if(ev.color == 'primary'){
                        ev.backgroundColor = '#0073b7';
                        ev.borderColor = '#0073b7';
                    }
                    else if(ev.color == 'danger'){
                        ev.backgroundColor = '#f56954';
                        ev.borderColor = '#f56954';
                    } 
                    else if(ev.color == 'warning'){
                        ev.backgroundColor = '#f39c12';
                        ev.borderColor = '#f39c12';
                    }
                    else if(ev.color == 'info'){
                        ev.backgroundColor = '#00c0ef';
                        ev.borderColor = '#00c0ef';
                    }   
                    else if(ev.color == 'success'){
                        ev.backgroundColor = '#00a65a';
                        ev.borderColor = '#00a65a';
                    }
                    else if(ev.color == 'secondary' || ev.color == 'muted'){
                        ev.backgroundColor = '#6c757d';
                        ev.borderColor = '#6c757d';
                    }
                    else{
                        ev.backgroundColor = "white";
                        ev.borderColor = "black";
                        ev.textColor = "black";
                    }
                       // backgroundColor: '#3c8dbc', //Primary (light-blue)
                       // borderColor    : '#3c8dbc' //Primary (light-blue)

                });


                _calendar.removeAllEventSources();

                // Add the new events as a new event source
                _calendar.addEventSource(newEvents);

                // Refetch and render the events
                _calendar.refetchEvents();

            }
        },
        mounted:function(){
            this.initCalendar();       
        },
        updated:function(){ 
            this.initCalendar();
        }
    }
</script>
