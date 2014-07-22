<ul class="breadcrumb">
  <li>Registration</li>
</ul>

{if isset($registered)}
<div class="alert alert-success">
 Your account has been created. Please confirm your email address.
</div>
{else}

<form method="post" role="form">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-danger">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  <div class="form-group">
    <label for="username">Username</label>
    <input type="text" name="username" {if isset($smarty.post.username)}value="{$smarty.post.username|escape:'html'}"{/if} id="username" placeholder="Username" class="form-control" autofocus required maxlength="49">
  </div>

  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" name="email" {if isset($smarty.post.email)}value="{$smarty.post.email|escape:'html'}"{/if} id="email" placeholder="Email" class="form-control" required maxlength="255">
  </div>

  <div class="form-group">
    <label for="password">Password</label>
    <input type="password" name="password" {if isset($smarty.post.password)}value="{$smarty.post.password|escape:'html'}"{/if} id="password" placeholder="Password" class="form-control" required maxlength="255">
  </div>

  <div class="form-group">
    <label for="repeat_password">Repeat password</label>
    <input type="password" name="repeat_password" {if isset($smarty.post.repeat_password)}value="{$smarty.post.repeat_password|escape:'html'}"{/if} id="repeat_password" placeholder="Repeat password" class="form-control" required maxlength="255">
  </div>

  <div class="control-group">
  <div class="controls">   Have an account? <a href="{$settings->site_path}/user/signin" class="signin_caller">Sign In</a>.
  </div>
  </div>

{input_submit name="register" id="register" bootstrap_horizontal=true value="Register"}

</form>

{literal}
<script>
  $(function(){$('#username').focus()});
</script>
{/literal}



{/if}






