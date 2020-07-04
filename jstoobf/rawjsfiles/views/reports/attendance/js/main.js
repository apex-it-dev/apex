$(function() {
    'use strict';
  
    // initialize datepickers
    serverDateNow((newdate) => {
    //   $("#timesheet_date").datepicker({
    //     maxDate: 0,
    //     dateFormat: "D dd M yy",
    //     changeMonth: true,
    //     changeYear: true,
    //     yearRange: "1900:" + newdate.getFullYear()
    //   });
  
      $('#leavefrom-filter, #leaveto-filter').daterangepicker({
        showDropdowns: true,
        maxDate: newdate,
        startDate: newdate,
        endDate: newdate,
        opens: 'left',
        applyButtonClasses: "btn-danger",
        locale: {
          format: 'DD MMM YY'
        }
      }
      // , function(start, end) {
      //     $('#joinedfrom').val(start.format('ddd DD MMM YYYY'));
      //     $('#joinedto').val(end.format('ddd DD MMM YYYY'));
      // }
      );
    });
    
  
    $('#leavefrom, #leaveto').on('click', function(e) {
      const thisid = `#${e.target.id}`;
      const currentState = $(thisid).attr('checked') == 'checked';
      $(thisid).attr('checked', !currentState);
  
      if(currentState) {
        $(`${thisid}-filter`)
          .removeClass('active')
          .attr('disabled', true);
      } else {
        $(`${thisid}-filter`)
          .addClass('active')
          .attr('disabled', false);
      }
    });
    
    
    // blockUI(()=> {
        // loadDefault();
        loadFilters(() => {
          $('#filter-data').val(JSON.stringify(generateFilters()));
          const reportstable = '#reportstable';
          
          initializeReportsDataTable(reportstable);
        });
  
  
  
    // });
  });
  
  