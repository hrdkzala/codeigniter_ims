    <script>
        $(document).ready(function(){
            $('#active').change(function(){
                var v = $(this).val();
                if(v == 1) {
                    $('#api_key').attr('required', 'required');
                } else {
                    $('#api_key').removeAttr('required');
                }
            });
            var v = <?=$mollie->active;?>;
            if(v == 1) {
                $('#api_key').attr('required', 'required');

            } else {
                $('#api_key').removeAttr('required');
            }
        });
    </script>
    
    <div class="page-head">
      <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("enter_info"); ?></span> </h2>
  </div>
  <div class="clearfix"></div>
  <div class="matter">
      <div class="container">

        <?php $attrib = array('role' => 'form', 'id'=>'mollie_form','method'=>'post');
        echo form_open("settings/mollie", $attrib);
        ?>
        <div class="row">            
            <div class="col-md-6">
                <div class="form-group">
                    <?= lang("activate", "active"); ?>
                    <?php
                    $yn = array('1' => 'Yes', '0' => 'No');
                    echo form_dropdown('active', $yn, $mollie->active, 'class="form-control tip" required="required" id="active"');
                    ?>
                </div>

                <div class="form-group">
                    <?= lang("mollie_payment_mode", "mode"); ?>                                        <?php                    $modeList = array('0' => 'Test', '1' => 'Live');
                    
                    echo form_dropdown('mode', $modeList, $mollie->mode, 'class="form-control tip" required="required" id="mode"');                    ?>

                </div>                                 <div class="form-group">                    <?= lang("mollie_api_key", "api_key"); ?>                    <?= form_input('api_key', $mollie->api_key, 'class="form-control tip" id="api_key"'); ?>                </div>
                <div class="form-group">
                    <?= lang("fixed_charges", "fixed_charges"); ?>
                    <?= form_input('fixed_charges', $mollie->fixed_charges, 'class="form-control tip" id="fixed_charges"'); ?>
                    <small class="help-block"><?=lang("fixed_charges_tip");?></small>
                </div>
                <div class="form-group">
                    <?= lang("extra_charges_my", "extra_charges_my"); ?>
                    <?= form_input('extra_charges_my', $mollie->extra_charges_my, 'class="form-control tip" id="extra_charges_my"'); ?>
                    <small class="help-block"><?=lang("extra_charges_my_tip");?></small>
                </div>
                <div class="form-group">
                    <?= lang("extra_charges_others", "extra_charges_other"); ?>
                    <?= form_input('extra_charges_other', $mollie->extra_charges_other, 'class="form-control tip" id="extra_charges"'); ?>
                    <small class="help-block"><?=lang("extra_charges_others_tip");?></small>
                </div>

            </div>
        </div>
        <div style="clear: both; height: 10px;"></div>
        <div class="form-group">
            <?= form_submit('update_settings', lang("update_settings"), 'class="btn btn-primary"'); ?> 
        </div>
    </div>
    <?= form_close(); ?> 
</div>                         
