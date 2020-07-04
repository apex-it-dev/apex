$(function(){
  AnalogClock();
	// initClock();
  // renderCalendarEvents();
  // renderCalendarTask();
  $("#event-cal-container").simpleCalendar({
      events: ['2019-03-04', '2019-11-08', '2019-11-12', '2019-11-15'],
      eventsInfo: ['John\'s Birthday', 'Janet\'s Marriage','Graduation Day', 'Final Exams !'],
      selectCallback: function(date){
          console.log('date selected '+date);
      }
  });

  tippy('.day.event', {
      theme: 'translucent',
  });
  
  $.blockUI({ 
      message: $('#preloader_image'), 
      fadeIn: 1000, 
      onBlock: function() {
        loadCalendarEvents();
      }
    });
});

function AnalogClock(){
  clockd1_={
              "indicate": true,
              "indicate_color": "#222",
              "dial1_color": "#FF9B54",
              "dial2_color": "#F45B69",
              "dial3_color": "#c3282d",
              "time_add": 1,
              "time_24h": true,
              "date_add":3,
              "date_add_color": "#999",
             };

  var c = document.getElementById('clock1_');
        cns1_ = c.getContext('2d');
  clock_conti(200,cns1_,clockd1_);
}
// START CLOCK SCRIPT
function loadCalendarEvents(){
  var url = getAPIURL() + 'dashboard.php';
  var f = "loadCalendarEvents"; 
  var data = { "f":f };
  // console.log(data);
  // return false;

  $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ "data":data }),
        dataType: 'json'
        ,success: function(data){
          console.log(data);
          // var events = data['calendarevents']['events'];
          // renderCalendarEvents();
          // console.log(events);
      
          $.unblockUI();            
        }
        ,error: function(request, status, err){

        }
    });
}

Number.prototype.pad = function(n) {
  for (var r = this.toString(); r.length < n; r = 0 + r);
  return r;
};

function updateClock() {
  var now = new Date();
  var milli = now.getMilliseconds(),
    sec = now.getSeconds(),
    min = now.getMinutes(),
    hou = now.getHours(),
    mo = now.getMonth(),
    dy = now.getDate(),
    yr = now.getFullYear();
  var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  var tags = ["mon", "d", "y", "h", "m", "s", "mi"],
    corr = [months[mo], dy, yr, hou.pad(2), min.pad(2), sec.pad(2), milli];
  for (var i = 0; i < tags.length; i++)
    document.getElementById(tags[i]).firstChild.nodeValue = corr[i];
}

function initClock() {
  updateClock();
  window.setInterval("updateClock()", 1);
}

// END CLOCK SCRIPT

function renderCalendarEvents() {

  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
  plugins: [ 'interaction', 'dayGrid' ],
  //      defaultDate: '2019-08-12',
  editable: false,
  eventLimit: true, // allow "more" link when too many events
  events: [
    {
      title: 'All Day Event',
      start: '2019-11-01'
    }
  ]
  });

  calendar.render();
}

// selectable:true,
  // selectHelper:true,
  // events: [

  //   {
  //     title: 'All Day Event',
  //     start: '2019-11-01'
  //   }
  // ]

  
function renderCalendarTask () {
  var calendarEx = document.getElementById('calendar2');
  var calendar2 = new FullCalendar.Calendar(calendarEx, {
    plugins: [ 'list' ],
    aspectRatio: 1,
    header: {
      left: '',
      center: '',
      right: 'prev,next today'
    },

    // customize the button names,
    // otherwise they'd all just say "list"
    views: {
      listDay: { buttonText: 'list day' },
      listWeek: { buttonText: 'list week' }
    },

    defaultView: 'listWeek',
  //      defaultDate: '2019-08-12',
    navLinks: true, // can click day/week names to navigate views
    editable: false,
    eventLimit: true, // allow "more" link when too many events
    events: [
      {
        title: 'All Day Event',
        start: '2019-10-01'
      },
      {
        title: 'Long Event',
        start: '2019-10-07',
        end: '2019-10-10'
      },
      {
        groupId: 999,
        title: 'Repeating Event',
        start: '2019-10-09T16:00:00'
      },
      {
        groupId: 999,
        title: 'Repeating Event',
        start: '2019-10-16T16:00:00'
      },
      {
        title: 'Conference',
        start: '2019-10-11',
        end: '2019-10-13'
      },
      {
        title: 'Meeting',
        start: '2019-10-12T10:30:00',
        end: '2019-10-12T12:30:00'
      },
      {
        title: 'Lunch',
        start: '2019-10-13T12:00:00'
      },
      {
        title: 'Meeting',
        start: '2019-10-14T14:30:00'
      },
      {
        title: 'Happy Hour',
        start: '2019-10-17T17:30:00'
      },
      {
        title: 'Dinner',
        start: '2019-10-26T20:00:00'
      },
      {
        title: 'Birthday Party',
        start: '2019-10-29T07:00:00'
      },
      {
        title: 'Click for Google',
        url: 'http://google.com/',
        start: '2019-10-28'
      }
    ]
  });

  calendar2.render();
}  

