<link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
<script src='../js/lib/jquery.min.js'></script>
<script src='../js/lib/moment.min.js'></script>
<script src='../js/fullcalendar.js'></script>
<link href='../css/fullcalendar.min.css' rel='stylesheet' />
<link href='../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script>
$(document).ready(function() {
            $('#calendar').fullCalendar({
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
            events: <?php echo $events ?>
            });
});

</script>
<center>
<div id='calendar' style='height: 750 px; width: 750px'></div><br>
</center>

