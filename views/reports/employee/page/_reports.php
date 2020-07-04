
<div id="accordion" class="mb-3">
    <div class="card accordion-style">
        <div class="card-header text-light font-weight-bold" id="headingFilter" data-toggle="collapse" 
            data-target="#collapseFilter" aria-expanded="true" aria-controls="collapseFilter">
            Filters
        </div>
        <div id="collapseFilter" class="collapse show row" aria-labelledby="headingFilter" data-parent="#accordion">
            <div class="card-body mr-2 ml-2">
                <div class="row">
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="office-lbl">Office:</span>
                        </div>
                        <select id="office-filter" name="office-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="office-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="department-lbl">Department:</span>
                        </div>
                        <select id="department-filter" name="department-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="department-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="position-lbl">Position:</span>
                        </div>
                        <select id="position-filter" name="position-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="position-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="rank-lbl">Ranking:</span>
                        </div>
                        <select id="rank-filter" name="rank-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="rank-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="gender-lbl">Gender:</span>
                        </div>
                        <select id="gender-filter" name="gender-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="gender-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="age-lbl">Age Range:</span>
                        </div>
                        <select id="agefrom-filter" name="agefrom-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="age-lbl"></select>
                        <select id="ageto-filter" name="ageto-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="age-lbl"></select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="employeetype-lbl">Employee Type:</span>
                        </div>
                        <select id="employeetype-filter" name="employeetype-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="employeetype-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
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
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="joineddate-lbl">Joined Date:</span>
                        </div>
                        <label class="checkcontainer">
                            <input id="joineddate" type="checkbox">
                            <span class="checkmark">x</span>
                        </label>
                        <input id="joineddate-filter" name="joineddate-filter" type="text" class="form-control form-control-sm date-filter" readonly value="" disabled aria-label="Small" aria-describedby="joineddate-lbl">
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="enddate-lbl">End Date:</span>
                        </div>
                        <label class="checkcontainer">
                            <input id="enddate" type="checkbox">
                            <span class="checkmark">x</span>
                        </label>
                        <input id="enddate-filter" name="enddate-filter" type="text" class="form-control form-control-sm date-filter" readonly value="" disabled aria-label="Small" aria-describedby="enddate-lbl">
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="probationdate-lbl">Probation End Date:</span>
                        </div>
                        <label class="checkcontainer">
                            <input id="probationdate" type="checkbox">
                            <span class="checkmark">x</span>
                        </label>
                        <input id="probationdate-filter" name="probationdate-filter" type="text" class="form-control form-control-sm date-filter" readonly value="" disabled aria-label="Small" aria-describedby="probationdate-lbl">
                    </div>
                </div>
                <div class="row mb-2"></div>
                <div class="row">
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="reportsdirect-lbl">Direct head:</span>
                        </div>
                        <select id="reportsdirect-filter" name="reportsdirect-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="reportsdirect-lbl">
                            <option value="" selected></option>
                        </select>
                    </div>
                    <div class="input-group input-group-sm filter-container col-lg-3 col-md-12 col-sm-12 col-xs-12 mb-1">
                        <div class="input-group-prepend labelinput">
                            <span class="input-group-text labeltext" id="reportsindirect-lbl">Indirect head:</span>
                        </div>
                        <select id="reportsindirect-filter" name="reportsindirect-filter" class="form-control form-control-sm" aria-label="Small" aria-describedby="reportsindirect-lbl">
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