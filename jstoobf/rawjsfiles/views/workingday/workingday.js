const accessitem_ofc = $('#viewofc').val().split(',');
const canupdate = $('#canupdate').val() == 1;
$(function() {
  $('#workingdaymodal_startdate,#workingdaymodal_enddate')
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

  $('#workingday-tab')
    .one('click', function() {
      // $("#workingdaygroup").val('workingdaylist');
      // $.blockUI({
      // 	message: $('#preloader_image'),
      // 	fadeIn: 1000,
      // 	onBlock: function () {
      // 		console.log('Tab: personal-tab');
      // 		loadWorkingdayList();
      // 	}
      // });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          console.log('Tab: personal-tab');
          loadWorkingdayList();
        }
      });
    });

  $('#btnBack').on('click', function() {
    alert('Not yet implemented!');
  });

  $('#workingdayItemModal').on('hidden.bs.modal', function() {
    $('#workingdaymodal_title').val('');
    $('#workingdaymodal_ofc option[value="default"]').prop('selected', true);
    $('#workingdaymodal_startdate').val('');
    $('#workingdaymodal_enddate').val('');
    $('#workingdaymodal_desc').val('');
    $('#workingdaymodal_region option[value="international"]').prop('selected', true);
    $('#workingdayModalId').val('');
    $('#workingdayCreatedBy').val('');
    $('#workingdayCreatedDate').val('');
    $('#numberOfDaysOfWorkingday').val('');
    $('#btnWorkingdayClick').attr('disabled', true);
    $(this).off('keypress');
    $(this).off('change');
    $(this).off('click');
    $(this).off('keydown');
    $(this).off('keyup');
  });

  if($('#btnAddWorkingday').length == 1) {
    $('#btnAddWorkingday')
      .off('click')
      .on('click', function() {
        //Modal Add
        $('#btnWorkingdayDelete').attr({
          disabled: true,
          hidden: true
        });
        $('#btnWorkingdayPublish').attr('hidden', true);
        $('#btnWorkingdayClick').html('Add New');
        $('#btnWorkingdayClick').attr('onClick', 'addWorkingdayItem();');
        $('#workingdayUserId').val($('#userid').val());

        let fieldWatcher = function() {
          // console.log('Field watcher triggered');
          let noEmpty =
            $('#workingdaymodal_title').val() != '' &&
            $('#workingdaymodal_ofc').val() != 'default' &&
            $('#workingdaymodal_startdate').val() != '' &&
            $('#workingdaymodal_enddate').val() != '' &&
            $('#workingdaymodal_desc').val() != '' &&
            $('#workingdaymodal_region').val() != '';
          $('#btnWorkingdayClick').attr('disabled', !noEmpty);
        };
        $('#workingdayItemModal')
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

        $('#btnWorkingdayClick').show();
        $('#workingday-footer').show();
        $('#workingdayItemModal').modal('show');
      });
  }

  $('#btnWorkingdayDelete').on('click', function() {
    $('#workingdayDelete').modal('show');
    $('#deleteYes').on('click', function() {
      $('#workingdayDelete').modal('hide');
      $('#workingdayItemModal').modal('hide');
      deleteWorkingday();
    });
  });

  function deleteWorkingday() {
    let url = getAPIURL() + 'workingday.php';
    let f = 'deleteWorkingdays';

    let id = $('#workingdayModalId').val();
    let userid = $('#workingdayUserId').val();

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
        loadWorkingdayList();
        $.unblockUI();
      },
      error: function(request, status, err) {
        alert('Something went wrong, please contact the IT admin: ' + err);
        $.unblockUI();
      }
    });
  }

  // $('#btnUpdateWorkingdayClick').on('click', function(){
  //     $('#btnUpdateWorkingdayClick').attr('disabled',true);
  //     // updateWorkingdayItem();
  //     $('#btnUpdateWorkingdayClick').attr('disabled',false);
  // });
  // $('#btnAddWorkingdayClick').on('click', function(){
  //     $('#btnAddWorkingdayClick').attr('disabled',true);
  //     addWorkingdayItem();
  //     // alert('Workingdays successfully added!');
  //     $('#btnAddWorkingdayClick').attr('disabled',false);
  // });

  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      $('#workingdaygroup').val('workingdaylist');
      loadWorkingdayList();
    }
  });
});

function daysCounter() {
  let start = new Date($('#workingdaymodal_startdate').val());
  let end = new Date($('#workingdaymodal_enddate').val());
  // end - start returns difference in milliseconds
  let diff = new Date(end - start);

  // get days
  let days = diff / 1000 / 60 / 60 / 24;
  if (days < 0 || isNaN(days)) {
    $('#workingdaymodal_enddate').val($('#workingdaymodal_startdate').val());
    $('#numberOfDaysOfWorkingday').val(0);
  } else {
    $('#numberOfDaysOfWorkingday').val(days);
  }
}

function loadWorkingdayList() {
  let url = getAPIURL() + 'workingday.php';
  let f = 'getWorkingdays';
  // var userid = $("#userid").val();
  let currentyear = new Date().getFullYear();
  if ($('#yearFilterWorkingday').length != 0) {
    currentyear = $('#yearFilterWorkingday option:selected').val();
  }
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
      if (data.workingdays.rows.length > 0) {
        genWorkingdayList(data);
      } else {
        genWorkingdayList([]);
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
  const officeLists = data;
  let allofc = '<option value="default"></option><option value="all">all</option>';
  let ofcshown = [];
  if(officeLists.length === accessitem_ofc.length){
    ofcshown = officeLists;
  } else {
    allofc = '';
    ofcshown = officeLists.filter(ofc => {
      let ofc_exist = false;
      accessitem_ofc.map(ofcitem => {
        if(!ofc_exist) ofc_exist = ofcitem === ofc.salesofficeid;
      });
      return ofc_exist;
    });
  }

  let officeshtml = allofc;
  ofcshown.forEach(office => {
    officeshtml += `<option value="${office.salesofficeid}">${office.description}</option>`;
  });
  $('#workingdaymodal_ofc').html(officeshtml);
  $('#workingdaymodal_ofc_edit').html(officeshtml);
}

function genWorkingdayList(data) {
  if(data == []) return;
  const allWorkingdayLists = data.workingdays.rows;
  const officeLists = data.offices;
  let allofc = '<option value="all offices">all offices</option>';
  let ofcshown = [];

  if(officeLists.length === accessitem_ofc.length){
    ofcshown = officeLists;
  } else {
    // allofc = '';
    ofcshown = officeLists.filter(ofc => {
      let ofc_exist = false;
      accessitem_ofc.map(ofcitem => {
        if(!ofc_exist) ofc_exist = ofcitem === ofc.salesofficeid;
      });
      return ofc_exist;
    });
  }

  const workingdayLists = allWorkingdayLists.filter(workingdayitem => {
    let ofc_exist = false;
    ofcshown.map(ofcitem => {
      if(!ofc_exist) ofc_exist = ofcitem.salesofficeid === workingdayitem.office;
    });
    return ofc_exist;
  });

  function changeCursor(arrayVal) {
    if (arrayVal.length == 0) {
      $('#workingdayListDataTable tbody').css('cursor', 'no-drop');
    } else {
      $('#workingdayListDataTable tbody').css('cursor', 'pointer');
    }
  }

  let initialFilter = function(workingday_list, ofc_f, year_f, region_f) {
    let ofcFilter;
    let yrFilter;
    let regionFilter;

    if (workingday_list == undefined) {
      return workingday_list;
    }

    yrFilter = workingday_list.filter(workingdayItem => new Date(workingdayItem.datestart).getFullYear() == year_f);

    if (ofc_f == 'all offices') {
      ofcFilter = yrFilter;
    } else {
      // office is selected
      ofcFilter = yrFilter.filter(workingdayItem => workingdayItem.officename == ofc_f);
    }

    if (region_f != 'all region') {
      regionFilter = ofcFilter.filter(workingdayItem => workingdayItem.region == region_f);
    } else {
      regionFilter = ofcFilter;
    }
    changeCursor(regionFilter);
    return regionFilter;
  };

  let newListFilter = initialFilter(workingdayLists, 'all offices', new Date().getFullYear(), 'all region');

  if (!$.fn.DataTable.isDataTable('#workingdayListDataTable')) {
    $('#workingdayListDataTable').DataTable({
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
        emptyTable: 'No workingdays recorded'
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
        $('#workingdayListDataTable_length')
          .parent()
          .removeAttr('class');
        $('#workingdayListDataTable_length')
          .parent()
          .attr('class', 'col-sm-12 col-md-3');

        $('#workingdayListDataTable_filter')
          .parent()
          .removeAttr('class');
        $('#workingdayListDataTable_filter')
          .parent()
          .attr('class', 'col-sm-12 col-md-9');
        // ----------------------------------------

        let dataTable = $('#workingdayListDataTable').dataTable();
        if ($('#yearFilterWorkingday').length == 0 && $('#ofcFilterWorkingday').length == 0 && workingdayLists != undefined) {
          // year filter --------------------------------------
          let startYear = 2019;
          let defaultYear = new Date().getFullYear();
          let yearLatest = defaultYear + 1;
          let yearSelect = '<span id="yrFilterContainer">';
          yearSelect += 'Year: <label style="padding-right: 2vh; width: 15vh; white-space: nowrap;">';
          yearSelect += '<select class="form-control form-control-sm" id="yearFilterWorkingday" name="yearFilterWorkingday" aria-controls="workingdayListDataTable">';
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
          ofcSelect += 'Office: <label style="padding-right: 2vh; width: 15vh; white-space: nowrap;">';
          ofcSelect += '<select class="form-control form-control-sm" id="ofcFilterWorkingday" name="ofcFilterWorkingday" aria-controls="workingdayListDataTable">';
          ofcSelect += allofc;
          // let distinctOffice = [...new Set(workingdayLists.map(workingdayItem => workingdayItem.officename))];
          // distinctOffice.forEach(officeName => {
          //   if (officeName != null) {
          //     ofcSelect += '<option value="' + officeName + '">' + officeName + '</option>';
          //   }
          // });
          ofcSelect += ofcshown.map((ofc) => `<option value="${ofc.salesofficeid}">${ofc.description}</option>`);
          ofcSelect += '</select>';
          ofcSelect += '</label>';
          ofcSelect += '</span>';
          // ---------------------------------------------------

          // region filter --------------------------------------
          let regionSelect = '<span id="regionFilterContainer">';
          regionSelect += 'Region: <label style="padding-right: 2vh; width: 15vh; white-space: nowrap;">';
          regionSelect += '<select class="form-control form-control-sm" id="regionFilterWorkingday" name="regionFilterWorkingday" aria-controls="workingdayListDataTable">';
          regionSelect += '<option value="all region">all region</option>';
          let distinctRegion = [...new Set(workingdayLists.map(workingdayItem => workingdayItem.region))];
          distinctRegion.forEach(regionName => {
            if (regionName != null) {
              regionSelect += '<option value="' + regionName + '">' + regionName + '</option>';
            }
          });
          regionSelect += '</select>';
          regionSelect += '</label>';
          regionSelect += '</span>';
          // ---------------------------------------------------

          $('#workingdayListDataTable_filter').prepend(yearSelect);
          $('#workingdayListDataTable_filter').prepend(regionSelect);
          $('#workingdayListDataTable_filter').prepend(ofcSelect);

          $('#yearFilterWorkingday, #ofcFilterWorkingday, #regionFilterWorkingday').on('change', function() {
            let filteredYear = $('#yearFilterWorkingday option:selected').text();
            let filteredOfc = $('#ofcFilterWorkingday option:selected').text();
            let filteredRegion = $('#regionFilterWorkingday option:selected').text();
            let finalFilter = '';

            finalFilter = initialFilter(workingdayLists, filteredOfc, filteredYear, filteredRegion);

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
    $('#workingdayListDataTable')
      .dataTable()
      .fnClearTable();
    if (workingdayLists != undefined) {
      $('#workingdayListDataTable')
        .dataTable()
        .fnAddData(workingdayLists);
    }
    $('#workingdayItemModal').modal('hide');
  }

  $('#workingdayListDataTable tbody').on('click', 'tr', function(e) {
    //Modal Edit
    if ($(e.currentTarget.childNodes[0]).hasClass('dataTables_empty')) {
      return;
    }
    if (workingdayLists == undefined) {
      loadWorkingdayList();
      return;
    }
    let workingdayItem = e.currentTarget;
    let workingdayId = workingdayItem.id;
    let region = workingdayItem.childNodes[5].innerText.toLowerCase();
    let createdBy = '';
    let createdDate = '';
    let workingdayDescFull = '';
    let title = '';
    let officeId = '';
    workingdayLists.find(element => {
      if (element.id == workingdayId) {
        officeId = element.office;
        workingdayDescFull = element.description;
        createdBy = element.createdby;
        createdDate = element.createddate;
        title = element.title;
      }
    });
    let startdate = workingdayItem.childNodes[2].innerText;
    let enddate = workingdayItem.childNodes[3].innerText;
    $('#workingdaymodal_title').val(title);
    $('#workingdaymodal_ofc option[value="' + officeId + '"]').prop('selected', true);
    $('#workingdaymodal_startdate').val(startdate);
    $('#workingdaymodal_enddate').val(enddate);
    $('#workingdaymodal_desc').val(workingdayDescFull);
    $('#workingdaymodal_region option[value="' + region + '"]').prop('selected', true);
    $('#btnWorkingdayPublish').attr('hidden', false);
    $('#btnWorkingdayClick').html('Save Changes');
    $('#btnWorkingdayClick').attr('onClick', 'updateWorkingdayItem();');

    $('#workingdayUserId').val($('#userid').val());
    $('#workingdayModalId').val(workingdayId);
    $('#workingdayCreatedBy').val(createdBy);
    $('#workingdayCreatedDate').val(createdDate);

    let fieldWatcher_edit = function() {
      // console.log('Field watcher triggered');
      let notAllEmpty =
        $('#workingdaymodal_title').val() != '' &&
        $('#workingdaymodal_ofc').val() != '' &&
        $('#workingdaymodal_startdate').val() != '' &&
        $('#workingdaymodal_enddate').val() != '' &&
        $('#workingdaymodal_desc').val() != '' &&
        $('#workingdaymodal_region').val() != '';

      let allTheSame =
        $('#workingdaymodal_title')
          .val()
          .trim() == title &&
        $('#workingdaymodal_ofc').val() == officeId &&
        $('#workingdaymodal_startdate').val() == startdate &&
        $('#workingdaymodal_enddate').val() == enddate &&
        $('#workingdaymodal_desc')
          .val()
          .trim() == workingdayDescFull &&
        $('#workingdaymodal_region').val() == region;
      $('#btnWorkingdayClick').attr('disabled', !(notAllEmpty && !allTheSame));
    };
    $('#workingdayItemModal')
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
    // $('#btnWorkingdayClick').attr('disabled',false);
    $('#btnWorkingdayDelete').attr({
      disabled: false,
      hidden: false
    });
    if(!canupdate) {
      $('#btnWorkingdayClick').html('');
      $('#btnWorkingdayClick').attr('onClick', '');
      $('#btnWorkingdayClick').hide();
      $('#btnWorkingdayDelete').remove();
      $('#workingday-footer').hide();
    }
    $('#workingdayItemModal').modal('show');
  });
}
function addWorkingdayItem() {
  console.log('Adding');
  let url = getAPIURL() + 'workingday.php';
  let f = 'addWorkingdays';

  let title = $('#workingdaymodal_title').val();
  let ofc = $('#workingdaymodal_ofc option:selected').val();
  let startdate = $('#workingdaymodal_startdate').val();
  let enddate = $('#workingdaymodal_enddate').val();
  let desc = $('#workingdaymodal_desc').val();
  let region = $('#workingdaymodal_region option:selected')
    .val()
    .toLowerCase();
  let days_count = $('#numberOfDaysOfWorkingday').val();
  let userid = $('#workingdayUserId').val();
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
      loadWorkingdayList();
      $.unblockUI();
    },
    error: function(request, status, err) {
      alert('Something went wrong, please contact the IT admin: ' + err);
      $.unblockUI();
    }
  });
}

function updateWorkingdayItem() {
  console.log('Updating');
  let url = getAPIURL() + 'workingday.php';
  let f = 'updateWorkingdays';

  let title = $('#workingdaymodal_title').val();
  let ofc = $('#workingdaymodal_ofc option:selected').val();
  let startdate = $('#workingdaymodal_startdate').val();
  let enddate = $('#workingdaymodal_enddate').val();
  let region = $('#workingdaymodal_region option:selected')
    .val()
    .toLowerCase();
  let desc = $('#workingdaymodal_desc').val();

  let id = $('#workingdayModalId').val();
  let userid = $('#workingdayUserId').val();
  let createdby = $('#workingdayCreatedBy').val();
  let createddate = $('#workingdayCreatedDate').val();
  let days_count = $('#numberOfDaysOfWorkingday').val();

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
      console.log(data);
      loadWorkingdayList();
      $.unblockUI();
    },
    error: function(request, status, err) {
      alert('Something went wrong, please contact the IT admin: ' + err);
      $.unblockUI();
    }
  });
}
