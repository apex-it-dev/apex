<div class="modal fade shadow-lg" data-backdrop="static" tabindex="-1" role="dialog" id="loanofee">
  <div class="modal-dialog modal-dialog-centered modal-xl" role="dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Employee Loan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 40%;overflow-y: auto;">
        <div class="row">
          <div class="col-sm-12 col-md-5 mt-2">
            <div class="row">
              <div class="col-md-6 col-sm-6">
                  <label><b>Name:</b></label>
              </div>
              <div class="col-md-6 col-sm-6">
                  <label id="txtEeNameView"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6">
                  <label><b>Loan Type:</b></label>
              </div>
              <div class="col-md-6 col-sm-6">
                  <label id="loanTypeView"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6">
                  <label><b>Payment frequency:</b></label>
              </div>
              <div class="col-md-6 col-sm-6">
                  <label id="paymentFreqView"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6">
                  <label><b>Loan amount:</b></label>
              </div>
              <div class="col-md-6 col-sm-6">
                  <label id="loanAmountView"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6">
                  <label><b>Amount due per freq.:</b></label>
              </div>
              <div class="col-md-6 col-sm-6">
                  <label id="amountDueView"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 col-sm-6">
                  <label><b>Start date:</b></label>
              </div>
              <div class="col-md-6 col-sm-6">
                  <label id="startDateView"></label>
              </div>
            </div>
          </div>
          <div class="col-sm-12 col-md-7">
            <div class="table-responsive eedtcontainer">
              <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <table id="loan_of_ee" class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" width="100%" cellspacing="0">
                  <thead class="thead-dark"></thead>
                  <tbod></tbody>
                </table>	
              </div>		
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" id="eeid" readonly>
        <button type="button" class="btn btn-danger" id="fullyPaid" disabled hidden>Mark as fully paid</button>
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button> -->
      </div>
    </div>
  </div>
</div>
<?php 
  include_once('views/loans/loan-markaspaid.php');
?>