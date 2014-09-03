<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Sign In</h4>
      </div>
      <div class="modal-body" style="padding-bottom: 0;">

      <form method="post" action="{$settings->site_path}/user/signin" role="form" id="signin_modal_form">
      <fieldset>
        <div class="form-group">
          <label class="sr-only" for="input_username">Username or Email</label>
          <input type="text" name="username" class="form-control" id="input_username" placeholder="Username or Email">
        </div>
        <div class="form-group">
          <label class="sr-only" for="input_password">Password</label>
          <input type="password" name="password" class="form-control" id="input_password" placeholder="Password">
        </div>
        <div class="alert alert-danger errors-container" id="signin_invalid_password_alert" style="display: none;">
          Invalid username or password
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary pull-left" value="Sign In" data-loading-text="Signing in..." id="signin_modal_form_submit">
        </div>
      </fieldset>
      </form>

      </div>
      <div class="modal-footer">
        <a href="{$settings->site_path}/user/registration" class="btn btn-default btn-sm">Register</a>
        <a href="{$settings->site_path}/user/restore" class="btn btn-default btn-sm">Restore your password</a>
      </div>
  </div>
</div>

