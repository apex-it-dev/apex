<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="frmuploadphoto">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
		<div class="modal-body"> 
			<form name="form"  method="Post" enctype="multipart/form-data" action="#">
				<!-- <div class="col-md-4 col-sm-12" id="drop_file_zone" ondrop="upload_file(event)" ondragover="return false">					 -->
					<!-- <div id="drag_upload_file"> -->
						<!-- <p>Drop file here</p> -->
						<!-- <p>or</p> -->
						<div class="form-data">
							<!-- <p> -->
								<input type="file" value="Select File" id="selectfile" class="form-control-file" name="selectfile" accept="image/*" style="border:1px solid gray; border-radius: 5px;">
							<!-- </p> -->
						</div>
						<!-- <input type="file"  > -->
					<!-- </div> -->
				<!-- </div > -->
				<label class="col-lg-3 col-sm-4 control-label"></label>
				<div class="text-center" >
					<input type="submit" name="submit" value="Submit" id="btnSubmit" class="btn btn-grad btn-danger">
					<input type="hidden" id="uploadphoto" name="uploadphoto" value="1" />
					<input type="hidden" id="txtuserid" name="txtuserid" value="<?php echo $userid;?>"  />
					<input type="hidden" id="txteeid" name="txteeid" value="" />
				</div>
				<input type="hidden" id="avatar" name="avatar" value="" />
			</form>
	  	</div>
    </div>
  </div>
</div>
