$(function() {
    loadEmployeeList();
});

function loadEmployeeList(){
    url = getAPIURL() + 'employees.php';
    f = 'loadEmployeeData';
    var statFilter;
    if (statFilter == undefined) {
      statFilter = 2;
    }
    var ofc = $('#ofc').val();
    // var userid = $('#userid').val();
    var showAll = $('#accesslvl').val() == 1 ? 1 : 0;
    data = { f: f, statFilter: statFilter, ofc: ofc, showAll: showAll };
  
    console.log(data);
    // return false;
    $.ajax({
        type: 'POST',
        url: url,
        data: JSON.stringify({ data: data }),
        dataType: 'json',
        success: function(data) {
        console.log(data);
        // return false;
        abaeelist = data['eedata']['rows'];

        const datatableid = '#employeelistviewtbl';
        // const isAdmin = $('#hasaccess').val() == 1;
        if ($.fn.DataTable.isDataTable(datatableid)) {
            $(datatableid).dataTable().fnClearTable();
            if (abaeelist.length > 0) {
                $(datatableid).dataTable().fnAddData(abaeelist);
            }
        } else {
            abaeelisttbl = $('#employeelistviewtbl').DataTable({
                "dom": '<"pull-right" f><t>',
                "searching": true,
                data: abaeelist,
                language: {
                    emptyTable: '<center>No attendance record</center>'
                },
                paging: true,
                responsive: true,
                ordering: true,
                order: [[0, 'asc']],
                lengthMenu: [[25, 50, 100, -1], [25, 50, 100, "All"]],
                columns: [
                  { data: 'eename' }
                ],
                fnDrawCallback: function() {
                    // resize filter --------------------------
                    // $('#employeelistviewtbl_length')
                    //   .parent()
                    //   .removeAttr('class')
                    //   .attr('class', 'col-sm-12 col-md-4');
        
                    $('#employeelistviewtbl_filter')
                      .parent()
                      .removeAttr('class')
                      .attr('class', 'col-sm-12 col-md-12');
                    // ----------------------------------------
                    $('#employeelistviewtbl_filter label input').attr('id', 'employeelistviewtbl_search');
                    let dataTable = $('#employeelistviewtbl').dataTable();
                    $('#employeelistviewtbl_search').hide();
                    $('#employeelistviewtbl_search_label').hide();
                    if ($('#statusFilter').length == 0 && $('#stationFilter').length == 0) {
                      // Status

                      let statusSelect = '';
                      statusSelect += '<span id="statusFilterDiv">';
                      statusSelect += 'Status: <label style="padding-right: 1vh; width: 22vh; white-space: nowrap;">';
                      statusSelect += '<select class="form-control form-control-sm" id="statusFilter" name="statusFilter" aria-controls="employeelistviewtbl">';
                      statusSelect += '<option value="2">active or inactive</option>';
                      statusSelect += '<option value="1" selected>active</option>';
                      statusSelect += '<option value="0">inactive</option>';
                      statusSelect += '</select>';
                      statusSelect += '</label>';
                      statusSelect += '</span>';
                      $('#employeelistviewtbl_filter').prepend(statusSelect);
                      
                      // Station
                      let hideStation = 'style="display:none;"';
                      console.log($('#accesslvl').val());
                      if ($('#accesslvl').val() == 1) {
                        hideStation = '';
                      }
                      let stationSelect = '';
                      stationSelect += '<span id="stationFilterDiv" ' + hideStation + '>';
                      stationSelect += 'Station: <label style="padding-right: 1vh; width: 13vh; white-space: nowrap;">';
                      stationSelect += '<select class="form-control form-control-sm" id="stationFilter" name="stationFilter" aria-controls="employeelistviewtbl">';
                      stationSelect += '<option value="all">all</option>';
        
                      // get list of offices
                      let ofc = $('#ofc').val();
                      let distinctStation = [...new Set(abaeelist.map(abaee => abaee.officename))];
                      distinctStation.forEach(stationName => {
                        if (stationName != null) {
                            if(ofc == stationName){
                                stationSelect += '<option value="' + stationName + '" selected>' + stationName + '</option>';
                            }else{
                                stationSelect += '<option value="' + stationName + '">' + stationName + '</option>';
                            }
                          
                        }
                      });
                      stationSelect += '</select>';
                      stationSelect += '</label>';
                      stationSelect += '</span>';
                      $('#employeelistviewtbl_filter').prepend(stationSelect);
        
                      $('#employeelistviewtbl_filter label input').attr('style', 'width:25vh;');

                    //   console.log(stationSelect);
        
                      $('#statusFilter, #stationFilter').on('change', function() {
                        let filteredStat = $('#statusFilter option:selected').text();
                        let filteredStation = $('#stationFilter option:selected').text();
                        let finalFilter = '';
        
                        let statIsFiltered = filteredStat != 'active or inactive';
                        let stationIsFiltered = filteredStation != 'all';
                        if (statIsFiltered && stationIsFiltered) {
                          finalFilter = abaeelist.filter(abaee => abaee.statusname == filteredStat && abaee.officename == filteredStation);
                        } else if (statIsFiltered) {
                          finalFilter = abaeelist.filter(abaee => abaee.statusname == filteredStat);
                        } else if (stationIsFiltered) {
                          finalFilter = abaeelist.filter(abaee => abaee.officename == filteredStation);
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
              });
        }
  
        $('#employeelistviewtbl tbody').on('click', 'tr', function() {
          thisdata = abaeelisttbl.row(this).data();
          id = thisdata['sesid'];
        //   status = btoa(thisdata['statusname']);
        //   let filteredStatus = $('#statusFilter option:selected').val();
        //   let filteredStation = $('#stationFilter option:selected').val();
        //   let searchq = $('#employeelistviewtbl_search').val();
        //   // if (status == 'inactive') {
        //   // alertDialog(id);
        //   window.location =
        //     'profile.php?id=' + id + '&action=' + btoa('viewprofile') + '&s=' + status + '&fstatus=' + filteredStatus + '&fstation=' + filteredStation + '&searchq=' + searchq;
  
          // alertDialog('Employee is inactive. Please select other employee record to view.');
          // return false;
          // } else {
          //   window.location = 'profile-edit.php?id=' + id + '&action=' + btoa('editprofile');
          // }
          return false;
        });
        // $.unblockUI();
      },
      error: function(request, status, err) {}
    });
}