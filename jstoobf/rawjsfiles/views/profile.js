$(function() {
  let dateFields =
    '#txtBirthdate,#txtProbationPeriod,#txtProbationStartDate,#txtVisaExpDate,#txtJoinedDate,#effectivedate,#enddate,#txtProbationComplete,' +
    '#txtLastWorkingDt,#txtEffectiveDate,#txtRecentCtrctDt,#txtIssuedDate,#txtExpirationDate,' +
    '#txtRegularizationDate, #txtEndOfEmploymentDate,#txtStartOfVisa, #txtProbationEndDate,#txtStartContractDate,' +
    '#txtEndContractDate, #txtIssuedDate_edit, #txtExpirationDate_edit';
  $(dateFields)
    .datepicker({
      // minDate: -6,
      dateFormat: 'D d M yy',
      changeMonth: true,
      changeYear: true,
      yearRange: '1900:2050'
    })
    .on('click', function() {
      $(this).prop('readonly', true);
    });

  $(dateFields).prop('readonly', true);

  $('#activepresent').on('click', function(e) {
    const isPresent = $(`#${e.target.id}`).is(':checked');
    $('#enddate').attr('disabled', isPresent);
    if(isPresent) $('#enddate').val('');
  });

  $('#personal-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('personalinfo');
    });

  $('#contact-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: contact-tab');
          loadDropdown();
        }
      });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('contactinfo');
    });

  $('#employee-tab')
    .one('click', function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: employee-tab');
          loadDropdown();
        }
      });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('employeedata');
    });

  $('#accountsettings-tab')
    .one('click', function() {
      $('#profilegroup').val('accountsettings');
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: accountsettings-tab');
          loadDropdown();
        }
      });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('accountsettings');
    });

  $('#compensationandbenefits-tab')
    .one('click', function() {
      $('#profilegroup').val('compensationandbenefits');
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          // //console.log('Tab: compensationandbenefits-tab');
          loadDropdown();
          // loadCompensationBenefits();
        }
      });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('compensationandbenefits');
    });

  $('#certification-tab')
    .one('click', function() {
      $('#profilegroup').val('certification');
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          // //console.log('Tab: compensationandbenefits-tab');
          loadDropdown();
          // loadCompensationBenefits();
        }
      });
    })
    .dblclick(function() {
      $.blockUI({
        message: $('#preloader_image'),
        fadeIn: 1000,
        onBlock: function() {
          //console.log('Tab: personal-tab');
          loadDropdown();
        }
      });
    })
    .on('click', function() {
      $('#profilegroup').val('certification');
    });

  // $('#txtLastname,#txtFirstname').on('change', function(e) {
  // 	let fname = $('#txtFirstname').val();
  // 	let lname = $('#txtLastname').val();
  // 	if($('#' + e.target.id).val() != '') {
  // 		if(fname != '' && lname != ''){
  // 			$('#txtNameAbvt_edit').val(getInitial(fname,lname));
  // 		}
  // 	}
  // });

  generateInitial('#txtFirstname', '#txtLastname', '#txtNameAbvt_edit', function(){
    $('#txtNameAbvt_edit').trigger('change');
    $('#txtNameAbvt_edit').trigger('keyup');
  });

  $('#txtNameAbvt_edit').on('change', function() {
      $('#iniexist').val(0);
      if($(this).val() != '')
        checkini($(this).val());
    }).on('keyup',  function(){
      $(this).trigger('change');
    });
  function checkini(inival) {
    var url = getAPIURL() + 'eesprofile.php';
    var f = 'checkini';

    $('#btnSaveChanges1').attr('disabled',true);
    var data = { f: f, inival: inival };
    $.ajax({
      type: 'POST',
      url: url,
      data: JSON.stringify({ data: data }),
      dataType: 'json',
      success: function(data) {
        $('#iniexist').val(data.isExist);
        $('#btnSaveChanges1').removeAttr('disabled');
      },
      error: function(request, status, err) {
        $('#btnSaveChanges1').removeAttr('disabled');}
    });
  }

  // Name cases
  $('#txtLastname').on('change', function() {
    $(this).val(
      $(this)
        .val()
        .toUpperCase()
    );
  });

  $('#txtFirstname').on('change', function() {
    $(this).val(firstWordCase($(this).val()));
  });
  function firstWordCase(str) {
    var splitStr = str.toLowerCase().split(' ');
    for (var i = 0; i < splitStr.length; i++) {
      // You do not need to check if i is larger than splitStr length, as your for does that for you
      // Assign it back to the array
      splitStr[i] = splitStr[i].charAt(0).toUpperCase() + splitStr[i].substring(1);
    }
    // Directly return the joined string
    return splitStr.join(' ');
  }

  ccyHandler('#txtMonthlygrossSal,#txtxMosEmpContri,#txtmosaplusmedinshkd,' + '#txtmosmedinsinlocalcur,#txtmosbusexpinilocalcur');

  leaveVals('#txtEntitled');

  $('#btnEditContact, #btnEditContactSmall').on('click', function() {
    var params = new window.URLSearchParams(window.location.search);
    var action = params.get('action');
    if (action == null) {
      action = btoa('editcontact');
    }

    var sesid = $('#sesid').val();
    window.location = 'profile-edit.php?id=' + sesid + '&action=' + action;
  });
  $('#btnEditProfile').on('click', function() {
    var params = new window.URLSearchParams(window.location.search);
    var action = params.get('action');
    if (atob(action) == 'viewprofile') {
      action = btoa('editprofile');
    }

    var sesid = $('#sesid').val();
    window.location = 'profile-edit.php?id=' + sesid + '&action=' + action;
  });

  $('#btnCancel1,#btnCancel2,#btnCancel3,#btnCancel4,#btnBack,#btnBackSmall').on('click', function() {
    let previous_url = document.referrer;
    window.location = previous_url = '' ? 'index.php' : previous_url;
  });

  $('#btnSaveChanges1,#btnSaveChanges2,#btnSaveChanges3,#btnSaveChanges4, #btnSaveChanges5').on('click', function() {
    $.blockUI({
      message: $('#preloader_image'),
      fadeIn: 1000,
      onBlock: function() {
        updateEmployeeInfo();
      }
    });
  });

  $('#txtMobileNo,#txtHomePhone,#txtEmergencyPhoneNo,#txtOfficeNo,#txtWhatsapp').keypress(function(e) {
    //if the letter is not digit then display error and don't type anything
    if (e.which != 8 && e.which != 0 && e.which != 43 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });
  $('#rate').keypress(function(e) {
    //if not in ccy, block typing
    let keypressed = e.which;
    if (keypressed != 8 && keypressed != 0 && keypressed != 43 && keypressed != 44 && (keypressed < 48 || keypressed > 57)) {
      return false;
    }
  });
  
  $('#bntSaveLeaveBen').on('click', function() {
    if ($('#isexist').val() == 1) {
      updateBenefit();
    } else {
      saveBenefit();
    }
  });

  if($('#isadmin').val() == 1){
    $('#positionBtnDelete').on('click', function() {
      confirmDialog('Are you sure to remove this position history?', () => {
        deletePositionHistory();
      });
    });
  } else {
    $('#positionBtnDelete').remove();
  }


  $('#department,#position,#rate,#effectivedate,#enddate,#activepresent').on('change', function(e) {
    let departmentIsEmpty = $('#department').val() == '';
    let positionIsEmpty;
    if (e.target.id == 'department') {
      positionIsEmpty = true;
    } else {
      positionIsEmpty = $('#position').val() == '';
    }
    let rateIsEmpty = $('#rate').val() == '';
    let effectivedateIsEmpty = $('#effectivedate').val() == '';
    let enddateIsEmpty = $('#enddate').val() == '';
    let isPresent = $('#activepresent').is(':checked');
    if (departmentIsEmpty || positionIsEmpty || rateIsEmpty || effectivedateIsEmpty || ((!isPresent && enddateIsEmpty) || (isPresent && !enddateIsEmpty))) {
      $('#positionBtnSave').attr('disabled', true);
    } else {
      $('#positionBtnSave').removeAttr('disabled', false);
    }
  });

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
    $('#btnCertificateSave').attr('disabled', true);
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
    const limitSize = 1;
    var fd = new FormData();
    var files = $('#certificateattachment')[0].files[0];
    fd.append('file', files);
    let fileType = files.type;
    if (files.size >= limitSize * mbSize) {
      alertDialog('File size limit exceeded, only <= ' + limitSize + 'MB accepted.');
      $('#certificateattachment').val('');
      return false;
    }

    let outputhtml = function(fileType, response) {
      // console.log(fileType);
      if (fileType.includes('image')) {
        return '<img src="' + response + '" width="75%" style="display: inline-block; object-fit: cover; object-position: center; color: #c3282d !important"></img>';
      } else if (fileType == 'application/pdf') {
        return '<i class="fas fa-file-pdf fa-10x" style="color: #c3282d !important"></i>';
      } else if (fileType == 'application/msword' || fileType == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
        return '<i class="fas fa-file-word fa-10x" style="color: #c3282d !important"></i>';
      } else {
        return '<i class="fas fa-paperclip fa-10x" style="color: #c3282d !important"></i>';
      }
    };

    // AJAX request
    // $.blockUI({
    // 	message: $('#preloader_image'),
    // 	fadeIn: 1000,
    // 	onBlock: function () {
    $.ajax({
      url: 'controllers/certificateattachment_controller.php',
      type: 'post',
      data: fd,
      contentType: false,
      processData: false,
      success: function(response) {
        // console.log(data);
        if (response == 0) {
          alertDialog('File not uploaded, please try again.');
          $('#certificateattachment').val('');
        } else if (response == 1) {
          alertDialog('File size limit exceeded, only <= 3MB accepted.');
          $('#certificateattachment').val('');
        } else {
          $('#certificatepreview').html(outputhtml(fileType, response));
          certificateWatcher();
        }
        // $.unblockUI();
      }
    });
    // 	}
    // });
  });

  $('#addCertBtn').on('click', function() {
    $('#btnCertificateSave').html('Add New');
    $('#ses_id').val($('#sesid').val());
    $('#certuserid').val($('#eeid').val());
    $('#byuserid').val($('#userid').val());
    certificateWatcher();
    $('#certificationModal').modal('show');
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

  function certificateWatcher() {
    // console.log($('#certfilename').val() + ' = ' + $('#certificatepreview').find('img').attr('src'));
    let isEmpty =
      $('#certificatename').val() == '' || $('#certificateorganization').val() == '' || $('#certificateissuemonth option:selected').val() == 0 || $('#certificateissueyear option:selected').val() == 0;
    // || $('#certificatepreview').find('img').attr('src') == undefined;
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

  // $('#upload_certificate').on('submit', function(e) {
  // 	// console.log(new FormData(this));
  // 	e.preventDefault();
  // 	$.ajax({
  // 		url: 'controllers/certificateattachment_controller.php',
  // 		type: 'post',
  // 		data: new FormData(this),
  // 		contentType: false,
  // 		processData: false,
  // 		success: function(result){
  // 			console.log(result);
  // 			return false;
  // 			// $('#output').html(result);
  // 		}
  // 	});
  // });

  
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      var profilegroup = $('#profilegroup').val();

      $('#profilegroup').val('contactinfo');
      //}

      var params = new window.URLSearchParams(window.location.search);
      var action = atob(params.get('action'));

      if (action == 'viewprofile') {
        // $('#btnEditContact').html('<i class="fas fa-edit"></i>Edit Profile');
        $('#btnEditContact, #btnEditContactSmall').hide();
        $('#btnBackToEeList')
          .attr('hidden', false)
          .on('click', function() {
            let params = new window.URLSearchParams(window.location.search);
            let fstatus = params.get('fstatus');
            let fstation = params.get('fstation');
            let searchq = params.get('searchq');
            window.location = 'employees.php?fstatus=' + fstatus + '&fstation=' + fstation + '&searchq=' + searchq;
          });
      } else if (action == 'viewbygm') {
        $('#btnEditContact, #btnEditContactSmall').hide();
        $('#btnBackToEeList')
          .attr('hidden', false)
          .on('click', function() {
            window.location = 'directory.php';
          });
      } else {
        $('#btnEditContact,#btnEditContactSmall').attr('hidden', false);
      }

      $('#sesid').val(params.get('id'));

      if (profilegroup == 'profile') {
        $('#profilegroup').val(profilegroup);
        loadEmployeeProfile(employeeProfileLoaded => {
          if (employeeProfileLoaded) {
            loadCertifications();
            loadCompensationBenefits();
          } else {
            //
          }
        });

        // setTimeout(() => {
        // }, 4000);
      } // else {
      // 	//
      profileEditTabs(action);
    }
  });
});

function profileEditTabs(action) {
  const maxTab = 5;
  const profileEdit = [0, 1, 2, 3, 4, 5];
  const contactEdit = [1, 4];
  const editProfile = 'editprofile';
  const editContact = 'editcontact';
  let isEditProfile = action == editProfile;
  let isEditContact = action == editContact;
  if ((action != null && action == editProfile) || (action != null && action == editContact)) {
    // $("#myTab").tabs();
    for (let eachTab = 0; eachTab <= maxTab; eachTab++) {
      if ((!profileEdit.includes(eachTab) && isEditProfile) || (!contactEdit.includes(eachTab) && isEditContact)) {
          // $($('#myTab').find('li')[eachTab]).remove();
          continue;
      }
      $($('#myTab').find('li')[eachTab]).removeAttr('hidden');
      $($('#myTab').find('li')[eachTab]).show();
      if (isEditProfile && eachTab == profileEdit[0] && $('#personal-tab').length === 1) {
        $('#personal-tab').trigger('click');
      }
      if ((isEditContact && eachTab == contactEdit[0]) || $('#personal-tab').length === 0) {
        $('#contact-tab').trigger('click');
      }
    }
  }
}

function loadEmployeeProfile(callback) {
  // console.log('Load Employee Profile');
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'loadEmployeeProfile';
  var userid = $('#userid').val();
  var profilegroup = $('#profilegroup').val();
  var params = new window.URLSearchParams(window.location.search);
  var action = params.get('action');
  if (action != null) action = action.includes('edit') ? action : atob(action);
  var sesid = params.get('id');
  //console.log('profilegroup: ' + profilegroup + ', action: ' + action);

  var data = { f: f, userid: userid, profilegroup: profilegroup, sesid: sesid, action: action };
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
      var eedata = data['eedata']['rows'][0];
      var countries = data['countries']['rows'];
      var avatar = data['avatar'];
      // var jobpositions = data['jobpositions']['rows'];
      $('#avatar').val(eedata['avatarorig']);
      $('#eeid').val(eedata['userid']);
      $('#eeini').val(eedata['abaini']);
      // $("#eeid_view").val(eedata['userid']);

      //upload profile picture
      var pphtml = '';
      pphtml = '<img src="' + avatar + '" alt=""/>';
      pphtml += '<div class="file btn btn-lg btn-primary" id="btnChangePhoto" style="cursor: pointer;">';
      pphtml += 'Change Photo';
      // pphtml += '<input  type="button" class="btn btn-sm btn-primary">';
      pphtml += '</div>';
      $('#profileimg').html(pphtml);

      var countryhtml = '';
      var countryhtml_list = '';
      countryhtml = '<select id="txtCountry" class="form-control form-control-sm">';
      countryhtml_list += '<option value=""></option>';
      for (var i = 0; i < countries.length; i++) {
        countryhtml_list += '<option value="' + countries[i]['countryid'] + '">' + countries[i]['description'] + '</option>';
      }
      countryhtml += countryhtml_list;
      countryhtml += '</select>';
      $('#divCountries').html(countryhtml);
      $('#txtPassportCountry_edit').html(countryhtml_list);
      $('#txtActPlaceofwork').html(countryhtml_list);

      // if($("#divPosition").html() != ''){
      // 	var jobposthtml = "";
      // 	jobposthtml = '<select id="txtPositions" class="form-control form-control-sm">';
      // 	jobposthtml += '<option value="" selected></option>';
      // 	for (var i = 0; i < jobpositions.length; i++) {
      // 		jobposthtml += '<option value="' + jobpositions[i]['designationid'] + '">' + jobpositions[i]['description'] + '</option>'
      // 	}
      // 	jobposthtml += '</select>';
      // 	$("#divPosition").html(jobposthtml);
      // }

      //profile pic
      var pphtml2 = '';
      pphtml2 = '<img src="' + avatar + '" alt=""/>';
      pphtml2 += '</div>';
      $('#profileimg1').html(pphtml2);

      $('#btnChangePhoto').on('click', function() {
        eeid = $('#eeid').val();
        if (action == 'editprofile') {
          $('#txteeid').val(eeid);
        }
        $('#selectfile')
          .trigger('click');
        $('#frmuploadphoto').modal('show');
      });

      $('#txtEECat').change(function() {
        var eecat = $('#txtEECat').val();
        //console.log(eecat);
        switch (eecat) {
          case '1':
          case '2':
          case '8':
          case '11':
            $('#contractdetails,#contractdetails_view').show();
            $('#probationdetails,#regularizationdetails,#probationdetails_view,#regularizationdetails_view').hide();
            break;
          case '6':
            $('#probationdetails,#probationdetails_view').show();
            $('#contractdetails,#regularizationdetails,#contractdetails_view,#regularizationdetails_view').hide();
            break;
          default:
            $('#contractdetails,#contractdetails_view').hide();
            $('#regularizationdetails,#regularizationdetails_view').show();
            break;
        }
      });
      // console.log(statushtml);

      genEmployeeData(eedata);
      genContactInfo(eedata);
      $.unblockUI();
      if (callback != undefined) callback(true);
    },
    error: function(request, status, err) {
      if (callback != undefined) callback(false);
    }
  });
}

function checkDateNull(dateVal) {
  if (dateVal == 'Mon 01 Jan 1900') {
    return '';
  } else {
    return dateVal;
  }
}

function genEmployeeData(data) {
  //console.log('Generate Employee Data');
  //console.log(data);

  var address = '';
  var sesid = data['sesid'];
  $('#sesid').val(sesid);

  //load img source
  var salutationid = checkNull(data['salutationid']);
  var salutation = checkNull(data['salutation']);
  var lastname = checkNull(data['lname']);
  var firstname = checkNull(data['fname']);
  var cnname = checkNull(data['cnname']);
  var nationality = checkNull(data['nationalitydesc']);
  var maritalstatus = checkNull(data['maritalstat']);
  var birthdate = checkDateNull(checkNull(data['birthdt']));
  var gender = checkNull(data['gender']);
  if (gender == 'f') {
    gender = 'Female';
  } else if (gender == 'm') {
    gender = 'Male';
  } else {
    gender = '';
  }
  var profilename = firstname + ' ' + lastname + ' ' ;

  $("#txtSalutation_edit option[value='" + salutationid + "']").prop('selected', true);
  $('#profilename').html(profilename + '(' + data['abaini'] + ')')
  $('#txtLastname').html(lastname);
  $('#txtFirstname').html(firstname);
  $('#txtChinesename').html(cnname);
  $('#txtNationality').html(nationality);
  $('#txtMaritalStatus').html(maritalstatus);
  $('#txtBirthdate').html(birthdate);
  $('#txtGender').html(gender);
  $('#txtSalutation').html(salutation);
  // $("#txtGovertmentID").val(govtidsocsec);

  let abaini = checkNull(data['abaini']);
  let govtidsocsec = checkNull(data['govtidsocsec']);
  let passportno = checkNull(data['passportno']);
  let passportissueddate = checkNull(data['passportissueddt']);
  let passportexpiry = checkNull(data['passportexpirydt']);
  let passportissuancecountry = checkNull(data['passportissuancecountry']);
  let passportissuancecountrydesc = checkNull(data['passportissuancecountrydesc']);

  // view
  $('#txtGovertmentID').html(govtidsocsec);
  $('#txtPassportNo').html(passportno);
  $('#txtIssuedDate').html(passportissueddate);
  $('#txtExpirationDate').html(passportexpiry);
  $('#txtPassportCountry').html(passportissuancecountrydesc);

  //edit
  $('#txtNameAbvt_edit').val(abaini);
  $('#txtGovertmentID_edit').val(govtidsocsec);
  $('#txtPassportNo_edit').val(passportno);
  $('#txtIssuedDate_edit').val(passportissueddate);
  $('#txtExpirationDate_edit').val(passportexpiry);
  $("#txtPassportCountry_edit option[value='" + passportissuancecountry + "']").prop('selected', true);
  // console.log('load: ' + passportissuancecountry);

  //contact info
  var eadd = checkNull(data['workemail']);
  var eaddPersonal = checkNull(data['emailaddress']);
  var mobphone = checkNull(data['mobileno']);
  var homephone = checkNull(data['homephoneno']);
  var wechat = checkNull(data['wechat']);
  var skype = checkNull(data['skype']);
  var whatsapp = checkNull(data['whatsapp']);
  var linkedin = checkNull(data['linkedin']);
  var presentcity = checkNull(data['presentcity']);
  var presentstate = checkNull(data['presentstate']);
  var countrydesc = checkNull(data['countrydesc']);
  var presentzipcode = checkNull(data['presentzipcode']);
  var presentaddr = checkNull(data['presentaddress']);

  address = presentaddr + ', ' + presentcity + ', ' + presentzipcode + ', ' + presentstate + ', ' + countrydesc;
  address = address.replace(/'/g, "");
  address = address.replace(/(\r\n|\n|\r)/gm, "");
  // console.log(data);
  $('#txtEmailAddress').html(eaddPersonal);
  $('#txtMobilePhone').html(mobphone);
  $('#txtHomePhone').html(homephone);
  $('#txtWechat').html(wechat);
  $('#txtSkype').html(skype);
  $('#txtWhatsapp').html(whatsapp);
  $('#txtLinkedin').html(linkedin);
  $('#txtPermanentAddress').html(address);

  //emergency contact
  var emercontactperson = checkNull(data['emercontactperson']);
  var emercontactno = checkNull(data['emercontactno']);
  var emercontactrelation = checkNull(data['emercontactrelation']);

  $('#txtEmergencyContactPerson').html(emercontactperson);
  $('#txtEmergencyPhoneNo').html(emercontactno);
  $('#txtRealtionship').html(emercontactrelation);

  //left side info
  var joineddate = checkNull(data['joindt']);
  var designationname = checkNull(data['designationnamedesc']);
  var departmentname = checkNull(data['deptdesc']);
  var eetype = checkNull(data['eetypedesc']);
  var eecategory = checkNull(data['eecategorydesc']);
  var reportsto = checkNull(data['reportsto']);
  var reportstoindirect = checkNull(data['reportstoindirect']);
  var ofc = checkNull(data['officename']);
  var ofcno = checkNull(data['officephoneno']);
  var postgradedesc = checkNull(data['postgradedesc']);

  $('#headerJoinedDate').html(joineddate);
  $('#headerDesignation').html(designationname);
  $('#headerDepartment').html(departmentname);
  // $("#headerEmpType").html(eetype);
  $('#headerEmpCat').html(eecategory);
  $('#headerReportsTo').html(reportsto);
  var reportshtml = '<p>Reports to Indirect</p><h6>' + reportstoindirect + '</h6>';
  if (reportstoindirect != '') $('#headerReportsToIndirect').html(reportshtml);

  //employee data
  $('#txtJoinedDate').html(joineddate);
  $('#txtOffice').html(ofc);
  $('#txtPosition').html(designationname);
  $('#txtDepartment').html(departmentname);
  $('#txtEECategory').html(eecategory);
  $('#headerEmpType').html(postgradedesc);
  $('#dataRanking').html(postgradedesc);
  $('#txtReportsto').html(reportsto);
  $('#txtReportstoindirect').html(reportstoindirect);

  let shiftfrom = checkNull(data['startshift']);
  let shiftto = checkNull(data['endshift']);
  $("#shiftschedFrom option[value='" + shiftfrom + "']").prop('selected', true);
  $("#shiftschedTo option[value='" + shiftto + "']").prop('selected', true);
  $('#txtShiftSched').html(intToTime(shiftfrom) + ' to ' + intToTime(shiftto));

  function intToTime(timeFromInt) {
    let intTime = parseInt(timeFromInt);
    if (intTime == 0) {
      return '00:00';
    } else if (intTime == 30) {
      return '00:30';
    } else {
      let last2 = timeFromInt.slice(-2);
      if (timeFromInt.length == 4) {
        return timeFromInt.substring(0, 2) + ':' + last2;
      } else {
        return '0' + timeFromInt.charAt(0) + ':' + last2;
      }
    }
  }
  $('#txtEmailAddress1').html(eadd);
  $('#txtOfficeNo').html(ofcno);
  $('#txtSkype1').html(skype);

  var probationperiod = checkNull(data['probationperiod']);
  var terminationperiod = checkNull(data['terminationperiod']);
  var visaprocessedbyaba = data['workingvisabyabacare'] == 'y' ? 'Yes' : 'No';
  var visaexpireddate = checkNull(data['visaexpireddate']);
  var probationcompletiondate = checkNull(data['probationcompletiondate']);
  var lastworkingday = checkNull(data['lastworkingday']);
  var effectivedate = checkNull(data['effectivedate']);
  var monthlygrosssalaryinlocalcurrencyshownincontract =
    data['monthlygrosssalaryinlocalcurrencyshownincontract'] == '' || data['monthlygrosssalaryinlocalcurrencyshownincontract'] == 0 ? '' : data['monthlygrosssalaryinlocalcurrencyshownincontract'];
  var monthlyaplusmedicalinsuranceinhkd = data['monthlyaplusmedicalinsuranceinhkd'] == '' || data['monthlyaplusmedicalinsuranceinhkd'] == 0 ? '' : data['monthlyaplusmedicalinsuranceinhkd'];
  var monthlymedicalinsuranceinlocal = data['monthlymedicalinsuranceinlocal'] == '' || data['monthlymedicalinsuranceinlocal'] == 0 ? '' : data['monthlymedicalinsuranceinlocal'];
  var monthlybusinessexpensesallowanceincontractinlocal =
    data['monthlybusinessexpensesallowanceincontractinlocal'] == '' || data['monthlybusinessexpensesallowanceincontractinlocal'] == 0 ? '' : data['monthlybusinessexpensesallowanceincontractinlocal'];
  var monthlyemployerscontributionmpfmfpsss =
    data['monthlyemployerscontributionmpfmfpsss'] == '' || data['monthlyemployerscontributionmpfmfpsss'] == 0 ? '' : data['monthlyemployerscontributionmpfmfpsss'];
  var companynameof1stcontractsigned = checkNull(data['companynameof1stcontractsigned']);
  var dateofmostrecentcontracteffective = checkNull(data['dateofmostrecentcontracteffective']);
  var actualplaceofcurrentwork = checkNull(data['actualplaceofcurrentwork']);
  var actualplaceofcurrentworkid = checkNull(data['actualplaceofcurrentworkid']);

  $('#txtProbationPeriod').html(probationperiod);
  $('#txtTerminationPeriod').html(terminationperiod);
  $('#dataVisaProcessedbyAbac').html(visaprocessedbyaba);
  $('#txtVisaExpDate').html(visaexpireddate);
  $('#txtProbationComplete_view').html(probationcompletiondate);
  $('#txtLastWorkingDt_view').html(lastworkingday);
  $('#txtEffectiveDate_view').html(effectivedate);
  $('#txtMonthlygrossSal_view').html(ccyFormat(monthlygrosssalaryinlocalcurrencyshownincontract));
  $('#txtxMosEmpContri_view').html(ccyFormat(monthlyemployerscontributionmpfmfpsss));
  $('#txtmosaplusmedinshkd_view').html(ccyFormat(monthlyaplusmedicalinsuranceinhkd));
  $('#txtmosmedinsinlocalcur_view').html(ccyFormat(monthlymedicalinsuranceinlocal));
  $('#txtmosbusexpinilocalcur_view').html(ccyFormat(monthlybusinessexpensesallowanceincontractinlocal));
  $('#txtcompanynamefirstctrcsigned_view').html(companynameof1stcontractsigned);
  $('#txtRecentCtrctDt_view').html(dateofmostrecentcontracteffective);
  $('#txtActPlaceofwork_view').html(actualplaceofcurrentwork);

  $('#txtProbationComplete').val(probationcompletiondate);
  $('#txtEndOfEmploymentDate').val(lastworkingday);
  $('#txtEffectiveDate').val(effectivedate);
  $('#txtMonthlygrossSal').val(ccyFormat(monthlygrosssalaryinlocalcurrencyshownincontract));
  $('#txtxMosEmpContri').val(ccyFormat(monthlyemployerscontributionmpfmfpsss));
  $('#txtmosaplusmedinshkd').val(ccyFormat(monthlyaplusmedicalinsuranceinhkd));
  $('#txtmosmedinsinlocalcur').val(ccyFormat(monthlymedicalinsuranceinlocal));
  $('#txtmosbusexpinilocalcur').val(ccyFormat(monthlybusinessexpensesallowanceincontractinlocal));
  $('#txtcompanynamefirstctrcsigned').val(companynameof1stcontractsigned);
  $('#txtRecentCtrctDt').val(dateofmostrecentcontracteffective);
  $("#txtActPlaceofwork option[value='" + actualplaceofcurrentworkid + "']").prop('selected', true);

  var probationenddate = checkNull(data['probationenddt']);
  var regularizationdate = checkNull(data['regularizationdt']);
  var typeofvisa = checkNull(data['workingtypeofvisa']);
  var visastartdate = checkNull(data['workingstartofvisadt']);
  //console.log(typeofvisa);

  $('#txtProbationStartDate_view').html(probationperiod);
  $('#txtProbationEndDate_view').html(probationenddate);
  $('#txtRegularizationDate_view').html(regularizationdate);
  $('#txtEndOfEmploymentDate_view').html(lastworkingday);
  $('#txtTypeOfVisa_view').html(typeofvisa);
  $('#txtVisaStartDate_view').html(visastartdate);
}

function genContactInfo(data) {
  //console.log('Generate contact info');
  //console.log(data);
  //personal info
  var lastname = checkNull(data['lname']);
  var firstname = checkNull(data['fname']);
  var cnname = checkNull(data['cnname']);
  var nationality = checkNull(data['nationality']);
  var maritalstatus = checkNull(data['maritalstatus']);
  var birthdate = checkNull(data['birthdt']);
  var gender = checkNull(data['gender']);
  // console.log(nationality);

  //contact info
  var eadd = checkNull(data['emailaddress']);
  var mobphone = checkNull(data['mobileno']);
  var homephone = checkNull(data['homephoneno']);
  var wechat = checkNull(data['wechat']);
  var skype = checkNull(data['skype']);
  var whatsapp = checkNull(data['whatsapp']);
  var linkedin = checkNull(data['linkedin']);
  var presentcity = checkNull(data['presentcity']);
  var presentstate = checkNull(data['presentstate']);
  var presentcountry = checkNull(data['presentcountry']);
  var presentzipcode = checkNull(data['presentzipcode']);
  var presentaddr = checkNull(data['presentaddress']);

  //emergency contact
  var emercontactperson = checkNull(data['emercontactperson']);
  var emercontactno = checkNull(data['emercontactno']);
  var emercontactrelation = checkNull(data['emercontactrelation']);

  //work info
  var joineddate = checkNull(data['joindt']);
  var designation = checkNull(data['designation']);
  var department = checkNull(data['department']);
  var eetype = checkNull(data['eetype']);
  var eecategory = checkNull(data['eecategory']);
  var reportsto = checkNull(data['reportstoid']);
  var reportstoindirect = checkNull(data['reportstoindirectid']);
  var ofc = checkNull(data['office']);
  var ofcno = checkNull(data['officephoneno']);
  var rankings = checkNull(data['positiongrade']);
  // console.log(eecategory);

  //employee data
  var workeadd = checkNull(data['workemail']);
  var workskype = checkNull(data['workskype']);
  var probationperiod = checkNull(data['probationperiod']);
  var terminationperiod = checkNull(data['terminationperiod']);
  var visaprocessedbyaba = checkNull(data['workingvisabyabacare']);
  var visaexpireddate = checkNull(data['visaexpireddate']);

  //account settings
  var zktecooffice = data['zkdeviceid'] == '' || data['zkdeviceid'] == 0 ? '' : data['zkdeviceid'];
  var zkid = data['zkid'] == '' || data['zkid'] == 0 ? '' : data['zkid'];

  var profilegroup = $('#profilegroup').val();
  $("#txtCountry option[value='" + presentcountry + "']").prop('selected', true);
  $('#eeid').val(data['userid']);
  $('#eeini').val(data.abaini);
  // $("#eeid_view").val(data['userid']);

  var probationenddate = checkNull(data['probationenddt']);
  var regularizationdate = checkNull(data['regularizationdt']);
  var typeofvisa = checkNull(data['workingtypeofvisa']);
  var visastartdate = checkNull(data['workingstartofvisadt']);
  //console.log(data);

  switch (profilegroup) {
    case 'personalinfo':
      $('#txtLastname').val(lastname);
      $('#txtFirstname').val(firstname);
      $('#txtChinesename').val(cnname);
      $("#txtNatl option[value='" + nationality + "']").prop('selected', true);
      $('#txtMaritalStat').val(maritalstatus);
      $('#txtBirthdate').val(birthdate);
      $('#txtgender').val(gender);
      break;
    case 'contactinfo':
      $('#txtEmailAddress').val(eadd);
      $('#txtMobileNo').val(mobphone);
      $('#txtHomePhone').val(homephone);
      $('#txtWeChat').val(wechat);
      $('#txtWhatsapp').val(whatsapp);
      $('#txtSkype').val(skype);
      $('#txtLinkedIn').val(linkedin);
      $('#txtPresentAdr').val(presentaddr);
      $('#txtCity').val(presentcity);
      $('#txtState').val(presentstate);
      $('#txtZipCode').val(presentzipcode);
      $('#txtEmergencyContactPerson').val(emercontactperson);
      $('#txtEmergencyPhoneNo').val(emercontactno);
      $('#txtRelationship').val(emercontactrelation);
      break;
    case 'employeedata':
      $('#txtJoinedDate').val(joineddate);
      $("#txtOffices option[value='" + ofc + "']").prop('selected', true);
      $("#txtDept option[value='" + department + "']").prop('selected', true);
      $("#txtEECat option[value='" + eecategory + "']").prop('selected', true);
      $("#txtRanking option[value='" + rankings + "']").prop('selected', true);
      $('#txtRepto').val(reportsto);
      $('#txtReptoindirect').val(reportstoindirect);
      $('#txtEmailAddress1').val(workeadd);
      $('#txtOfficeNo').val(ofcno);
      $('#txtSkype1').val(workskype);
      $('#txtProbationStartDate').val(probationperiod);
      $('#txtProbationEndDate').val(probationenddate);
      $('#txtTerminationPeriod').val(terminationperiod);
      $("#txtVisaProcessedbyAbac option[value='" + visaprocessedbyaba + "']").prop('selected', true);
      $('#txtVisaExpDate').val(visaexpireddate);
      $('#txtRegularizationDate').val(regularizationdate);
      $("#txtTypeOfVisa option[value='" + typeofvisa + "']").prop('selected', true);
      $('#txtStartOfVisa').val(visastartdate);
      if ($('#txtDept').val() != '') {
        loadJobTitles({ profilegroup: profilegroup, departmentid: department, valuepassed: designation });
      }
      break;
    case 'accountsettings':
      $("#txtZkOffice option[value='" + zktecooffice + "']").prop('selected', true);
      $('#txtZKID').val(zkid);
      break;
    default:
      break;
  }

  //console.log(probationenddate);
}

function emptyFields(selectors){
  return $(selectors)
  .map(function() {
    return {
      'key': this
              .parentElement
              .parentElement
              .innerText
              .replace('*','')
              .replace(':','')
              .trim(), 
      'value': this
                .value
                .trim()
    }
  })
  .get()
  .filter(e => e.value == '');
}

function requiredFilled(){
  let profilegroup = $('#profilegroup').val();
  let emptyFieldList;
  switch (profilegroup) {
    case 'personalinfo': // -------------------------------------------------------
      if($('#txtSalutation_edit').val() == ''){
          alertDialog('Salutation should not be empty!');
          $.unblockUI();
          return false;
      }
      if($('#txtMaritalStat').val() == ''){
          alertDialog('Marital status should not be empty!');
          $.unblockUI();
          return false;
      }
      if($('#txtgender').val() == ''){
          alertDialog('Gender should not be empty!');
          $.unblockUI();
          return false;
      }
      emptyFieldList = emptyFields('#txtLastname,#txtFirstname,#txtBirthdate,#txtNameAbvt_edit');
      if(emptyFieldList.length > 0){
        alertDialog(UCFirst(emptyFieldList[0].key.toLowerCase()) + ' should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#eeini').val() != $('#txtNameAbvt_edit').val() && $('#iniexist').val() == 1){
        alertDialog('Name abbreviation already exist, please choose a different one.');
        $.unblockUI();
        return false;
      }
      break;
    case 'contactinfo': // -------------------------------------------------------
      if (!isValidEmailAddress($('#txtEmailAddress').val())) {
        alertDialog('Personal email is not a valid email!');
        $.unblockUI();
        return false;
      }

      emptyFieldList = emptyFields('#txtMobileNo,#txtHomePhone');
      if(emptyFieldList.length > 0){
        alertDialog(UCFirst(emptyFieldList[0].key.toLowerCase()) + ' should not be empty!');
        $.unblockUI();
        return false;
      }
      
      if($('#txtCity').val() == '' || $('#txtState').val() == '' || 
         $('#txtCountry').val() == '' || $('#txtZipCode').val() == '' ||
         $('#txtPresentAdr').val() == ''){
          alertDialog('Present address should all be filled!');
          $.unblockUI();
          return false;
      }
      if($('#txtEmergencyContactPerson').val() == '' || $('#txtEmergencyPhoneNo').val() == '' || 
         $('#txtRelationship').val() == ''){
          alertDialog('Emergency contact should all be filled!');
          $.unblockUI();
          return false;
      }
      break;
    case 'employeedata': // -------------------------------------------------------
      if (!isValidEmailAddress($('#txtEmailAddress1').val())) {
        alertDialog('Work email is not a valid email!');
        $.unblockUI();
        return false;
      }

      if($('#txtOffices').val() == ''){
        alertDialog('Office should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#txtDept').val() == ''){
        alertDialog('Department should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#txtPositions').val() == ''){
        alertDialog('Position should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#txtEECat').val() == ''){
        alertDialog('Status should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#txtRanking').val() == ''){
        alertDialog('Ranking should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#txtRepto').val() == ''){
        alertDialog('Direct head should not be empty!');
        $.unblockUI();
        return false;
      }

      emptyFieldList = emptyFields('#txtJoinedDate,#txtEmailAddress1,#txtOfficeNo,#txtSkype1');
      if(emptyFieldList.length > 0){
        alertDialog(UCFirst(emptyFieldList[0].key.toLowerCase()) + ' should not be empty!');
        $.unblockUI();
        return false;
      }
      if($('#shiftschedFrom').val() == '00:00' || $('#shiftschedTo').val() == '00:00'){
        alertDialog('Shift schedule should not be empty!');
        $.unblockUI();
        return false;
      }
      break;
  }
  return true;
}

function updateEmployeeInfo() {
  //console.log('Update employee info');
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'updateEmployeeInfo';
  var userid = $('#userid').val();
  var eeid = $('#eeid').val();
  var profilegroup = $('#profilegroup').val();
  // console.log('profilegroup: ' + profilegroup);

  if(!requiredFilled()) return false;
  // console.log('Feilds filled');

  function ccyToDb(amountVal) {
    return amountVal.replace(/,/g, '');
  }

  // return false;

  //personal info
  var salutationid = $('#txtSalutation_edit option:selected').val();
  var lastname = $('#txtLastname').val();
  var firstname = $('#txtFirstname').val();
  var cnname = $('#txtChinesename').val();
  var nationality = $('#txtNatl').val();
  var maritalstatus = $('#txtMaritalStat').val();
  var birthdate = $('#txtBirthdate').val();
  var gender = $('#txtgender').val();
  var abaini = $('#txtNameAbvt_edit').val();
  var govtidsocsec = $('#txtGovertmentID_edit').val();
  var passportno = $('#txtPassportNo_edit').val();
  var passportissueddate = $('#txtIssuedDate_edit').val();
  var passportexpiry = $('#txtExpirationDate_edit').val();
  var passportissuancecountry = $('#txtPassportCountry_edit option:selected').val();

  //contact info
  var eadd = $('#txtEmailAddress').val();
  var mobphone = $('#txtMobileNo').val();
  var homephone = $('#txtHomePhone').val();
  var wechat = $('#txtWeChat').val();
  var skype = $('#txtSkype').val();
  var whatsapp = $('#txtWhatsapp').val();
  var linkedin = $('#txtLinkedIn').val();
  var presentcity = $('#txtCity').val();
  var presentstate = $('#txtState').val();
  var presentcountry = $('#txtCountry').val();
  var presentzipcode = $('#txtZipCode').val();
  var presentaddr = $('#txtPresentAdr').val();
  var emercontactperson = $('#txtEmergencyContactPerson').val();
  var emercontactno = $('#txtEmergencyPhoneNo').val();
  var emercontactrelation = $('#txtRelationship').val();

  //employee data
  var joineddate = $('#txtJoinedDate').val();
  var ofc = $('#txtOffices').val();
  var position = $('#txtPositions').val();
  var dept = $('#txtDept').val();
  var eecat = $('#txtEECat').val();
  var rankings = $('#txtRanking').val();
  var reportsto = $('#txtRepto').val();
  var reportstoindirect = $('#txtReptoindirect').val();
  var reportstotext = $('#txtRepto option:selected').text();
  var reportstoindirecttext = $('#txtReptoindirect option:selected').text();
  // $( "#txtRepto option:selected" ).text();
  var workeadd = $('#txtEmailAddress1').val();
  var ofcno = $('#txtOfficeNo').val();
  var workskype = $('#txtSkype1').val();
  var eestatus = 1;
  if (eecat == 5 || eecat == 7 || eecat == 9 || eecat == 10 || eecat == 11) {
    eestatus = 0;
  }
  var shiftfrom = $('#shiftschedFrom option:selected').val();
  var shiftto = $('#shiftschedTo option:selected').val();

  var probationcompletiondate = $('#txtProbationComplete').val();
  var lastworkingday = $('#txtEndOfEmploymentDate').val();
  var effectivedate = $('#txtEffectiveDate').val();
  var monthlygrosssalaryinlocalcurrencyshownincontract = ccyToDb($('#txtMonthlygrossSal').val());
  var monthlyemployerscontributionmpfmfpsss = ccyToDb($('#txtxMosEmpContri').val());
  var monthlyaplusmedicalinsuranceinhkd = ccyToDb($('#txtmosaplusmedinshkd').val());
  var monthlymedicalinsuranceinlocal = ccyToDb($('#txtmosmedinsinlocalcur').val());
  var monthlybusinessexpensesallowanceincontractinlocal = ccyToDb($('#txtmosbusexpinilocalcur').val());
  var companynameof1stcontractsigned = $('#txtcompanynamefirstctrcsigned').val();
  var dateofmostrecentcontracteffective = $('#txtRecentCtrctDt').val();
  var actualplaceofcurrentwork = $('#txtActPlaceofwork option:selected').val();

  //account settings
  var zkteco_office = $('#txtZkOffice').val();
  var zkteco_id = $('#txtZKID').val();

  //compensation and benefits
  // var leavebenefitsyear = $("#leavebenefitsyear option:selected").val();
  // var benefitlistdatatable = function(){
  // 	table = $('#benefitlistdatatable').DataTable();
  // 	let plainArray = table
  // 		.columns()
  // 		.data()
  // 		.toArray();
  // 	return (transpose_array(plainArray));
  // }

  var probationperiod = $('#txtProbationStartDate').val();
  var terminationperiod = $('#txtTerminationPeriod').val();
  var visaprocessedbyaba = $('#txtVisaProcessedbyAbac').val();
  var visaexpireddate = $('#txtVisaExpDate').val();

  var regularizationdate = $('#txtRegularizationDate').val();
  var typeofvisa = $('#txtTypeOfVisa').val();
  var startofvisa = $('#txtStartOfVisa').val();
  var probationenddate = $('#txtProbationEndDate').val();
  var startcontractdate = $('#txtStartContractDate').val();
  var endcontractdate = $('#txtEndContractDate').val();

  var data = {
    f: f,
    userid: userid,
    eeid: eeid,
    profilegroup: profilegroup,
    lastname: lastname,
    firstname: firstname,
    cnname: cnname,
    nationality: nationality,
    maritalstatus: maritalstatus,
    birthdate: birthdate,
    gender: gender,
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
    zkteco_office: zkteco_office,
    zkteco_id: zkteco_id,
    govtidsocsec: govtidsocsec,
    passportno: passportno,
    passportissueddate: passportissueddate,
    passportexpiry: passportexpiry,
    passportissuancecountry: passportissuancecountry,
    salutationid: salutationid,
    abaini: abaini,
    probationcompletiondate: probationcompletiondate,
    lastworkingday: lastworkingday,
    effectivedate: effectivedate,
    monthlygrosssalaryinlocalcurrencyshownincontract: monthlygrosssalaryinlocalcurrencyshownincontract,
    monthlyemployerscontributionmpfmfpsss: monthlyemployerscontributionmpfmfpsss,
    monthlyaplusmedicalinsuranceinhkd: monthlyaplusmedicalinsuranceinhkd,
    monthlymedicalinsuranceinlocal: monthlymedicalinsuranceinlocal,
    monthlybusinessexpensesallowanceincontractinlocal: monthlybusinessexpensesallowanceincontractinlocal,
    companynameof1stcontractsigned: companynameof1stcontractsigned,
    dateofmostrecentcontracteffective: dateofmostrecentcontracteffective,
    actualplaceofcurrentwork: actualplaceofcurrentwork,
    eestatus: eestatus,
    probationenddate: probationenddate,
    regularizationdate: regularizationdate,
    typeofvisa: typeofvisa,
    startofvisa: startofvisa,
    startcontractdate: startcontractdate,
    endcontractdate: endcontractdate,
    shiftfrom: shiftfrom,
    shiftto: shiftto
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
      // // return false;
      var err = data['contactupdated']['err'];
      var errmsg = data['contactupdated']['errmsg'];
      // var profilegroup = data['profilegroup'];
      // return false;
      if (err == 1) {
        alertDialog('An error occured while updating the data. Please contact web administrator.<br/><br/>Error message:<br/>' + errmsg);
      } else {
        alertDialog('Changes has been successfully applied.');
      }

      loadEmployeeProfile();
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function loadDropdown() {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'loadDropdown';
  var profilegroup = $('#profilegroup').val();
  var deptid = $('#txtDept').val();
  var data = { f: f, profilegroup: profilegroup, deptid: deptid };
  // console.log('loadDropDown: ' + profilegroup);

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
      var profilegroup = $('#profilegroup').val();
      var abappl = data['eedata']['rows'];
      switch (profilegroup) {
        case 'personalinfo':
          // nationalities 
          var natls = data['nationalities']['rows'];
          var natslshtml = '';
          natslshtml = '<select id="txtNatl" class="form-control form-control-sm">';
          natslshtml += '<option value="" selected></option>';
          for (var i = 0; i < natls.length; i++) {
            natslshtml += '<option value="' + natls[i]['nationalityid'] + '">' + natls[i]['description'] + '</option>';
          }
          natslshtml += '</select>';
          $('#divNationalities').html(natslshtml);

          // marital status
          var maritalstat = data['maritalstatus'];
          var marstathtml = '';
          marstathtml = '<select id="txtMaritalStat" class="form-control form-control-sm">';
          marstathtml += '<option value="" selected></option>';
          for (var i = 0; i < maritalstat.length; i++) {
            marstathtml += '<option value="' + maritalstat[i]['ddid'] + '">' + maritalstat[i]['dddescription'] + '</option>';
          }
          marstathtml += '</select>';
          $('#divMaritalStat').html(marstathtml);

          let salutationsdata = data.salutations;
          let salutationshtml = '<option value="" selected></option>';
          salutationsdata.forEach(saluts => {
            salutationshtml += '<option value="' + saluts.ddid + '">' + saluts.dddescription + '</option>';
          });
          $('#txtSalutation_edit').html(salutationshtml);
          break;

        case 'contactinfo':
          break;

        case 'employeedata':
          // job positions
          // var jobposthtml = "";
          // jobposthtml = '<select id="txtPositions" class="form-control form-control-sm">';
          // jobposthtml += '<option value="" selected></option>';
          // 	for(var i=0;i<jobpositions.length;i++){
          // 		jobposthtml += '<option value="'+ jobpositions[i]['designationid'] +'">'+ jobpositions[i]['description'] +'</option>'
          // 	}
          // jobposthtml += '</select>';
          // $("#divPosition").html(jobposthtml);

          // offices
          var ofcs = data['offices'];
          var ofchtml = '';
          ofchtml = '<select id="txtOffices" class="form-control form-control-sm">';
          ofchtml += '<option value="" selected></option>';
          for (var i = 0; i < ofcs.length; i++) {
            ofchtml += '<option value="' + ofcs[i]['salesofficeid'] + '">' + ofcs[i]['description'] + '</option>';
          }
          ofchtml += '</select>';
          $('#divOffice').html(ofchtml);

          // departments
          var dept = data['departments']['rows'];
          var depthtml = '';
          depthtml = '<select id="txtDept" class="form-control form-control-sm">';
          depthtml += '<option value="" selected></option>';
          dept.forEach(element => {
            depthtml += '<option value="' + element['departmentid'] + '">' + element['description'] + '</option>';
          });
          // for(var i=0;i<dept.length;i++){
          // 	depthtml += '<option value="'+ dept[i]['departmentid'] +'">'+ dept[i]['description'] +'</option>'
          // }
          depthtml += '</select>';
          $('#divDepartment').html(depthtml);

          $('#txtDept').change(function() {
            if ($('#txtDept').val() == '') {
              return false;
            }
            $.blockUI({
              message: $('#preloader_image'),
              fadeIn: 1000,
              onBlock: function() {
                loadJobTitles({ profilegroup: profilegroup, departmentid: $('#txtDept').val(), valuepassed: '' });
              }
            });
          });

          //status/eecategory
          var eecat = data['eecat'];
          var statushtml = '';
          statushtml = '<select id="txtEECat" class="form-control form-control-sm">';
          statushtml += '<option value="" selected></option>';
          for (var i = 0; i < eecat.length; i++) {
            statushtml += '<option value="' + eecat[i]['ddid'] + '">' + eecat[i]['dddescription'] + '</option>';
          }
          statushtml += '</select>';
          $('#divEEcategory').html(statushtml);

          //rankings

          var eeranks = data['eeranks'];
          var rankhtml = '';
          rankhtml = '<select id="txtRanking" class="form-control form-control-sm">';
          rankhtml += '<option value="" selected></option>';
          for (var i = 0; i < eeranks.length; i++) {
            rankhtml += '<option value="' + eeranks[i]['ddid'] + '">' + eeranks[i]['dddescription'] + '</option>';
          }
          rankhtml += '</select>';
          // console.log(rankhtml);
          $('#divRanking1').html(rankhtml);

          // direct head abaini
          var reptohtml = '';
          reptohtml = '<select id="txtRepto" class="form-control form-control-sm">';
          reptohtml += '<option value="" selected></option>';
          for (var i = 0; i < abappl.length; i++) {
            reptohtml += '<option value="' + abappl[i]['userid'] + '">' + abappl[i]['eename'] + '</option>';
          }
          reptohtml += '</select>';
          // console.log(reptohtml);
          $('#divReportsTo').html(reptohtml);

          // indirect head abaini

          var indreptohtml = '';
          indreptohtml = '<select id="txtReptoindirect" class="form-control form-control-sm">';
          indreptohtml += '<option value="" selected></option>';
          for (var i = 0; i < abappl.length; i++) {
            indreptohtml += '<option value="' + abappl[i]['userid'] + '">' + abappl[i]['eename'] + '</option>';
          }
          indreptohtml += '</select>';
          // console.log(reptohtml);
          $('#divReportsToIndirect').html(indreptohtml);

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

          break;

        case 'accountsettings':
          var zktecooffices = data['zktecooffices']['rows'];
          var zkhmtl = '';
          zkhmtl = '<select name="txtZkOffice" id="txtZkOffice" class="form-control form-control-sm">';
          zkhmtl += '<option value="" selected></option>';
          for (var i = 0; i < zktecooffices.length; i++) {
            zkhmtl += '<option value="' + zktecooffices[i]['id'] + '">' + zktecooffices[i]['devicename'] + '</option>';
          }
          zkhmtl += '</select>';
          $('#divZktecoOffices').html(zkhmtl);
          break;
        case 'compensationandbenefits':
          // departments
          var dept = data['departments']['rows'];
          var depthtml = '';
          depthtml += '<option value="" selected></option>';
          for (var i = 0; i < dept.length; i++) {
            depthtml += '<option value="' + dept[i]['departmentid'] + '">' + dept[i]['description'] + '</option>';
          }
          $('#department').html(depthtml);
          $('#positionBtnSave').attr('disabled', true);
          $('#department').change(function() {
            $('#position').attr('disabled', 'true');
            $('#position').html('');
            if ($('#department').val() == '') {
              $('#position').html('');
              return false;
            }
            loadJobTitles({ profilegroup: profilegroup, departmentid: $('#department').val(), valuepassed: '' });
          });
          loadCompensationBenefits();
          break;
        case 'certification':
          loadCertifications();
          break;
        default:
          break;
      }

      loadEmployeeProfile();
      // $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function loadJobTitles(val) {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'loadJobTitles';
  var deptid = val.departmentid;
  var profilegroup = val.profilegroup;
  var valuepassed = val.valuepassed;
  // console.log(val);
  var data = { f: f, deptid: deptid };
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
      var jobpositions = data['jobpositions']['rows'];
      var jobposthtml = '';
      // console.log(data);
      switch (profilegroup) {
        case 'employeedata':
          jobposthtml = '<select id="txtPositions" class="form-control form-control-sm">';
          jobposthtml += '<option value="" selected></option>';
          for (var i = 0; i < jobpositions.length; i++) {
            jobposthtml += '<option value="' + jobpositions[i]['designationid'] + '">' + jobpositions[i]['description'] + '</option>';
          }
          jobposthtml += '</select>';
          $('#divPosition').html(jobposthtml);
          //console.log('valuepassed: ' + valuepassed);
          if (valuepassed != '') $("#txtPositions option[value='" + valuepassed + "']").prop('selected', true);
          break;
        case 'compensationandbenefits':
          jobposthtml += '<option value="" selected></option>';
          jobpositions.forEach(element => {
            jobposthtml += '<option value="' + element.designationid + '">' + element.description + '</option>';
          });
          $('#position').html(jobposthtml);
          $('#position').removeAttr('disabled');
          break;
        default:
          break;
      }

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

// returns "n year(s) and n month(s)"
  function getTenure(d1, d2) {
    if (d1 == null)
      return '';
    if (d2 == null)
      return '';
    let date1 = new Date(d1);
    let date2 = new Date(d2);
    let date1Y = date1.getFullYear();
    let date2Y = date2.getFullYear();
    let date1M = date1.getMonth();
    let date2M = date2.getMonth();

    let monthsReturn = date2M + 12 * date2Y - (date1M + 12 * date1Y);
    let yearsReturn = date2Y - date1Y;
    
    if (monthsReturn == 0 && yearsReturn == 0) {
      let dayOnly = date2.getDate() - date1.getDate();
      dayOnly = dayOnly > 1 ? dayOnly + ' days' : dayOnly < 0 ? '-' : dayOnly + ' day';
      return dayOnly;
    }
  
    let newMonth = monthsReturn;
    let yearBaseMonth = 0;
    while(newMonth >= 12) {
      yearBaseMonth++;
      newMonth -= 12;
    }

    let monthOut = '';
    let finalOut = '';
    monthOut = newMonth > 1 ? newMonth + 'mos' : newMonth < 0 ? '-' : newMonth + 'mo';
    if (yearBaseMonth > 0) {
      finalOut = yearBaseMonth > 1 ? yearBaseMonth + 'yrs' : yearBaseMonth + 'yr';
      finalOut += ' and ' + monthOut;
    } else {
      finalOut = monthOut
    }

    return finalOut;
  }

function loadCompensationBenefits() {
  // console.log('Load compensation and benefits');
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'loadCompensationBenefits';
  var userid = $('#eeid').val();
  var params = new window.URLSearchParams(window.location.search);
  var action = params.get('action');
  var indexid = $('#index_id').val() == undefined ? '' : $('#index_id').val();
  if (action != null) {
    action = action.includes('edit') ? action : atob(action);
  }
  var sesid = params.get('id');
  if (sesid == null) {
    sesid = $('#sesid').val();
  }
  // year of benefits
  const initialYear = 2019;
  let thisYear = new Date().getFullYear();
  let listyearbenefitshtml = '';
  for (let yearIncrement = initialYear; yearIncrement <= thisYear; yearIncrement++) {
    listyearbenefitshtml += '<option value=' + yearIncrement + '>' + yearIncrement + '</option> ';
    listyearbenefitshtml += '<option value=' + yearIncrement + '>' + yearIncrement - 1 + '</option> ';
  }
  $('#leavebenefitsyear').html(listyearbenefitshtml);

  // year of benefits view
  let yearleavehtml = '';
  for (let yearIncrement = initialYear; yearIncrement <= thisYear; yearIncrement++) {
    yearleavehtml += '<option value=' + yearIncrement + '>' + yearIncrement + '</option> ';
    yearleavehtml += '<option value=' + yearIncrement + '>' + yearIncrement - 1 + '</option> ';
  }
  $('#benefitsleaveyear').html(yearleavehtml);

  var data = { f: f, userid: userid, indexid: indexid, sesid: sesid };
  // console.log(data);
  // return false;

  var compben_leave = function(data) {
    let leave_benefits = data.leave_benefits.rows;
    let leave_credits = data.leave_credits.rows;
    let leave_pending = data.leave_pending.rows;
    let leave_benefitshtml = '';
    // console.log(data);

    $('#leavebenefitsyear').on('change', function() {
      // console.log('hi');
      loadEditCompBen(leave_benefits);
    });
    $('#benefitsleaveyear').on('change', function() {
      // console.log('hi');
      viewLeaveBenefitsProfileView(leave_benefits);
    });
    $('#leavebenefitsyear').val(thisYear);
    $('#benefitsleaveyear').val(thisYear);
    // console.log($('#leavebenefitsyear').val());
    // console.log(data);
    var loadEditCompBen = function(leave_benefits) {
      // alertDialog('hi');
      //console.log('>Editing');
      var fiscalyr = '';
      let benefitlist = '';
      let editBen = '';
      let editStat = '';
      let fcolor = '';
      let checkToggle = '';
      let entitled_tmp = '0.0';
      let status_tmp = 'disabled';
      let statusid_tmp = '0';
      // let fiscalyr;
      let creditid;
      let creditini;
      let leavepending;
      let leavebalance;
      let leavetaken;
      // let status = "";
      // console.log(leave_pending);
      let statusList = [];
      leave_benefits.forEach(benefits => {
        entitled_tmp = '0.0';
        status_tmp = 'disabled';
        creditid = '0';
        statusid_tmp = '0';
        leavepending = 0.0;
        leavebalance = '0.0';
        leavetaken = '0.0';
        creditini = benefits.benefitini;
        // fiscalyr = new Date().getFullYear();
        var fiscalyr = $('#leavebenefitsyear').val();

        // return false;

        leave_pending.forEach(pending => {
          // console.log(pending.leavetype + ' == ' + benefits.benefitini.toUpperCase() + '' + fiscalyr.slice(-2));
          if (pending.leavetype == benefits.benefitini.toUpperCase() + '' + fiscalyr.slice(-2)) {
            leavepending = parseFloat(parseFloat(leavepending) + parseFloat(pending.noofdays)).toFixed(1);
          }
        });

        leave_credits.find(credits => {
          if (credits.leavetype == benefits.benefitini && credits.fiscalyear == fiscalyr) {
            entitled_tmp = credits.entitleddays;
            status_tmp = credits.statusname;
            statusid_tmp = credits.status;
            creditid = credits.id;
            creditini = credits.leavetype;
            fiscalyr = credits.fiscalyear;
            leavetaken = credits.takendays;
            leavebalance = parseFloat(credits.leavebalance) - parseFloat(leavepending);
          }
        });

        leavebalance = leavebalance < 0 ? 0 : leavebalance;

        fcolor = '';
        checkToggle = 'checked';
        if (statusid_tmp == 0) {
          fcolor = 'color: #ff0000;';
          checkToggle = '';
        }

        editBen = "return editBenefit('" + creditini + "', " + fiscalyr + ');';
        editStat = "return toggleStatus('" + creditini + "', " + fiscalyr + ');';

        benefitlist += '<tr style="' + fcolor + ' cursor: pointer;" id="' + creditini + '' + fiscalyr + '">';
        benefitlist += '<td onClick="' + editBen + '">' + benefits.description + '</td>';
        benefitlist += '<td class="text-center" onClick="' + editBen + '">' + parseFloat(entitled_tmp).toFixed(1) + '</td>';
        benefitlist += '<td class="text-center" onClick="' + editBen + '">' + parseFloat(leavepending).toFixed(1) + '</td>';
        benefitlist += '<td class="text-center" onClick="' + editBen + '">' + parseFloat(leavetaken).toFixed(1) + '</td>';
        benefitlist += '<td class="text-center" onClick="' + editBen + '">' + parseFloat(leavebalance).toFixed(1) + '</td>';
        // benefitlist += '<td class="text-center" onClick="' + editBen + '">' + status_tmp + '</td>';
        // benefitlist += '<td class="text-center">';
        // benefitlist += '	<input ' + checkToggle + ' id="' + creditini + '" type="checkbox" data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-height="5" data-offstyle="info" data-onstyle="danger">';
        // benefitlist += '</td>';
        benefitlist +=
          '<td><input  style="height:1.3vw;" type="checkbox" class="form-control form-control-sm align-middle" value="' + statusid_tmp + '" ' + checkToggle + ' onClick="' + editStat + '"/></td>';
        // benefitlist += '<td align="center"><a href="#" onClick="' + editBen + '" title="Edit" class="px-1"><i class="fas fa-edit fa-md text-gray-800" ></i></a></td>';
        benefitlist += '</tr>';
        statusList.push(creditini);
      });
      $('#benefitlistdatatable')
        .find('tbody')
        .html(benefitlist);
      // const statusUnique = statusList.filter((item, index) => statusList.indexOf(item) === index);
      // statusUnique.forEach(statusItem => {
      // 	$('#' + statusItem).bootstrapToggle();
      // });
    };
    viewLeaveBenefitsProfileView();

    function viewLeaveBenefitsProfileView() {
      if (action == 'editprofile') {
        loadEditCompBen(leave_benefits);
      } else {
        leave_benefitshtml = '';
        let leavebalance_view = '0.0';
        let leavetaken = '0.0';
        let leavepending = 0.0;
        leave_benefits.forEach(benefits => {
          entitled_tmp = '0.0';
          status_tmp = 'disabled';
          creditid = '0';
          statusid_tmp = '0';
          leavebalance_view = '0.0';
          leavepending = 0.0;
          creditini = benefits.benefitini;
          // fiscalyr = new Date().getFullYear();
          var fiscalyr = $('#benefitsleaveyear').val();
          // console.log(fiscalyr);

          leave_pending.forEach(pending => {
            // console.log(pending.leavetype + ' == ' + benefits.benefitini.toUpperCase() + '' + fiscalyr.slice(-2));
            if (pending.leavetype == benefits.benefitini.toUpperCase() + '' + fiscalyr.slice(-2)) {
              leavepending = parseFloat(parseFloat(leavepending) + parseFloat(pending.noofdays)).toFixed(1);
            }
          });

          leave_credits.find(credits => {
            if (credits.leavetype == benefits.benefitini && credits.fiscalyear == fiscalyr) {
              entitled_tmp = credits.entitleddays;
              status_tmp = credits.statusname;
              statusid_tmp = credits.status;
              creditid = credits.id;
              creditini = credits.leavetype;
              fiscalyr = credits.fiscalyear;
              leavebalance_view = parseFloat(credits.leavebalance) - parseFloat(leavepending);
              leavetaken = credits.takendays;
              // console.log(fiscalyr);
            }
          });

          leavebalance_view = leavebalance_view < 0 ? 0 : leavebalance_view;

          // console.log(leave_credits);
          // console.log(benefits.benefitini);
          fcolor = '';
          if (statusid_tmp > 0) {
            leave_benefitshtml += '<tr>';
            leave_benefitshtml += '<td>' + benefits.description + '</td>';
            leave_benefitshtml += '<td class="text-right">' + parseFloat(entitled_tmp).toFixed(1) + '</td>';
            leave_benefitshtml += '<td class="text-right">' + parseFloat(leavepending).toFixed(1) + '</td>';
            leave_benefitshtml += '<td class="text-right">' + parseFloat(leavetaken).toFixed(1) + '</td>';
            leave_benefitshtml += '<td class="text-right">' + parseFloat(leavebalance_view).toFixed(1) + '</td>';
            leave_benefitshtml += '</tr>';
          }
          $('#leave_benefits')
            .find('tbody')
            .html(leave_benefitshtml);
        });
      }
    }
    // viewLeaveBenefitsProfileView();
    // console.log(leave_benefitshtml);
  };

  let compben_positionhistory = function(data) {
    const positionhistory = data.position_history.rows;

    if (action == null || action == 'viewprofile' || action == 'viewbygm') {
    const norecord = '<tr><td>No Record</td><td></td><td></td><td></td><td></td><td></td></tr>';
    let position_history_out = '';
    serverDateNow((datenow) => {
      positionhistory.forEach((position, index, thisarr) =>{
        let prevdate = new Date(index > 0 ? thisarr[index-1].startdate : datenow);
        const positionDesc = position.positiondescription == null ? position.positiondesc : position.positiondescription;
        let enddate = position.newenddate == null || position.enddate == '1900-01-01 00:00:00' ? '' : position.newenddate;
        let datefortenure = enddate == '' ? prevdate : enddate;
        position_history_out += '<tr>';
        position_history_out += '<td>' + positionDesc + '</td>';
        position_history_out += "<td class='text-right'>" + position.newstartdate + '</td>';
        position_history_out += "<td class='text-right'>" + enddate + '</td>';
        position_history_out += '<td>' + getTenure(position.newstartdate, datefortenure) + '</td>';
        position_history_out += "<td class='text-right'>" + ccyFormat(position.rate) + '</td>';
        position_history_out += "<td style='max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" + position.remarks + '</td>';
        position_history_out += '</tr>';
      });
      position_history_out = position_history_out == '' ? norecord : position_history_out;
      $('#position_history').html(position_history_out);
    });
    } else {
      compben_position_history();
    }
    //#region old
    //   let column_names = ['desc', 'salary', 'date'];
    //   let position_history_out = '';
    //   let notDisplayed = 0;
    //   if (action == null || action == 'viewprofile' || action == 'viewbygm') {
    //     for (let i = itemMax; i >= 0; i--) {
    //       let desc_n = abaperson[column_names[0] + i];
    //       // console.log(desc_n);
    //       if (desc_n == null) {
    //         notDisplayed += 1;
    //         continue;
    //       }
    //       let salary_n = addCommas(parseFloat(abaperson[column_names[1] + i]).toFixed(0));
    //       let date_n = abaperson[column_names[2] + i];
    //       let tenure_n = getTenure(date_n, abaperson[column_names[2] + (i + 1)]);
    //       position_history_out += '<tr>';
    //       position_history_out += '<td>' + desc_n + '</td>';
    //       position_history_out += "<td class='text-right'>" + salary_n + '</td>';
    //       position_history_out += '<td>' + tenure_n + '</td>';
    //       position_history_out += '</tr>';
    //     }
    //     if (notDisplayed - 1 === itemMax) {
    //       position_history_out += '<tr>';
    //       position_history_out += '<td>No Record</td>';
    //       position_history_out += '<td></td>';
    //       position_history_out += '<td></td>';
    //       position_history_out += '</tr>';
    //     }
    // $('#position_history').html(position_history_out);
    //   } else {
    //     compben_position_history();
    //   }
    //#endregion
  }

  let compben_hmo = function(data) {
    let hmo_benefits = data.hmo_benefits.rows;
    let hmo_benefitshtml = '';
    hmo_benefits.forEach(element => {
      let hmo_coverageamount = element['hmo_benefits_coverageamount'];
      hmo_coverageamount = addCommas(hmo_coverageamount);
      hmo_benefitshtml += '<tr>';
      hmo_benefitshtml += '<td>' + element['hmo_benefits_description'] + '</td>';
      hmo_benefitshtml += "<td class='text-right'>" + hmo_coverageamount + '</td>';
      hmo_benefitshtml += '</tr>';
    });
    $('#hmo_benefits')
      .find('tbody')
      .html(hmo_benefitshtml);
  };

  

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data)
      // return;
      compben_positionhistory(data);
      compben_leave(data);
      compben_hmo(data);
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}






function addPositionHistory() {
  
  $('#positionBtnDelete').hide();
  $('#index_id').val('');
  $('#positionBtnSave')
    .html('  Add  ')
    .attr('onClick', 'return addNewPositionHistory();');
  // $("#position option[value='"+ positionHistory.positionid +"']").prop('selected','true');
  $('#position').html('');
  $('#position').attr('disabled', 'true');
  $('#rate').val('0.00');
  $('#remarks').val('');
  $('#effectivedate').val('');
  $('#activepresent').prop('checked', false);
  $('#enddate').val('');
  $('#user_id').val($('#eeid').val());
  $('#department option[value=""]').prop('selected', 'true');

  $('#editPorfileHistory').modal('show');
  // $.blockUI({
  //   message: $('#preloader_image'),
  //   fadeIn: 1000,
  //   onBlock: function() {
  //     addPosHist();
  //   }
  // });
}
// function addPosHist() {
//   // let url = getAPIURL() + 'eesprofile.php';
//   // let f = 'loadCompensationBenefits';
//   let sesid = $('#sesid').val();
//   let userid = $('#userid').val();
//   let eeid = $('#eeid').val();

//   // let data = { f: f, userid: userid, eeid: eeid, sesid: sesid };
//   // //console.log(data);
//   // $.ajax({
//   //   type: 'POST',
//   //   url: url,
//   //   data: JSON.stringify({ data: data }),
//   //   dataType: 'json',
//   //   success: function(data) {
//       // let dataList = data.position_list.rows;
//       // let listhtml = '';
//       // listhtml += '<option></option>';
//       // dataList.forEach(element => {
//       // 	listhtml += '<option value="' + element.designationid + '">' + element.description + '</option>';
//       // })
//       // $('#position').html(listhtml);
//       // $('#department option[value=""]').prop('selected', 'true');
//       $('#positionBtnSave')
//         .html('  Add  ')
//         .attr('onClick', 'return addNewPositionHistory();');
//       // $("#position option[value='"+ positionHistory.positionid +"']").prop('selected','true');
//       $('#position').html('');
//       $('#position').attr('disabled', 'true');
//       $('#rate').val('');
//       $('#effectivedate').val('');
//       $('#user_id').val(userid);

//       $('#editPorfileHistory').modal('show');
//       $.unblockUI();
//     // },
//     // error: function(request, status, err) {}
//   // });
// }

function addNewPositionHistory() {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'addPositionHistory';

  var data = {
    f: f,
    userid: $('#userid').val(),
    eeid: $('#eeid').val(),
    position_n: $('#position').val(),
    salary_n: $('#rate').val(),
    effectivedate_n: $('#effectivedate').val(),
    enddate_n: $('#enddate').val(),
    remarks: $('#remarks').val()
  };
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ data: data }),
        dataType: 'json',
        success: function(data) {
          // console.log(data);
          compben_position_history();
          $('#editPorfileHistory').modal('hide');
          // return false;
    
          $.unblockUI();
        },
        error: function(request, status, err) {}
      });
    }
  });
}

function getPositionHistory(id) {
  // console.log(id);
  $('#positionBtnDelete').show();
  $('#index_id').val(id);
  $('#positionBtnSave')
    .html('  Add  ')
    .attr('onClick', 'return updatedPositionHistory();');
    
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      viewPosHist();
    }
  });
}

function viewPosHist() {
  const url = getAPIURL() + 'eesprofile.php';
  const f = 'loadCompensationBenefits';
  const sesid = $('#sesid').val();
  const userid = $('#eeid').val();
  const indexid = $('#index_id').val();

  const data = { f: f, userid: userid, indexid: indexid, sesid: sesid };
  //console.log(data);

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      // return;
      const positionHistory = data.position_history.rows;
      const positionItem = positionHistory.find(position => position.id == indexid);
      let dataList = data.position_list.rows;
      let listhtml = '';
      const getdepartment = dataList.find(item => item.designationid == positionItem.position);
      let departmentid = '';
      if(getdepartment != undefined){
        departmentid = getdepartment.departmentid;
        dataList.forEach(item => {
          if (item.departmentid == departmentid) {
            listhtml += '<option value="' + item.designationid + '">' + item.description + '</option>';
          }
        });
      }
      let rate_val = ccyFormat(positionItem.rate);
      $('#position').html(listhtml);
      $('#position').removeAttr('disabled');
      $('#positionBtnSave').html('Save Changes');
      $('#positionBtnSave').removeAttr('disabled', false);
      $("#department option[value='" + departmentid + "']").prop('selected', 'true');
      $("#position option[value='" + positionItem.position + "']").prop('selected', 'true');
      $('#remarks').val(positionItem.remarks);
      $('#rate').val(rate_val);
      ccyHandler('#rate');
      $('#effectivedate').val(positionItem.newstartdate);
      if(positionItem.newenddate == null || positionItem.enddate == '1900-01-01 00:00:00') {
        $('#activepresent').prop('checked', true);
        $('#enddate').attr('disabled', true);
        $('#enddate').val('');
      } else {
        $('#activepresent').prop('checked', false);
        $('#enddate').attr('disabled', false);
        $('#enddate').val(positionItem.newenddate);
      }
      $('#user_id').val(userid);
      $('#index_id').val(indexid);

      $('#editPorfileHistory').modal('show');
      $.unblockUI();
    },
    error: function(request, status, err) {
      $.unblockUI();
    }
  });
}

function updatedPositionHistory() {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'updatePositionHistory';
  var userid = $('#user_id').val();
  var eeid = $('#eeid').val();
  var indexid = $('#index_id').val();
  var remarks = $('#remarks').val();
  
  var position_n = $('#position').val();
  var salary_n = $('#rate').val();
  var effectivedate_n = $('#effectivedate').val();
  var enddate_n = $('#enddate').val();
  
  var data = {
    f: f,
    userid: userid,
    eeid: eeid,
    indexid: indexid,
    position_n: position_n,
    salary_n: salary_n,
    effectivedate_n: effectivedate_n,
    enddate_n: enddate_n,
    remarks: remarks
  };
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // loadCompensationBenefits();
      // console.log(data);
      compben_position_history();
      $('#editPorfileHistory').modal('hide');
      // return false;

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function deletePositionHistory() {
  const indexid = $('#index_id').val();
  // if(indexid != '')
  const data = {
    userid: $('#userid').val(),
    eeid: $('#eeid').val(),
    indexid: $('#index_id').val()
  };

  blockUI(() => {
    qryData('eesprofile', 'deletePositionHistory', data, (success) => {
      compben_position_history();
      $('#editPorfileHistory').modal('hide');
      $.unblockUI();
    }, true);
  });
}


function compben_position_history() {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'loadCompensationBenefits';
  let sesid = $('#sesid').val();
  var userid = $('#eeid').val();
  var indexid = '';
  var data = { f: f, userid: userid, indexid: indexid, sesid: sesid };

  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      // return false;
      const positionhistory = data.position_history.rows;
      const norecord = '<tr><td>No Record</td><td></td><td></td><td></td><td></td><td></td></tr>';
      let position_history_out = '';
      serverDateNow((datenow) => {
        positionhistory.forEach((position, index, thisarr) =>{
          let prevdate = new Date(index > 0 ? thisarr[index-1].startdate : datenow);
          let positionitem = position.positiondescription == null ? position.positiondesc : position.positiondescription;
          let enddate = position.newenddate == null || position.enddate == '1900-01-01 00:00:00' ? '' : position.newenddate;
          let datefortenure = enddate == '' ? prevdate : enddate;
          // position_history_out += index == 0 ? '<tr id="poshisttd' + position.id + '" onClick="return getPositionHistory(' + position.id + ');" style="cursor: pointer;">' : '<tr>';
          position_history_out += '<tr id="poshisttd' + position.id + '" onClick="return getPositionHistory(' + position.id + ');" style="cursor: pointer;">';
          position_history_out += '<td>' + positionitem + '</td>';
          position_history_out += '<td>' + position.newstartdate + '</td>';
          position_history_out += '<td>' + enddate + '</td>';
          position_history_out += '<td>' + getTenure(position.newstartdate, datefortenure) + '</td>';
          position_history_out += "<td class='text-right'>" + ccyFormat(position.rate) + '</td>';
          position_history_out += "<td style='max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;'>" + position.remarks + '</td>';
          position_history_out += '</tr>';
        });
        position_history_out = position_history_out == '' ? norecord : position_history_out;
        $('#position_history_edit')
          .find('tbody')
          .html(position_history_out);
      });
      $('#addPositionHistory')
        .off('click')
        .on('click', function() {
          addPositionHistory();
        });
      
      // let column_names = ['desc', 'salary', 'date'];
      // let position_history_out = '';

      // let j = 0;
      // for (let i = itemMax; i >= 0; i--) {
      //   let desc_n = abaperson[column_names[0] + i];
      //   if (desc_n == null) {
      //     continue;
      //   }
      //   j = j + 1;
      //   let salary_n = addCommas(parseInt(abaperson[column_names[1] + i]));
      //   let date_n = abaperson[column_names[2] + i];

      //   position_history_out += '<tr id="poshisttd' + i + '" onClick="return getPositionHistory(' + i + ');" style="cursor: pointer;">';
      //   position_history_out += '<td>' + desc_n + '</td>';
      //   position_history_out += '<td align="right">' + salary_n + '</td>';
      //   position_history_out += '<td align="center">' + date_n + '</td>';
      //   // position_history_out += '<td align="center">';
      //   // position_history_out += '<a href="#" title="Edit" onClick="return getPositionHistory(' + i + ');" class="px-1"><i class="fas fa-edit fa-sm text-gray-800" ></i></a>';
      //   // position_history_out += '</td>';
      //   position_history_out += '</tr>';
      // }
      // if (position_history_out == '') {
      //   position_history_out += '<tr>';
      //   position_history_out += '<td>No history</td>';
      //   position_history_out += '<td></td>';
      //   position_history_out += '<td></td>';
      //   position_history_out += '</tr>';
      // }
      // $('#position_history_edit')
      //   .find('tbody')
      //   .html(position_history_out);


      // if (j > 0) {
      //   for (let i = 0; i < j - 1; i++) {
      //     $('#poshisttd' + i).removeAttr('onClick', false);
      //     $('#poshisttd' + i).removeAttr('style', false);
      //   }
      // }
      // if (j < itemMax) {
      //   let addrowbtnhtml = '<input id="addRow" onClick="return addPositionHistory(' + j + ');" type="button" class="btn btn-sm btn-secondary" value="   ADD   "/>	';
      //   $('#addrowbtn').html(addrowbtnhtml);
      // }

      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function editBenefit(creditini, fiscalyr) {
  $('#creditini').val(creditini);
  $('#fiscalyr').val(fiscalyr);
  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      viewBenefit();
    }
  });
}

function toggleStatus(creditini, fiscalyr) {
  $('#creditini').val(creditini);
  $('#fiscalyr').val(fiscalyr);
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'getLeaveCreditProfile';
  var userid = $('#eeid').val();
  var creditini = creditini;
  var fiscalyr = fiscalyr;
  var data = { f: f, creditini: creditini, fiscalyr: fiscalyr, userid: userid };
  $.ajax({
    type: 'POST',
    url: url,
    data: JSON.stringify({ data: data }),
    dataType: 'json',
    success: function(data) {
      // console.log(data);
      // return false;
      let leaves = data.leavebal.rows[0];
      let newStatus = leaves.status == 1 ? 0 : 1;
      let val = [];
      val.push({
        entitled: leaves.entitleddays,
        status: newStatus,
        leavetypeid: leaves.leavetypeid,
        fiscalyr: leaves.fiscalyear
      });
      //   console.log(val);
      if (data.isExist == 1) {
        updateBenefit(val);
      } else {
        // saveBenefit();
      }
      //   $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function viewBenefit() {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'getLeaveCreditProfile';
  var userid = $('#eeid').val();
  var creditini = $('#creditini').val();
  var fiscalyr = $('#fiscalyr').val();
  var data = { f: f, creditini: creditini, fiscalyr: fiscalyr, userid: userid };
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
      var leavebal = data['leavebal']['rows'][0];
      $('#isexist').val(data.isExist);
      if (data.isExist == 1) {
        $('#dataleavetype').html(leavebal['leavetypedesc']);
        $('#txtEntitled').val(parseFloat(leavebal['entitleddays']).toFixed(1));
        // $("#txtTaken").val(leavebal['takendays']);
        $("#txtStatus option[value='" + leavebal['status'] + "']").prop('selected', true);
        // $("#leaveid").val(leavebal['id']);
      } else {
        $('#dataleavetype').html(leavebal.description);
        $('#txtEntitled').val(parseFloat(leavebal.reg_credit).toFixed(1));
        // $("#txtTaken").val(leavebal['takendays']);
        $("#txtStatus option[value='" + 0 + "']").prop('selected', true);
        $('#creditini').val(leavebal.benefitini);
        $('#fiscalyr').val(fiscalyr);
      }
      $('#editLeaveBenefit').modal('show');
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function updateBenefit(val) {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'updateLeaveBenefitProfile';
  var eeid = $('#eeid').val();
  var userid = $('#userid').val();
  // var leavetypeid = $("#leavetypeid").val();
  var entitled = $('#txtEntitled').val();
  // var taken = $("#txtTaken").val();

  var creditini = $('#creditini').val();
  var fiscalyr = $('#fiscalyr').val();

  var leavetypeid = creditini.toUpperCase() + fiscalyr.substring(fiscalyr.length - 2);
  var status = $('#txtStatus').val();

  if (val != undefined) {
    var entitled = val[0].entitled;
    var fiscalyr = val[0].fiscalyr;
    var leavetypeid = val[0].leavetypeid;
    var status = val[0].status;
  }
  console.log(val);
  var data = { f: f, userid: userid, eeid: eeid, entitled: entitled, status: status, leavetypeid: leavetypeid, fiscalyr: fiscalyr };
  console.log(data);
  // return false;

  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ data: data }),
        dataType: 'json',
        success: function(data) {
          console.log(data);
          // return false;
          loadCompensationBenefits();
          $('#editLeaveBenefit').modal('hide');
          $.unblockUI();
        },
        error: function(request, status, err) {}
      });
    }
  });
}

function saveBenefit(val) {
  var url = getAPIURL() + 'eesprofile.php';
  var f = 'saveLeaveBenefitProfile';
  var eeid = $('#eeid').val();
  var userid = $('#userid').val();
  // var leavetypeid = $("#leavetypeid").val();
  var entitled = $('#txtEntitled').val();
  // var taken = $("#txtTaken").val();

  var creditini = $('#creditini').val();
  var fiscalyr = $('#fiscalyr').val();

  var leavetypeid = creditini.toUpperCase() + fiscalyr.substring(fiscalyr.length - 2);
  var status = $('#txtStatus').val();

  var data = { f: f, userid: userid, eeid: eeid, entitled: entitled, status: status, leavetypeid: leavetypeid, fiscalyr: fiscalyr, creditini: creditini };
  // console.log(data);
  // return false;

  $.blockUI({
    message: $('#preloader_image'),
    fadeIn: 1000,
    onBlock: function() {
      $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ data: data }),
        dataType: 'json',
        success: function(data) {
          // console.log(data);
          // return false;
          loadCompensationBenefits();
          $('#editLeaveBenefit').modal('hide');
          $.unblockUI();
        },
        error: function(request, status, err) {}
      });
    }
  });
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
      if ($('#profilegroup').val() != 'profile') {
        if (data.certificates.rows.length > 0) {
          populateCertificates(data.certificates);
        } else {
          populateCertificates([]);
        }
        loadCertificateDates();
      } else {
        populateCertificatesView(data.certificates);
      }
      $.unblockUI();
    },
    error: function(request, status, err) {}
  });
}

function populateCertificatesView(data) {
  let certificateList = data.rows;
  if (certificateList.length == 0) {
    certificateList = [];
  }
  // console.log('Populating certificate table')
  // console.log(certificateList);
  let tableID = '#certificatedatatable_view';
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
              return '<span style="display:none;">' + data.expirationmonth + '_' + data.expirationyear + '</span>' + monthIntToName(data.expirationmonth) + ' ' + data.expirationyear;
            } else {
              return 'No expiration';
            }
          }
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
    const dataTable = $(tableID).DataTable();
    if ($(e.currentTarget.childNodes[0]).hasClass('dataTables_empty')) {
      return false;
    }
    const attachment = dataTable.row(this).data().attachments;
    const folderName = $('#eeid').val();
    const siteURL = window.location.origin + '/' + window.location.pathname.split('/')[1];
    const locationURL = siteURL + '/upload/certification_attachment_files/' + folderName + '/';
    // let popupURL = '';
    // popupURL = 'onclick="window.open(\''+ locationURL + attachment + '\',\'popup\',\'width=600,height=600\'); return false;"';

    let splitFileName = attachment.split('.');
    let fileExtension = splitFileName[splitFileName.length - 1];
    let openWith = '';
    switch (fileExtension) {
      case 'docx':
      case 'doc':
        openWith = 'https://view.officeapps.live.com/op/embed.aspx?src=';
        break;
      default:
        openWith = '';
        break;
    }
    window.open(openWith + locationURL + attachment, '_blank');
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
              return '<span style="display:none;">' + data.expirationmonth + '_' + data.expirationyear + '</span>' + monthIntToName(data.expirationmonth) + ' ' + data.expirationyear;
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
          },
          visible: false
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

      let openWith = '';
      openWith = 'https://view.officeapps.live.com/op/embed.aspx?src=';

      let splitFile = attachmentname.split('.');
      let fileType = splitFile[splitFile.length - 1];
      switch (fileType) {
        case 'docx':
        case 'doc':
          thumbnailoutput = '<a href="' + openWith + locationURL + attachmentname + '" target="_blank"><i class="fas fa-file-word fa-10x"></i></a>';
          break;
        case 'pdf':
          thumbnailoutput = '<a href="' + locationURL + attachmentname + '" target="_blank"><i class="fas fa-file-pdf fa-10x"></i></a>';
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
            '" width="60%" style="display: inline-block; object-fit: cover; object-position: center;"></img></a>';
          break;
        default:
          thumbnailoutput = '<a href="' + locationURL + attachmentname + '" target="_blank"><i class="fas fa-paperclip fa-10x"></i></a>';
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
    // $('#btnCertificateSave').removeAttr('disabled');
    $('#certificationModal').modal('show');
  });
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