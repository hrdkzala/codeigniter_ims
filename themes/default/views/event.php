
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('day_event')." (".$this->sim->hrsd($date).")"; ?></h4>
        </div>

        <div class="modal-body">
            <!--<p><?= lang('list_results'); ?></p>-->

            <?= $event ? str_replace('|', '<br>', $event->data) : lang('no_event'); ?>

            </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('close')?></button>
      </div>
</div>
