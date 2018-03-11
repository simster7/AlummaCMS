<link rel='stylesheet' href='fullcalendar/fullcalendar.css' />
<script src='../js/lib/jquery.min.js'></script>
<script src='../js/lib/moment.min.js'></script>
<script src='../js/fullcalendar.js'></script>
<link href='../css/fullcalendar.min.css' rel='stylesheet' />
<link href='../css/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<script>
$(document).ready(function() {
            $('#mv-calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            editable: false,
            slotDuration: '00:15:00', 
            minTime: '08:00:00',
            maxTime: '21:00:00',
            zIndex: 0,
            defaultView: 'agendaWeek',
            droppable: false, // this allows things to be dropped onto the calendar
            dragRevertDuration: 0,
            eventLimit: true, // allow "more" link when too many events
            events: <?php echo $mv_events ?>
            });
});

$(document).ready(function() {
            $('#vi-calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            editable: false,
            slotDuration: '00:15:00', 
            scrollTime: '08:00:00',
            defaultView: 'agendaWeek',
            zIndex: 0,
            droppable: false, // this allows things to be dropped onto the calendar
            dragRevertDuration: 0,
            eventLimit: true, // allow "more" link when too many events
            events: <?php echo $vi_events ?>
            });
});
</script>
<center>
<!--     <table style="width: 1500px; height: 800px;"> -->
<!--         <tbody> -->
<!--             <tr style="height: 50px;"> -->
<!--                 <td style="width: 750px; text-align: center; height: 50px;">MISSION VALLEY</td> -->
<!--                 <td style="width: 750px; text-align: center; height: 50px;">VISTA</td> -->
<!--             </tr> -->
<!--             <tr style="height: 750px;"> -->
<!--                 <td style="width: 750px; text-align: center; height: 750px;"><div id='calendar' style='height: 750 px; width: 750px'></div></td> -->
<!--                 <td style="width: 750px; text-align: center; height: 750px;"> -->
<!--                 <div id='calendar' style='height: 750 px; width: 750px'></div> </td> -->
<!--             </tr> -->
<!--         </tbody> -->
<!--     </table> -->
<h3>MISSION VALLEY</h3><br>
<div id='mv-calendar' style='height: 750 px; width: 750px'></div><br>
<h3>VISTA</h3><br>
<div id='vi-calendar' style='height: 750 px; width: 750px'></div><br>
</center>
