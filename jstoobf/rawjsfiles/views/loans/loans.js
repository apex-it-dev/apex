$(function() {
   'use strict';
    // add loan button
    $('#addNewLoan').on('click', function (e) {
        ccyHandler('#loanAmount');
        ccyHandler('#amountDue');
        loadEeList();
        loadLoanTypeList();
        loadLoanPaymentFreq();
        loadDate();
        $('#addloan').find('#loanAmount').val('');
        ccyHandler('#loanAmount');
        loadSaveAction_add();
        $('#addloan').modal('show');
    });

    
    $('#loanofee').on('hidden.bs.modal', function() {
        loadLoanDataTable();
    });

    blockUI(()=> {
        const loan_ee_list = '#loan_ee_list';
        initializeLoanDataTable(loan_ee_list);
        loadLoanDataTable();

        
        const loan_of_ee = '#loan_of_ee';
        initializeEELoanDataTable(loan_of_ee);

        loadOfcList();
        $.unblockUI(); //tmp
    });
});

//#region main page ------------------------------------------------------------------
function loadOfcList(){
    qryData('loan', 'getOfc', {}, data => {
        const ofclist = data.ofclist;
        let ofchtml = '<option value="" selected>All offices</option>';
        ofclist.forEach(ofc => {
            ofchtml += '<option value="'+ ofc.salesofficeid +'">' + ofc.description + '</option>';
        });
        $('#officeList')
            .html(ofchtml)
            .off('change')
            .on('change',function(){
                const ofc = $(this).val();
                blockUI(()=> {
                    loadLoanDataTable({ofc: ofc});
                    $.unblockUI();
                });
            });
    });
}

function loadEeList() {
    const modal = '#addloan';
    $(modal).find('#eename').html('<option>Loading...</option>');
    qryData('loan', 'getEeList', {ofcid: ''}, data => {
        const eelist = data.eelist;
        let eenamehtml = '<option selected></option>';
        eenamehtml += eelist.map(ee => '<option value="'+ ee.userid +'">'+ ee.eename +'</option>');
        $(modal).find('#eename').html(eenamehtml);
        eenamehtml = null; // clean
    });
}

function loadLoanTypeList() {
    const modal = '#addloan';
    $(modal).find('#loantype').html('<option>Loading...</option>');
    qryData('loan', 'getLoanTypeList', {}, data => {
        const loantypelist = data.loantypelist.rows;
        let loantypehtml = '<option selected></option>';
        loantypehtml += loantypelist.map(type => '<option value="'+ type.ddid +'">'+ type.dddescription +'</option>');
        $(modal).find('#loantype').html(loantypehtml);
        loantypehtml = null; // clean
    });
}

function loadLoanPaymentFreq() {
    const modal = '#addloan';
    $(modal).find('#paymentfreqadd').html('<option>Loading...</option>');
    qryData('loan', 'getPaymentFreq', {}, data => {
        const paymentfreq = data.loanpaymentfreq.rows;
        let paymentfreqhtml = '<option selected></option>';
        paymentfreqhtml += paymentfreq.map(freq => '<option value="'+ freq.ini +'">'+ freq.dddescription +'</option>');
        $(modal).find('#paymentfreqadd').html(paymentfreqhtml);
        paymentfreqhtml = null; // clean
    });
}

function loadDate(){
    const modal = '#addloan';
    serverDateNow((now) => {
        $(modal).find("#startDate").datepicker({
            dateFormat: "D dd M yy",
            changeMonth: true,
            changeYear: true,
            yearRange: "1900:" + now.getFullYear()
        })
        .on('click', function() {
            $(this).prop('readonly', true);
        });
    });
}

function loadSaveAction_add(){
    const modal = '#addloan';
    $(modal).find('#saveBtn')
        .off('click')
        .on('click', function(e) {
            let loanAmountTmp = parseFloat(($(modal).find('#loanAmount').val()).replace(',',''));
            let amountDueTmp = parseFloat(($(modal).find('#amountDue').val()).replace(',',''));
            if($(modal).find('#eename option:selected').val() === ''){alertDialog('Please select a name');return;}
            if($(modal).find('#loantype option:selected').val() === ''){alertDialog('Please select a loan type');return;}
            if($(modal).find('#paymentfreqadd option:selected').val() === ''){alertDialog('Please select a payment frequency');return;}
            if(loanAmountTmp == 0){alertDialog('Please enter the loan amount');return;}
            if(amountDueTmp == 0){alertDialog('Please enter the amount per frequency');return;}
            if(amountDueTmp > loanAmountTmp){alertDialog('Amount due is higher that the loan');return;}
            if($(modal).find('#startDate').val() === ''){alertDialog('Please select a date');return;}

            // proceed saving
            addNewLoan();
        });
}


function addNewLoan() {
    const modal = '#addloan';
    const data = {
        userid: $(modal).find('#eename option:selected').val(),
        loantype: $(modal).find('#loantype option:selected').val(),
        paymentfreq: $(modal).find('#paymentfreqadd option:selected').val(),
        loanamount: $(modal).find('#loanAmount').val(),
        amountdue: $(modal).find('#amountDue').val(),
        startdate: $(modal).find('#startDate').val(),
        createdby: $('#userid').val()
    }
    blockUI(()=> {
        qryData('loan', 'addNewLoan', data, (data) => {
            if(data.loanexist){
                alertDialog('The employee already have a loan with the same loan type.');
            } else {
                loadLoanDataTable();
                $(modal).modal('hide');
            }
            $.unblockUI(); 
        }, true);
    });
}

function initializeLoanDataTable(tableID) {
    $(loan_ee_list).DataTable({
        data: [],
        language: {
            emptyTable: '<center>No employee with loan</center>'
        },
        info: false,
        // paging: false,
        responsive: true,
        lengthMenu: [[25], [25]],
        order: [[8,'desc']],
        columns: [
            {data: 'userid', title: '', visible: false},
            {data: 'loanidmst', title: '', visible: false},
            {data: 'eename', title: 'Employee name', width:"20%"},
            {data: 'loantypename', title: 'Loan type', width:"10%"},
            {data: 'loanamount', title: 'Loan amount',
                render: function(data){ return ccyFormat(data, 2);},
                width:"10%",
                className: "text-right",
            }, 
            {data: 'totalpaid', title: 'Paid',
                render: function(data){ return ccyFormat(data, 2);},
                width:"10%",
                className: "text-right",
            }, 
            {data: 'remaining', title: 'Remaining',
                render: function(data){ return ccyFormat(data, 2);},
                width:"10%",
                className: "text-right",
            }, 
            {data: 'paymentfrequencydesc', title: 'Payment frequency', width:"10%"},
            {data: 'newstartdate', title: 'Start date', width:"15%"},
        ],
        columnDefs: [
            {
                targets: [6, 7], render: function (data) {
                    return '<span style="display:none;">' + toTimestamp(data) + '</span>' + data;
                }
            },
        ],
        rowId: 'id',
    });

    $(tableID + '_wrapper div.row div:first-child')[0].remove(); // remove first div
    $(tableID + '_filter').html('');    // clear filter and replace with custom search
    $('#loan_ee_list_search').on('keyup', function(){
        $(tableID).DataTable().search($(this).val()).draw();
    });

        
    $(tableID + ' thead').css({'font-size':'0.9vw'});
    $(tableID + ' tbody').css({'font-size':'0.8vw'});

    $(loan_ee_list).dataTable().fnClearTable();
}

function loadLoanDataTable(apiParam = {}) {
    if($.isEmptyObject(apiParam)){
        apiParam = {
            ofc: '',
        }
    }
    qryData('loan', 'getEeWithLoan', apiParam, data => {
        // console.log(data);
        // return;
        updateLoanEeList(data.eewithloans.rows);
    },true);
}
//#endregion ----------------------------------------------------


//#region edit loan ----------------------------------------------------------------
/**
 * @function updateLoanEeList 
 * @param {array} data - Data to be pushed to datatable
 */
function updateLoanEeList(data){
    const tableID = '#' + $.fn.dataTable.tables()[0].id;
    $(tableID)
        .dataTable()
        .fnClearTable();
    if(data.length > 0){
        $(tableID)
            .dataTable()
            .fnAddData(data);
        $(tableID + ' tbody')
            .off('click')
            .on('click', 'tr', function (evt) {
                const dataTable = $(tableID).DataTable();
                if ($(evt.currentTarget.childNodes[0]).hasClass('dataTables_empty')) return false;
                const rowdata = dataTable.row(this).data();
                loanOfEeForm(rowdata);
            });
    }
    
    // 
    if ($(tableID + ' tbody tr td').hasClass('dataTables_empty')) {
        $(tableID + ' tbody').css({'cursor':'no-drop'});
    } else {
        $(tableID + ' tbody').css({'cursor':'pointer'});
    }
    
}

function loanOfEeForm(data) {
    // console.log(data);
    const modalID = '#loanofee';

    $(modalID).find('#txtEeNameView').html(data.eename);
    $(modalID).find('#loanTypeView').html(data.loantypename);
    $(modalID).find('#paymentFreqView').html(data.paymentfrequencydesc);
    $(modalID).find('#loanAmountView').html(ccyFormat(data.loanamount));
    $(modalID).find('#amountDueView').html(ccyFormat(data.amountdue));
    $(modalID).find('#startDateView').html(data.newstartdate);
    $(modalID).find('#eeid').val(data.userid);

    loadLoanEEDataTable(data.loanidmst);

    $(modalID).modal('show');
}




function initializeEELoanDataTable(tableID) {
    $(tableID).DataTable({
        data: [],
        language: {
            emptyTable: '<center>No list found</center>'
        },
        info: false,
        paging: false,
        searching: false,
        responsive: true,
        ordering: false,
        columns: [
            {data: 'loanid', name: 'loanid', title: '', visible: false},
            {data: 'loanidmst', name: 'loanidmst', title: '', visible: false},
            // {data: 'frequencynumber', name: 'frequencynumber', title: 'Freq', width:"5%"},
            {data: 'newduedate', name: 'newduedate', title: 'Due date', width:"10%"},
            {data: 'runningbalance', name: 'runningbalance', title: 'Running Balance', width:"20%",
                render: function(data){ return ccyFormat(data, 2);},
                className: "text-right",
            },
            {data: 'amountdue', name: 'amountdue', title: 'Amount due', width:"20%",
                render: function(data){return ccyFormat(data, 2);},
                className: "text-right",
            },
            {data: 'totalpaid', name: 'amountpaid', title: 'Amount paid', width:"20%",
                render: function(data){
                    if(!isNaN(data)){
                        return ccyFormat(data, 2);
                    } else {
                        const getamount = data.split(':');
                        return '<input type="number" value="' + getamount[1].trim() + '" style="text-align:right; width:80%;"/>';
                    }
                },
                className: "text-right",
            },
            {data: 'actions', name: 'actions', title: 'Actions', width:"20%",
                render: function(data){
                    let value = '';
                    switch (data.state) {
                        case 'current': case 'continue':
                            const payfn = "payLoan('" + data.loanid + "','" + data.id +"','" + data.state +"')";
                            value = '<a href="#" title="Mark as paid" onClick="'+ payfn +'" class="px-1"> <i class="text-red-800 fas fa-donate" ></i> </a>';
                            break;
                        // case 'continue':
                        //     const continuefn = "continueLoan('" + data.loanid + "','" + data.id +"')";
                        //     value = '<a href="#" title="Mark as paid" onClick="'+ continuefn +'" class="px-1"> <i class="text-red-800 fas fa-donate" ></i> </a>';
                        //     break;
                        case 'done':
                            // value = '<a href="#" title="" onClick="" class="px-1"> <i class="far fa-edit text-gray-800" ></i> </a>';
                            break;
                        default:
                            break;
                    }
                    return value;
                },
                className: "text-left"
            },
        ],
        rowId: 'id',
    });
    $(tableID + ' thead').css({'font-size':'0.9vw'});
    $(tableID + ' tbody').css({'font-size':'0.8vw'});
    $(tableID).dataTable().fnClearTable();
}

function editLoan(loanid, id){
    return;
    const table = $(loan_of_ee).DataTable();
    let rowdata = table.row('#' + id).data();
    rowdata.amountdue = 'input: '+rowdata.amountdue;
    table.row('#' + id).data(rowdata).draw();
    // console.log('editLoan: ' + loanid);
}

function payLoan(loanid = '', id = '', state = ''){
    const modal = '#markaspaid';
    const table = $(loan_of_ee).DataTable();
    const rowdata = table.row('#' + id).data();
    const loanidmst = rowdata.loanidmst;
    const amount = state == 'current' ? ccyFormat(rowdata.amountdue) : ccyFormat(rowdata.amountdue - rowdata.totalpaid);
    const runningbalance = rowdata.runningbalance;
    const duedate = rowdata.newduedate;
    let btnOfPaid = false;

    $(modal).find('#amounttmp').val(amount);
    $(modal).find('#btnMarkPaid').removeAttr('disabled');
    $(modal).find('#amounttobepaid')
        .val(amount)
        .off('keyup')
        .on('keyup', function (e) {
            if(e.target.value != ''){
                let targetCcy = ((e.target.value).replace(',',''));
                if(targetCcy == 0 || targetCcy > runningbalance){
                    $(modal).find('#btnMarkPaid').attr('disabled',true);
                    btnOfPaid = true;
                } else {
                    $(modal).find('#btnMarkPaid').removeAttr('disabled');
                    btnOfPaid = false;
                }
            } else {
                $(modal).find('#btnMarkPaid').removeAttr('disabled');
                btnOfPaid = false;
            }
        });

    $(modal).modal('show');
    ccyHandler('#amounttobepaid');

    $(modal).find('#btnMarkPaid')
        .off('click')
        .on('click', function(e){
            if(!btnOfPaid) {
                const payData = {
                    userid: $('#eeid').val(),
                    loanid: loanid,
                    loanidmst: loanidmst,
                    runningbalance: runningbalance,
                    amountdue: $(modal).find('#amounttmp').val(),
                    amountpaid: $(modal).find('#amounttobepaid').val(),
                    duedate: duedate,
                    createdby: $('#userid').val(),
                    paystate: state
                }
                markPaid(payData);
                $(modal).modal('hide');
            } else {
                // alertDialog('');
                $(modal).find('#btnMarkPaid').attr('disabled',true);
            }
        });
}

function continueLoan(loanid = '', id = ''){
    const modal = '#markaspaid';
    const table = $(loan_of_ee).DataTable();
    const rowdata = table.row('#' + id).data();
    const loanidmst = rowdata.loanidmst;
    const amount = ccyFormat(rowdata.amountdue - rowdata.amountpaid);
    const runningbalance = rowdata.runningbalance;
    const duedate = rowdata.newduedate;

    $(modal).find('#amounttmp').val(amount);
    $(modal).find('#btnMarkPaid').removeAttr('disabled');
    $(modal).find('#amounttobepaid')
        .val(amount)
        .off('keyup')
        .on('keyup', function (e) {
            if(e.target.value == '' || e.target.value == 0){
                $(modal).find('#btnMarkPaid').attr('disabled',true);
            } else {
                $(modal).find('#btnMarkPaid').removeAttr('disabled');
            }
        });
    ccyHandler('#amounttobepaid');

    $(modal).modal('show');

    $(modal).find('#btnMarkPaid')
        .off('click')
        .on('click', function(e){
            const payData = {
                userid: $('#eeid').val(),
                loanid: loanid,
                loanidmst: loanidmst,
                runningbalance: runningbalance,
                amountdue: $(modal).find('#amounttmp').val(),
                amountpaid: $(modal).find('#amounttobepaid').val(),
                duedate: duedate,
                createdby: $('#userid').val(),
            }
            markPaid(payData);
            $(modal).modal('hide');
        });
}

function markPaid(payData) {
    console.log(payData);
    const passedData = {'userid': payData.userid,
                        'loanid': payData.loanid, 
                        'loanidmst': payData.loanidmst,
                        'runningbalance': payData.runningbalance,
                        'amountdue': payData.amountdue,
                        'amountpaid': payData.amountpaid,
                        'duedate': payData.duedate,
                        'createdby': payData.createdby,
                        'paystate': payData.paystate,
    };
    qryData('loan', 'payLoan', passedData, data => {
        loadLoanEEDataTable(passedData.loanidmst);
    }, true);
}

function loadLoanEEDataTable(loanidmst) {
    apiParam = {'loanidmst': loanidmst};
    qryData('loan', 'getLoanOfEE', apiParam, data => {
        updateLoanEeData(data.loanofee.rows);
        if(parseFloat(data.lastloanpay.runningbalance) == parseFloat(data.lastloanpay.totalpaid) && data.loanstatus == 0){
            btnVisible(true);
            $('#loanofee').find('#fullyPaid')
                .off('click')
                .on('click', function(e){
                    confirmDialog('This will close the loan.',() => {
                        btnVisible(false);
                        markAsFullyPaid(loanidmst);
                    });
                });
        } else {
            btnVisible(false);
            $('#loanofee').find('#fullyPaid')
                .off('click')
                .on('click', function(){
                    btnVisible(false);
                });
        }

        function btnVisible(bool){
            if(bool){
                $('#loanofee').find('#fullyPaid').removeAttr('disabled');
                $('#loanofee').find('#fullyPaid').removeAttr('hidden');
            } else {
                $('#loanofee').find('#fullyPaid').attr('disabled', 'disabled');
                $('#loanofee').find('#fullyPaid').attr('hidden', 'disabled');
            }
        }
    }, true);
}

function markAsFullyPaid(loanidmst){
    const userid = $('#userid').val();
    const passedData = {loanidmst: loanidmst, userid: userid};
    qryData('loan', 'closeLoan', passedData, data => {

    }, true);
}

function updateLoanEeData(data = {}){
    const tableID = '#loan_of_ee';
    // console.log(tableID);
    $(tableID)
        .dataTable()
        .fnClearTable();
    if(data.length > 0){
        $(tableID)
            .dataTable()
            .fnAddData(data);
    }
    
    // $(tableID + ' tbody').css({'cursor':'pointer'});
}
//#endregion ---------------------------------------------------------------------


//#region Loan adding---------------------------------------------------------------


























//#endregion-------------------------------------------------------------------------
