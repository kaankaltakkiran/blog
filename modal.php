<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
          <form method="POST">
    <div class="input-group mb-3 inputGroup-sizing-default">
      <input type="password" name="form_oldpassword" class="form-control" id="reoldPassword" placeholder="Write Old Password">
      <span class="input-group-text bg-transparent"><i id="retoggleOldPassword" class="bi bi-eye-slash"></i></span>
    </div>
    <div class="input-group mb-3 inputGroup-sizing-default">
      <input type="password" name="form_repassword" class="form-control" id="reoldRePassword" placeholder="Write Again Old Password">
      <span class="input-group-text bg-transparent"><i id="retoggleOldRePassword" class="bi bi-eye-slash"></i></span>
    </div>
    <div class="input-group mb-3 inputGroup-sizing-default">
      <input type="password"  name="form_newpassword" class="form-control" id="renewRePassword" placeholder="Write New Password">
      <span class="input-group-text bg-transparent"><i id="retoggleNewRePassword" class="bi bi-eye-slash"></i></span>
    </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Close <i class="bi bi-x-circle"></i></button>
            <button type="submit" name="form_submit" class="btn btn-outline-success">Change Password <i class="bi bi-arrow-repeat"></i> </button>
          </div>
          </form>
        </div>
      </div>
    </div>
