<div class="col-md-12 col-sm-12">
    <!-- <div class="row mb-3" hidden>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-left-warning">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title text-uppercase text-muted mb-0">
                                <i class="fas fa-sign-in-alt" id="kpistyle"> </i> Time in</h6>
                            </div>
                            <div class="col-auto">
                                <span class="h5 font-weight-bold mb-0" id="count_signin">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-left-warning">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title text-uppercase text-muted mb-0"><i class="fas fa-business-time" id="kpistyle"> </i> Late</h6>
                            </div>
                            <div class="col-auto">
                                <span class="h5 font-weight-bold mb-0" id="count_late">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-left-warning">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title text-uppercase text-muted mb-0">
                                <i class="fas fa-plane-departure" id="kpistyle"> </i> Leave</h6>
                            </div>
                            <div class="col-auto">
                                <span class="h5 font-weight-bold mb-0" id="count_onleave">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="card border-left-warning">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h6 class="card-title text-uppercase text-muted mb-0">
                                <i class="fas fa-times-circle" id="kpistyle"> </i> Absent</h6>
                            </div>
                            <div class="col-auto">
                                <span class="h5 font-weight-bold mb-0" id="count_absent">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <div id="accordion">
        <div class="card accordion-style">
            <div class="card-header text-light font-weight-bold" id="headingKpi" data-toggle="collapse" 
                data-target="#collapseKpi" aria-expanded="true" aria-controls="collapseKpi" style="background-color:#5a5c69; height:40px !important; font-size:1rem !important; cursor: pointer;">
                Key Performance Indicator (KPI) / Filters
            </div>
            <div id="collapseKpi" class="collapse show row" aria-labelledby="headingKpi" data-parent="#accordion">
                <div class="card-body">
                    <div class="row pl-2 pr-2 pt-1" style="margin-bottom: -10px">
                        <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                            <div class="chart-container" id="attendance_chart_container" style="width:95%;">
                                <canvas id="attendance_chart"></canvas>
                            </div>
                            <!-- <input type="checkbox" checked data-toggle="toggle" 
                                data-on="GENERAL" data-off="LEAVES" 
                                data-onstyle="success" data-offstyle="danger"
                                data-width="120"> -->
                        </div>

                        <div class="col-lg-5 col-md-5 col-sm-12 col-12 mt-2">
                            <div class="row">
                                <div class="col-lg-7 col-md-7 col-sm-12 col-12">
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                            <i class="fas fa-circle" id="color_late"> </i>
                                            <span style="font-size:1rem;">LATE:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_late" style="font-size:1rem;">
                                            0
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                            <i class="fas fa-circle" id="color_onleave"> </i>
                                            <!-- <i class="fas fa-plane-departure"></i> -->
                                            <span style="font-size:1rem;">LEAVE:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_onleave" style="font-size:1rem;">
                                            0
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                            <i class="fas fa-circle" id="color_present"> </i>
                                            <span style="font-size:1rem;">PRESENT:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_present" style="font-size:1rem;">0</div>
                                    </div>
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                            <i class="fas fa-circle" id="color_absent"> </i>
                                            <!-- <i class="fas fa-user-times"></i> -->
                                            <span style="font-size:1rem;">ABSENT:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_absent" style="font-size:1rem;">
                                            0
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                            <i class="fas fa-circle" id="color_absent"> </i>
                                            <span style="font-size:1rem;">TOTAL:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_attendance" style="font-size:1rem;">
                                            0
                                        </div>
                                    </div> -->
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                                <span style="font-size:1rem;">AL:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_lal">0</div>
                                    </div>
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                                <span style="font-size:1rem;">SL:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_lsl">0</div>
                                    </div>
                                    <div class="row">
                                        <label class="col-6 control-label font-weight-bold h6">
                                                <span style="font-size:1rem;">UL:</span>
                                        </label>
                                        <div class="col-6 font-weight-bold h6" id="count_lul">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-lg-2 col-md-2 col-sm-12 col-12 mt-2">
                            <div class="row">
                                <label class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 control-label font-weight-bold h6">
                                        AL: 
                                </label>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 font-weight-bold h6" id="count_lal">0</div>
                            </div>
                            <div class="row">
                                <label class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 control-label font-weight-bold h6">
                                        SL: 
                                </label>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 font-weight-bold h6" id="count_lsl">0</div>
                            </div>
                            <div class="row">
                                <label class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 control-label font-weight-bold h6">
                                        UL: 
                                </label>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 font-weight-bold h6" id="count_lul">0</div>
                            </div>
                        </div> -->

                        <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                            <div class="row" id="forLblLogFrom" style="display: none;">
                                <label class="col-lg-3 col-md-3 col-sm-3 control-label font-weight-bold">Date:</label>
                                <div class="col-lg-4 col-md-4 col-sm-3">
                                    <input id="txtLogFrom" type="text" class="form-control form-control-sm" readonly value="<?php echo formatDate('D d M y',TODAY);?>" style="background-color: white;" aria-describedby="inputGroup-datefrom">
                                </div>
                                <div class="col-lg-1 col-md-1 col-sm-3 text-center align-middle">
                                    <label class="font-weight-bold">TO</label>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-3">
                                    <input id="txtLogTo" type="text" class="form-control form-control-sm" readonly value="<?php echo formatDate('D d M y',TODAY);?>" style="background-color: white;" aria-describedby="inputGroup-dateto">
                                </div>
                            </div>
                            <div class="row" id="dataofclist" style="display: none;">
                                <label class="col-lg-3 col-md-3 col-sm-4 control-label font-weight-bold" for="txtofc">Office:</label>
                                <div class="col-lg-9 col-md-9 col-sm-8">
                                    <select title="Offices" id="txtofc" name="txtofc" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm"></select>
                                </div>
                            </div>
                            <div class="row" id="dataeelist" style="display: none;">
                                <label class="col-lg-3 col-md-3 col-sm-4 control-label font-weight-bold" for="txtee">Employee:</label>
                                <div class="col-lg-9 col-md-9 col-sm-8">
                                    <select title="Employees" id="txtee" name="txtee" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm" style="display:inline-block;"></select>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-lg-12 col-md-12 col-sm-12" id="forbtnretrieve">
                                    <input title="Retrieve a record base on the filtered items" type="button" id="btnRetrieve" value="  Retrieve  " class="btn btn-grad btn-danger btn-lg float-right" /> 
                                    <!-- <input title="Generate payroll base on period and office" type="button" id="btnGeneratePayroll" value="  Generate Payroll  " class="btn btn-grad btn-danger btn-lg float-right mr-2" /> -->
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter under KPI -->
    <!-- <div class="row pb-2 pt-3">
        <div class="col-md-3 col-sm-6 col-xs-12 col-12" id="forLblLogFrom" style="display: none;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend" style="width:30%;">
                    <span class="input-group-text attendance_filter" id="inputGroup-sizing-sm" style="width:100%;">Date From</span>
                </div>
                <input id="txtLogFrom" type="text" class="form-control form-control-sm" readonly value="<?php //echo formatDate('D d M Y',TODAY);?>" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 col-12" id="forLblLogTo" style="display: none;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend" style="width:30%;">
                    <span class="input-group-text attendance_filter" id="inputGroup-sizing-sm" style="width:100%;">Date To</span>
                </div>
                <input id="txtLogTo" type="text" class="form-control form-control-sm" readonly value="<?php //echo formatDate('D d M Y',TODAY);?>" style="background-color: white;" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
            </div>
        </div> -->
        <!-- <div class="col-md-2 col-sm-2 col-xs-12 col-12" id="fortxtMonth" style="display: none;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend" style="min-width:30%;">
                    <span class="input-group-text attendance_filter" id="inputGroup-sizing-sm" style="width:100%;">Month</span>
                </div>
                <select id="txtMonth" name="txtMonth" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    <option value="01" <?php //if(date("m") == '01'){ echo 'selected'; } ?>>Jan</option>
                    <option value="02" <?php //if(date("m") == '02'){ echo 'selected'; } ?>>Feb</option>
                    <option value="03" <?php //if(date("m") == '03'){ echo 'selected'; } ?>>Mar</option>
                    <option value="04" <?php //if(date("m") == '04'){ echo 'selected'; } ?>>Apr</option>
                    <option value="05" <?php //if(date("m") == '05'){ echo 'selected'; } ?>>May</option>
                    <option value="06" <?php //if(date("m") == '06'){ echo 'selected'; } ?>>June</option>
                    <option value="07" <?php //if(date("m") == '07'){ echo 'selected'; } ?>>July</option>
                    <option value="08" <?php //if(date("m") == '08'){ echo 'selected'; } ?>>Aug</option>
                    <option value="09" <?php //if(date("m") == '09'){ echo 'selected'; } ?>>Sep</option>
                    <option value="10" <?php //if(date("m") == '10'){ echo 'selected'; } ?>>Oct</option>
                    <option value="11" <?php //if(date("m") == '11'){ echo 'selected'; } ?>>Nov</option>
                    <option value="12" <?php //if(date("m") == '12'){ echo 'selected'; } ?>>Dec</option>
                </select>
            </div>
        </div>
        <div class="col-md-2 col-sm-2 col-xs-12 col-12" id="fortxtYear" style="display: none;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend" style="min-width:30%;">
                    <span class="input-group-text attendance_filter" id="inputGroup-sizing-sm" style="width:100%;">Year</span>
                </div>
                <select id="txtYear" name="txtYear" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
                    <option value="<?php //echo date("Y");?>" selected><?php // echo date("Y");?></option>
                    <option value="<?php //echo (date("Y") - 1);?>"><?php // echo (date("Y") - 1);?></option>
                </select>
            </div>
        </div> -->
        <!-- <div class="col-md-2 col-sm-6 col-xs-12 col-12" id="dataofclist" style="display: none;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend" style="width:30%;">
                    <span class="input-group-text attendance_filter" id="inputGroup-sizing-sm" style="width:100%;">Office</span>
                </div>
                <select id="txtofc" name="txtofc" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm"></select>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 col-12" id="dataeelist" style="display: none;">
            <div class="input-group input-group-sm">
                <div class="input-group-prepend" style="width:30%;">
                    <span class="input-group-text attendance_filter" for="txtee" style="width:100%;">Employees</span>
                </div>
                <select id="txtee" name="txtee" class="form-control form-control-sm" aria-label="Small" aria-describedby="inputGroup-sizing-sm" style="display:inline-block;"></select>
            </div>
            
        </div>
        <div class="col-md-1 col-sm-12 col-xs-12 col-12" id="forbtnretrieve">
            <input type="button" id="btnRetrieve" value="RETRIEVE" class="btn btn-grad btn-danger btn-sm float-right" />
        </div>
    </div> -->


    <div class="table-responsive" style="width: 101%;"> 
        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer" style="table-layout:fixed; width: 99% !important;">
            <table id="attendancedatatable" class="table table-sm table-bordered dataTable no-footer table-hover dt-responsive display nowrap" width="100%" cellspacing="0">
                <thead class="thead-dark">
                    <tr style="cursor: pointer;">

                    </tr>
                </thead>  
                <tbody>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>

