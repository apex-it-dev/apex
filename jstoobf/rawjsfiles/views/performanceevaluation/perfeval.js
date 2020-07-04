'use strict';
$(function() {
  //   $.blockUI({
  //     message: $('#preloader_image'),
  //     fadeIn: 1000,
  //     onBlock: function() {
  datatableInitialize();
  yrFilterList();
  //     }
  //   });
});

let tableID = '#perfevaldatatable';

function datatableInitialize(data = []) {
  if (!$.fn.DataTable.isDataTable(tableID)) {
    let theTable = $(tableID).DataTable({
      //   data: data,
      lengthMenu: [
        [15, 25, 50, -1],
        [15, 25, 50, 'All']
      ],
      responsive: true,
      paging: false,
      searching: false,
      language: {
        emptyTable: 'No record found'
      }
    });
  } else {
    let theTable = $(tableID).dataTable();
    theTable.fnClearTable();
    theTable.fnAddData(data);
  }
}

function yrFilterList() {
  const yrInitial = 2020;
  const yrCurrent = new Date().getFullYear();

  let periodHtml = '';
  for (let yrAdd = yrInitial; yrAdd <= yrCurrent; yrAdd++) periodHtml += '<option value="' + yrAdd + '" selected>' + yrAdd + '</option>';
  $('#periodList').html(periodHtml);
}

function eeFilterList() {}
