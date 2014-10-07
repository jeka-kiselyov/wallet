<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Add Wallet</h4>
      </div>
      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

      <form method="post" action="{$settings->site_path}/wallets/add" role="form" id="add_wallet_modal_form">
      <fieldset>

        <div class="form-group">
          <label class="sr-only" for="input_name">Name</label>
          <input type="text" name="name" class="form-control" id="input_name" placeholder="Name">
        </div>

        <div class="form-group">
          <label class="sr-only" for="input_name">Currency</label>
          <select name="currency" id="input_currency" class="form-control">
            <option value="">Select Currency</option>
            {foreach from=$settings.currencies item=c key=id}
              <option value="{$id}" {if $id == 'USD'}selected="selected"{/if}>{$c}</option>
            {/foreach}
          </select>
        </div>

        <div class="alert alert-danger errors-container" style="display: none;">
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary pull-left" value="Add" data-loading-text="Saving..." id="add_wallet_modal_form_submit">
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

