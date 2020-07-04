<div class="row">
    <div class="col-3">
        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
            aria-orientation="vertical">
            <a class="nav-link active" id="v-pills-employeesum1-tab" data-toggle="pill"
                href="#v-pills-employeesum1" role="tab" aria-controls="v-pills-employeesum1"
                aria-selected="true">Employees Summary</a>
            <a class="nav-link" id="v-pills-eereport-tab" data-toggle="pill"
                href="#v-pills-eereport" role="tab" aria-controls="v-pills-eereport"
                aria-selected="false">Employee Reports</a>
            <a class="nav-link" id="v-pills-overtimetrend-tab" data-toggle="pill"
                href="#v-pills-overtimetrend" role="tab" aria-controls="v-pills-overtimetrend"
                aria-selected="false">Overtime Trend</a>
        </div>
    </div>
    <div class="col-9">
        <div class="tab-content">
            <!--								Employee Summary-->
            <div class="tab-pane fade show active" id="v-pills-employeesum1" role="tabpanel"
                aria-labelledby="v-pills-employeesum1-tab">
                <h5>Employee Summary</h5>
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
                <h5>Report Options</h5>
                <div class="form-group row">
                    <label for="example-text-input" class="col-6 col-form-label">Additional
                        Report Sections</label>
                    <div class="col-6">
                        <select class="form-control" id="companyname">
                            <option>Leaves Summary</option>
                            <option>Qualifications</option>
                            <option>Work Experience</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="example-text-input" class="col-6 col-form-label">Performance Add
                        on Results</label>
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
                    data-target="#eesummaryModal">Generate Report</button>
            </div>
            <!--								Employee Reports-->
            <div class="tab-pane fade" id="v-pills-eereport" role="tabpanel"
                aria-labelledby="v-pills-eereport-tab">
                <h5>Employee Reports</h5>
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
                        Type:</label>
                    <div class="col-6">
                        <select class="form-control" id="companyname">
                            <option>Consultant</option>
                            <option>Permanent</option>
                            <option>Temporary</option>
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
                <button type="button" class="btn btn-danger" data-toggle="modal"
                    data-target="#eeModal1">Generate Report</button>
            </div>
            <!--								Overtime Trend-->
            <div class="tab-pane fade" id="v-pills-overtimetrend" role="tabpanel"
                aria-labelledby="v-pills-overtimetrend-tab">
                <h5>Overtime Trend</h5>
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
                    <label for="example-text-input" class="col-6 col-form-label">Year:</label>
                    <div class="col-6">
                        <select class="form-control" id="companyname">
                            <option>2019</option>
                            <option>2018</option>
                            <option>2017</option>
                            <option>2016</option>
                        </select>
                    </div>
                </div>
                <h5 mb-4>Report Options</h5>
                <button type="button" class="btn btn-danger" data-toggle="modal"
                    data-target="#eeModal2">Generate Report</button>
            </div>
        </div>
    </div>
</div>