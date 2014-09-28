<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Users who have access to this wallet</h4>
      </div>

      <form method="post" role="form">
      <fieldset>

      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

      {if $status == 'loading'}
        <div class="page_loading"></div>
      {else}

        {if $accesses|count == 0}
          <div class="alert alert-warning" role="alert">Only you have access to this wallet</div>  
        {else}
        <div class="table-responsive">
          <table class="table table-hover table-striped">
          {foreach from=$accesses item=a}
            <tr>
              <td style="max-width: 300px; overflow: hidden;">
                <strong title="{$a->to_email|escape}" data-id="{$a->id}" id="emails_with_access_{$a->id}">{$a->to_email|escape}</strong>
              </td>
              <td style="text-align: center;">{if !$a->to_user_id || $a->to_user_id == '0'}<span class="glyphicon glyphicon-user" style="color: #ccc;" title="Not registered yet"></span>{else}<span class="glyphicon glyphicon-user"></span>{/if}</td>
              <td><div class="pull-right"><a href="#" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-trash"></span>  Remove Access</a></div></td>
            </tr>
          {/foreach}
          </table>
        </div>
        {/if}

      {/if}

        <div class="form-group">
          <label class="sr-only" for="input_email">Email</label>
          <input type="email" name="email" class="form-control" id="input_email" placeholder="Email">
        </div>

        <div class="alert alert-danger errors-container" style="display: none;">
        </div>



      <div class="modal-body modal-body-success" style="display: none;">
        <div class="alert alert-info" role="alert">Done. You can <a href="{$settings->site_path}/user/signin">sign in</a> now.</div>
      </div>

      </div>
      <div class="modal-footer">
        <div class="form-group">
          <input type="submit" class="btn btn-primary pull-left" value="Give Access" data-loading-text="Saving...">
        </div>
      </div>
      </fieldset>
      </form>
    </form>
  </div>
</div>

