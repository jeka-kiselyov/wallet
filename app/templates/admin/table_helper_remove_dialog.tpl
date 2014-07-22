
<!-- Modal -->
<div id="delete_item_modal" class="modal fade" tabindex="-1" role="dialog" 
  aria-labelledby="delete_item_modal_label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="delete_item_modal_label">{t}Are you sure that you want to delete this item?{/t}</h3>
      </div>
      <div class="modal-footer">
        <form method="post">
          <input type="hidden" name="item_id" id="delete_item_modal_item_id">
          <input type="hidden" name="delete" value="delete">
          <button class="btn" data-dismiss="modal" aria-hidden="true">{t}Cancel{/t}</button>
          <input type="submit" value="{t}Yes, delete it{/t}" class="btn btn-danger">
        </form>
      </div>
    </div>
  </div>
</div>
