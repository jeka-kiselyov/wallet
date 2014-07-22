<h4>Create new password</h4>

{if isset($password_changed) && $password_changed}

<div class="alert alert-success">
 Your password has been changed. You can <a href="{$settings->site_path}/user/signin/" class="signin_caller">sign in</a> now.
</div>

{else}

<form class="form-horizontal" method="post">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  {input_password name="password" id="password" caption="New password" placeholder="Password" bootstrap_horizontal=true}
  {input_password name="repeat_password" id="repeat_password" caption="Repeat password" placeholder="Repeat password" bootstrap_horizontal=true}

  {input_submit name="save" id="save" bootstrap_horizontal=true value="Save"}

</form>

{/if}


