<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="certificationModal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="exampleModalCenterTitle">Certificate</h5>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<div class="modal-body">
        <form autocomplete="off">
          <div class="row">
            <div class="col-lg">
              <div class="form-group">
                <label for="certificatename" class="col-form-label">Name:</label>
                <input type="text" class="form-control" id="certificatename" name="certificatename">
              </div>
              <div class="form-group">
                <label for="certificateorganization" class="col-form-label">Organization:</label>
                <input type="text" class="form-control" id="certificateorganization" name="certificateorganization">
              </div>
              <div class="row">
                <div class="col-lg">
                  <div class="form-group">
                    <label for="certificateissuemonth" class="col-form-label">Issue Month:</label>
                    <select class="form-control" id="certificateissuemonth" name="certificateissuemonth">
                  
                    </select>
                  </div>
                </div>
                <div class="col-lg">
                  <div class="form-group">
                    <label for="certificateissueyear" class="col-form-label">Issue Year:</label>
                    <select class="form-control" id="certificateissueyear" name="certificateissueyear">
                    
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="certificatenoexpiry" name="certificatenoexpiry" checked>
                <label class="form-check-label" for="certificatenoexpiry">No Expiry</label>
              </div>
              <div class="row">
                <div class="col-lg">
                  <div class="form-group">
                    <label for="certificateexpirymonth" class="col-form-label">Expiry Month:</label>
                    <select class="form-control" id="certificateexpirymonth" name="certificateexpirymonth" disabled>
                      
                    </select>
                  </div>
                </div>
                <div class="col-lg">
                  <div class="form-group">
                    <label for="certificateexpiryyear" class="col-form-label">Expiry Year:</label>
                    <select class="form-control" id="certificateexpiryyear" name="certificateexpiryyear" disabled>
                    
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg">
                <label for="" class="col-form-label">Certificate Attachment:</label> 
                <div class="row">
                  <div class="col-md-12">
                    <input type="file" id="certificateattachment" class="form-control-file" style="border:1px solid gray; border-radius: 5px;" name="certificateattachment" accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx"/>
                  </div>
                </div>
                <div>&nbsp;</div>
                <div id="certificatepreview" class="text-center"></div>
            </div>
          </div>
        </form>
        <!-- <form method="post" action="" enctype="multipart/form-data"> -->
          
        <!-- </form> -->
        <input type="hidden" name="ses_id" id="ses_id" value="" readonly/>
        <input type="hidden" name="certuserid" id="certuserid" value="" readonly/>
        <input type="hidden" name="byuserid" id="byuserid" value="" readonly/>
        <input type="hidden" name="certid" id="certid" value="" readonly/>
        <input type="hidden" name="certfilename" id="certfilename" value="" readonly/>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-auto" id="btnCertificateDelete" hidden>Delete</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="btnCertificateSave" disabled>Save Changes</button>
      </div>
    </div>
  </div>
</div>
<?php include_once('views/profile-certification-yesno-modal.php');?>
<?php include_once('views/profile-certification-upload-modal.php');?>
