<h4>Restore password</h4>


{if isset($email_sent) && $email_sent}

<div class="alert alert-success">
 Instructions have been sent to your email address.
</div>

{else}

<form class="form-horizontal" method="post">

  {if isset($form_checker) && !$form_checker->is_good()}
    <div class="alert alert-error">
      {$form_checker->get_errors_as_html()}
    </div>
  {/if}

  {input_text name="email" id="email" caption="Email" placeholder="Email" getvaluefrompost=true bootstrap_horizontal=true}

  {input_submit name="restore" id="restore" bootstrap_horizontal=true value="Restore password"}

</form>

{/if}





