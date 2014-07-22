<h4>Sign In</h4>

<form class="form-horizontal" method="post">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  {input_text name="username" id="username" caption="Username" placeholder="Username" getvaluefrompost=true bootstrap_horizontal=true}
  {input_password name="password" id="password" caption="Password" placeholder="Password" getvaluefrompost=true bootstrap_horizontal=true}


  <div class="control-group">
  <div class="controls">   Forgot your password? <a href="{$settings->site_path}/user/restore">Restore it</a>.
  </div>
  </div>

  {input_submit name="signin" id="signin" bootstrap_horizontal=true value="Sign In"}

</form>

{literal}
<script>
  $(function(){$('#username').focus()});
</script>
{/literal}

<!---

<form method="post" class="stylingform">
{input_text name="email" id="email" caption="Email:" getvaluefrompost=true}
{input_password name="password" id="password" caption="Password:"}


  <div class="jform_element">
   <div class="jform_caption"></div>
   <div class="jform_input_submit">
    <div style="float: left;"><input type="submit" name="signin" value="Sign In" id="signin"></div>

    {if $settings->user_allow_facebook_registration}
    <div style="float: left; padding: 5px; padding-top: 8px;">or</div>
    <div style="float: left;">
       <div id="facebook_connect"><a href="{$settings->site_path}/facebook/connect/">Connect with Facebook</a></div>
    </div>
    {/if}

   <div class="clear"></div>
   </div>
   <div class="clear"></div>
  </div>
</form>


-->






