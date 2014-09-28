<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">
        {if $item->status|default:'active' == 'active'}
          Are you sure that you want to hide {if $item->name|default:''==''}this wallet{else}wallet {$item->name|escape:'html'}{/if}?
        {else}
          Are you sure that you want to remove {if $item->name|default:''==''}this wallet{else}wallet {$item->name|escape:'html'}{/if}?
        {/if}
        </h4>
      </div>
      <form method="post" action="{$settings->site_path}/wallets/remove" role="form">
      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

        {if $item->status|default:'active' == 'active'}
          <p>You will be able to restore it from Trash folder</p>
        {else}
          <p>All wallet data(transactions etc.) will be lost</p>
        {/if}
        <div class="alert alert-danger errors-container" style="display: none;">
        </div>

      </div>
      <div class="modal-footer">

        <div class="form-group">
          <input type="button" class="process_button btn btn-danger pull-left" value="{if $item->status|default:'active' == 'active'}Yes, Hide It{else}Yes, Remove{/if}" data-loading-text="Removing...">
          <input type="submit" class="btn btn-primary pull-left" value="Cancel" data-loading-text="Canceling...">
        </div>

      </div>
      </form>
  </div>
</div>

