<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Registration</h4>
      </div>
      <div class="modal-body" style="padding-bottom: 0;">

      <form method="post" action="{$settings->site_path}/user/signin" role="form" id="registration_modal_form">
      <fieldset>
        <div class="form-group">
          <label class="sr-only" for="input_username">Username</label>
          <input type="text" name="username" class="form-control" id="input_username" placeholder="Username">
        </div>
        
        <div class="form-group">
          <label class="sr-only" for="input_email">Email</label>
          <input type="email" name="email" class="form-control" id="input_email" placeholder="Email">
        </div>

        <div class="form-group">
          <label class="sr-only" for="input_password">Password</label>
          <input type="password" name="password" class="form-control" id="input_password" placeholder="Password">
        </div>

        <div class="alert alert-danger" id="registration_invalid_password_alert" style="display: none;">
          Invalid username or password
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary pull-left" value="Sign Up" data-loading-text="Registration..." id="registration_modal_form_submit">
        </div>

      </fieldset>
      </form>

      </div>
      <div class="modal-footer">
        <div class="pull-right">
        Already a member? <a href="{$settings->site_path}/user/signin" class="btn btn-default btn-sm">Sign In</a>
        </div>
      </div>
    </form>
  </div>
</div>

