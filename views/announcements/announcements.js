$(function() {
  $('#holidaymodal_startdate,#holidaymodal_enddate')
    .datepicker({
      dateFormat: 'D d M yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1900:2050'
    })
    .on('click', function() {
      //prevents typing
      $(this).attr('readonly', 'true');
    })
    .on('change', function() {
      daysCounter();
    });

  $('#holiday-tab')
    .one('click', function() {
      // $("#holidaygroup").val('holidaylist');
      // $.blockUI({
      // 	message: $('#preloader_image'),
      // 	fadeIn: 1000,
      // 	onBlock: function () {
      // 		console.log('Tab: personal-tab');
      // 		loadHolidayList();
      // 	}
      // });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          console.log('Tab: personal-tab');
          loadHolidayList();
        }
      });
    });

  $('#btnBack').on('click', function() {
    alert('Not yet implemented!');
  });

  $('#holidayItemModal').on('hidden.bs.modal', function() {
    $('#holidaymodal_title').val('');
    $('#holidaymodal_ofc option[value="default"]').prop('selected', true);
    $('#holidaymodal_startdate').val('');
    $('#holidaymodal_enddate').val('');
    $('#holidaymodal_desc').val('');
    $('#holidaymodal_region option[value="international"]').prop('selected', true);
    $('#holidayModalId').val('');
    $('#holidayCreatedBy').val('');
    $('#holidayCreatedDate').val('');
    $('#numberOfDaysOfHoliday').val('');
    $('#btnHolidayClick').attr('disabled', true);
    $(this).off('keypress');
    $(this).off('change');
    $(this).off('click');
    $(this).off('keydown');
    $(this).off('keyup');
  });

  $('#btnAddHoliday').on('click', function() {
    //Modal Add
    $('#btnHolidayDelete').attr({
      disabled: true,
      hidden: true
    });
    $('#btnHolidayPublish').attr('hidden', true);
    $('#btnHolidayClick').html('Add New');
    $('#btnHolidayClick').attr('onClick', 'addHolidayItem();');
    $('#holidayUserId').val($('#userid').val());

    let fieldWatcher = function() {
      // console.log('Field watcher triggered');
      let noEmpty =
        $('#holidaymodal_title').val() != '' &&
        $('#holidaymodal_ofc').val() != 'default' &&
        $('#holidaymodal_startdate').val() != '' &&
        $('#holidaymodal_enddate').val() != '' &&
        $('#holidaymodal_desc').val() != '' &&
        $('#holidaymodal_region').val() != '';
      $('#btnHolidayClick').attr('disabled', !noEmpty);
    };
    $('#holidayItemModal')
      .on('keypress', function() {
        fieldWatcher();
      })
      .on('change', function() {
        fieldWatcher();
      })
      .on('click', function() {
        1;
        fieldWatcher();
      })
      .on('keydown', function() {
        fieldWatcher();
      })
      .on('keyup', function() {
        fieldWatcher();
      });

    $('#holidayItemModal').modal('show');
  });

  $('#btnHolidayDelete').on('click', function() {
    $('#holidayDelete').modal('show');
    $('#deleteYes').on('click', function() {
      $('#holidayDelete').modal('hide');
      $('#holidayItemModal').modal('hide');
      deleteHoliday();
    });
  });

  function deleteHoliday() {
    let url = getAPIURL() + 'holidays.php';
    let f = 'deleteHolidays';

    let id = $('#holidayModalId').val();
    let userid = $('#holidayUserId').val();

    let data = {
      f: f,
      id: id,
      userid: userid
    };

    // console.log(data);
    $.ajax({
      type: 'POST',
      url: url,
      data: JSON.stringify({ data: data }),
      dataType: 'json',
      success: function(data) {
        // console.log(data);
        loadHolidayList();
        $.unblockUI();
      },
      error: function(request, status, err) {
        alert('Something went wrong, please contact the IT admin: ' + err);
        $.unblockUI();
      }
    });
  }

  // $('#btnUpdateHolidayClick').on('click', function(){
  //     $('#btnUpdateHolidayClick').attr('disabled',true);
  //     // updateHolidayItem();
  //     $('#btnUpdateHolidayClick').attr('disabled',false);
  // });
  // $('#btnAddHolidayClick').on('click', function(){
  //     $('#btnAddHolidayClick').attr('disabled',true);
  //     addHolidayItem();
  //     // alert('Holidays successfully added!');
  //     $('#btnAddHolidayClick').attr('disabled',false);
  // });

  $('#btnHolidayPublish').on('click', function() {
    publishHoliday();
    // $('#btnHolidayPublish')
    //   .attr('disabled', true)
    //   .removeClass('btn-info')
    //   .addClass('btn-secondary')
    //   .html('Publishing...');
    // console.log('t');

    // $('#btnHolidayPublish')
    //   .html('Published!')
    //   .removeClass('btn-secondary')
    //   .addClass('btn-success');

    //   $('#btnHolidayPublish')
    //     .html('Unpublish')
    //     .removeClass('btn-success')
    //     .addClass('btn-danger')
    //     .removeAttr('disabled');
    // }, 2000);
  });

  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      $('#holidaygroup').val('holidaylist');
      loadHolidayList();
    }
  });
});

function daysCounter() {
  let start = new Date($('#holidaymodal_startdate').val());
  let end = new Date($('#holidaymodal_enddate').val());
  // end - start returns difference in milliseconds
  let diff = new Date(end - start);

  // get days
  let days = diff / 1000 / 60 / 60 / 24;
  if (days < 0 || isNaN(days)) {
    $('#holidaymodal_enddate').val($('#holidaymodal_startdate').val());
    $('#numberOfDaysOfHoliday').val(0);
  } else {
    $('#numberOfDaysOfHoliday').val(days);
  }
}

function loadHolidayList() {
  let url = getAPIURL() + 'holidays.php';
  let f = 'getHolidays';
  // var userid = $("#userid").val();
  let currentyear = new Date().getFullYear();
  // if ($('#yearFilterHoliday').length != 0) {
  //   currentyear = $('#yearFilterHoliday option:selected').val();
  // }
  let data = { f: f, currentyear: currentyear };
  // console.log(data);
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      loadSalesOfc(data.offices);
      if (data.holidays.rows.length > 0) {
        genHolidayList(data.holidays);
      } else {
        genHolidayList([]);
      }
      $.unblockUI();
    },
    error: function(request, status, err) {
      // alert('Something went wrong, please contact the IT admin: ' + err);
      // $.unblockUI();
    }
  });
}

function loadSalesOfc(data) {
  let officeList = data;
  let officeshtml = '<option value="default"></option>';
  officeshtml += '<option value="all">all</option>';
  officeList.forEach(office => {
    // if(element.name != null)
    officeshtml += '<option value="' + office.salesofficeid + '">' + office.description + '</option>';
  });
  $('#holidaymodal_ofc').html(officeshtml);
  $('#holidaymodal_ofc_edit').html(officeshtml);
}

function genHolidayList(data) {
  let holidayLists = data.rows;

  function changeCursor(arrayVal) {
    if (arrayVal.length == 0) {
      $('#holidayListDataTable tbody').css('cursor', 'no-drop');
    } else {
      $('#holidayListDataTable tbody').css('cursor', 'pointer');
    }
  }

  let initialFilter = function(holiday_list, ofc_f, year_f, region_f) {
    let ofcFilter;
    let yrFilter;
    let regionFilter;

    if (holiday_list == undefined) {
      return holiday_list;
    }

    yrFilter = holiday_list.filter(holidayItem => new Date(holidayItem.datestart).getFullYear() == year_f);

    if (ofc_f == 'all offices') {
      ofcFilter = yrFilter;
    } else {
      // office is selected
      ofcFilter = yrFilter.filter(holidayItem => holidayItem.officename == ofc_f || holidayItem.office == 'all');
    }

    if (region_f != 'all region') {
      regionFilter = ofcFilter.filter(holidayItem => holidayItem.region == region_f);
    } else {
      regionFilter = ofcFilter;
    }
    changeCursor(regionFilter);
    return regionFilter;
  };

  let newListFilter = initialFilter(holidayLists, 'all offices', new Date().getFullYear(), 'all region');

  if (!$.fn.DataTable.isDataTable('#holidayListDataTable')) {
    $('#holidayListDataTable').DataTable({
      data: newListFilter,
      lengthMenu: [
        [15, 25, 50, -1],
        [15, 25, 50, 'All']
      ],
      responsive: true,
      // paging: false,
      // searching: false,
      // info: false,
      order: [[2, 'asc']],
      language: {
        emptyTable: 'No holidays recorded'
      },
      columns: [
        {
          data: 'title',
          render: function(data) {
            return truncateStr(data, 50);
          }
        },
        {
          data: function(data) {
            if (data.office == 'all') {
              return 'all';
            } else {
              return data.officename;
            }
          }
        },
        { data: 'startdate' },
        { data: 'enddate' },
        {
          data: 'description',
          render: function(data) {
            return truncateStr(data, 80);
          }
        },
        { data: 'region' }
      ],
      fnDrawCallback: function() {
        // resize filter --------------------------
        $('#holidayListDataTable_length')
          .parent()
          .removeAttr('class')
          .attr('class', 'col-sm-12 col-md-2');

        $('#holidayListDataTable_filter')
          .parent()
          .removeAttr('class')
          .attr('class', 'col-sm-12 col-md-10');
        // ----------------------------------------

        let dataTable = $('#holidayListDataTable').dataTable();
        if ($('#yearFilterHoliday').length == 0 && $('#ofcFilterHoliday').length == 0 && holidayLists != undefined) {
          // year filter --------------------------------------
          let startYear = 2019;
          let defaultYear = new Date().getFullYear();
          let yearLatest = defaultYear + 1;
          let yearSelect = '<span id="yrFilterContainer">';
          yearSelect += 'Year: <label style="padding-right: 1vh; width: 15vh; white-space: nowrap;">';
          yearSelect += '<select class="form-control form-control-sm" id="yearFilterHoliday" name="yearFilterHoliday" aria-controls="holidayListDataTable">';
          for (let yearStart = startYear; yearStart <= yearLatest; yearStart++) {
            if (yearStart == defaultYear) {
              yearSelect += '<option value="' + yearStart + '" selected>' + yearStart + '</option>';
            } else {
              yearSelect += '<option value="' + yearStart + '">' + yearStart + '</option>';
            }
          }
          yearSelect += '</select>';
          yearSelect += '</label>';
          yearSelect += '</span>';
          // -------------------------------------------------

          // office filter --------------------------------------
          let ofcSelect = '<span id="ofcFilterContainer">';
          ofcSelect += 'Office: <label style="padding-right: 1vh; width: 15vh; white-space: nowrap;">';
          ofcSelect += '<select class="form-control form-control-sm" id="ofcFilterHoliday" name="ofcFilterHoliday" aria-controls="holidayListDataTable">';
          ofcSelect += '<option value="all offices">all offices</option>';
          let distinctOffice = [...new Set(holidayLists.map(holidayItem => holidayItem.officename))];
          distinctOffice.forEach(officeName => {
            if (officeName != null) {
              ofcSelect += '<option value="' + officeName + '">' + officeName + '</option>';
            }
          });
          ofcSelect += '</select>';
          ofcSelect += '</label>';
          ofcSelect += '</span>';
          // ---------------------------------------------------

          // region filter --------------------------------------
          let regionSelect = '<span id="regionFilterContainer">';
          regionSelect += 'Region: <label style="padding-right: 1vh; width: 15vh; white-space: nowrap;">';
          regionSelect += '<select class="form-control form-control-sm" id="regionFilterHoliday" name="regionFilterHoliday" aria-controls="holidayListDataTable">';
          regionSelect += '<option value="all region">all region</option>';
          let distinctRegion = [...new Set(holidayLists.map(holidayItem => holidayItem.region))];
          distinctRegion.forEach(regionName => {
            if (regionName != null) {
              regionSelect += '<option value="' + regionName + '">' + regionName + '</option>';
            }
          });
          regionSelect += '</select>';
          regionSelect += '</label>';
          regionSelect += '</span>';
          // ---------------------------------------------------

          $('#holidayListDataTable_filter').prepend(yearSelect);
          $('#holidayListDataTable_filter').prepend(regionSelect);
          $('#holidayListDataTable_filter').prepend(ofcSelect);

          $('#yearFilterHoliday, #ofcFilterHoliday, #regionFilterHoliday').on('change', function() {
            let filteredYear = $('#yearFilterHoliday option:selected').text();
            let filteredOfc = $('#ofcFilterHoliday option:selected').text();
            let filteredRegion = $('#regionFilterHoliday option:selected').text();
            let finalFilter = '';

            finalFilter = initialFilter(holidayLists, filteredOfc, filteredYear, filteredRegion);

            dataTable.fnClearTable();
            if (finalFilter.length > 0) {
              dataTable.fnAddData(finalFilter);
            }
          });
        }
      },
      columnDefs: [
        //hide a timestamp for dates for sorting
        {
          targets: [2, 3],
          render: function(data) {
            return '<span style="display:none;">' + toTimestamp(data) + '</span>' + moment(data).format('ddd D MMM YYYY');
          }
        }
      ],
      rowId: 'id'
    });
  } else {
    $('#holidayListDataTable')
      .dataTable()
      .fnClearTable();
    if (holidayLists != undefined) {
      $('#holidayListDataTable')
        .dataTable()
        .fnAddData(holidayLists);
    }
    $('#holidayItemModal').modal('hide');
  }

  $('#holidayListDataTable tbody').on('click', 'tr', function(e) {
    //Modal Edit
    if ($(e.currentTarget.childNodes[0]).hasClass('dataTables_empty')) {
      return;
    }
    if (holidayLists == undefined) {
      loadHolidayList();
      return;
    }
    let holidayItem = e.currentTarget;
    let holidayId = holidayItem.id;
    let region = holidayItem.childNodes[5].innerText.toLowerCase();
    let createdBy = '';
    let createdDate = '';
    let holidayDescFull = '';
    let title = '';
    let officeId = '';
    holidayLists.find(element => {
      if (element.id == holidayId) {
        officeId = element.office;
        holidayDescFull = element.description;
        createdBy = element.createdby;
        createdDate = element.createddate;
        title = element.title;
      }
    });
    let startdate = holidayItem.childNodes[2].innerText;
    let enddate = holidayItem.childNodes[3].innerText;
    $('#holidaymodal_title').val(title);
    $('#holidaymodal_ofc option[value="' + officeId + '"]').prop('selected', true);
    $('#holidaymodal_startdate').val(startdate);
    $('#holidaymodal_enddate').val(enddate);
    $('#holidaymodal_desc').val(holidayDescFull);
    $('#holidaymodal_region option[value="' + region + '"]').prop('selected', true);
    $('#btnHolidayPublish').attr('hidden', false);
    $('#btnHolidayClick').html('Save Changes');
    $('#btnHolidayClick').attr('onClick', 'updateHolidayItem();');

    $('#holidayUserId').val($('#userid').val());
    $('#holidayModalId').val(holidayId);
    $('#holidayCreatedBy').val(createdBy);
    $('#holidayCreatedDate').val(createdDate);

    let fieldWatcher_edit = function() {
      // console.log('Field watcher triggered');
      let notAllEmpty =
        $('#holidaymodal_title').val() != '' &&
        $('#holidaymodal_ofc').val() != '' &&
        $('#holidaymodal_startdate').val() != '' &&
        $('#holidaymodal_enddate').val() != '' &&
        $('#holidaymodal_desc').val() != '' &&
        $('#holidaymodal_region').val() != '';

      let allTheSame =
        $('#holidaymodal_title')
          .val()
          .trim() == title &&
        $('#holidaymodal_ofc').val() == officeId &&
        $('#holidaymodal_startdate').val() == startdate &&
        $('#holidaymodal_enddate').val() == enddate &&
        $('#holidaymodal_desc')
          .val()
          .trim() == holidayDescFull &&
        $('#holidaymodal_region').val() == region;
      $('#btnHolidayClick').attr('disabled', !(notAllEmpty && !allTheSame));
    };
    $('#holidayItemModal')
      .on('keypress', function() {
        fieldWatcher_edit();
      })
      .on('change', function() {
        fieldWatcher_edit();
      })
      .on('click', function() {
        fieldWatcher_edit();
      })
      .on('keydown', function() {
        fieldWatcher_edit();
      })
      .on('keyup', function() {
        fieldWatcher_edit();
      });

    daysCounter();
    // $('#btnHolidayClick').attr('disabled',false);
    $('#btnHolidayDelete').attr({
      disabled: false,
      hidden: false
    });
    $('#holidayItemModal').modal('show');
  });
}

function addHolidayItem() {
  // console.log('Adding');
  let url = getAPIURL() + 'holidays.php';
  let f = 'addHolidays';

  let title = $('#holidaymodal_title').val();
  let ofc = $('#holidaymodal_ofc option:selected').val();
  let startdate = $('#holidaymodal_startdate').val();
  let enddate = $('#holidaymodal_enddate').val();
  let desc = $('#holidaymodal_desc').val();
  let region = $('#holidaymodal_region option:selected')
    .val()
    .toLowerCase();
  let days_count = $('#numberOfDaysOfHoliday').val();
  let userid = $('#holidayUserId').val();
  let createddate = moment(new Date()).format('ddd D MMM YYYY');

  let data = {
    f: f,
    title: title,
    ofc: ofc,
    startdate: startdate,
    enddate: enddate,
    desc: desc,
    region: region,
    days_count: days_count,
    userid: userid,
    createddate: createddate
  };
  // console.log(data);
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      loadHolidayList();
      $.unblockUI();
    },
    error: function(request, status, err) {
      alert('Something went wrong, please contact the IT admin: ' + err);
      $.unblockUI();
    }
  });
}

function updateHolidayItem() {
  // console.log('Updating');
  let url = getAPIURL() + 'holidays.php';
  let f = 'updateHolidays';

  let title = $('#holidaymodal_title').val();
  let ofc = $('#holidaymodal_ofc option:selected').val();
  let startdate = $('#holidaymodal_startdate').val();
  let enddate = $('#holidaymodal_enddate').val();
  let region = $('#holidaymodal_region option:selected')
    .val()
    .toLowerCase();
  let desc = $('#holidaymodal_desc').val();

  let id = $('#holidayModalId').val();
  let userid = $('#holidayUserId').val();
  let createdby = $('#holidayCreatedBy').val();
  let createddate = $('#holidayCreatedDate').val();
  let days_count = $('#numberOfDaysOfHoliday').val();

  let modifieddate = moment(new Date()).format('ddd D MMM YYYY');

  // var userid = $("#userid").val();
  let data = {
    f: f,
    title: title,
    ofc: ofc,
    startdate: startdate,
    enddate: enddate,
    region: region,
    desc: desc,
    id: id,
    userid: userid,
    createdby: createdby,
    createddate: createddate,
    days_count: days_count,
    modifieddate: modifieddate
  };
  // console.log(data);
  // return false;
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      loadHolidayList();
      $.unblockUI();
    },
    error: function(request, status, err) {
      alert('Something went wrong, please contact the IT admin: ' + err);
      $.unblockUI();
    }
  });
}

function publishHoliday() {
  let url = getAPIURL() + 'holidays.php';
  let f = 'updateHolidays';
}
