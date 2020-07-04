
<div id="accordion" class="mb-3">
    <div class="card accordion-style">
        <div class="card-header text-light font-weight-bold" id="headingFilter" data-toggle="collapse" 
            data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
            Filters
        </div>
        <div id="collapseFilter" class="collapse show row" aria-labelledby="headingFilter" data-parent="#accordion">
            <div class="card-body mr-2 ml-2">
                <div class="row">
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="office-lbl">Office:</span>
                        </div>
                        <select id="office-filter" name="office-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="office-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="department-lbl">Department:</span>
                        </div>
                        <select id="department-filter" name="department-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="department-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1 d-none">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="position-lbl">Position:</span>
                        </div>
                        <select id="position-filter" name="position-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="position-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="employeestatus-lbl">Employee Status:</span>
                        </div>
                        <select id="employeestatus-filter" name="employeestatus-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="employeestatus-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2"></div>
                <div class="row">
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="leavefrom-lbl">Leave From:</span>
                        </div>
                        <label class="checkcontainer">
                            <input id="leavefrom" type="checkbox">
                            <span class="checkmark">x</span>
                        </label>
                        <input id="leavefrom-filter" name="leavefrom-filter" type="text" class="form-control form-control-sm date-filter" readonly value="" disabled aria-label="Small" aria-describedby="leavefrom-lbl">
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="leaveto-lbl">Leave To:</span>
                        </div>
                        <label class="checkcontainer">
                            <input id="leaveto" type="checkbox">
                            <span class="checkmark">x</span>
                        </label>
                        <input id="leaveto-filter" name="leaveto-filter" type="text" class="form-control form-control-sm date-filter" readonly value="" disabled aria-label="Small" aria-describedby="leaveto-lbl">
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="leavetype-lbl">Leave Type:</span>
                        </div>
                        <select id="leavetype-filter" name="leavetype-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="leavetype-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="leavestatus-lbl">Leave Status:</span>
                        </div>
                        <select id="leavestatus-filter" name="leavestatus-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="leavestatus-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2"></div>
                <div class="row">
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="reportsdirect-lbl">Reports to direct:</span>
                        </div>
                        <select id="reportsdirect-filter" name="reportsdirect-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="reportsdirect-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="reportsindirect-lbl">Reports to indirect:</span>
                        </div>
                        <select id="reportsindirect-filter" name="reportsindirect-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="reportsindirect-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-3 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="employee-lbl">Employee:</span>
                        </div>
                        <select id="employee-filter" name="employee-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="employee-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <!-- <button title="Reset the filter to default" type="button" class="btn btn-primary btn-lg float-right apply-filter ml-2" id="clear-filter">Clear filter</button> -->
                        <button title="Apply filter" type="button" class="btn btn-danger btn-lg float-right apply-filter" id="apply-filter" disabled>Retrieve</button>
                    </div>
                </div>
                <input type="hidden" id="filter-data" name="filter-data" value="" readonly disabled />
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div id="exportBtns"></div>
                <div class="d-md-none d-lg-none d-sm-flex mb-2"></div>
            </div>
            <div class="col-lg-8 col-md-4 col-sm-12">
                <select id="columnVisible" multiple style="display: none;" class="float-right"></select>
            </div>
        </div>
        <div class="table-responsive" style="min-height: 400px;">
            <table id="reportstable" class="table table-sm table-bordered no-footer table-hover display nowrap" width="100%">
                <thead class="thead-dark"></thead>
                <tbody></tbody>
            </table>
        </div>	
    </div>
</div>