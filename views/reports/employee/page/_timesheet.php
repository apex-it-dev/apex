<div class="tab-pane fade show" id="eetimesheet" role="tabpanel"
    aria-labelledby="v-pills-eetimesheet-tab">
    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                aria-orientation="vertical">
                <a class="nav-link active" id="timesheet_reports_vtab" data-toggle="pill"
                    href="#timesheet_reports" role="tab" aria-controls="timesheet_reports"
                    aria-selected="true">Employee Timesheet</a>
                <a class="nav-link" id="v-pills-eeatt-tab" data-toggle="pill"
                    href="#v-pills-eeatt" role="tab" aria-controls="v-pills-eeatt"
                    aria-selected="false">Employee Attendance</a>
                <a class="nav-link" id="v-pills-eeleaves-tab" data-toggle="pill"
                    href="#v-pills-eeleaves" role="tab" aria-controls="v-pills-eeleaves"
                    aria-selected="false">Employee Leaves</a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="timesheet_reports" role="tabpanel"
                    aria-labelledby="timesheet_reports">
                    <!-- <h5 mb-2>Employee Timesheet</h5>
                    <h6>Report Filters</h6>
                    <div class="form-group row">
                        <label for="companyname" class="col-6 col-form-label">Employee</label>
                        <select class="form-control col-6" id="companyname">
                            <option>Abigail ALDAVE</option>
                            <option>Aireen RALLOS</option>
                            <option>Aiza MUTIA</option>
                            <option>Cherry ACA-AC</option>
                        </select>
                    </div>
                    <div class="form-group row">
                        <label for="example-date-input" class="col-6 col-form-label">Date
                            Range</label>
                        <input class="form-control col-6" type="date" value="2011-08-19"
                            id="example-date-input" />
                    </div>
                    <h5>Report Options</h5>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-6 col-form-label">Ignore Daily
                            Multiple Attendance</label>
                        <div class="col-6">
                            <input type="checkbox" checked data-toggle="toggle"
                                data-onstyle="outline-danger" data-offstyle="outline-warning" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-6 col-form-label">Goal
                            Results</label>
                        <div class="col-6">
                            <input type="checkbox" checked data-toggle="toggle"
                                data-onstyle="outline-danger" data-offstyle="outline-warning" />
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#eetimesheet1">Generate Report</button> -->
                    
                    <div class="form-group row">
                        <label for="timesheet_date" class="col-6 col-form-label">Date: </label>
                        <input class="form-control col-6" type="text" value="<?php echo formatDate('D d M Y',TODAY);?>"
                            id="timesheet_date" style="background-color: white;" />
                            
                    </div>
                    <div class="chart-container" id="timesheet_chart_container">
                        <canvas id="timesheet_chart"></canvas>
                    </div>
                </div>
                <!--								Employee Attendance-->
                <div class="tab-pane fade" id="v-pills-eeatt" role="tabpanel"
                    aria-labelledby="v-pills-eeatt-tab">
                    <div class="form-group row">
                        <label for="example-text-input"
                            class="col-6 col-form-label">Employee</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>Abigail ALDAVE</option>
                                <option>Aireen RALLOS</option>
                                <option>Aiza MUTIA</option>
                                <option>Cherry ACA-AC</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-6 col-form-label">Company</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>ssceb</option>
                                <option>sshk</option>
                                <option>sssg</option>
                                <option>abasha</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input"
                            class="col-6 col-form-label">Station:</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>ssceb</option>
                                <option>sshk</option>
                                <option>sssg</option>
                                <option>abasha</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input"
                            class="col-6 col-form-label">Department:</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>Admin and HR</option>
                                <option>IT and Marketing</option>
                                <option>Client Servicing</option>
                                <option>Business Development</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input"
                            class="col-6 col-form-label">Designation:</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>Account Executive-Corporate</option>
                                <option>Account Executive-General Insurance</option>
                                <option>Account Supervisor</option>
                                <option>Advanced Business Development Manager</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-6 col-form-label">Employee
                            Status:</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>Active</option>
                                <option>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-date-input" class="col-6 col-form-label">Attendance Date
                            Range</label>
                        <div class="col-6">
                            <input class="form-control" type="date" value="2011-08-19"
                                id="example-date-input" /> to
                            <input class="form-control" type="date" value="2011-08-19"
                                id="example-date-input" />
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#eetimesheet2">Generate Report</button>
                </div>
                <!--									Leaves-->
                <div class="tab-pane fade" id="v-pills-eeleaves" role="tabpanel"
                    aria-labelledby="v-pills-eeleaves-tab">
                    <div class="form-group row">
                        <label for="example-text-input"
                            class="col-6 col-form-label">Employee</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>Abigail ALDAVE</option>
                                <option>Aireen RALLOS</option>
                                <option>Aiza MUTIA</option>
                                <option>Cherry ACA-AC</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="example-text-input" class="col-6 col-form-label">Company</label>
                        <div class="col-6">
                            <select class="form-control" id="companyname">
                                <option>ssceb</option>
                                <option>sshk</option>
                                <option>sssg</option>
                                <option>abasha</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" data-toggle="modal"
                        data-target="#eetimesheet3">Generate Report</button>
                </div>
            </div>
        </div>
    </div>
</div>