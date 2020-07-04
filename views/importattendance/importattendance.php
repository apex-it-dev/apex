
    <div class="card shadow mb-4">
    <div class="card-header py-3 border-bottom-danger">
        <div class="row">
            <div class="col-md-10"> 
                <h3 class="m-0 font-weight-bold text-gray-600">Import Attendance</h3>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
        <div class="col-md-12">  
            <div class="profile-head">
            </div>
                <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                    <div class="card-body">
                        <form method="post">
                        <div class="row">
                            <div class="form-group col-lg-6">
                            <label for="leavetype">Date From</label>
                            <input id="txtDateFrom" name="sdt" class="form-control form-control-sm" type="text" value="">
                            </div>
                            <div class="form-group col-lg-6">
                            <label for="leavetype">Date To</label>
                            <input id="txtDateTo" name="edt" class="form-control form-control-sm" type="text" value="">
                            </div>
                            <div class="form-group col-lg-6">
                            <label for="leavetype">ZK Device ID</label>
                            <select id="txtZKDeviceLoc" name="zkdeviceid" class="form-control form-control-sm">
                                <option value="1">HK</option>
                                <option value="2">SHA</option>
                                <option value="3">BEI</option>
                                <option value="4">SG</option>
                                <option value="5">CEB</option>
                            </select>
                            </div>
                        </div>
                        <input id="btnSubmit" type="submit" name="submit" value="Get Logs" class="btn btn-primary btn-sm" style="colo" >
                        <input id="log" name="log" type="hidden" value="1">
                        
                        </form> 
                    </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="col-md-12">
                    <h3></h3>
                    <div class="table-responsive"> 
                        <table id="attendancemntr" class="table table-sm table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-dark">
                            <tr>
                            <th>Date</th>
                            <th>Office</th>
                            <th>No of Logs</th>
                            <th>Imported by</th>
                            <th>Imported date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i=0; $i < count($res); $i++) { ?>
                            <tr>
                            <td><?php echo formatDate("M d Y",$res[$i]['loggedno']); ?></td>
                            <td><?php echo $res[$i]['devicename']; ?></td>
                            <td><?php echo $res[$i]['nooflogs']; ?></td>
                            <td><?php echo $res[$i]['createdby']; ?></td>
                            <td><?php echo formatDate("D d M Y",$res[$i]['createddate']); ?></td>
                            </tr>
                        <?php  } ?>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>                      
                </div>
            </div>  
            </div> 
        </div>
    </div> 