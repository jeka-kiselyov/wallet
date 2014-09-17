<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Are you sure that you want to hide {if $item->name|default:''==''}this wallet{else}wallet {$item->name|escape:'html'}{/if}?</h4>
      </div>
      <form method="post" action="{$settings->site_path}/wallets/remove" role="form">
      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

        <p>You will be able to restore it from Trash folder</p>
        <div class="alert alert-danger errors-container" style="display: none;">
        </div>

      </div>
      <div class="modal-footer">

        <div class="form-group">
          <input type="submit" class="btn btn-danger pull-left" value="Yes, Hide It" data-loading-text="Hiding...">
        </div>

      </div>
      </form>
    </form>
  </div>
</div>

