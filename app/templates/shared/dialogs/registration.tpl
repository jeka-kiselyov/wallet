<div class="modal-dialog">
  <div class="modal-content">
    <form method="post" action="{$settings->site_path}/user/signin" role="form" id="registration_modal_form">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Registration</h4>
      </div>
      <div class="modal-body" style="padding-bottom: 0;">

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
      </div>
      <div class="modal-footer">
        <a href="{$settings->site_path}/user/signin" class="btn btn-info signin_caller">Sign In</a>
        <input type="submit" class="btn btn-primary pull-left" value="Register" data-loading-text="Registration..." id="registration_modal_form_submit">
      </div>
    </form>
  </div>
</div>

