<script type="text/html" id="templates_dialogs_signin">

<div id="dialog_signin" class="modal fade dialog_signin" role="dialog" aria-labelledby="dialog_signin_label">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="{$settings->site_path}/user/signin" role="form" id="signin_modal_form">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="dialog_signin_label">{t}Sign In{/t}</h4>
        </div>
        <div class="modal-body" style="padding-bottom: 0;">

          <div class="form-group">
            <label class="sr-only" for="input_username">{t}Username or Email{/t}</label>
            <input type="text" name="username" class="form-control" id="input_username" placeholder="{t}Username or Email{/t}">
          </div>
          <div class="form-group">
            <label class="sr-only" for="input_password">{t}Password{/t}</label>
            <input type="password" name="password" class="form-control" id="input_password" placeholder="{t}Password{/t}">
          </div>
          <div class="alert alert-danger" id="signin_invalid_password_alert" style="display: none;">
            {t}Invalid username or password{/t}
          </div>
        </div>
        <div class="modal-footer">
          <a href="{$settings->site_path}/user/registration" class="btn btn-info">Register</a>
          <a href="{$settings->site_path}/user/restore" class="btn btn-info">Restore your password</a>
          <input type="submit" class="btn btn-primary pull-left" value="{t}Sign In{/t}" data-loading-text="{t}Signing in...{/t}" id="signin_modal_form_submit">
        </div>
      </form>
    </div>
  </div>
</div>

{if 1==0}
<div class="dialog_signin_container">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="delete_item_modal_label">{t}Sign In{/t}</h3>
  </div>

  <div class="modal-body">
    <form method="post" action="{$settings->site_path}/user/signin" onsubmit="" id="signin_modal_form">
      <input type="text" name="username" id="input_username" placeholder="{t}Username or Email{/t}"><br>
      <input type="password" name="password" id="input_password" placeholder="{t}Password{/t}" class="error"><br>

      <div class="modal-forget-password"><a href="{$settings->site_path}/user/restore">{t}Forgot your password?{/t}</a></div>

      <div class="alert alert-error" id="signin_invalid_password_alert">
        {t}Invalid username or password{/t}
      </div>
      <input type="submit" value="{t}Sign In{/t}" class="btn btn-primary" data-loading-text="{t}Signing in...{/t}" id="signin_modal_form_submit">
    </form>
  </div>

  {if $settings->user_allow_vk_registration || $settings->user_allow_facebook_registration}
  <div class="modal-header modal-social">
    <h4>{t}Sign in with social network{/t}</h4>

    {if $settings->user_allow_vk_registration}<a class="btn btn-info btn-block" href="{$settings->site_path}/user/vkconnect"><span class="social_button_icon">B</span>{t}VK{/t}</a>{/if}
    {if $settings->user_allow_facebook_registration}<a class="btn btn-info btn-block" href="{$settings->site_path}/user/vkconnect"><span class="social_button_icon">f</span>{t}Facebook{/t}</a>{/if}

  </div>
  {/if}

  <div class="modal-header modal-link-to-registration btn-primary ">
    <a href="{$settings->site_path}/user/registration">{t}Don't have an account?{/t} <i class="icon-forward icon-white"></i> {t}Register{/t}</a>
  </div>

</div>
</div>
{/if}
</script>
