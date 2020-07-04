$(function () { 
  // new Treant( chart_config );
  $('#searchEmployee').focus();

  $('#btnSearch').on('click', function () {
    var sData = $('#searchEmployee').val();
    if (sData == '' || sData == null) {
      alert('Please enter employee name to search');
      $('#searchEmployee').focus();
      return false;
    }
    $.blockUI({
      message: $('#preloader_image'),
      fadeIn: 1000,
      onBlock: function () {
        searchEmployee();
      }
    });
  });

  $('#searchEmployee').keydown(function (event) {
    var keyCode = event.keyCode ? event.keyCode : event.which;
    if (keyCode == 13) {
      searchee();
    }
  });

  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function () {
      loadEmployeeData();
    }
  });
});

function loadEmployeeData() {
  var url = getAPIURL() + 'contactlist.php';
  var f = 'loadEmployeeData';
  var userid = $('#userid').val();
  var profilegroup = $('#profilegroup').val();
  var action = '';
  var sesid = '';
  var data = {
    f: f,
    userid: userid,
    profilegroup: profilegroup,
    sesid: sesid,
    action: action
  };

  // console.log(data);
  // return false;

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function (data) {
      // console.log(data);
      // return false;
      var eedata = data['eedata']['rows'][0];
      var abappl = data['abappl'];
      var eedesignationini = eedata['eedesignationini'];
      genEmployeeDirectory(abappl);
      genEmployeeInfo(eedata);

      if (eedesignationini == 'ADMIN') {
        $('#editProfile').show();
      }
      $.unblockUI();
    },
    error: function (request, status, err) { }
  });
}

function genEmployeeInfo(data) {
  //personal info
  var lastname = data['lname'] == '' || data['lname'] == null ? '' : data['lname'];
  var firstname = data['fname'] == '' || data['fname'] == null ? '' : data['fname'];
  var cnname = data['cnname'] == '' || data['cnname'] == null ? '' : data['cnname'];
  var nationality = data['nationalitydesc'] == '' || data['nationalitydesc'] == null ? '' : data['nationalitydesc'];
  var maritalstatus = data['maritalstat'] == '' || data['maritalstat'] == null ? '' : data['maritalstat'];
  var birthdate = data['birthdt'] == '' || data['birthdt'] == null ? '' : data['birthdt'];
  var gender = data['gender'] == '' || data['gender'] == null ? '' : data['gender'];
  var fullname = firstname + ' ' + lastname;
  var avatarorig = data['avatarorig'] == '' || data['avatarorig'] == null ? '' : data['avatarorig'];
  var imgsourcepath = 'img/ees/';
  var designationname = data['designationnamedesc'] == '' || data['designationnamedesc'] == null ? '' : data['designationnamedesc'];
  var ofc = data['officename'] == '' || data['officename'] == null ? '' : data['officename'];
  var reportstoname = data['reportstoname'] == '' || data['reportstoname'] == null ? '' : data['reportstoname'];
  var reportstoindirectname = data['reportstoindirectname'] == '' || data['reportstoindirectname'] == null ? '' : data['reportstoindirectname'];
  var telext = data['telext'] == '' || data['telext'] == null ? '' : ' local ' + data['telext'];
  var officephoneno = data['officephoneno'] == '' || data['officephoneno'] == null ? '' : data['officephoneno'] + telext;

  checkImage(imgsourcepath + avatarorig, (exists) => {
      const imgtag = (url) => '<img style="width: 50%; height: auto;" src="' + url + '">';
      let imgfile = '';
      if(exists && avatarorig != '') {
          imgfile = imgtag(imgsourcepath + avatarorig);
      } else {
          imgfile = imgtag(`${imgsourcepath}default.svg`);
      }
      $('#profilepic').html(imgfile);
  });

  switch (gender) {
    case 'f':
      gender = 'Female';
      break;
    case 'm':
      gender = 'Male';
      break;
    default:
      gender = '';
      break;
  }
  $('#txtFullName').html(fullname);
  $('#txtChinesename').html(cnname);
  $('#txtPosition').html(designationname);
  $('#txtOffice').html(ofc);
  $('#txtChinesename').html(cnname);
  $('#txtNationality').html(nationality);
  $('#txtMaritalStatus').html(maritalstatus);
  $('#txtBirthdate').html(birthdate);
  $('#txtGender').html(gender);
  $('#dataReportingTo').html(reportstoname);
  //reportstoindirectname
  var indirecthtml = '';
  $('#dataReportingToIndirect').html('');
  if (reportstoindirectname != '') {
    indirecthtml = '<div class="col-md-6">';
    indirecthtml += '<label>Reporting to indirect</label>';
    indirecthtml += '</div>';
    indirecthtml += '<div class="col-md-6">';
    indirecthtml += '<p>' + reportstoindirectname + '</p>';
    indirecthtml += '</div>';
    $('#dataReportingToIndirect').html(indirecthtml);
  }
  // console.log(data);
  $('#dataOfcPhoneNo').html('<a href="tel:' + officephoneno + '">' + officephoneno + '</a>');

  //work contact details
  // console.log(data);
  var workeadd = data['workemail'] == '' || data['workemail'] == null ? '' : data['workemail'];
  var workskype = data['skype'] == '' || data['skype'] == null ? '' : data['skype'];
  var wechat = data['wechat'] == '' || data['wechat'] == null ? '' : data['wechat'];
  var whatsapp = data['whatsapp'] == '' || data['whatsapp'] == null ? '' : data['whatsapp'];
  // var emercontactperson = data['emercontactperson'] == "" || data['emercontactperson'] ==  null ? "" : data['emercontactperson'];
  // var emercontactno = data['emercontactno'] == "" || data['emercontactno'] ==  null ? "" : data['emercontactno'];
  // var emercontactrelation = data['emercontactrelation'] == "" || data['emercontactrelation'] ==  null ? "" : data['emercontactrelation'];
  var mobphone = data['mobileno'] == '' || data['mobileno'] == null ? '' : data['mobileno'];

  $('#txtWorkEmail').html('<a href="mailto:' + workeadd + '">' + workeadd + '</a>');
  $('#txtMobileNo').html('<a href="tel:' + mobphone + '">' + mobphone + '</a>');
  $('#txtWorkSkype').html(workskype);
  $('#txtWhatsapp').html('<a href="tel:' + whatsapp + '">' + whatsapp + '</a>');
  $('#txtWeChat').html(wechat);
  // $("#txtEmergencyContactPerson").html(emercontactperson);
  // $("#txtEmergencyPhoneNo").html(emercontactno);
  // $("#txtRelationship").html(emercontactrelation);
  $('#sesid').val(data['sesid']);
}

function genEmployeeDirectory(data) {
  var rows = data['rows'];
  // var html="";
  // var viewee = "";
  // var cnt = 0;
  var ctctslist = $('#contactlistdatatable').DataTable({
    data: rows,
    responsive: true,
    language: {
      emptyTable: 'No contacts found'
    },
    lengthMenu: [
      [25, 50, 100, -1],
      [25, 50, 100, 'All']
    ],
    columns: [{ data: 'abaini' }, { data: 'eename' }, { data: 'designationnamedesc' }, { data: 'officename' }],
    fnDrawCallback: function () {
      // resize filter --------------------------
      $('#contactlistdatatable_length')
        .parent()
        .removeAttr('class')
        .attr('class', 'col-2');
      // .attr('style', 'display:none;');

      $('#contactlistdatatable_filter')
        .parent()
        .removeAttr('class')
        .attr('class', 'col-10');
      // ----------------------------------------
      let dataTable = $('#contactlistdatatable').dataTable();
      if ($('#ofcFilterContacts').length == 0) {
        // office filter --------------------------------------
        let ofcSelect = '<span id="ofcFilterContainer">';
        ofcSelect += 'Office: <label style="padding-right: 2vh; width: 15vh; white-space: nowrap;">';
        ofcSelect += '<select class="form-control form-control-sm" id="ofcFilterContacts" name="ofcFilterContacts" aria-controls="contactlistdatatable">';
        ofcSelect += '<option value="all">all</option>';
        let distinctOffice = [...new Set(rows.map(rowItem => rowItem.officename))];
        distinctOffice.forEach(officeName => {
          if (officeName != null) {
            ofcSelect += '<option value="' + officeName + '">' + officeName + '</option>';
          }
        });
        ofcSelect += '</select>';
        ofcSelect += '</label>';
        ofcSelect += '</span>';
        // ---------------------------------------------------
        $('#contactlistdatatable_filter').prepend(ofcSelect);
        $('#ofcFilterContacts').on('change', function () {
          let filteredOfc = $('#ofcFilterContacts option:selected').text();
          let finalFilter = '';

          if (filteredOfc != 'all') {
            finalFilter = rows.filter(rowItem => rowItem.officename == filteredOfc);
          } else {
            finalFilter = rows;
          }

          dataTable.fnClearTable();
          if (finalFilter.length > 0) {
            dataTable.fnAddData(finalFilter);
          }
        });
      }
    }
    // ,
    // columnDefs: [ {
    //      targets: 2,
    //      render: $.fn.dataTable.render.ellipsis( 25 )
    //    } ]
  });

  $('#contactlistdatatable tbody').on('click', 'tr', function () {
    var thisdata = ctctslist.row(this).data();
    // console.log(vdata);
    // alert (thisdata['userid']);
    userid = thisdata['userid'];
    var url = getAPIURL() + 'contactlist.php';
    var f = 'getEmployeeInfo';
    var profilegroup = $('#profilegroup').val();
    var action = '';
    var sesid = '';
    var data = {
      f: f,
      userid: userid,
      profilegroup: profilegroup,
      sesid: sesid,
      action: action
    };

    // console.log(data);
    // return false;

    $.blockUI({
      message: $('#preloader_image'),
      fadeIn: 1000,
      onBlock: function () {
        $.ajax({
          type: 'POST',
          url: url,
          data: JSON.stringify({ data: data }),
          dataType: 'json',
          success: function (data) {
            // console.log(data);
            // return false;
            var eedata = data['eedata']['rows'][0];
            // var eedesignationini = eedata['eedesignationini'];
            genEmployeeInfo(eedata);
            $.unblockUI();
          },
          error: function (request, status, err) { }
        });
      }
    });
  });
}

function searchee() {
  var sData = $('#searchEmployee').val();
  if (sData == '' || sData == null) {
    // alert("Please enter employee name to search");
    // $("#searchEmployee").focus();
    return false;
  }
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function () {
      searchEmployee();
    }
  });
}

function searchEmployee() {
  var url = getAPIURL() + 'contactlist.php';
  var f = 'searchEmployee';
  var sData = $('#searchEmployee').val();
  var data = { f: f, search: sData };

  // console.log(data);
  // return false;

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function (data) {
      // console.log(data);
      // return false;

      var abappl = data['abappl'];
      var rows = abappl['rows'];
      var eedata = abappl['rows'][0];

      genEmployeeDirectory(abappl);
      if (rows.length > 0) {
        genEmployeeInfo(eedata);
      }
      $('#searchEmployee').select();
      $.unblockUI();
    },
    error: function (request, status, err) { }
  });
}

function viewee(userid) {
  $('#userid').val(userid);
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function () {
      loadEmployeeData();
    }
  });
}

function editee() {
  var id = $('#sesid').val();
  var action = btoa('editprofile');
  // alert(id);
  window.location = 'profile-edit.php?id=' + id + '&action=' + action;
  return false;
}

function viewgm() {
  var id = $('#sesid').val();
  // alert(id);
  window.location = 'profile.php?id=' + id + '&action=' + btoa('viewbygm') + '&s=' + btoa('inactive');
  return false;
}

jQuery.fn.dataTable.render.ellipsis = function (cutoff, wordbreak, escapeHtml) {
  var esc = function (t) {
    return t
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  };

  return function (d, type, row) {
    // Order, search and type get the original data
    if (type !== 'display') {
      return d;
    }

    if (typeof d !== 'number' && typeof d !== 'string') {
      return d;
    }

    d = d.toString(); // cast numbers

    if (d.length <= cutoff) {
      return d;
    }

    var shortened = d.substr(0, cutoff - 1);

    // Find the last white space character in the string
    if (wordbreak) {
      shortened = shortened.replace(/\s([^\s]*)$/, '');
    }

    // Protect against uncontrolled HTML input
    if (escapeHtml) {
      shortened = esc(shortened);
    }

    return '<span class="ellipsis" title="' + esc(d) + '">' + shortened + '&#8230;</span>';
  };
};
