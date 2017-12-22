<style>
    .ui-autocomplete { width:20%; list-style: none; padding:0px; border: 1px solid #ccc; background:#FFF; box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset; /*z-index:500; */}
    .ui-autocomplete a, .ui-autocomplete li {display: block; border-radius: 0; padding: 2px 5px;}
    .ui-autocomplete a:hover, .ui-autocomplete a:active, .ui-autocomplete li:hover, .ui-autocomplete a:focus, .ui-autocomplete .ui-corner-all:focus { background: #444; color:#ccc; text-decoration: none; }
    .ui-helper-hidden-accessible {padding-left: 10px; }
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { text-decoration: none; }
    .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus { background: #444; border:0; color:#ccc; text-decoration: none; }
    span.ui-helper-hidden-accessible { display: none; }
    .ui-autocomplete-loading { background:url('<?= $assets; ?>img/loading.gif') no-repeat right center }
</style>

<script type="text/javascript">

    $(document).ready(function(){

        var availableProducts = [<?php if(!empty($products)) { foreach($products as $product) { echo '"'.addslashes($product->name).'",'; } } ?>],
            tax_rates = <?=json_encode($tax_rates)?>;
        $(document).on('change', '.price', function() {
            calculateTotal();
        });
        $(document).on('change', '.tax', function() {
            calculateTotal();
        });
        $(document).on('change', '.quantity', function() {
            calculateTotal();
        });
        calculateTotal();
        function calculateTotal() {
            var total = 0, pins = $('.price');
            $.each(pins, function(){
                if($(this).val()) {
                    var id = $(this).attr('id');
                    var rid = id.split('-');
                    var row_id = rid[1];
                    var pr_tax = 0;
                    var pr_qty = parseFloat($('#quantity' + row_id).val() ? $('#quantity' + row_id).val() : 0);
                    var tax = $('#tax_rate' + row_id).val();
                    var pr_price = parseFloat($(this).val());
                    <?php if($Settings->default_tax_rate) { ?>
                    $.each(tax_rates, function () {
                        if (this.id == tax) {
                            if (this.type == 1 && this.rate != 0) {
                                pr_tax = parseFloat((pr_price * this.rate) / 100);
                            } else {
                                pr_tax = parseFloat(this.rate);
                            }
                        }
                    });
                    <?php } ?>
                    total += (pr_price + pr_tax) * pr_qty;
                }
            });
            $('#total_amount').text(formatMoney(total));
        }

        $("form select").chosen({no_results_text: "No results matched", disable_search_threshold: 5, allow_single_deselect:true});

        var counter = <?= $Settings->no_of_rows+1; ?>;

        $("#addButton").click(function () {

            if(counter><?= $Settings->total_rows; ?>){
                alert("<?= lang("not_allowed"); ?>");
                return false;
            }

            /*var newTr = $(document.createElement('tr'))
             .attr("id", 'line' + counter);*/

            var newTr = $('<tr></tr>').attr("id", 'line' + counter);

            newTr.html('<td style="width: 20px; text-align: center; padding-right: 10px;">'+ counter +'</td><td><input type="text" class="quantity form-control" name="quantity' + counter +
            '" id="quantity' + counter + '" value="" style="min-width: 70px; text-align: center;" /></td><td><input type="text" name="product' + counter +
            '" id="product' + counter + '" value="" class="form-control" /></td><?php if($Settings->default_tax_rate) { ?><td><select class="tax form-control" style="min-width: 100px;" name="tax_rate' + counter + '" id="tax_rate' + counter + '"><?php
		foreach($tax_rates as $tax) {
			echo "<option value=" . $tax->id;
			if($tax->id == $Settings->default_tax_rate) { echo ' selected="selected"'; }
			echo ">" . $tax->name . "</option>";
		}
		?></select></td><?php } ?><td><input type="text" name="unit_price' + counter +
            '" id="price-' + counter + '" value="" class="price form-control text-right" style="min-width: 100px;"></td>');

            newTr.appendTo("#dyTable");

            counter++;
            $("form select").chosen({no_results_text: "No results matched", disable_search_threshold: 5, allow_single_deselect:true});

            $('input[id^="product"]').blur(function(event, data, formatted) {

                var len=$(this).attr('id').length;
                var v = $(this).val();

                var q='#quantity'+$(this).attr('id').substr(len-2);
                if($(q).val().length == 0 && v.length != 0 ){
                    $(q).val(1).change();
                }

            });

            $( 'input[id^="product"]' ).autocomplete({
                source: availableProducts,
                select: function( event, ui ) {
                    var pr = ui.item ? ui.item.value : this.value;
                    var pid = $(this).attr('id');
                    rw = pid.substr(pid.length-2);
                    $.ajax({
                        type: "get",
                        async: false,
                        url: "<?= site_url('sales/pr_details'); ?>",
                        data: { name: pr, <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash() ?>' },
                dataType: "json",
                success: function(data) {
                    price = data.price;
                    tax_rate = data.tax_rate;
                },
                error: function(){
                    alert('<?= lang('ajax_error'); ?>');
                    return false;
                }
            });
            $('#price-'+rw).val(price);
            $('#tax_rate'+rw).val(tax_rate).trigger("liszt:updated");
            if($('#quantity'+rw).val().length == 0){
                $('#quantity'+rw).val(1).change();
            }
        }
    });

    });

    $("#removeButton").click(function () {
        if(counter==<?= $Settings->no_of_rows+1; ?>){
            alert("<?= lang("not_allowed"); ?>");
            return false;
        }

        counter--;

        $("#line" + counter).remove();

    });

    $('input[id^="product"]').blur(function(event, data, formatted) {

        var len=$(this).attr('id').length;
        var v = $(this).val();

        var q='#quantity'+$(this).attr('id').substr(len-2);
        if($(q).val().length == 0 && v.length != 0 ){
            $(q).val(1).change();
        }

    });

    $( "#date, #expiry_date" ).datepicker({
        dateFormat: "<?= $dateFormats['js_sdate']; ?>",
        autoclose: true
    });
    $( "#date" ).datepicker("setDate", new Date());

    $( "#customer" ).change(function () {
        if($(this).val() == 'new') {
            $('#customerForm').slideDown('100');
        } else {
            $('#customerForm').slideUp('100');
        }
    });

    $( 'input[id^="product"]' ).autocomplete({
        source: availableProducts,
        select: function( event, ui ) {
            var pr = ui.item ? ui.item.value : this.value;
            var pid = $(this).attr('id');
            rw = pid.substr(pid.length-2);
            $.ajax({
                type: "get",
                async: false,
                url: "<?= site_url('sales/pr_details'); ?>",
                data: { name: pr, <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash() ?>' },
        dataType: "json",
        success: function(data) {
            price = data.price;
            tax_rate = data.tax_rate;
        },
        error: function(){
            alert('<?= lang('ajax_error'); ?>');
            return false;
        }
    });
    $('#price-'+rw).val(price);
    $('#tax_rate'+rw).val(tax_rate).trigger("liszt:updated");
    if($('#quantity'+rw).val().length == 0){
        $('#quantity'+rw).val(1).change();
    }
    }
    });

    });
</script>
<?php if($message) { echo "<div class=\"alert alert-danger\"><button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button>" . $message . "</div>"; } ?>

<div class="page-head">
    <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("enter_info"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
    <div class="container">
        <?php $attrib = array('class' => 'form-horizontal'); echo form_open("sales/add_quote");?>
        <div class="row">
            <div class="col-md-5">
                <div class="form-group">
                    <label for="billing_company"><?= lang("billing_company"); ?></label>
                    <div class="controls">
                        <?php
                        $bc[""] = lang("select")." ".lang("billing_company");
                        foreach($companies as $company){
                            $bu[$company->id] = $company->company;
                        }
                        echo form_dropdown('billing_company', $bu, (isset($_POST['billing_company']) ? $_POST['billing_company'] : ""), 'class="billing_company form-control" data-placeholder="'.lang("select")." ".lang("billing_company").'" id="billing_company"');  ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="reference_no"><?= lang("reference_no"); ?></label>
                    <div class="input-group">
                        <?= form_input($reference_no, (isset($_POST['reference_no']) ? $_POST['reference_no'] : ""), 'class="form-control" id="reference_no"');?>
                        <span class="input-group-addon" id="gen_ref" style="cursor: pointer;"><i class="fa fa-random"></i></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="date"><?= lang("date"); ?></label>
                    <div class="controls"> <?= form_input($date, (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control" id="date"');?> </div>
                </div>
                <div class="form-group">
                    <label for="expiry_date"><?= lang("expiry_date"); ?></label>
                    <div class="controls"> <?= form_input('expiry_date', (isset($_POST['expiry_date']) ? $_POST['expiry_date'] : ""), 'class="form-control" id="expiry_date"');?> </div>
                </div>
            </div>

            <div class="col-md-5 col-md-offset-1">
                <div class="form-group">
                    <label for="customer"><?= lang("customer"); ?></label>
                    <div class="controls">
                        <?php
                        $cu[""] = lang("select")." ".lang("customer");
                        $cu["new"] = lang("new_customer");
                        foreach($customers as $customer){
                            $cu[$customer->id] = $customer->company && trim($customer->company) != '-' ? $customer->company .' ('.$customer->name.')' : $customer->name;
                        }
                        echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="customer form-control" data-placeholder="'.lang("select")." ".lang("customer").'" id="customer"');  ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount"><?= lang("discount_lable"); ?></label>
                    <div class="controls"> <?= form_input('discount', (isset($_POST['discount']) ? $_POST['discount'] : ""), 'class="form-control" id="discount"');?> </div>
                </div>
                <div class="form-group">
                    <label for="shipping"><?= lang("shipping"); ?></label>
                    <div class="controls"> <?= form_input('shipping', (isset($_POST['shipping']) ? $_POST['shipping'] : ""), 'class="form-control" id="shipping" ');?> </div>
                </div>
                <div class="form-group">
                    <label for="customer"><?= lang("status"); ?></label>
                    <div class="controls">
                        <?php
                        $st = array(
                            '' 		=> lang("select")." ".lang("status"),
                            'canceled' => lang('canceled'),
                            'ordered'	=> lang('ordered'),
                            'pending'	=> lang('pending'),
                            'sent'	=> lang('sent')
                        );

                        echo form_dropdown('status', $st, (isset($_POST['status']) ? $_POST['status'] : ""), 'class="status form-control" data-placeholder="'.lang("select")." ".lang("status").'" id="status"');  ?>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div class="row" id="customerForm" style="display:none;">
                <div class="well well-sm">
                    <div class="clearfix"></div>
                    <h3><?= lang('new_customer'); ?></h3>
                    <div class="clearfix"></div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="company"><?= lang("company"); ?></label>
                            <div class="controls"> <?= form_input('company', (isset($_POST['company']) ? $_POST['company'] : ""), 'class="form-control" id="company" ');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name"><?= lang("contact_person"); ?></label>
                            <div class="controls"> <?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ""), 'class="form-control" id="name"');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="phone"><?= lang("phone"); ?></label>
                            <div class="controls"> <?= form_input('phone', (isset($_POST['phone']) ? $_POST['phone'] : ""), 'class="form-control" id="phone"');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="email_address"><?= lang("email_address"); ?></label>
                            <div class="controls"> <?= form_input('email', (isset($_POST['email']) ? $_POST['email'] : ""), 'class="form-control" id="email_address"');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="address"><?= lang("address"); ?></label>
                            <div class="controls"> <?= form_input('address', (isset($_POST['address']) ? $_POST['address'] : ""), 'class="form-control" id="address" ');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="city"><?= lang("city"); ?></label>
                            <div class="controls"> <?= form_input('city', (isset($_POST['city']) ? $_POST['city'] : ""), 'class="form-control" id="city" ');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="state"><?= lang("state"); ?></label>
                            <div class="controls"> <?= form_input('state', (isset($_POST['state']) ? $_POST['state'] : ""), 'class="form-control" id="state" ');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="postal_code"><?= lang("postal_code"); ?></label>
                            <div class="controls"> <?= form_input('postal_code', (isset($_POST['postal_code']) ? $_POST['postal_code'] : ""), 'class="form-control" id="postal_code" ');?> </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="country"><?= lang("country"); ?></label>
                            <div class="controls"> <?= form_input('country', (isset($_POST['country']) ? $_POST['country'] : ""), 'class="form-control" id="country" ');?> </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

        <table id="dyTable" class="table table-striped" style="margin-bottom:5px;">
            <thead>
            <tr class="active">
                <th class="text-center"><?= lang("no"); ?></th>
                <th class="col-sm-2 text-center"><?= lang("quantity"); ?></th>
                <th class="text-center"><?= lang("product_code"); ?></th>
                <?php if($Settings->default_tax_rate) { ?><th class="col-sm-2 text-center"><?= lang("tax_rate"); ?></th><?php } ?>
                <th class="col-sm-2 text-center"><?= lang("unit_price"); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            $quantity = "quantity0";
            $product = "product0";
            $tax_rate = "tax_rate0";
            $unit_price = "unit_price0";
            /*$sp[0] = "";
                     foreach($products as $product){
                      $sp[$product->id] = $product->code;
                  }*/

            foreach($tax_rates as $tax){
                $tr[$tax->id] = $tax->name;
            }

            for($r=1; $r<=$Settings->no_of_rows; $r++) {

                if(isset($_POST['submit'])) {
                    if(isset($_POST['quantity'.$r])) { $qt_value = $_POST['quantity'.$r]; } else { $qt_value = ""; }
                    if(isset($_POST['product'.$r])) { $pr_value = $_POST['product'.$r]; } else { $pr_value = "";  }
                    if(isset($_POST['tax_rate'.$r])) { $tr_value = $_POST['tax_rate'.$r]; } else { $tr_value = $Settings->default_tax_rate;  }
                    if(isset($_POST['unit_price'.$r])) { $price_value = $_POST['unit_price'.$r]; } else { $price_value = "";  }

                } else {
                    $qt_value = "";
                    $pr_value = "";
                    $tr_value = $Settings->default_tax_rate;
                    $price_value = "";
                }

                ?>
                <tr id="line<?= $r; ?>">
                    <td style="width: 20px; text-align: center; padding-right: 10px; padding-right: 10px;"><?= $r; ?></td>
                    <td><?= form_input('quantity'.$r, $qt_value, 'id="quantity0'.$r.'" class="quantity form-control text-center input-sm" style="min-width: 70px;"');?></td>
                    <td><?= form_input('product'.$r, $pr_value, 'id="product0'.$r.'" class="form-control input-sm" tyle="min-width:270px;"');
                        /*echo form_dropdown('product'.$r, $sp, '', 'id="product0'.$r.'" class="chzn-select" data-placeholder="Choose a Product" style="width:270px;"'); */ ?></td>
                    <?php if($Settings->default_tax_rate) { ?><td><?php
                        echo form_dropdown('tax_rate'.$r, $tr, $tr_value, 'id="tax_rate0'.$r.'" class="tax form-control input-sm" style="min-width: 100px;"');  ?></td><?php } ?>
                    <td><?= form_input('unit_price'.$r, $price_value, 'id="price-0'.$r.'" class="price form-control text-right input-sm" style="min-width: 100px;"'); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-primary" id='addButton'><i class="fa fa-plus"></i></button>
        <button type="button" class="btn btn-danger" id='removeButton'><i class="fa fa-minus"></i></button>
        <div class="col-xs-12 col-sm-4 pull-right" style="position:fixed; bottom:-20px; right:0;">
            <div class="well well-sm bold">
                <h4 style="margin: 0;">Total <span class="pull-right" id="total_amount">0.00</span></h4>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group"> <?= form_textarea($note, (isset($_POST['note']) ? $_POST['note'] : ""), 'class="input-block-level" placeholder="'.lang("add_note").'" rows="3" style="margin-top: 10px; height: 100px;"');?> </div>
        <div class="form-group"> <?= form_submit('submit', lang("add_quote"), 'class="btn btn-primary btn-large"');?> </div>
        <?= form_close();?>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>
