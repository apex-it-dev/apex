
function clickTimeSheet() {
    $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
        loadTimesheet();
        $.unblockUI();
        }
    });
}

function loadTimesheet(){
	const url = getAPIURL() + 'attendance.php';
    const f = "getAttendanceByDate";
    const datefilter = $("#timesheet_date").val();
    const data = {"f": f, "datefilter": datefilter};

    $.ajax({
		type: 'POST',
		url: url,
		data: JSON.stringify({ "data": data }),
		dataType: 'json'
		, success: function (data) {
            // console.log(data);
            loadTimesheetChart(data.attendance.rows);
        }, error: function (request, status, err) {
			// console.log(request.responseText);
		}
	});
}

function loadTimesheetChart(timein){
	let id = 'timesheet_chart';
  let chartContainer = $('#' + id);
  const interval = 10; // tmp
  const timeRange = generateTimeRange(1000, interval);
  const timeLabel = timeRange.map(timeV => {
      const timeStr = timeV.toString();
      const position = timeStr.length - 2
      return [timeStr.slice(0,position), ':', timeStr.slice(position)].join('');
  });
  const timeColors =  dynamicColors(timeLabel.length);

  // console.log(timeRange);
  timeData = [];
  timeRange.forEach((timeV,index,array) => {
      if(array[index+1] === undefined) return;
      let thistime = timeV;
      if(index !== 0) thistime += 1
      // console.log(thistime + ' - ' + array[index+1])
      timeData.push(timein.filter(inval => inval.datein >= thistime && inval.datein <= array[index+1]).length);
  });

  // clean chart to avoid flickering bug
  if(chartContainer.hasClass('chartjs-render-monitor')){
    $('#timesheet_chart_container').html('').html('<canvas id="timesheet_chart"></canvas>');
  }
  // chartContainer = $('#' + id);
  chartContainer = document.getElementById(id).getContext('2d')
  let chartOptions = {
    // maintainAspectRatio: true,
    scales: {
      // xAxes: [
      //   {
      //     display: false,
      //     ticks: {
      //         max: 3,
      //     }
      //   },
      //   {
      //     display: true,
      //     ticks: {
      //       autoSkip: false,
      //       max: 4,
      //     }
      //   }],
      yAxes: [{
        ticks: {
          beginAtZero:true,
          precision:0
        }
      }]
    },
    onClick: barClicked
  };

  let chartConfig = {
    type: 'bar',
    data: {
      labels: timeLabel,
      datasets: [{
        data: timeData,
        barPercentage: 1.3,
        backgroundColor: timeColors,
      }],
        },
      options: chartOptions,
  };
  let timeSheetHistogram = new Chart(chartContainer,chartConfig);
  function barClicked(e) {
    let eValue = timeSheetHistogram.getElementAtEvent(e);
    if(eValue.length > 0) {
      const val = {
        'index': eValue[0]._index, 
        'data': timeSheetHistogram.data,
        'interval': interval
      };
      loadTable(val);
    }
  }
}
  
function loadTable(data){
  const histData = data.data;
  const index = data.index;
  const interval = data.interval;
  const timenum = parseInt((histData.labels[index]).replace(':',''));
  console.log(timenum);
  // pull data from histogamData base barindex
  // use histogramData.labels[barindex] time and convert to int
  // then compare it to DB time in of selected date
}

function generateTimeRange(end = 1000, interval = 10) {
    let start = 600;
    let start_str = start.toString();
    let setOfTimes = [];
    while (start <= end) {
        start_str = start.toString();
        if(parseInt(start_str.slice(-2)) >= 60){
            start = parseInt(start_str.substr(0,start_str.length-2)) + 1;
            start += '00'
            start = parseInt(start);
        }
        setOfTimes.push(start);
        start += interval;
    }
    return setOfTimes;
}

var dynamicColors = function(downto, interval = 4) {
    let start = 40;
    let end = downto;
    let setOfColors = [];
    while (downto >= 0){
        r = Math.floor(start);
        const g = Math.floor(start);
        const b = Math.floor(start);
        setOfColors.push("rgb(" + r + "," + g + "," + b + ")");
        start += interval;
        downto -= 1;
    }
    return setOfColors;
}