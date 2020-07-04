const accessitem_ofc = $('#viewofc').val().split(',');
$(function() {
  //Initialize tooltips
  $('#btnUploadPhoto').change(function() {
    // alertDialog('hi!');
    readURL(this);
  });
  $('.nav-tabs > li a[title]').tooltip();

  //Wizard
  $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
    $target = $(e.target);
    if ($target.parent().hasClass('disabled')) {
      return false;
    }
  });

  generateInitial('#txtFirstname', '#txtLastname', '#txtNameAbvt',function(){
    $('#txtNameAbvt').trigger('change');
    $('#txtNameAbvt').trigger('keyup');
  });

  $('#txtNameAbvt').on('change', function() {
    $('#iniexist').val(0);
    if($(this).val() != '')
      checkini($(this).val());
  }).on('keyup',  function(){
    $(this).trigger('change');
  });
  function checkini(inival) {
    var url = getAPIURL() + 'eesprofile.php';
    var f = 'checkini';

    var data = { f: f, inival: inival };
    $.ajax({
      type: 'POST',
      url: url,
      data: JSON.stringify({ data: data }),
      dataType: 'json',
      success: function(data) {
        // console.log(data);
        $('#iniexist').val(data.isExist);
      },
      error: function(request, status, err) {}
    });
  }

  $('.next-step').click(function(e) {
    $active = $('.nav-tabs li>.active');
    if ($active[1]['id'] == 'personal-tab') {
      if ($('#txtSalutation').val() == '') {
        alertDialog('Please select Salutation.');
        return false;
      }
      if ($('#txtLastname').val() == '') {
        alertDialog('Please enter Last Name.');
        return false;
      }
      if ($('#txtFirstname').val() == '') {
        alertDialog('Please enter First Name.');
        return false;
      }
      if ($('#txtMaritalStat').val() == '') {
        alertDialog('Please select Marital Status.');
        return false;
      }
      if ($('#txtBirthdate').val() == '') {
        alertDialog('Please enter Birthdate.');
        return false;
      }
      if ($('#txtgender').val() == '') {
        alertDialog('Please select Gender.');
        return false;
      }
      if ($('#txtNameAbvt').val() == '') {
        alertDialog('Please enter Name abbreviation, please choose a different one.');
        return false;
      }
      if ($('#iniexist').val() == 1) {
        alertDialog('Name abbreviation already exist.');
        return false;
      }
    } else if ($active[1]['id'] == 'contact-tab') {
      if ($('#txtEmailAddress').val() == '') {
        alertDialog('Please enter Personal Email Address.');
        return false;
      }
      if ($('#txtMobileNo').val() == '') {
        alertDialog('Please enter Mobile No.');
        return false;
      }
      if ($('#txtHomePhone').val() == '') {
        alertDialog('Please enter Home Phone No.');
        return false;
      }
      if ($('#txtPresentAdr').val() == '') {
        alertDialog('Please enter Street.');
        return false;
      }
      if ($('#txtCity').val() == '') {
        alertDialog('Please enter City.');
        return false;
      }
      if ($('#txtCountry').val() == '') {
        alertDialog('Please enter Country.');
        return false;
      }
      if ($('#txtZipCode').val() == '') {
        alertDialog('Please enter Zip Code.');
        return false;
      }
      if ($('#txtEmergencyContactPerson').val() == '') {
        alertDialog('Please enter Emergecy Contact Person.');
        return false;
      }
      if ($('#txtEmergencyPhoneNo').val() == '') {
        alertDialog('Please enter Emergecy Phone No.');
        return false;
      }
      if ($('#txtRelationship').val() == '') {
        alertDialog('Please enter Relationship of the contact person.');
        return false;
      }
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadShiftTimes();
        }
      });
    } else if ($active[1]['id'] == 'employee-tab') {
      if ($('#txtJoinedDate').val() == '') {
        alertDialog('Please enter Joined Date.');
        return false;
      }
      if ($('#txtOffices').val() == '') {
        alertDialog('Please select office.');
        return false;
      }
      if ($('#txtDept').val() == '') {
        alertDialog('Please select Department.');
        return false;
      }
      if ($('#txtPositions').val() == '') {
        alertDialog('Please select Position.');
        return false;
      }
      if ($('#txtEECat').val() == '') {
        alertDialog('Please select Employee Status.');
        return false;
      }
      if ($('#txtRanking').val() == '') {
        alertDialog('Please select Ranking.');
        return false;
      }
      if ($('#txtRepto').val() == '') {
        alertDialog('Please enter Direct Head.');
        return false;
      }
      if ($('#shiftschedFrom option:selected').val() == 0 || $('#shiftschedTo option:selected').val() == 0) {
        alertDialog('Please provide shift schedule.');
        return false;
      }
      if ($('#txtEmailAddress1').val() == '') {
        alertDialog('Please enter Email Address under Work Contact Details.');
        return false;
      }
      if ($('#txtOfficeNo').val() == '') {
        alertDialog('Please enter Office No  under Work Contact Details.');
        return false;
      }
      if ($('#txtSkype1').val() == '') {
        alertDialog('Please enter Skype under Work Contact Details.');
        return false;
      }
      var eeid = $('#eeid').val();
      if (eeid == '') {
        $.blockUI({
          message: $('#preloader_image'),
          fadeIn: 1000,
          onBlock: function() {
            saveNewEmployeeInfo();
          }
        });
      }
    } else if ($active[1]['id'] == 'compensationandbenefits-tab') {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadCertifications();
        }
      });
      // $.blockUI({
      //   message: $('#preloader_image'),
      //   fadeIn: 1000,
      //   onBlock: function() {
      //     updateAccountSettings();
      //   }
      // });
    } else if ($active[1]['id'] == 'certification-tab') {
    }
    $active
      .parent()
      .next()
      .find('.nav-link')
      .removeClass('disabled');
    nextTab($active);
  });

  $('.prev-step').click(function(e) {
    $active = $('.nav-tabs li>a.active');
    prevTab($active);
  });

  $(
    '#txtBirthdate,#txtProbationPeriod,#txtProbationStartDate,#txtVisaExpDate,#txtJoinedDate,#effectivedate,' +
      '#txtProbationComplete,#txtLastWorkingDt,#txtEffectiveDate,#txtRecentCtrctDt,#txtIssuedDate,' +
      '#txtRegularizationDate, #txtEndOfEmploymentDate,#txtStartOfVisa,#txtProbationEndDate,#txtStartContractDate,' +
      '#txtEndContractDate'
  ).datepicker({
    // minDate: -6,
    dateFormat: 'D d M yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1900:2050'
  });

  $('#txtExpirationDate').datepicker({
    // minDate: -6,
    dateFormat: 'D d M yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1900:2050'
  });

  $(
    '#txtBirthdate,#txtProbationPeriod,#txtProbationStartDate,#txtVisaExpDate,#txtJoinedDate,#effectivedate,#txtProbationComplete,' +
      '#txtLastWorkingDt,#txtEffectiveDate,#txtRecentCtrctDt,#txtIssuedDate,#txtExpirationDate,' +
      '#txtRegularizationDate, #txtEndOfEmploymentDate,#txtStartOfVisa, #txtProbationEndDate,#txtStartContractDate,' +
      '#txtEndContractDate'
  ).prop('readonly', true);

  $('#AddNewEmployee').on('click', function() {
    document.getElementById('onboard-tab').hidden = false;
    $('#onboard-tab').trigger('click');
    $('#personal-tab').trigger('click');
    $('#AddNewEmployee').hide();
  });
  $('#employeelists-tab')
    .on('click', function() {
      $('#AddNewEmployee').show();
      $('#contact-tab').off('click');
      $('#employee-tab').off('click');
      // $('a.active[data-toggle="tab"]').removeClass("active");
      document.getElementById('onboard-tab').hidden = true;
      clearAllFields();
    })
    .on('dblclick', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadEmployeeData();
        }
      });
    });
  $('#onboard-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('personalinfo');
    });

  $('#employee-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('employeedata');
    });

  $('#contact-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('contactinfo');
    });

  $('#accountsettings-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('accountsettings');
    });

  $('#txtMobileNo,#txtHomePhone,#txtEmergencyPhoneNo,#txtOfficeNo,#txtWhatsapp').keypress(function(e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 && e.which != 43 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });

  $('#txtZipCode').keypress(function(e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which < 48 || e.which > 57) {
      return false;
    }
  });
  // $('#compensationandbenefits-tab').one('click', function() {
  //     $("#profilegroup").val('compensationandbenefits');
  //     $.blockUI({
  //       message: $('#preloader_image'),
  //       fadeIn: 1000,
  //       onBlock: function() {
  //         loadCompensationBenefits();
  //       }
  //     });
  // });
  // #btnSaveChanges1,#btnSaveChanges2,,#BacktoCtcInfo"

  $('#BackToPersoInfo').on('click', function() {
    $('#personal-tab').trigger('click');
  });
  $('#BackToContactInfo').on('click', function() {
    $('#contact-tab').trigger('click');
  });
  $('#BackToEEData').on('click', function() {
    $('#employee-tab').trigger('click');
  });
  $('#BackToCompBen').on('click', function() {
    $('#compensationandbenefits-tab').trigger('click');
  });

  // certificate -------------------------------------------------------------
  $('#certificationModal').on('hidden.bs.modal', function() {
    $('#certificatename').val('');
    $('#certificateorganization').val('');
    $('#certificateissuemonth option[value="0"]').prop('selected', true);
    $('#certificateissueyear option[value="0"]').prop('selected', true);
    $('#certificatenoexpiry').prop('checked', true);
    $('#certificateexpirymonth option[value="0"]').prop('selected', true);
    $('#certificateexpiryyear option[value="0"]').prop('selected', true);
    $('#certificateexpirymonth').attr('disabled', true);
    $('#certificateexpiryyear').attr('disabled', true);
    $('#certificateattachment').val('');
    $('#certificatepreview').html('');
    $('#btnCertificateSave').html('Save Changes');
    $('#btnCertificateDelete').attr('hidden', true);
    $('#ses_id').val('');
    $('#certuserid').val('');
    $('#byuserid').val('');
    $('#certid').val('');
  });

  $('#certificateattachment').on('change', function() {
    if ($(this).val() != '') {
      $('#certificateUploadModal').modal('show');
    }
  });

  $('#btnUploadCertificateNo').on('click', function() {
    $('#certificateUploadModal').modal('hide');
    $('#certificateattachment').val('');
  });

  $('#btnUploadCertificateYes').on('click', function() {
    $('#certificateUploadModal').modal('hide');
    // console.log(new FormData(this));
    const mbSize = 1048576;
    var fd = new FormData();
    var files = $('#certificateattachment')[0].files[0];
    fd.append('file', files);
    let fileType = files.type;

    let outputhtml = function(fileType, response) {
      // console.log(fileType);
      if (fileType.includes('image')) {
        return (
          '<img src="' + response + '" width="100px" height="100px" style="display: inline-block; object-fit: cover; object-position: center; color: #c3282d !important"></img>'
        );
      } else if (fileType == 'application/pdf') {
        return '<i class="fas fa-file-pdf fa-5x" style="color: #c3282d !important"></i>';
      } else if (fileType == 'application/msword' || fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        return '<i class="fas fa-file-word fa-5x" style="color: #c3282d !important"></i>';
      } else {
        return '<i class="fas fa-paperclip fa-5x" style="color: #c3282d !important"></i>';
      }
    };

    // AJAX request
    $.ajax({
      url: 'controllers/certificateattachment_controller.php',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,
      success: function(response) {
        if (files.size >= 3 * mbSize) {
          alertDialog('File size limit exceeded, only <= 3MB accepted.');
          $('#certificateattachment').val('');
        } else {
          if (response == 0) {
            alertDialog('File not uploaded, please try again.');
            $('#certificateattachment').val('');
          } else if (response == 1) {
            alertDialog('File size limit exceeded, only <= 3MB accepted.');
            $('#certificateattachment').val('');
          } else {
            $('#certificatepreview').html(outputhtml(fileType, response));
          }
        }
      }
    });
  });

  $('#addCertBtn').on('click', function() {
    $('#btnCertificateSave').html('Add New');
    $('#ses_id').val($('#sesid').val());
    $('#certuserid').val($('#eeid').val());
    $('#byuserid').val($('#userid').val());
    certificateWatcher();
    $('#certificationModal').modal('show');
  });

  function certificateWatcher() {
    let isEmpty =
      $('#certificatename').val() == '' ||
      $('#certificateorganization').val() == '' ||
      $('#certificateissuemonth option:selected').val() == 0 ||
      $('#certificateissueyear option:selected').val() == 0 ||
      $('#certificateattachment').val() == '';
    let isExpiryEmpty;
    if ($('#certificatenoexpiry').prop('checked')) {
      isExpiryEmpty = false;
    } else {
      isExpiryEmpty = $('#certificateexpirymonth').val() == 0 || $('#certificateexpiryyear').val() == 0;
    }
    if (!isEmpty && !isExpiryEmpty) {
      $('#btnCertificateSave').removeAttr('disabled');
    } else {
      $('#btnCertificateSave').attr('disabled', true);
    }
  }
  $('#certificationModal')
    .on('change', function() {
      certificateWatcher();
    })
    .on('click', function() {
      certificateWatcher();
    })
    .on('keypress', function() {
      certificateWatcher();
    });

  $('#btnCertificateSave').on('click', function() {
    let btnName = this.innerHTML;
    if (btnName == 'Add New') {
      addNewCertificate();
    } else if (btnName == 'Save Changes') {
      updateCertificate();
    }
  });

  $('#btnCertificateDelete').on('click', function() {
    $('#certificateDeleteModal').modal('show');
  });
  $('#deleteCertificateYes').on('click', function() {
    $('#certificateDeleteModal').modal('hide');
    deleteCertificate();
  });

  $('#certificatenoexpiry').on('click', function() {
    let isChecked = $(this).prop('checked');
    $('#certificateexpirymonth').attr('disabled', isChecked);
    $('#certificateexpiryyear').attr('disabled', isChecked);
    if (isChecked) {
      $('#certificateexpirymonth option[value="0"]').prop('selected', true);
      $('#certificateexpiryyear option[value="0"]').prop('selected', true);
    }
  });

  $('#certificateissueyear, #certificateexpiryyear').on('change', function() {
    let certissue = $('#certificateissueyear option:selected').val();
    let certexpiry = $('#certificateexpiryyear option:selected').val();

    if (certissue > certexpiry && !$('#certificatenoexpiry').prop('checked')) {
      $('#certificateexpiryyear option[value="' + certissue + '"]').prop('selected', true);
    }
  });
  // end of certificate -------------------------------------------------------------

  // shift schedule -------------------------------------------------------------
  $('#shiftschedFrom, #shiftschedTo').on('change', function(e) {
    const ssFrom = 'shiftschedFrom';
    const ssTo = 'shiftschedTo';
    let shiftfrom = parseInt($('#' + ssFrom).val() + '');
    let shiftto = parseInt($('#' + ssTo).val() + '');
    if (shiftfrom > shiftto) {
      // if(e.target.id == ssFrom){
      $('#' + ssTo + ' option[value="' + shiftfrom + '"]').prop('selected', true);
      // } else if(e.target.id == ssTo){
      // $('#' + ssTo + ' option[value="'+ shiftfrom +'"]').prop('selected',true);
      // }
    }
  });
  function loadShiftTimes() {
    let timeschedhtml = '';
    let timeTmp;
    for (let hour_v = 0; hour_v < 24; hour_v++) {
      timeTmp = hour_v < 10 ? '0' + hour_v : hour_v;
      valTmp = hour_v == 0 ? '' : hour_v;
      timeschedhtml += '<option value="' + valTmp + '00' + '">' + timeTmp + ':00' + '</option>';
      timeschedhtml += '<option value="' + valTmp + '30' + '">' + timeTmp + ':30' + '</option>';
    }
    $('#shiftschedFrom').html(timeschedhtml);
    $('#shiftschedTo').html(timeschedhtml);
    // console.log(timeschedhtml);
  }
  // end of 'shift schedule' -------------------------------------------------------------

  $('#btnUpdate').on('click', function() {
    // if($('#txtZkOffice').val() == ''){
    //     alertDialog('Please select Office.');
    //     return false;
    // }
    // if($('#txtZKID').val() == ''){
    //     alertDialog('Please enter ZKID.');
    //     return false;
    // }
    $.blockUI({
      message: $('#preloader_image'),
      fadeIn: 1000,
      onBlock: function() {
        updateAccountSettings();
        window.location.reload();
      }
    });
  });

  $('#bntSaveLeaveBen').on('click', function() {
    updateLeaveBenefitsData();
  });

  $('#genLeaveQuota').on('click', function() {
    $.blockUI({
      message: $('#preloader_image'),
      fadeIn: 1000,
      onBlock: function() {
        generateLeaveQuota();
      }
    });
  });

  $('#genHMO').on('click', function() {
    $.blockUI({
      message: $('#preloader_image'),
      fadeIn: 1000,
      onBlock: function() {
        generateHMO();
      }
    });
  });

  $('#btnChangePhoto').on('click', function() {
    var eeid = $('#eeid').val();
    $('#frmuploadphoto').modal('show');
    $('#txteeid').val(eeid);
  });

  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      loadEmployeeData();
    }
  });
});

function nextTab(elem) {
  $(elem)
    .parent()
    .next()
    .find('a[data-toggle="tab"]')
    .click();
}
function prevTab(elem) {
  $(elem)
    .parent()
    .prev()
    .find('a[data-toggle="tab"]')
    .click();
}

function loadEmployeeData() {
  url = getAPIURL() + 'employees.php';
  f = 'loadEmployeeData';
  var statFilter;
  if (statFilter == undefined) {
    statFilter = 2;
  }
  var ofc = $('#ofc').val();
  // var userid = $('#userid').val();
  // var showAll = $('#accesslvl').val() == 1 ? 1 : 0;
  data = { f: f, statFilter: statFilter, ofc: ofc };

  // console.log(data);
  // return false;
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      // return false;
      const allabaeelist = data.eedata.rows;

      const ofclist = data.offices;
      // console.log(accessitem_ofc);shi
      let ofcshown = [];
      if(ofclist.length === accessitem_ofc.length){
        ofcshown = ofclist;
      } else {
        ofcshown = ofclist.filter(ofc => {
          let ofc_exist = false;
          accessitem_ofc.map(ofcitem => {
            if(!ofc_exist) ofc_exist = ofcitem === ofc.id;
          });
          return ofc_exist;
        });
      }
      
      const abaeelist = allabaeelist.filter(abaeeitem => {
        let ofc_exist = false;
        ofcshown.map(ofcitem => {
          if(!ofc_exist) ofc_exist = ofcitem.id === abaeeitem.office;
        });
        return ofc_exist;
      });

      if (!$.fn.DataTable.isDataTable('#abaeelistdatatable')) {
        abaeelisttbl = $('#abaeelistdatatable').DataTable({
          responsive: true,
          data: abaeelist,
          lengthMenu: [
            [25, 50, 100, -1],
            [25, 50, 100, 'All']
          ],
          language: {
            emptyTable: '<center>No employee list</center>'
          },
          columns: [
            { data: 'eename' },
            { data: 'designationnamedesc' },
            { data: 'departmentdesc' },
            { data: 'officename' },
            { data: 'joindt' },
            { data: 'statusname' }
            // { data: null, className: "text-center", defaultContent: '<a href="" class="editor_edit"><i class="fas fa-edit"></i></a>'},
            // { data: 'sesid'},
          ],
          fnDrawCallback: function() {
            // resize filter --------------------------
            $('#abaeelistdatatable_length')
              .parent()
              .removeAttr('class')
              .attr('class', 'col-sm-12 col-md-2');

            $('#abaeelistdatatable_filter')
              .parent()
              .removeAttr('class')
              .attr('class', 'col-sm-12 col-md-10');
            // ----------------------------------------
            $('#abaeelistdatatable_filter label input').attr('id', 'abaeelistdatatable_search');
            let dataTable = $('#abaeelistdatatable').dataTable();
            if ($('#statusFilter').length == 0 && $('#stationFilter').length == 0) {
              // Status
              let statusSelect = '';
              statusSelect += '<span id="statusFilterDiv">';
              statusSelect += 'Status: <label style="padding-right: 1vh; width: 22vh; white-space: nowrap;">';
              statusSelect += '<select class="form-control form-control-sm" id="statusFilter" name="statusFilter" aria-controls="abaeelistdatatable">';
              statusSelect += '<option value="2" selected>active or inactive</option>';
              statusSelect += '<option value="1">active</option>';
              statusSelect += '<option value="0">inactive</option>';
              statusSelect += '</select>';
              statusSelect += '</label>';
              statusSelect += '</span>';
              $('#abaeelistdatatable_filter').prepend(statusSelect);

              // Station
              let hideStation = 'style="display:none;"';
              if (ofcshown.length > 1) {
                hideStation = '';
              }
              let stationSelect = '';
              stationSelect += '<span id="stationFilterDiv" ' + hideStation + '>';
              stationSelect += 'Station: <label style="padding-right: 1vh; width: 13vh; white-space: nowrap;">';
              stationSelect += '<select class="form-control form-control-sm" id="stationFilter" name="stationFilter" aria-controls="abaeelistdatatable">';
              stationSelect += '<option value="all">all</option>';

              // get list of offices
              // let distinctStation = [...new Set(abaeelist.map(abaee => abaee.officename))];
              // distinctStation.forEach(stationName => {
              //   if (stationName != null) {
              //     stationSelect += '<option value="' + stationName + '">' + stationName + '</option>';
              //   }
              // });
              ofcshown.map(ofcitem => {
                stationSelect += `<option value="${ofcitem.id}">${ofcitem.ini}</option>`;
              });
              stationSelect += '</select>';
              stationSelect += '</label>';
              stationSelect += '</span>';
              $('#abaeelistdatatable_filter').prepend(stationSelect);

              $('#abaeelistdatatable_filter label input').attr('style', 'width:25vh;');

              $('#statusFilter, #stationFilter').on('change', function() {
                let filteredStat = $('#statusFilter option:selected').text();
                let filteredStation = $('#stationFilter option:selected').text();
                let finalFilter = '';

                let statIsFiltered = filteredStat != 'active or inactive';
                let stationIsFiltered = filteredStation != 'all';
                if (statIsFiltered && stationIsFiltered) {
                  finalFilter = abaeelist.filter(abaee => {
                    if(filteredStation.includes('hk')){
                      return abaee.statusname == filteredStat && (abaee.officename).includes('hk');
                    }
                    return abaee.statusname == filteredStat && abaee.officename == filteredStation;
                  });
                } else if (statIsFiltered) {
                  finalFilter = abaeelist.filter(abaee => abaee.statusname == filteredStat);
                } else if (stationIsFiltered) {
                  finalFilter = abaeelist.filter(abaee => {
                    if(filteredStation.includes('hk')){
                      return (abaee.officename).includes('hk');
                    }
                    return abaee.officename == filteredStation
                  });
                } else {
                  finalFilter = abaeelist;
                }
                dataTable.fnClearTable();
                if (finalFilter.length > 0) {
                  dataTable.fnAddData(finalFilter);
                }
              });
              $("#statusFilter option[value='" + 1 + "']").prop('selected', true);
              $('#statusFilter').trigger('change');
            }
          }
          // ,
          // columnDefs:[
          //     {
          //         targets: [ 7 ],
          //         visible: false,
          //         searchable: false
          //     }
          // ],
        });
      } else {
        $('#abaeelistdatatable')
          .dataTable()
          .fnClearTable();
        if (abaeelist != undefined) {
          $('#abaeelistdatatable')
            .dataTable()
            .fnAddData(abaeelist);
        }
      }

      let params = new window.URLSearchParams(window.location.search);
      let fstatus = params.get('fstatus');
      let fstation = params.get('fstation');
      let searchq = params.get('searchq');

      $("#statusFilter option[value='" + fstatus + "']").prop('selected', true);
      $("#stationFilter option[value='" + fstation + "']").prop('selected', true);

      $('#statusFilter').trigger('change');
      $('#stationFilter').trigger('change');

      $('#abaeelistdatatable_filter label input')
        .val(searchq)
        .trigger('input');

      $('#abaeelistdatatable tbody').on('click', 'tr', function() {
        thisdata = abaeelisttbl.row(this).data();
        id = thisdata['sesid'];
        status = btoa(thisdata['statusname']);
        let filteredStatus = $('#statusFilter option:selected').val();
        let filteredStation = $('#stationFilter option:selected').val();
        let searchq = $('#abaeelistdatatable_search').val();
        // if (status == 'inactive') {
        // alertDialog(id);
        window.location =
          'profile.php?id=' + id + '&action=' + btoa('viewprofile') + '&s=' + status + '&fstatus=' + filteredStatus + '&fstation=' + filteredStation + '&searchq=' + searchq;

        // alertDialog('Employee is inactive. Please select other employee record to view.');
        // return false;
        // } else {
        //   window.location = 'profile-edit.php?id=' + id + '&action=' + btoa('editprofile');
        // }
        return false;
      });
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function loadDropdown() {
  url = getAPIURL() + 'employees.php';
  f = 'loadDropdown';
  profilegroup = $('#profilegroup').val();
  deptid = $('#txtDept').val();
  data = { f: f, profilegroup: profilegroup, deptid: deptid };

  //console.log(data);
  // return false;
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      //console.log(data);
      // return false;
      profilegroup = $('#profilegroup').val();
      countries = data['countries']['rows'];
      abappl = data['eedata']['rows'];
      switch (profilegroup) {
        case 'personalinfo':
          //salutations
          salutations = data['salutations'];
          salhtml = '';
          salhtml = '<select id="txtSalutation" class="form-control form-control-sm">';
          salhtml += '<option value="" selected></option>';
          for (i = 0; i < salutations.length; i++) {
            salhtml += '<option value="' + salutations[i]['ddid'] + '">' + salutations[i]['dddescription'] + '</option>';
          }
          salhtml += '</select>';
          $('#divtxtSalutation').html(salhtml);

          // nationalities
          natls = data['nationalities']['rows'];
          natslshtml = '';
          natslshtml = '<select id="txtNatl" class="form-control form-control-sm">';
          natslshtml += '<option value="" selected></option>';
          for (i = 0; i < natls.length; i++) {
            natslshtml += '<option value="' + natls[i]['nationalityid'] + '">' + natls[i]['description'] + '</option>';
          }
          natslshtml += '</select>';
          $('#divNationalities').html(natslshtml);
          // console.log(natslshtml);
          // marital status
          maritalstat = data['maritalstatus'];
          marstathtml = '';
          marstathtml = '<select id="txtMaritalStat" class="form-control form-control-sm">';
          marstathtml += '<option value="" selected></option>';
          for (i = 0; i < maritalstat.length; i++) {
            marstathtml += '<option value="' + maritalstat[i]['ddid'] + '">' + maritalstat[i]['dddescription'] + '</option>';
          }
          marstathtml += '</select>';
          $('#divMaritalStat').html(marstathtml);

          cntryhtml = '';
          cntryhtml = '<select id="txtPassportCountry" class="form-control form-control-sm">';
          cntryhtml += '<option value="" selected></option>';
          for (i = 0; i < countries.length; i++) {
            cntryhtml += '<option value="' + countries[i]['countryid'] + '">' + countries[i]['description'] + '</option>';
          }
          $('#divtxtPassport').html(cntryhtml);

          break;

        case 'contactinfo':
          countryhtml = '';
          countryhtml = '<select id="txtCountry" class="form-control form-control-sm">';
          countryhtml += '<option value=""></option>';
          for (i = 0; i < countries.length; i++) {
            countryhtml += '<option value="' + countries[i]['countryid'] + '">' + countries[i]['description'] + '</option>';
          }
          countryhtml += '</select>';
          $('#divCountries').html(countryhtml);
          break;

        case 'employeedata':
          // job positions
          // jobposthtml = "";
          // jobposthtml = '<select id="txtPositions" class="form-control form-control-sm">';
          // jobposthtml += '<option value="" selected></option>';
          //  for(i=0;i<jobpositions.length;i++){
          //      jobposthtml += '<option value="'+ jobpositions[i]['designationid'] +'">'+ jobpositions[i]['description'] +'</option>'
          //  }
          // jobposthtml += '</select>';
          // $("#divPosition").html(jobposthtml);

          // offices
          ofcs = data['offices'];
          ofchtml = '';
          ofchtml = '<select id="txtOffices" class="form-control form-control-sm">';
          ofchtml += '<option value="" selected></option>';
          for (i = 0; i < ofcs.length; i++) {
            ofchtml += '<option value="' + ofcs[i]['salesofficeid'] + '">' + ofcs[i]['description'] + '</option>';
          }
          ofchtml += '</select>';
          $('#divOffice').html(ofchtml);

          // departments
          dept = data['departments']['rows'];
          depthtml = '';
          depthtml = '<select id="txtDept" class="form-control form-control-sm">';
          depthtml += '<option value="" selected></option>';
          for (i = 0; i < dept.length; i++) {
            depthtml += '<option value="' + dept[i]['departmentid'] + '">' + dept[i]['description'] + '</option>';
          }
          depthtml += '</select>';
          $('#divDepartment').html(depthtml);

          $('#txtDept').change(function() {
            if ($('#txtDept').val() == '') {
              return false;
            }
            loadJobTitles();
          });

          //status/eecategory
          eecat = data['eecat'];
          statushtml = '';
          statushtml = '<select id="txtEECat" class="form-control form-control-sm">';
          statushtml += '<option value="" selected></option>';
          for (i = 0; i < eecat.length; i++) {
            statushtml += '<option value="' + eecat[i]['ddid'] + '">' + eecat[i]['dddescription'] + '</option>';
          }
          statushtml += '</select>';
          $('#divEEcategory').html(statushtml);

          //rankings

          eeranks = data['eeranks'];
          rankhtml = '';
          rankhtml = '<select id="txtRanking" class="form-control form-control-sm">';
          rankhtml += '<option value="" selected></option>';
          for (i = 0; i < eeranks.length; i++) {
            rankhtml += '<option value="' + eeranks[i]['ddid'] + '">' + eeranks[i]['dddescription'] + '</option>';
          }
          rankhtml += '</select>';
          // console.log(rankhtml);
          $('#divRanking1').html(rankhtml);

          // direct head abaini
          reptohtml = '';
          reptohtml = '<select id="txtRepto" class="form-control form-control-sm">';
          reptohtml += '<option value="" selected></option>';
          for (i = 0; i < abappl.length; i++) {
            reptohtml += '<option value="' + abappl[i]['userid'] + '">' + abappl[i]['eename'] + '</option>';
          }
          reptohtml += '</select>';
          // console.log(reptohtml);
          $('#divReportsTo').html(reptohtml);

          // indirect head abaini

          indreptohtml = '';
          indreptohtml = '<select id="txtReptoindirect" class="form-control form-control-sm">';
          indreptohtml += '<option value="" selected></option>';
          for (i = 0; i < abappl.length; i++) {
            indreptohtml += '<option value="' + abappl[i]['userid'] + '">' + abappl[i]['eename'] + '</option>';
          }
          indreptohtml += '</select>';
          // console.log(reptohtml);
          $('#divReportsToIndirect').html(indreptohtml);

          countryhtml = '';
          countryhtml = '<select id="txtActPlaceofwork" class="form-control form-control-sm">';
          countryhtml += '<option value=""></option>';
          for (i = 0; i < countries.length; i++) {
            countryhtml += '<option value="' + countries[i]['countryid'] + '">' + countries[i]['description'] + '</option>';
          }
          countryhtml += '</select>';
          //console.log(countryhtml);
          $('#divtxtActPlaceofwork').html(countryhtml);

          break;

        case 'accountsettings':
          zktecooffices = data['zktecooffices']['rows'];
          zkhmtl = '';
          zkhmtl = '<select name="txtZkOffice" id="txtZkOffice" class="form-control form-control-sm">';
          zkhmtl += '<option value="" selected></option>';
          for (i = 0; i < zktecooffices.length; i++) {
            zkhmtl += '<option value="' + zktecooffices[i]['id'] + '">' + zktecooffices[i]['devicename'] + '</option>';
          }
          zkhmtl += '</select>';
          $('#divZktecoOffices').html(zkhmtl);
          break;

        default:
          break;
      }

      $('#txtEECat').change(function() {
        var eecat = $('#txtEECat').val();
        // console.log(eecat);
        switch (eecat) {
          case '1':
          case '2':
          case '8':
            $('#contractdetails,#contractdetails_view').show();
            $('#probationdetails,#regularizationdetails,#probationdetails_view,#regularizationdetails_view').hide();
            break;
          case '6':
            $('#probationdetails,#probationdetails_view').show();
            $('#contractdetails,#regularizationdetails,#contractdetails_view,#regularizationdetails_view').hide();
            break;
          default:
            $('#contractdetails,#contractdetails_view,#probationdetails,#probationdetails_view').hide();
            $('#regularizationdetails,#regularizationdetails_view').show();
            break;
        }
      });
      // loadEmployeeProfile();
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}
function loadJobTitles(jobtitle) {
  url = getAPIURL() + 'eesprofile.php';
  f = 'loadJobTitles';
  deptid = $('#txtDept').val();

  data = { f: f, deptid: deptid };
  // console.log(data);
  // return false;

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      // return false;
      jobpositions = data['jobpositions']['rows'];
      jobposthtml = '';
      jobposthtml = '<select id="txtPositions" class="form-control form-control-sm">';
      jobposthtml += '<option value="" selected></option>';
      for (i = 0; i < jobpositions.length; i++) {
        jobposthtml += '<option value="' + jobpositions[i]['designationid'] + '">' + jobpositions[i]['description'] + '</option>';
      }
      jobposthtml += '</select>';
      $('#divPosition').html(jobposthtml);

      if (jobtitle != '') {
        $("#txtPositions option[value='" + jobtitle + "']").prop('selected', true);
      }

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function saveNewEmployeeInfo() {
  url = getAPIURL() + 'employees.php';
  f = 'saveNewEmployeeInfo';
  profilegroup = $('#profilegroup').val();
  userid = $('#userid').val();

  //personal info
  salutation = $('#txtSalutation').val();
  lastname = $('#txtLastname').val();
  firstname = $('#txtFirstname').val();
  cnname = $('#txtChinesename').val();
  nationality = $('#txtNatl').val();
  maritalstatus = $('#txtMaritalStat').val();
  birthdate = $('#txtBirthdate').val();
  gender = $('#txtgender').val();
  abaini = $('#txtNameAbvt').val();
  govtid = $('#txtGovertmentID').val();
  passportno = $('#txtPassportNo').val();
  issueddate = $('#txtIssuedDate').val();
  passportexpdate = $('#txtExpirationDate').val();
  passissuedcountry = $('#txtPassportCountry').val();

  //contact info
  eadd = $('#txtEmailAddress').val();
  mobphone = $('#txtMobileNo').val();
  homephone = $('#txtHomePhone').val();
  wechat = $('#txtWeChat').val();
  skype = $('#txtSkype').val();
  whatsapp = $('#txtWhatsapp').val();
  linkedin = $('#txtLinkedIn').val();
  presentcity = $('#txtCity').val();
  presentstate = $('#txtState').val();
  presentcountry = $('#txtCountry').val();
  presentzipcode = $('#txtZipCode').val();
  presentaddr = $('#txtPresentAdr').val();
  emercontactperson = $('#txtEmergencyContactPerson').val();
  emercontactno = $('#txtEmergencyPhoneNo').val();
  emercontactrelation = $('#txtRelationship').val();

  //employee data
  joineddate = $('#txtJoinedDate').val();
  ofc = $('#txtOffices').val();
  position = $('#txtPositions').val();
  dept = $('#txtDept').val();
  eecat = $('#txtEECat').val();
  rankings = $('#txtRanking').val();
  reportsto = $('#txtRepto').val();
  reportstoindirect = $('#txtReptoindirect').val();
  reportstotext = $('#txtRepto option:selected').text();
  reportstoindirecttext = $('#txtReptoindirect option:selected').text();
  // $( "#txtRepto option:selected" ).text();
  workeadd = $('#txtEmailAddress1').val();
  ofcno = $('#txtOfficeNo').val();
  workskype = $('#txtSkype1').val();

  //account settings
  // zkteco_office = $("#txtZkOffice").val();
  // zkteco_id = $("#txtZKID").val();

  //other info
  // probationperiod = $("#txtProbationPeriod").val();
  probationperiod = $('#txtProbationStartDate').val();
  lastworkingdate = $('#txtEndOfEmploymentDate').val();
  terminationperiod = $('#txtTerminationPeriod').val();
  probcompletedate = $('#txtProbationComplete').val();

  visaprocessedbyaba = $('#txtVisaProcessedbyAbac').val();
  visaexpireddate = $('#txtVisaExpDate').val();

  // lastworkingdate = $("#txtLastWorkingDt").val();
  lastworkingdate = $('#txtEndOfEmploymentDate').val();
  effectivedate = $('#txtEffectiveDate').val();
  // monnthlygrosssalinlocalcurshowninctrc = $("#txtMonthlygrossSal").val();
  // monthlempcontri = $("#txtxMosEmpContri").val();
  // monthaplusmedinhkd = $("#txtmosaplusmedinshkd").val();
  // monthlymedinsinlocal = $("#txtmosmedinsinlocalcur").val();
  // monthlybusinessexpensesallowan = $("#txtmosbusexpinilocalcur").val();
  companynamefirstctrcsigned = $('#txtcompanynamefirstctrcsigned').val();
  recenteffectivedatectrc = $('#txtRecentCtrctDt').val();
  curplaceofwork = $('#txtActPlaceofwork').val();

  //newlyadded fields

  regularizationdate = $('#txtRegularizationDate').val();
  typeofvisa = $('#txtTypeOfVisa').val();
  startofvisa = $('#txtStartOfVisa').val();
  probationenddate = $('#txtProbationEndDate').val();
  startcontractdate = $('#txtStartContractDate').val();
  endcontractdate = $('#txtEndContractDate').val();

  var shiftfrom = $('#shiftschedFrom option:selected').val();
  var shiftto = $('#shiftschedTo option:selected').val();

  data = {
    f: f,
    userid: userid,
    salutation: salutation,
    lastname: lastname,
    firstname: firstname,
    cnname: cnname,
    nationality: nationality,
    maritalstatus: maritalstatus,
    birthdate: birthdate,
    gender: gender,
    abaini: abaini,
    govtid: govtid,
    passportno: passportno,
    issueddate: issueddate,
    passportexpdate: passportexpdate,
    passissuedcountry: passissuedcountry,
    eadd: eadd,
    mobphone: mobphone,
    homephone: homephone,
    wechat: wechat,
    skype: skype,
    whatsapp: whatsapp,
    linkedin: linkedin,
    presentcity: presentcity,
    presentstate: presentstate,
    presentcountry: presentcountry,
    presentzipcode: presentzipcode,
    presentaddr: presentaddr,
    emercontactperson: emercontactperson,
    emercontactno: emercontactno,
    emercontactrelation: emercontactrelation,
    joineddate: joineddate,
    ofc: ofc,
    positions: position,
    department: dept,
    eecat: eecat,
    posgrade: rankings,
    reportsto: reportsto,
    reportstoindirect: reportstoindirect,
    reportstotext: reportstotext,
    reportstoindirecttext: reportstoindirecttext,
    workeadd: workeadd,
    ofcno: ofcno,
    workskype: workskype,
    probationperiod: probationperiod,
    terminationperiod: terminationperiod,
    visaprocessedbyaba: visaprocessedbyaba,
    visaexpireddate: visaexpireddate,
    probcompletedate: probcompletedate,
    lastworkingdate: lastworkingdate,
    effectivedate: effectivedate,
    companynamefirstctrcsigned: companynamefirstctrcsigned,
    recenteffectivedatectrc: recenteffectivedatectrc,
    curplaceofwork: curplaceofwork,
    regularizationdate: regularizationdate,
    typeofvisa: typeofvisa,
    startofvisa: startofvisa,
    probationenddate: probationenddate,
    startcontractdate: startcontractdate,
    endcontractdate: endcontractdate,
    shiftfrom: shiftfrom,
    shiftto: shiftto
  };
  // "zkteco_office":zkteco_office,"zkteco_id":zkteco_id
  // console.log(data);
  // return false;

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      newuserid = data['savenew']['newuserid'];
      var err = data['err'];

      if (err == 1) {
        alertDialog('An error occur while saving the the record. Please contact Web administrator.');
        return false;
      }
      // return false;
      $('#eeid').val(newuserid);
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function updateAccountSettings() {
  url = getAPIURL() + 'employees.php';
  f = 'updateAccountSettings';
  profilegroup = 'accountsettings';
  userid = $('#userid').val();
  eeid = $('#eeid').val();
  // eeid = 'A191111-00144';

  //account settings
  zkteco_office = $('#txtZkOffice').val();
  zkteco_id = $('#txtZKID').val();

  //other benefits
  monnthlygrosssalinlocalcurshowninctrc = $('#txtMonthlygrossSal').val();
  monthlempcontri = $('#txtxMosEmpContri').val();
  monthaplusmedinhkd = $('#txtmosaplusmedinshkd').val();
  monthlymedinsinlocal = $('#txtmosmedinsinlocalcur').val();
  monthlybusinessexpensesallowan = $('#txtmosbusexpinilocalcur').val();

  data = {
    f: f,
    userid: userid,
    eeid: eeid,
    zkteco_office: zkteco_office,
    zkteco_id: zkteco_id,
    monnthlygrosssalinlocalcurshowninctrc: monnthlygrosssalinlocalcurshowninctrc,
    monthlempcontri: monthlempcontri,
    monthaplusmedinhkd: monthaplusmedinhkd,
    monthlymedinsinlocal: monthlymedinsinlocal,
    monthlybusinessexpensesallowan: monthlybusinessexpensesallowan
  };
  //console.log(data);
  // return false;

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      var err = data['err'];

      if (err == 1) {
        alertDialog('An error occur while saving the the record. Please contact Web administrator.');
        return false;
      } else {
        alertDialog('Employee has been added successfully.');
      }
      // return false;
      // newuserid = data['savenew']['newuserid'];
      // // return false;
      // $("#eeid").val(newuserid);
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function generateLeaveQuota() {
  url = getAPIURL() + 'employees.php';
  f = 'generateLeaveQuota';
  // eeid = "A191104-00143";
  eeid = $('#eeid').val();

  data = { f: f, eeid: eeid };
  // console.log(data);
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      leavequotas = data['leavequotas']['rows'];
      html = '';

      html += '<thead class="thead-dark">';
      html += '<tr>';
      html += '<th width="50%">Leave Type</th>';
      html += '<th width="40%">Entitle</th>';
      html += '<th width="10%">Status</th>';
      html += '</tr>';
      html += '</thead>';
      html += '<tbody>;';
      for (i = 0; i < leavequotas.length; i++) {
        fcolor = '';
        if (leavequotas[i]['status'] == 0) {
          fcolor = 'color: #ff0000;';
        }
        editleavebenefit = "return getLeaveBenefitsData('" + leavequotas[i]['id'] + "');";
        leavetype = leavequotas[i]['leavedesc'];
        entitleddays = leavequotas[i]['entitleddays'];
        status = leavequotas[i]['statusname'];

        html += '<tr style="cursor: pointer; ' + fcolor + '" onClick="' + editleavebenefit + '" data-toggle="modal" data-target="editLeaveBenefit">';
        html += '<td >' + leavetype + '</td>';
        html += '<td>' + entitleddays + '</td>';
        html += '<td>' + status + '</td>';
        html += '</tr>';
      }
      html += '</tbody>;';

      $('#benefitlistdatatable').html(html);
      $('#genLeaveQuota').hide();

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function getLeaveBenefitsData(id) {
  // console.log($id +' '+ $eeid + ' ' + $leavetypeid)
  url = getAPIURL() + 'employees.php';
  f = 'getLeaveBenefit';
  // id = "A180716-00110"
  data = { f: f, id: id };

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      leavetype = data['leavebal']['rows'][0];
      // $("#dataleavetype").html(leavetype['leavetypedesc']);
      $('#txtEntitled').val(parseFloat(leavetype['entitleddays']).toFixed(1));
      // $("#txtTaken").val(leavebal['takendays']);
      $("#txtStatus option[value='" + leavetype['status'] + "']").prop('selected', true);
      $('#leaveid').val(leavetype['id']);
      $('#editLeaveBenefit').modal('show');

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function updateLeaveBenefitsData() {
  url = getAPIURL() + 'employees.php';
  f = 'updateLeaveBenefit';
  id = $('#leaveid').val();
  entitled = $('#txtEntitled').val();
  status = $('#txtStatus').val();
  userid = $('#userid').val();
  eeid = $('#eeid').val();

  data = { f: f, userid: userid, eeid: eeid, id: id, entitled: entitled, status: status };
  // console.log(data);

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      // generateLeaveQuota();
      leavequotas = data['leavequotas']['rows'];
      html = '';

      html += '<thead class="thead-dark">';
      html += '<tr>';
      html += '<th width="55%">Leave Type</th>';
      html += '<th width="40%">Entitle</th>';
      html += '</tr>';
      html += '</thead>';
      html += '<tbody>;';
      for (i = 0; i < leavequotas.length; i++) {
        fcolor = '';
        if (leavequotas[i]['status'] == 0) {
          fcolor = 'color: #ff0000;';
        }
        editleavebenefit = "return getLeaveBenefitsData('" + leavequotas[i]['id'] + "');";
        leavetype = leavequotas[i]['leavedesc'];
        entitleddays = leavequotas[i]['entitleddays'];

        html += '<tr style="cursor: pointer; ' + fcolor + '" onClick="' + editleavebenefit + '" data-toggle="modal" data-target="editLeaveBenefit">';
        html += '<td >' + leavetype + '</td>';
        html += '<td>' + entitleddays + '</td>';
        html += '</tr>';
      }
      html += '</tbody>;';

      $('#benefitlistdatatable').html(html);
      $('#editLeaveBenefit').modal('hide');
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function generateHMO() {
  url = getAPIURL() + 'employees.php';
  f = 'generateHMOBenefits';
  // eeid = "A191104-00143";
  eeid = $('#eeid').val();

  data = { f: f, eeid: eeid };
  // console.log(data);

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      hmobenefits = data['hmobenefits']['rows'];
      html = '';

      html += '<thead class="thead-dark">';
      html += '<tr>';
      html += '<th width="55%">Health Insurance Coverage</th>';
      html += '<th width="40%">Coverage Amount</th>';
      html += '</tr>';
      html += '</thead>';
      html += '<tbody>;';
      for (i = 0; i < hmobenefits.length; i++) {
        // fcolor = "";
        // if(hmobenifits[i]['status'] == 0){
        //     fcolor = "color: #ff0000;";
        // }
        // editleavebenefit = "return getLeaveBenefitsData('"+ hmobenifits[i]['id'] + "');";
        healthinsurantype = hmobenefits[i]['description'];
        coverageamount = addCommas(parseFloat(hmobenefits[i]['coverageamount']).toFixed(0));
        // style="cursor: pointer; '+ fcolor +'" onClick="'+ editleavebenefit +'" data-toggle="modal" data-target="editLeaveBenefit"
        html += '<tr>';
        html += '<td >' + healthinsurantype + '</td>';
        html += '<td>' + coverageamount + '</td>';
        html += '</tr>';
      }
      html += '</tbody>;';

      $('#hmobenefits_edit').html(html);
      $('#genHMO').hide();

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function clearAllFields() {
  $('#txtSalutation').val('');
  $('#txtLastname').val('');
  $('#txtFirstname').val('');
  $('#txtChinesename').val('');
  $('#txtNatl').val('');
  $('#txtMaritalStat').val('');
  $('#txtBirthdate').val('');
  $('#txtgender').val('');
  $('#txtNameAbvt').val('');
  $('#txtGovertmentID').val('');
  $('#txtPassportNo').val('');
  $('#txtIssuedDate').val('');
  $('#txtExpirationDate').val('');
  $('#txtPassportCountry').val('');

  //contact info
  $('#txtEmailAddress').val('');
  $('#txtMobileNo').val('');
  $('#txtHomePhone').val('');
  $('#txtWeChat').val('');
  $('#txtSkype').val('');
  $('#txtWhatsapp').val('');
  $('#txtLinkedIn').val('');
  $('#txtCity').val('');
  $('#txtState').val('');
  $('#txtCountry').val('');
  $('#txtZipCode').val('');
  $('#txtPresentAdr').val('');
  $('#txtEmergencyContactPerson').val('');
  $('#txtEmergencyPhoneNo').val('');
  $('#txtRelationship').val('');

  //employee data
  $('#txtJoinedDate').val('');
  $('#txtOffices').val('');
  $('#txtPositions').val('');
  $('#txtDept').val('');
  $('#txtEECat').val('');
  $('#txtRanking').val('');
  $('#txtRepto').val('');
  $('#txtReptoindirect').val('');
  $('#txtRepto').val('');
  $('#txtReptoindirect').val('');
  $('#txtEmailAddress1').val('');
  $('#txtOfficeNo').val('');
  $('#txtSkype1').val('');

  //account settings
  $('#txtZkOffice').val('');
  $('#txtZKID').val('');

  //other info
  $('#txtProbationPeriod').val('');
  $('#txtTerminationPeriod').val('');
  $('#txtVisaProcessedbyAbac').val('');
  $('#txtVisaExpDate').val('');
  $('#txtProbationComplete').val('');
  $('#txtLastWorkingDt').val('');
  $('#txtEffectiveDate').val('');
  $('#txtMonthlygrossSal').val('');
  $('#txtxMosEmpContri').val('');
  $('#txtmosaplusmedinshkd').val('');
  $('#txtmosmedinsinlocalcur').val('');
  $('#txtmosbusexpinilocalcur').val('');
  $('#txtcompanynamefirstctrcsigned').val('');
  $('#txtRecentCtrctDt').val('');
  $('#txtActPlaceofwork').val('');
}

/////////////////////////////////////////////////////////
function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $('#upimage').attr('src', e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}

function loadCertifications() {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'getCertificates';
  var eeid = $('#eeid').val();
  // var userid = $("#userid").val();
  // var sesid = $("#sesid").val();

  var data = { f: f, eeid: eeid };
  // console.log(data);

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      if (data.certificates.rows.length > 0) {
        populateCertificates(data.certificates);
      } else {
        populateCertificates([]);
      }
      loadCertificateDates();
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function populateCertificates(data) {
  let certificateList = data.rows;
  // console.log('Populating certificate table')
  // console.log(certificateList);
  let tableID = '#certificatedatatable';
  if (!$.fn.DataTable.isDataTable(tableID)) {
    $(tableID).DataTable({
      data: certificateList,
      responsive: true,
      paging: false,
      searching: false,
      info: false,
      language: {
        emptyTable: '<center>No certificate</center>'
      },
      columns: [
        { data: 'certificationname' },
        { data: 'issuingorganization' },
        {
          data: function(data, type, dataToSet) {
            return '<span style="display:none;">' + data.issuedmonth + '_' + data.issuedyear + '</span>' + monthIntToName(data.issuedmonth) + ' ' + data.issuedyear;
          }
        },
        {
          data: function(data, type, dataToSet) {
            if (data.noExpiry == 0) {
              return (
                '<span style="display:none;">' + data.expirationmonth + '_' + data.expirationyear + '</span>' + monthIntToName(data.expirationmonth) + ' ' + data.expirationyear
              );
            } else {
              return 'No expiration';
            }
          }
        },
        {
          data: function(data, type, dataToSet) {
            if (data.attachments != null) {
              return '<i class="fas fa-paperclip"></i>';
            } else {
              return '';
            }
          }
        }
      ],
      columnDefs: [
        {
          targets: 4,
          className: 'text-center'
        }
      ],
      rowId: 'id'
    });
  } else {
    $(tableID)
      .dataTable()
      .fnClearTable();
    if (certificateList != undefined) {
      $(tableID)
        .dataTable()
        .fnAddData(certificateList);
    }
  }
  if ($(tableID + ' tbody tr td').hasClass('dataTables_empty')) {
    $(tableID + ' tbody').css('cursor', 'no-drop');
  } else {
    $(tableID + ' tbody').css('cursor', 'pointer');
  }
  $(tableID + ' tbody').on('click', 'tr', function(e) {
    if ($(e.currentTarget.childNodes[0]).hasClass('dataTables_empty')) {
      return false;
    }
    if (certificateList == undefined) {
      loadCertifications();
      return false;
    }
    let tableItem = e.currentTarget;
    let clickedTableId = tableItem.id;
    let tdissuedate = tableItem.childNodes[2].childNodes[0].innerText.split('_');
    let attachmentname = '';

    $('#certificatename').val(tableItem.childNodes[0].innerText);
    $('#certificateorganization').val(tableItem.childNodes[1].innerText);
    $('#certificateissuemonth option[value="' + tdissuedate[0] + '"').prop('selected', true);
    $('#certificateissueyear option[value="' + tdissuedate[1] + '"').prop('selected', true);

    if (tableItem.childNodes[3].innerText == 'No expiration') {
      $('#certificatenoexpiry').attr('checked', true);
    } else {
      $('#certificatenoexpiry').attr('checked', false);
      let tdexpirydate = tableItem.childNodes[3].childNodes[0].innerText.split('_');
      $('#certificateexpirymonth').removeAttr('disabled');
      $('#certificateexpiryyear').removeAttr('disabled');
      $('#certificateexpirymonth option[value="' + tdexpirydate[0] + '"').prop('selected', true);
      $('#certificateexpiryyear option[value="' + tdexpirydate[1] + '"').prop('selected', true);
    }
    certificateList.find(certificateItem => {
      if (certificateItem.id == clickedTableId) {
        attachmentname = certificateItem.attachments;
      }
    });
    if (attachmentname != null) {
      let thumbnailoutput = '';
      // let previewoutput =

      let folderName = $('#eeid').val();
      let siteURL = window.location.origin + '/' + window.location.pathname.split('/')[1];
      let locationURL = siteURL + '/upload/certification_attachment_files/' + folderName + '/';

      let splitFile = attachmentname.split('.');
      let fileType = splitFile[splitFile.length - 1];
      switch (fileType) {
        case 'docx':
        case 'doc':
          thumbnailoutput = '<a href="' + locationURL + attachmentname + '" target="_blank"><i class="fas fa-file-word fa-5x"></i></a>';
          break;
        case 'pdf':
          thumbnailoutput = '<a href="' + locationURL + attachmentname + '" target="_blank"><i class="fas fa-file-pdf fa-5x"></i></a>';
          break;
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
          thumbnailoutput =
            '<a href="' +
            locationURL +
            attachmentname +
            '" target="_blank"><img src="' +
            locationURL +
            attachmentname +
            '" width="100px" height="100px" style="display: inline-block; object-fit: cover; object-position: center;"></img></a>';
          break;
        default:
          thumbnailoutput = '<a href="' + locationURL + attachmentname + '" target="_blank"><i class="fas fa-paperclip fa-5x"></i></a>';
          break;
      }
      $('#certificatepreview').html(thumbnailoutput);
      $('#certfilename').val(attachmentname);
    }
    // $('#certificatepreview')
    // $('#btn_certificate_upload').val('REPLACE');
    $('#certid').val(clickedTableId);
    $('#ses_id').val($('#sesid').val());
    $('#certuserid').val($('#eeid').val());
    $('#byuserid').val($('#userid').val());
    $('#btnCertificateDelete').attr('hidden', false);
    $('#btnCertificateSave').removeAttr('disabled');
    $('#certificationModal').modal('show');
  });
}

function loadCertificateDates() {
  const yearStart = 1990;
  let currentYear = new Date().getFullYear();
  // let currentMonth = new Date().getMonth();
  let idIssue = '#certificateissue';
  let idExpiry = '#certificateexpiry';

  let monthHtml = '<option selected="selected" value="0"></option>';
  for (let monthNum = 1; monthNum <= 12; monthNum++) {
    monthHtml += '<option value="' + monthNum + '">' + monthIntToName(monthNum, true) + '</option>';
  }
  $(idIssue + 'month').html('');
  $(idExpiry + 'month').html('');
  $(idIssue + 'month').html(monthHtml);
  $(idExpiry + 'month').html(monthHtml);

  //option[value="default"]').prop('selected', true);

  let yearHtml = '<option selected="selected" value="0"></option>';
  for (let yearAdd = yearStart; yearAdd <= currentYear; yearAdd++) {
    yearHtml += '<option value="' + yearAdd + '">' + yearAdd + '</option>';
  }
  $(idIssue + 'year').html('');
  $(idExpiry + 'year').html('');
  $(idIssue + 'year').html(yearHtml);
  $(idExpiry + 'year').html(yearHtml);
}

function addNewCertificate() {
  // console.log('Adding Certificate');
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'addCertificates';
  var eeid = $('#certuserid').val();
  var certificatename = $('#certificatename').val();
  var certificateorganization = $('#certificateorganization').val();
  var certificateissuemonth = $('#certificateissuemonth option:selected').val();
  var certificateissueyear = $('#certificateissueyear option:selected').val();
  var certificateexpirymonth = 0;
  var certificateexpiryyear = 0;
  var certificatenoexpiry = 1;
  if ($('#certificatenoexpiry').prop('checked')) {
    certificatenoexpiry = 1;
  } else {
    certificatenoexpiry = 0;
    certificateexpirymonth = $('#certificateexpirymonth option:selected').val();
    certificateexpiryyear = $('#certificateexpiryyear option:selected').val();
  }
  var addedby = $('#userid').val();

  var data = {
    f: f,
    eeid: eeid,
    certificatename: certificatename,
    certificateorganization: certificateorganization,
    certificateissuemonth: certificateissuemonth,
    certificateissueyear: certificateissueyear,
    certificateexpirymonth: certificateexpirymonth,
    certificateexpiryyear: certificateexpiryyear,
    certificatenoexpiry: certificatenoexpiry,
    addedby: addedby
  };

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      $('#certificationModal').modal('hide');
      loadCertifications();
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function deleteCertificate() {
  // console.log('Deleting certificate');
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'deleteCertificates';
  var id = $('#certid').val();
  var eeid = $('#certuserid').val();

  var data = { f: f, id: id, eeid: eeid };

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      $('#certificationModal').modal('hide');
      loadCertifications();
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function updateCertificate() {
  // console.log('Updating certificate');
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'updateCertificates';
  var id = $('#certid').val();
  var eeid = $('#certuserid').val();
  var certificatename = $('#certificatename').val();
  var certificateorganization = $('#certificateorganization').val();
  var certificateissuemonth = $('#certificateissuemonth option:selected').val();
  var certificateissueyear = $('#certificateissueyear option:selected').val();
  var certificateexpirymonth = 0;
  var certificateexpiryyear = 0;
  var certificatenoexpiry = 1;
  if ($('#certificatenoexpiry').prop('checked')) {
    certificatenoexpiry = 1;
  } else {
    certificatenoexpiry = 0;
    certificateexpirymonth = $('#certificateexpirymonth option:selected').val();
    certificateexpiryyear = $('#certificateexpiryyear option:selected').val();
  }
  var modifiedby = $('#userid').val();
  var attachmentname = $('#certfilename').val();

  var data = {
    f: f,
    id: id,
    eeid: eeid,
    certificatename: certificatename,
    certificateorganization: certificateorganization,
    certificateissuemonth: certificateissuemonth,
    certificateissueyear: certificateissueyear,
    certificateexpirymonth: certificateexpirymonth,
    certificateexpiryyear: certificateexpiryyear,
    certificatenoexpiry: certificatenoexpiry,
    modifiedby: modifiedby,
    attachmentname: attachmentname
  };
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      $('#certificationModal').modal('hide');
      loadCertifications();
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}
