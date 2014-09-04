<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">New Password</h4>
      </div>
      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

      <form method="post" action="{$settings->site_path}/user/newpassword" role="form" id="newpassword_modal_form">
      <fieldset>

        <div class="form-group">
          <label class="sr-only" for="input_password">Password</label>
          <input type="password" name="password" class="form-control" id="input_password" placeholder="Password">
        </div>

        <div class="form-group">
          <label class="sr-only" for="input_confirm_password">Confirm password</label>
          <input type="password" name="confirm_password" class="form-control" id="input_confirm_password" placeholder="Confirm Password">
        </div>

        <div class="alert alert-danger errors-container" id="newpassword_invalid_password_alert" style="display: none;">
          Invalid username or password
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary pull-left" value="Save" data-loading-text="Saving..." id="newpassword_modal_form_submit">
        </div>

      </fieldset>
      </form>

      </div>
      <div class="modal-body modal-body-success" style="display: none;">
        <div class="alert alert-info" role="alert">Done. You can <a href="{$settings->site_path}/user/signin">sign in</a> now.</div>
      </div>
      <div class="modal-footer">
        <div class="pull-right">
        </div>
      </div>
    </form>
  </div>
</div>

