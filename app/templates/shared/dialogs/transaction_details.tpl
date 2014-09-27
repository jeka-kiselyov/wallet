<div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="dialog_label">Transaction Details</h4>
      </div>
      <div class="modal-body modal-body-default" style="padding-bottom: 0;">

        <table class="table table-hover table-striped">
          <tr>
            <td><strong>Amount</strong></td>
            <td><strong>${$item->amount|rational}.<sup>{$item->amount|decimal}</sup></strong></td>
          </tr>
          <tr>
            <td><strong>Date</strong></td>
            <td>{$item->datetime|date_format}</td>
          </tr>
          <tr>
            <td><strong>Time</strong></td>
            <td>{$item->datetime|date_format:'g:i a'}</td>
          </tr>
          <tr>
            <td><strong>Description</strong></td>
            <td>{$item->description|escape:'html'|default:'&nbsp;'}</td>
          </tr>
        </table>


<!--       <form method="post" action="{$settings->site_path}/wallets/edit" role="form">
      <fieldset>

        <div class="form-group">
          <label class="sr-only" for="input_name">Name</label>
          <input type="text" name="name" class="form-control" id="input_name" placeholder="Name" value="{$item->name|escape:'html'}">
        </div>

        <div class="alert alert-danger errors-container" style="display: none;">
        </div>

        <div class="form-group">
          <input type="submit" class="btn btn-primary pull-left" value="Save" data-loading-text="Saving...">
        </div>

      </fieldset>
      </form> -->

      </div>
      <div class="modal-footer">
        <div class="pull-right">
          <a href="#" class="btn btn-default btn-sm" id="remove_transaction_button"><span class="glyphicon glyphicon-trash"></span>  Remove</a>
        </div>
      </div>
    </form>
  </div>
</div>

