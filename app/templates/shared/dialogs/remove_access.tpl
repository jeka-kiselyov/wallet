<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">
          Are you sure that you want to remove access for email {$access->to_email|escape} from {if $item->name|default:''==''}this wallet{else}wallet "{$item->name|escape:'html'}"{/if}?
        </h4>
      </div>
      <form method="post"  role="form">
      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

        <div class="alert alert-danger errors-container" style="display: none;">
        </div>

      </div>
      <div class="modal-footer">

        <div class="form-group">
          <input type="button" class="process_button btn btn-danger pull-left" value="Yes, Remove" data-loading-text="Removing...">
          <input type="submit" class="btn btn-primary pull-left" value="Cancel" data-loading-text="Canceling...">
        </div>

      </div>
      </form>
    </form>
  </div>
</div>

