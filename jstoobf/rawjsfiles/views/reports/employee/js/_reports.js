function initializeReportsDataTable(tableID){
    'use strict';
    const reportTitle = 'ACES Employee Report';
    const datatable = $(reportstable).DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
            contentType: "application/json",
            type: 'POST',
            url: `${getAPIURL()}reports.php`,
            data: data => JSON.stringify({data: $.extend(data, getFilters())})
        },
        language: {
            emptyTable: '<center>Nothing to show</center>',
            processing: `<div><img src="${getBaseURL()}/img/abaload.svg" height="60vw" width="100%">Loading...</div>`
        },
        dom: 'Bfrtip',
        buttons: [
            // change this to custom buttons to handle server side processing
            {extend: 'print', title: reportTitle, titleAttr: 'Print', text: '<i class="fas fa-print"></i> Print', exportOptions: {columns: ':visible'}, 
                action: function (e, dt, button, config) {
                    removePagination('print', {thisaction: this, e: e, dt: dt, button: button, config: config});
                }
            },
            {extend: 'pdfHtml5', title: reportTitle, titleAttr: 'Export as PDF', text: '<i class="fas fa-file-pdf"></i> PDF', exportOptions: {columns: ':visible'}, 
                action: function (e, dt, button, config) {
                    removePagination('pdf', {thisaction: this, e: e, dt: dt, button: button, config: config});
                }
            },
            {extend: 'excelHtml5', title: reportTitle, titleAttr: 'Export as Excel', text: '<i class="fas fa-file-excel"></i> Excel', exportOptions: {columns: ':visible'}, 
                action: function (e, dt, button, config) {
                    removePagination('excel', {thisaction: this, e: e, dt: dt, button: button, config: config});
                },
                exportOptions: {
                    format: {
                        body: function(data, row, column, node){
                            // format date for excel compatibility
                            if(moment(data, 'ddd DD MMM YYYY').isValid()){
                                const result = data != '' ? moment(new Date(data)).format('YYYY-MM-DD') : '';
                                return result.toLowerCase() != 'invalid date' ? result : data;
                            }
                            return data;
                        }
                    }
                },
                autoFilter: true
            },
            // {extend: 'colvis', title: '', titleAttr: 'Change column visibility', text: '<i class="fas fa-eye"></i> Columns'}
        ],
        info: true,
        paging: true,
        searching: false,
        responsive: true,
        colReorder: { realtime: false },
        lengthMenu: [[25, -1], [25, "All"]],
        columns: [
            {data: 'eename', name: 'eename', title: 'Full name'},
            {data: 'fname', name: 'fname', title: 'First name'},
            {data: 'mname', name: 'mname', title: 'Middle name', visible: false},
            {data: 'lname', name: 'lname', title: 'Last name'},
            {data: 'cnname', name: 'cnname', title: 'Chinese name', visible: false},
            {data: 'abaini', name: 'abaini', title: 'ini', visible: false},
            {data: 'newdepartment', name: 'newdepartment', title: 'Department'},
            {data: 'newposition', name: 'newposition', title: 'Position'},
            {data: 'newoffice', name: 'newoffice', title: 'Office'},
            {data: 'newranking', name: 'newranking', title: 'Ranking'},
            {data: 'newgender', name: 'newgender', title: 'Gender'},
            {data: 'age', name: 'age', title: 'Age', visible: false},
            {data: 'newbirthdate', name: 'newbirthdate', title: 'Birthdate', render: function(data){ return checkDateIfDefault(data); }},
            {data: 'newjoineddate', name: 'newjoineddate', title: 'Joined date', render: function(data){ return checkDateIfDefault(data); }},
            {data: 'newemployeetype', name: 'newemployeetype', title: 'Employee type'},
            {data: 'newprobationenddate', name: 'newprobationenddate', title: 'Probation end date', render: function(data){ return checkDateIfDefault(data); }},
            {data: 'newlastworkingdate', name: 'newlastworkingdate', title: 'End date', render: function(data){ return checkDateIfDefault(data); }},
            {data: 'directname', name: 'directname', title: 'Direct head'},
            {data: 'indirectname', name: 'indirectname', title: 'Indirect head'},
            {data: 'newstatus', name: 'newstatus', title: 'Status'},
            {data: 'presentstreet', name: 'presentstreet', title: 'Present street', visible: false},
            {data: 'presentcity', name: 'presentcity', title: 'Present city', visible: false},
            {data: 'presentstate', name: 'presentstate', title: 'Present state', visible: false},
            {data: 'presentzipcode', name: 'presentzipcode', title: 'Present zip code', visible: false},
            {data: 'presentcountry', name: 'presentcountry', title: 'Present country', visible: false},
            {data: 'personalemail', name: 'personalemail', title: 'Personal Email', visible: false},
            {data: 'mobileno', name: 'mobileno', title: 'Mobile no.', visible: false},
            {data: 'homephoneno', name: 'homephoneno', title: 'Home phone no.', visible: false},
            {data: 'wechat', name: 'wechat', title: 'WeChat', visible: false},
            {data: 'skype', name: 'skype', title: 'Work skype', visible: false},
            {data: 'whatsapp', name: 'whatsapp', title: 'Whatsapp', visible: false},
            {data: 'linkedin', name: 'linkedin', title: 'Linkedin', visible: false},
            {data: 'emercontactperson', name: 'emercontactperson', title: 'Emergency contact name', visible: false},
            {data: 'emercontactno', name: 'emercontactno', title: 'Emergency contact no.', visible: false},
            {data: 'emercontactrelation', name: 'emercontactrelation', title: 'Emergency contact relation', visible: false},
            {data: 'workemail', name: 'workemail', title: 'Work email', visible: false},
            {data: 'officephoneno', name: 'officephoneno', title: 'Office phone no.', visible: false},
            {data: 'workskype', name: 'workskype', title: 'Work skype', visible: false},
            {data: 'nationality', name: 'nationality', title: 'Nationality', visible: false},
            {data: 'maritalstatus', name: 'maritalstatus', title: 'Marital status', visible: false},
        ],
        rowId: 'userid',
        fnDrawCallback: function(data) {
            $('#apply-filter').removeAttr('disabled');
            // console.log(data.json);
            $.unblockUI();
        }
    });

    // render date
    function checkDateIfDefault(date_v) {
        const thisdate = new Date(date_v);
        if((thisdate.getFullYear() == 1900) && thisdate.getMonth()+1 == 1 && thisdate.getDate() == 1) return '';
        return date_v;
    }

    // move export buttons to diff container
    datatable.buttons().container().appendTo('#exportBtns');

    // // use custom search buttons
    // $((`${tableID}_search`)
    //     .off('keyup')
    //     .on('keyup', function(){
    //         $(tableID).DataTable().search($(this).val()).draw();
    //     });


    initTailDropDown();
    loadColumnToggles(datatable);

    datatable
        .off('column-reorder')
        .on( 'column-reorder', function ( e, settings, details ) {
            loadColumnToggles(datatable);
        });
    $(`${tableID} thead`).css({'font-size':'0.8vw'});
    $(`${tableID} tbody`).css({'font-size':'0.7vw'});


    
    // $(reportstable).dataTable().fnClearTable();
}

function generateFilters(){
    return  {
        f:          'getEmployeeReports',
        filters: {
            office:     $('#office-filter option:selected').val(),
            officename: $('#office-filter option:selected').text(),
            ofc_access: accessitem_ofc,
            department: $('#department-filter option:selected').val(),
            position:   $('#position-filter option:selected').val(),
            ranking:    $('#rank-filter option:selected').val(),
            gender:     $('#gender-filter option:selected').val(),
            agerange: {
                enabled:    $('#agefrom-filter option:selected').val() != '' && $('#agefrom-filter option:selected').val() != -1 && 
                                $('#ageto-filter option:selected').val() != '' && $('#ageto-filter option:selected').val() != -1,
                from:    $('#agefrom-filter option:selected').val(),
                to:      $('#ageto-filter option:selected').val(),
            },
            eetype:     $('#employeetype-filter option:selected').val(),
            eestatus:   $('#employeestatus-filter option:selected').val(),
            joineddate: {
                enabled:    $('#joineddate').attr('checked') == 'checked',
                from:       moment(new Date($('#joineddate-filter').val().split(' - ')[0])).format('ddd DD MMM YYYY'),
                to:         moment(new Date($('#joineddate-filter').val().split(' - ')[1])).format('ddd DD MMM YYYY')
            },
            enddate: {
                enabled:    $('#enddate').attr('checked') == 'checked',
                from:       moment(new Date($('#enddate-filter').val().split(' - ')[0])).format('ddd DD MMM YYYY'),
                to:         moment(new Date($('#enddate-filter').val().split(' - ')[1])).format('ddd DD MMM YYYY')
            },
            probationenddate: {
                enabled:    $('#probationdate').attr('checked') == 'checked',
                from:       moment(new Date($('#probationdate-filter').val().split(' - ')[0])).format('ddd DD MMM YYYY'),
                to:         moment(new Date($('#probationdate-filter').val().split(' - ')[1])).format('ddd DD MMM YYYY')
            },
            direct:     $('#reportsdirect-filter option:selected').val(),
            indirect:   $('#reportsindirect-filter option:selected').val(),
        }
    };
}

function getFilters(){
    return JSON.parse($('#filter-data').val());
}

function getTableHeaders(datatable){
    let array_container = [];
    datatable.columns().every(function () {
        array_container.push({index: this.index(), title: this.header().innerHTML, visible: this.visible()});
    });
    return array_container;
}

function loadColumnToggles(datatable){
    const headers = getTableHeaders(datatable);

    // get all column name
    $('#columnVisible')
        .html(headers.map((header) => {
            const selected = header.visible ? 'selected' : '';
            return `<option value="${header.index}" ${selected}>${header.title}</option>`;
        }).join(''))
        .show();

    const visibleColums = tail.select('#columnVisible');
    visibleColums.reload();
    
    
    
    loadFunction(datatable);
    visibleColums.on('close', function(){
        loadFunction(datatable);
    });

    
}
function loadFunction(datatable) {
    let visibleItems = [];
    $('#columnVisible > option').each(function(){
        visibleItems.push({
            visible: this.selected,
            column: $(this).val(),
            name: $(this).text()
        });
    });
    // console.log(visibleItems);
    visibleItems.forEach(item => {
        datatable.column(item.column).visible(item.visible);
    });
}
function initTailDropDown(){
    tail.select('#columnVisible', {
        multiSelectAll: true,
        search: true,
        animate: true,
        placeholder: 'Select columns to be visible...',
        classNames: ['float-right']
    });
}

function loadFilters(callback) {
    const allFilter = '<option value="">All</option>';
    // Office
    qryData('reports', 'loadEmployeeFilters', {accessitem_ofc: accessitem_ofc}, (data)=> {
        $('#office-filter')
            .html(`${allFilter} ${data.offices.map(office => `<option value="${office.salesofficeid}">${office.ofcini}</option>}`)}`);
    
        $('#department-filter')
            .html(`${allFilter} ${data.departments.map(department => `<option value="${department.departmentid}">${department.description}</option>`)}`);

        $('#position-filter')
            .html(allFilter);

        $('#rank-filter')
            .html(`${allFilter} ${data.rankings.map(ranking => `<option value="${ranking.id}">${ranking.description}</option>`)}`);

        $('#gender-filter')
            .html(`${allFilter} ${data.genders.map(gender => `<option value="${gender.id}">${gender.description}</option>`)}`);

        $('#employeetype-filter')
            .html(`${allFilter} ${data.eetypes.map(type => `<option value="${type.id}">${type.description}</option>`)}`);

        $('#employeestatus-filter')
            .html(`${allFilter} ${data.eestatuses.map(status => `<option value="${status.id}">${status.description}</option>`)}`);

        $('#reportsdirect-filter')
            .html(`${allFilter} ${data.eedirect.map(direct => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);

        $('#reportsindirect-filter')
            .html(`${allFilter} ${data.eedirect.map(direct => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);

        if(callback != undefined) callback();
    });

    // Load event listener for department
    $('#department-filter')
        .off('change')
        .on('change', function(e) {
            
            if($(`#${e.target.id}`).val() == ''){
                // if all is selected, only show 'all'
                $('#position-filter').html(allFilter);
            } else {
                // disabled dropdown temporarily
                $('#position-filter')
                    .attr('disabled',true)
                    .html('<option>Loading...</option>');

                // get position under selected department
                qryData('reports', 'getPosition', {department: $(`#${e.target.id}`).val()}, data => {
                    const positions = data.positions;
                    if(positions != null) {
                        $('#position-filter')
                            .html(`${allFilter} ${positions.map(position => `<option value="${position.designationid}">${position.description}</option>`)}`);
                    }
    
                    // enable position filter
                    $('#position-filter').removeAttr('disabled');
                });
            }

        });

    //#region: from and to age

    //generate age list from 20 to 60 with 5 as interval
    const agefrom_placeholder = '<option value="" disabled selected>-from-</option>';
    const ageto_placeholder = '<option value="" disabled selected>-to-</option>';

    let agelist = [];
    agelist.push({value:-1, display:'All'});
    agelist.push({value:19, display:'below 20'});
    for(let age = 20; age <= 60; age+=5) agelist.push({value: age, display: age});
    agelist.push({value:61, display:'60 up'});

    $('#agefrom-filter')
        .html(`${agefrom_placeholder} ${agelist.map(age => `<option value="'${age.value}">${age.display}</option>`)}`);

    $('#ageto-filter')
        .html(`${ageto_placeholder} ${agelist.map(age => `<option value="${age.value}">${age.display}</option>`)}`);

    $('#agefrom-filter')
        .off('change')
        .on('change', function(e) {
            handleAgePicker('from');
        });

        
    $('#ageto-filter')
        .off('change')
        .on('change', function(e) {
            handleAgePicker('to');
        });
    //#endregion

    $('#reportsdirect-filter')
        .off('change')
        .on('change', function(e) {
            
            // disabled dropdown temporarily
            $('#reportsindirect-filter')
                .attr('disabled',true)
                .html('<option>Loading...</option>');

            // get position under selected department
            qryData('reports', 'getIndirect', {
                    direct: $(`#${e.target.id}`).val(), 
                    office: $('#office-filter option:selected').val()
                }, 
                data => {
                    const indirect = data.eeindirect;
                    if(indirect != null) {
                        $('#reportsindirect-filter')
                            .html(`${allFilter} ${indirect.map(eachindirect => `<option value="${eachindirect.userid}">${eachindirect.fullname}</option>`)}`);
                    }

                    // enable position filter
                    $('#reportsindirect-filter').removeAttr('disabled');
            });
        });
    
    $('#office-filter')
        .off('change')
        .on('change', function(e) {
            // disabled dropdown temporarily
            $('#reportsdirect-filter')
                .attr('disabled',true)
                .html('<option>Loading...</option>');
            $('#reportsindirect-filter')
                .attr('disabled',true)
                .html('<option>Loading...</option>');

            qryData('reports', 'getDirect', {office: $('#office-filter option:selected').val(), ofc_access: accessitem_ofc}, data => {
                $('#reportsdirect-filter')
                    .html(`${allFilter} ${data.eedirect.map(direct => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);
                
                $('#reportsindirect-filter')
                    .html(`${allFilter} ${data.eedirect.map(direct => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);


                // enable dropdown
                $('#reportsdirect-filter').removeAttr('disabled');
                $('#reportsindirect-filter').removeAttr('disabled');
            });
            
            // $('#reportsdirect-filter').trigger('change');
        });

    $('#apply-filter')
        .off('click')
        .on('click', function(e){
            $('#filter-data').val(JSON.stringify(generateFilters()));
            $('#apply-filter').attr('disabled', true);
            $('#reportstable').DataTable().page(0).ajax.reload(null, false);
        });

    $('#clear-filter')
        .off('click')
        .on('click', function(e){
            $(this).attr('disabled', true);
            loadFilters();
            setTimeout(() => {
                $(this).attr('disabled', false);
            }, 1000);
        });
}


function handleAgePicker(type = '') {
    const agefrom = $('#agefrom-filter').val();
    const ageto = $('#ageto-filter').val();

    switch (type) {
        case 'from':
            if(agefrom > ageto) $('#ageto-filter').val($('#agefrom-filter').val());
            if(agefrom == -1) $('#ageto-filter').val($('#agefrom-filter').val());
            break;
        case 'to':
            if(ageto < agefrom) $('#agefrom-filter').val($('#ageto-filter').val());
            if(ageto == -1) $('#agefrom-filter').val($('#ageto-filter').val());
            if(agefrom == -1 && ageto != -1) $('#agefrom-filter').val($('#ageto-filter').val());
            break;
        default:
            break;
    }
}

function removePagination(exportType = '', exportData){
    const table = $('#reportstable').DataTable();
    const tableSettings = table.settings();
    tableSettings[0]._iDisplayLength = tableSettings[0].fnRecordsTotal();
    table
        .off('draw')
        .on( 'draw', function () {
            blockUI(()=>{
                triggerExport(exportType, exportData);
            });
        });
    table.draw();
    
    function triggerExport(exportType = '', exportData) {
        const table = $('#reportstable').DataTable();
        switch (exportType) {
            case 'print':
                $.fn.dataTable.ext.buttons.print.action.call(exportData.thisaction, exportData.e, exportData.dt, exportData.button, exportData.config);
                break;
            case 'pdf':
                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(exportData.thisaction, exportData.e, exportData.dt, exportData.button, exportData.config);
                break;
            case 'excel':
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(exportData.thisaction, exportData.e, exportData.dt, exportData.button, exportData.config);
                break;
            default:
                break;
        }
        setTimeout(() => {
            table
                .off('draw')
                .on('draw', function() {
                    $.unblockUI();
                });
            table.settings()[0]._iDisplayLength = 25;
            table.draw();
        }, 1000);
    }
}
