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
            {data: 'newdepartment', name: 'newdepartment', title: 'Department'},
            {data: 'newposition', name: 'newposition', title: 'Position', visible: false},
            {data: 'newoffice', name: 'newoffice', title: 'Office'},
            {data: 'leavetypedescription', name: 'leavetypedescription', title: 'Leave Type'},
            {data: 'newleavefromdate', name: 'newleavefromdate', title: 'Leave From'},
            {data: 'newleavetodate', name: 'newleavetodate', title: 'Leave To'},
            {data: 'noofdays', name: 'noofdays', title: 'Length'},
            {data: 'fname', name: 'fname', title: 'First name', visible: false},
            {data: 'mname', name: 'mname', title: 'Middle name', visible: false},
            {data: 'lname', name: 'lname', title: 'Last name', visible: false},
            {data: 'abaini', name: 'abaini', title: 'ini', visible: false},
            {data: 'leavestatus', name: 'leavestatus', title: 'Leave Status'},
            {data: 'reason', name: 'reason', title: 'Leave reason'},
            {data: 'newstatus', name: 'newstatus', title: 'Employee Status', visible: false},
        ],
        rowId: 'userid',
        fnDrawCallback: function(data) {
            $('#apply-filter').removeAttr('disabled');
            // console.log(data);
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
        f:          'getLeaveReports',
        filters: {
            office:         $('#office-filter option:selected').val(),
            officename:     $('#office-filter option:selected').text(),
            ofc_access:     accessitem_ofc,
            department:     $('#department-filter option:selected').val(),
            position:       $('#position-filter option:selected').val(),
            eestatus:       $('#employeestatus-filter option:selected').val(),
            leavefrom: {
                enabled:    $('#leavefrom').attr('checked') == 'checked',
                from:       moment(new Date($('#leavefrom-filter').val().split(' - ')[0])).format('ddd DD MMM YYYY'),
                to:         moment(new Date($('#leavefrom-filter').val().split(' - ')[1])).format('ddd DD MMM YYYY')
            },
            leaveto: {
                enabled:    $('#leaveto').attr('checked') == 'checked',
                from:       moment(new Date($('#leaveto-filter').val().split(' - ')[0])).format('ddd DD MMM YYYY'),
                to:         moment(new Date($('#leaveto-filter').val().split(' - ')[1])).format('ddd DD MMM YYYY')
            },
            leavetype:      $('#leavetype-filter option:selected').val(),
            leavestatus:    $('#leavestatus-filter option:selected').val(),
            direct:         $('#reportsdirect-filter option:selected').val(),
            indirect:       $('#reportsindirect-filter option:selected').val(),
            employee:       $('#employee-filter option:selected').val(),
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
    qryData('reports', 'loadLeaveFilters', {accessitem_ofc: accessitem_ofc}, (data)=> {
        $('#office-filter')
            .html(`${allFilter} ${data.offices.map(office => `<option value="${office.salesofficeid}">${office.ofcini}</option>}`)}`);

        $('#department-filter')
            .html(`${allFilter} ${data.departments.map(department   => `<option value="${department.departmentid}">${department.description}</option>`)}`);

        $('#position-filter')
            .html(allFilter);

        $('#employeestatus-filter')
            .html(`${allFilter} ${data.eestatuses.map(status        => `<option value="${status.id}" ${status.id == 1 ? 'selected' : ''}>${status.description}</option>`)}`);

        $('#leavetype-filter')
            .html(`${allFilter} ${data.leavetype.map(status         => `<option value="${status.id}">${status.description}</option>`)}`);
        
        $('#leavestatus-filter')
            .html(`${allFilter} ${data.leavestatus.map(status       => `<option value="${status.id}">${status.description}</option>`)}`);

        $('#reportsdirect-filter')
            .html(`${allFilter} ${data.eedirect.map(direct          => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);

        $('#reportsindirect-filter')
            .html(`${allFilter} ${data.eedirect.map(direct          => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);

        $('#employee-filter')
            .html(`${allFilter} ${data.employees.map(employee       => `<option value="${employee.userid}">${employee.fullname}</option>`)}`);

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

                    loadEmployeeFilter();
                });
            }

        });
    $('#office-filter')
        .off('change')
        .on('change', function(e) {
            // disabled dropdown temporarily
            const office = {office: $('#office-filter option:selected').val(), ofc_access: accessitem_ofc};
            $('#reportsdirect-filter')
                .attr('disabled',true)
                .html('<option>Loading...</option>');
            $('#reportsindirect-filter')
                .attr('disabled',true)
                .html('<option>Loading...</option>');
            
            qryData('reports', 'getDirect', office, data => {
                $('#reportsdirect-filter')
                    .html(`${allFilter} ${data.eedirect.map(direct => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);
                
                $('#reportsindirect-filter')
                    .html(`${allFilter} ${data.eedirect.map(direct => `<option value="${direct.userid}">${direct.fullname}</option>`)}`);


                // enable dropdown
                $('#reportsdirect-filter').removeAttr('disabled');
                $('#reportsindirect-filter').removeAttr('disabled');
                
                loadEmployeeFilter();
            });
            
            // $('#reportsdirect-filter').trigger('change');
        });

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
                
                loadEmployeeFilter();
            });
        });

    $('#position-filter, #employeestatus-filter, #reportsindirect-filter')
        .off('change')
        .on('change', function(e) {
            loadEmployeeFilter();
        });
    // $('#employee-filter')
    //     .off('change')
    //     .on('change', function(e) {
    //         // qryData('reports', 'getEmployee',)
    //     });


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

function loadEmployeeFilter() {
    const allFilter = '<option value="">All</option>';
    $('#employee-filter')
        .attr('disabled',true)
        .html('<option>Loading...</option>');
    qryData('reports', 'getEmployee', generateFilters().filters, data => {
        $('#employee-filter')
            .html(`${allFilter} ${data.employees.map(employee => `<option value="${employee.userid}">${employee.fullname}</option>`)}`);

        $('#employee-filter').removeAttr('disabled');
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
