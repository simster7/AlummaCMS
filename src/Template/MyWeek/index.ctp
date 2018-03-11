<!-- <link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
<script src='../js/lib/jquery.min.js'></script>
<script src='../js/lib/moment.min.js'></script>
<script src='../js/fullcalendar.js'></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<link href='../css/fullcalendar.min.css' rel='stylesheet' />
<link href='../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/moment.min.js'></script>
<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery.min.js'></script>
<script src="http://fullcalendar.io/js/fullcalendar-2.1.1/lib/jquery-ui.custom.min.js"></script>
<script src='http://fullcalendar.io/js/fullcalendar-2.1.1/fullcalendar.min.js'></script> -->

<link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
<script src='../js/lib/jquery.min.js'></script>
<script src='../js/lib/jquery-ui.min.js'></script>
<script src='../js/lib/moment.min.js'></script>
<script src="../js/lib/select2.min.js"></script>
<script src='../js/fullcalendar.js'></script>
<link href='../css/fullcalendar.min.css' rel='stylesheet' />
<link href='../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<link href="../css/select2.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {

                $('.js-example-basic-single').select2({dropdownParent: $("#searchPatient"), width: "400px"});
                // Manage status of dragging event and calendar
                var calEventStatus = [];


                /* Required functions */

                var isEventOverDiv = function(x, y) {

                    var external_events = $( '#external-events' );
                    var offset = external_events.offset();
                    offset.right = external_events.width() + offset.left;
                    offset.bottom = external_events.height() + offset.top;

                    // Compare
                    if (x >= offset.left
                        && y >= offset.top
                        && x <= offset.right
                        && y <= offset .bottom) { return true; }
                    return false;

                }


                function makeEventsDraggable () { 
                   
                    $('.fc-draggable').each(function() {
                        // store data so the calendar knows to render an event upon drop
                        // $(this).data(
                        //     'event', {
                        //     title: "New Session", // use the element's text as the event title
                        //     stick: true // maintain when user navigates (see docs on the renderEvent method)
                        // }
                        // );

                        // make the event draggable using jQuery UI
                        $(this).draggable({
                            zIndex: 999,
                            revert: true,      // will cause the event to go back to its
                            revertDuration: 0  //  original position after the drag
                        });

                        console.log('makeEventsDraggable');

                        // Dirty fix to remove highlighted blue background
                        $("td").removeClass("fc-highlight");

                    });

                }

                function updateSessionStatus (sessionId, value) {
                    var values = "SessionID=" + sessionId + "&show=" + value;
                    $.ajax({
                        url: '/MyWeek/showOrNoShow',
                        type: 'POST',
                        data: values,
                        success: function(data) {
                            alert(data);
                        }
                    });
                }

                function scheduleSession (patientId, datetime, patientName) {
                    var values = "PatientID=" + patientId + "&datetime=" + datetime;
                    $.ajax({
                        url: '/MyWeek/schedule',
                        type: 'POST',
                        data: values,
                        success: function(data) {
                            if (data === 'success' || data[0] === '%') {
                                if (data[0] === '%') {
                                    alert(data.slice(1));
                                    patientName = '[TENTATIVE] ' + patientName;
                                }
                                var end = new Date(datetime);
                                end.setMinutes(end.getMinutes() + 45 - end.getTimezoneOffset());
                                $('#week_calendar').fullCalendar('renderEvent',
                                {
                                    title: patientName,
                                    start: datetime,
                                    end: end.toJSON()
                                }, 'stick');
                            } else {
                                alert(data);
                            }
                        }
                    });

                }

                function confirmAndSchedule (patientId, datetime, patientName) {
                    if (confirm("Schedule session for " + patientName + " at " + datetime.toLocaleString() + "?")) {
                        scheduleSession(patientId, datetime.toJSON(), patientName);
                    }
                }


                $('#week_calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'agendaWeek, agendaDay, listWeek'
                    },
                    editable: false,
                    slotDuration: '00:15:00', 
                    minTime: '08:00:00',
                    maxTime: '21:00:00',
                    zIndex: 0,
                    droppable: false, // this allows things to be dropped onto the calendar
                    dragRevertDuration: 0,
                    eventLimit: true, // allow "more" link when too many events
                    defaultView: 'agendaWeek',
                    events: <?php echo $events ?>,
                    timezone: 'America/Los_Angeles',
                    height: 750,
                    eventRender: function (event, element) {
                        element.attr('href', 'javascript:void(0);');

                        element.click(function() {
                            $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                            $("#patName").html(event.name);
                            $("#linkE").attr("href", "/patients/view/" + event.PatientDBID);
                            $("#sessLeft").html(event.sessionsLeft);
                            $("#eventInfo").html(event.description);
                            $("#show").off("click").click(function(){
                                updateSessionStatus(event.SessionID, true);
                            });
                            $("#noShow").off("click").click(function(){
                                updateSessionStatus(event.SessionID, false);
                            });
                            $("#schedEasy").off("click").click(function(){
                                var new_date = moment(event.start).add(7, 'days');
                                confirmAndSchedule(event.PatientID, new_date, event.name);
                            });
                            if (event.sessionsLeft < 1) {
                                $("#schedEasy").attr('disabled','disabled');
                            } else {
                                $("#schedEasy").removeAttr('disabled');
                            }
                            if (event.status != 1) {
                                $("#show").attr('disabled','disabled');
                                $("#noShow").attr('disabled','disabled');
                            } else {
                                $("#show").removeAttr('disabled');
                                $("#noShow").removeAttr('disabled');
                            }
                            $("#eventContent").dialog({ modal: true});
                        });
                        //element.click(function() {
                        //    window.location = "/patients/view/" + event.PatientDBID;
                        //});
                    },
                    dayClick: function(date) {
                        $("#searchPatient").dialog({ modal: true});
                        $("#schedTime").off("click").click(function(){
                            var data = $("#selectPatient").select2('data');
                            var name = data[0].text.split(" ").slice(0, -2).join(" ");
                            var id = data[0].id;
                            confirmAndSchedule(id, date, name);
                        });
                        $("#sessionTime").html(moment(date).format('MMM Do h:mm A'));
                    },
                    drop: function(date, jsEvent, ui) { console.log('calendar 1 drop'); console.log(date); console.log(jsEvent); console.log(ui); console.log(this);
                        if (calEventStatus['type'] == 'new') {
                            confirmAndSchedule(calEventStatus['PatientID'], date, calEventStatus['name']);
                            makeEventsDraggable();
                        }
                    },
                    eventAllow: function(dropLocation, draggedEvent) {
                        return true;
                    },
                    eventReceive: function( event ) {  console.log('calendar 1 eventReceive');
                        makeEventsDraggable();
                    },
                    eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) {  console.log('calendar 1 eventDrop');
                        makeEventsDraggable();
                    },
                    eventDragStart: function( event, jsEvent, ui, view ) {console.log(event); console.log(jsEvent); console.log(ui); console.log(view);
                        calEventStatus['calendar'] = '#week_calendar';
                        calEventStatus['event_id'] = event._id;
                        console.log('calendar 1 eventDragStart');
                    },
                    eventDragStop: function( event, jsEvent, ui, view ) { console.log('calendar 1 eventDragStop');
                        
                        // if(isEventOverDiv(jsEvent.clientX, jsEvent.clientY)) {
                        //     $('#week_calendar').fullCalendar('removeEvents', event._id);
                        //     var el = $( "<div class='fc-event'>" ).appendTo( '#external-events-listing' ).text( event.title );
                        //     el.draggable({
                        //       zIndex: 999,
                        //       revert: true, 
                        //       revertDuration: 0 
                        //     });
                        //     el.data('event', { title: event.title, id :event.id, stick: true });
                        // }

                        calEventStatus = []; // Empty
                        makeEventsDraggable();
                    },
                    eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
                        makeEventsDraggable();
                    },
                    viewRender: function() { console.log('calendar 1 viewRender');
                        makeEventsDraggable();
                    },
                });

                $('#day_calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''
                    },
                    editable: false,
                    slotDuration: '00:15:00', 
                    minTime: '08:00:00',
                    maxTime: '21:00:00',
                    zIndex: 0,
                    droppable: false, // this allows things to be dropped onto the calendar
                    dragRevertDuration: 0,
                    eventLimit: true, // allow "more" link when too many events
                    defaultView: 'agendaDay',
                    events: <?php echo $events ?>,
                    timezone: 'America/Los_Angeles',
                    height: 750,
                    eventRender: function (event, element) {
                        element.attr('href', 'javascript:void(0);');
                        element.click(function() {
                            $("#startTime").html(moment(event.start).format('MMM Do h:mm A'));
                            $("#patName").html(event.name);
                            $("#linkE").attr("href", "/patients/view/" + event.PatientDBID);
                            $("#sessLeft").html(event.sessionsLeft);
                            $("#eventInfo").html(event.description);
                            $("#show").off("click").click(function(){
                                updateSessionStatus(event.SessionID, true);
                            });
                            $("#noShow").off("click").click(function(){
                                updateSessionStatus(event.SessionID, false);
                            });
                            $("#schedEasy").off("click").click(function(){
                                var new_date = moment(event.start).add(7, 'days');
                                confirmAndSchedule(event.PatientID, new_date, event.name);
                            });
                            if (event.sessionsLeft < 1) {
                                $("#schedEasy").attr('disabled','disabled');
                            } else {
                                $("#schedEasy").removeAttr('disabled');
                            }
                            if (event.status != 1) {
                                $("#show").attr('disabled','disabled');
                                $("#noShow").attr('disabled','disabled');
                            } else {
                                $("#show").removeAttr('disabled');
                                $("#noShow").removeAttr('disabled');
                            }
                            $("#eventContent").dialog({ modal: true});
                        });
                    },
                    drop: function(date, jsEvent, ui) { console.log('calendar 2 drop'); console.log(date); console.log(jsEvent); console.log(ui); console.log(this);
                        // is the "remove after drop" checkbox checked?
                        // if ($('#drop-remove').is(':checked')) {
                        //     // if so, remove the element from the "Draggable Events" list
                        //     $(this).remove();
                        // }

                        // if event dropped from another calendar, remove from that calendar
                        // if (typeof calEventStatus['calendar'] != 'undefined') {
                        //     $(calEventStatus['calendar']).fullCalendar('removeEvents', calEventStatus['event_id']);
                        //     //$(calEventStatus['calendar']).fullCalendar('unselect');
                        // }

                        makeEventsDraggable();
                    },
                    eventAllow: function(dropLocation, draggedEvent) {
                        return false;
                    },
                    eventReceive: function( event ) {  console.log('calendar 2 eventReceive');
                        makeEventsDraggable();
                    },
                    eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) {  console.log('calendar 2 eventDrop');
                        makeEventsDraggable();
                    },
                    eventDragStart: function( event, jsEvent, ui, view ) {console.log(event); console.log(jsEvent); console.log(ui); console.log(view);

                        // Add dragging event in global var 
                        calEventStatus['calendar'] = '#day_calendar';
                        calEventStatus['event_id'] = event._id;
                        calEventStatus['sessionsLeft'] = event.sessionsLeft;
                        calEventStatus['SessionID'] = event.SessionID;
                        calEventStatus['name'] = event.name;
                        calEventStatus['PatientID'] = event.PatientID;
                        calEventStatus['type'] = 'new';
                        console.log('calendar 2 eventDragStart');
                    },
                    eventDragStop: function( event, jsEvent, ui, view ) { console.log('calendar 2 eventDragStop');
                        makeEventsDraggable();
                    },
                    eventResize: function( event, delta, revertFunc, jsEvent, ui, view ) {
                        makeEventsDraggable();
                    },
                    viewRender: function() { console.log('calendar 2 viewRender');
                        makeEventsDraggable();
                    },
                }); 

            makeEventsDraggable();
            });
              

</script>
<style>

/*body {
  text-align: center;
  font-size: 14px;
  font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
}*/

#tuna_wrap {
  width: 1500px;
  margin: 0 auto;
  margin-top: 20px;
}

#external-events {
  float: left;
  width: 300px;
  height: 750px;
  padding: 0 10px;
  border: 1px solid #ccc;
  background: #eee;
  text-align: left;
  margin-left: 50px;
}

#external-events h4 {
  font-size: 16px;
  margin-top: 0;
  padding-top: 1em;
}

#external-events .fc-event {
  margin: 0px;
  height: 50px;
  cursor: pointer;
}

.fc-agendaWeek-view tr {
    /*height: 50px;*/
}

.fc-agendaDay-view tr {
    height: 30px;
}

#external-events p {
  margin: 1.5em 0;
  font-size: 11px;
  color: #666;
}

#external-events p input {
  margin: 0;
  vertical-align: middle;
}

#calendar {
  float: right;
  width: 450px;
}
.mb-20{
  margin-bottom:20px;
}

.simon-window{
    display:none;
    z-index: 5;
    position: relative;
}

.ui-dialog {
    background: #fefefe !important;
    padding: 10px;
    width: 450px !important;
    box-shadow: 0 0 7px 0px black;
}

span#ui-id-1 {
    margin-right: 230px;
    font-weight: bold;
}

span#ui-id-2 {
    margin-right: 180px;
    font-weight: bold;
}

.ui-widget-overlay { 
    position: absolute; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background: #000000;
    opacity: 0.3;
}
.ui-front {
    z-index: 100;
}
</style>
<body>
<center>

    <div id="eventContent" title="Event Details" class="simon-window">
        Patient: <a id="linkE" href=""><span id="patName"></span></a><br>
        Session: <span id="startTime"></span><br><br>
        <button id="show">Show</button>&nbsp;&nbsp;&nbsp;<button id="noShow">No Show</button><br><br>
        Patient has <span id="sessLeft"></span> approved sessions left.
        <button id="schedEasy">Schedule for same time next week</button>
    </div>

    <div id="searchPatient" title="Schedule Session" class="simon-window">
        Schedule new session at <span id="sessionTime"></span> for<br>
        <?= $patientList ?><br><br>
        <center>
        <button id="schedTime">Schedule</button>
        </center>
    </div>

<div id='tuna_wrap'>
    <div id='external-events'>
        <div id='day_calendar' style='height: 750 px; width: 250px;'></div>
    </div>
    <div class="container">
        <div class="col-md-12">

          <div class="col-md-6 mb-20">
            <div id='week_calendar' style='height: 750 px; width: 750px;'></div>
          </div>
          
        </div>
    </div>
    <div style='clear:both'></div>
</div>
</center>
</body>
