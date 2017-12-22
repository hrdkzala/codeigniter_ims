<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= $page_title." ".lang("no")." ".$inv->id; ?></title>
<link rel="shortcut icon" href="<?= $assets; ?>img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= $assets; ?>style/font-awesome.min.css">
<link href="<?= $assets; ?>style/bootstrap2.css" rel="stylesheet">
<link href="<?= $assets; ?>style/style.css" rel="stylesheet">
<link href="<?= $assets; ?>style/rwd-table.css" rel="stylesheet">
<script type="text/javascript" src="<?= $assets; ?>js/jquery.js"></script>
<style type="text/css">
html, body { height: 100%; padding:0; margin: 0; }
#wrap { padding: 20px; }
td, th { padding: 3px 6px; }
.word_text { text-transform: capitalize; }
@media print {
    .page-break { height: 40px; }
    .page-break { page-break-before: always; }
}
</style>
</head>

<body>
<img src="<?= $assets; ?>img/<?= $inv->status; ?>.png" alt="<?= $inv->status; ?>" style="float: right; position: absolute; top:0; right: 0;"/>
<div id="wrap">
<img src="<?= base_url(); ?>uploads/<?= $biller->logo ? $biller->logo : $Settings->logo; ?>" alt="<?= $biller->company ? $biller->company : $Settings->site_name ?>" />
<div class="row-fluid">    
<div class="span6">
    
    <h3><?= $biller->company; ?></h3>
    <?= $biller->address.",<br />".$biller->city.", ".$biller->postal_code.", ".$biller->state.",<br />".$biller->country;

    echo "<br />".lang("tel").": ".$biller->phone."<br />".lang("email").": ".$biller->email; 
    
    if($biller->cf1 && $biller->cf1 != "-") { echo "<br />".lang("cf1").": ".$biller->cf1; }
    if($biller->cf2 && $biller->cf2 != "-") { echo "<br />".lang("cf2").": ".$biller->cf2; }
    if($biller->cf3 && $biller->cf3 != "-") { echo "<br />".lang("cf3").": ".$biller->cf3; }
    if($biller->cf4 && $biller->cf4 != "-") { echo "<br />".lang("cf4").": ".$biller->cf4; }
    if($biller->cf5 && $biller->cf5 != "-") { echo "<br />".lang("cf5").": ".$biller->cf5; }
    if($biller->cf6 && $biller->cf6 != "-") { echo "<br />".lang("cf6").": ".$biller->cf6; }
    
    ?>
    
    </div>
  
    <div class="span6">
    
   <?= lang("billed_to"); ?>:
   <h3><?php if($customer->company != "-") { echo $customer->company; } else { echo $customer->name; } ?></h3>
   <?php if($customer->company != "-") { echo "<p>Attn: ".$customer->name."</p>"; } ?>
   
   <?php if($customer->address != "-") { echo  lang("address").": ".$customer->address.", ".$customer->city.", ".$customer->postal_code.", ".$customer->state.", ".$customer->country; } ?><br>
   <?= lang("tel").": ".$customer->phone; ?><br>
   <?= lang("email").": ".$customer->email; ?><br>
   <?php
   if($customer->cf1 && $customer->cf1 != "-") { echo "<br />".lang("cf1").": ".$customer->cf1; }
    if($customer->cf2 && $customer->cf2 != "-") { echo "<br />".lang("cf2").": ".$customer->cf2; }
    if($customer->cf3 && $customer->cf3 != "-") { echo "<br />".lang("cf3").": ".$customer->cf3; }
    if($customer->cf4 && $customer->cf4 != "-") { echo "<br />".lang("cf4").": ".$customer->cf4; }
    if($customer->cf5 && $customer->cf5 != "-") { echo "<br />".lang("cf5").": ".$customer->cf5; }
    if($customer->cf6 && $customer->cf6 != "-") { echo "<br />".lang("cf6").": ".$customer->cf6; }
   ?>

    </div> 
</div>
<div style="clear: both;"></div>
<p>&nbsp;</p>
<div class="row-fluid"> 
<div class="span6">     
<h3 class="inv"><?= lang("invoice")." ". lang("no") ." ".$inv->id; ?> <a class="no-print" href="<?php echo site_url('clients/pdf/'); ?>?id=<?php echo $inv->id; ?>"><i class="fa fa-download"></i></a></h3>
</div>
<div class="span6">

<p style="font-weight:bold;"><?= lang("reference_no"); ?>: <?= $inv->reference_no; ?></p>

<p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sim->hrsd($inv->date); ?></p>
    
   </div>
   <p>&nbsp;</p>
 <div style="clear: both;"></div>   

    <table class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

    <thead> 

    <tr> 

        <th style="text-align:center; vertical-align:middle;"><?= lang("no"); ?></th> 
        <th style="vertical-align:middle;"><?= lang("description"); ?></th> 
        <?php if($Settings->default_tax_rate) { ?><th style="text-align:center; vertical-align:middle;"><?= lang("tax"); ?></th><?php } ?> 
        <th style="text-align:center; vertical-align:middle;"><?= lang("quantity"); ?></th>
        <th style="text-align:center; vertical-align:middle;"><?= lang("unit_price"); ?></th> 
        <?php if($Settings->default_tax_rate) { ?><th style="text-align:center; vertical-align:middle;"><?= lang("tax_value"); ?></th><?php } ?> 
        <th style="text-align:center; vertical-align:middle;"><?= lang("subtotal"); ?></th> 
    </tr> 

    </thead> 

    <tbody> 
    
    <?php $r = 1; foreach ($rows as $row):?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                <td style="vertical-align:middle;"><?= $row->product_name; ?></td>
                <?php if($Settings->default_tax_rate) { ?><td style="width: 100px; text-align:center; vertical-align:middle;"><?= $row->tax; ?></td><?php } ?>
                <td style="width: 100px; text-align:center; vertical-align:middle;"><?= $row->quantity; ?></td>
                <td style="width: 100px; text-align:right; vertical-align:middle;"><?= $this->sim->formatMoney($row->unit_price); ?></td>
                <?php if($Settings->default_tax_rate) { ?><td style="width: 100px; text-align:right; vertical-align:middle;"><?= $this->sim->formatMoney($row->val_tax); ?></td><?php } ?> 
                <td style="width: 100px; text-align:right; vertical-align:middle;"><?= $this->sim->formatMoney($row->gross_total); ?></td> 
            </tr> 
    <?php 
        $r++; 
        endforeach;
    ?>
    
<tr><td colspan="<?= $Settings->default_tax_rate ? '7' : '5'; ?>">&nbsp;</td></tr>     

<?php if($Settings->display_words) { ?>

<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->inv_total != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->inv_total)); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("total"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->inv_total); ?></td>
</tr>
<?php if ($inv->total_tax != 0) { ?>
<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->total_tax != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->total_tax)); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("tax"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_tax); ?></td></tr>
<?php } ?>
<?php if ($inv->shipping != 0) { ?>
<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->shipping != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->shipping)); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("shipping"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->shipping); ?></td></tr>
<?php } ?>

<?php if ($inv->total_discount != 0) { ?>
<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if (($inv->total+$inv->shipping) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal((($inv->total+$inv->shipping+$inv->total_discount)))); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("invoice_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping+$inv->total_discount)); ?></td></tr>

<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->total_discount != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->total_discount)); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("discount").' ('.$inv->discount.')'; ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_discount); ?></td></tr>

<tr class="info">
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if (($inv->total_discount) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal((($inv->total+$inv->shipping)))); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>

<?php } else { ?>
<tr class="info">
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if (($inv->total+$inv->shipping) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal((($inv->total+$inv->shipping)))); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>
<?php } ?>
<tr class="success">
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($paid != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($paid)); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("paid"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($paid); ?></td></tr>

<tr class="warning">
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ((($inv->total - $paid)+$inv->shipping) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal(((($inv->total - $paid) + $inv->shipping)))); echo ucfirst($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; font-weight:bold;"><?= lang("balance"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney((($inv->total - $paid) + $inv->shipping)); ?></td></tr>

<?php } else { ?>

<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("total"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->inv_total); ?></td>
</tr>
<?php if ($inv->total_tax != 0) { ?>
<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("tax"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_tax); ?></td></tr>
<?php } ?>

<?php if ($inv->shipping != 0) { ?>
<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("shipping"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->shipping); ?></td></tr>
<?php } ?>

<?php if ($inv->total_discount != 0) { ?>
<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("invoice_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping+$inv->total_discount)); ?></td></tr>

<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("discount").' ('.$inv->discount.')'; ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_discount); ?></td></tr>

<tr class="info">
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>

<?php } else { ?>
<tr class="info">
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>
<?php } ?>
<tr class="success">
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("paid"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney($paid); ?></td></tr>

<tr class="warning">
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; font-weight:bold;"><?= lang("balance"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; font-weight:bold;"><?= $this->sim->formatMoney((($inv->total - $paid) + $inv->shipping)); ?></td></tr>

<?php } ?>
    </tbody> 

    </table> 
<div style="clear: both;"></div>
<div class="row-fluid"> 
<div class="span12">        
    <?php if($inv->note && $inv->note != "<br>" && $inv->note != " " && $inv->note != "<p></p>" ) { ?>
    <p>&nbsp;</p>
    <p><span style="font-weight:bold; font-size:14px; margin-bottom:5px;"><?= lang("note"); ?>:</span></p>
    <p><?= $inv->note; ?></p>
    
    <?php } ?>
</div>

<div style="clear: both;"></div>
<div class="span4 pull-left"> 
<?php if($biller->ss_image) { ?>
<img src="<?= base_url('uploads/'.$biller->ss_image); ?>" alt="" />
<?php } else { ?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php } ?>
<p style="border-bottom: 1px solid #666;">&nbsp;</p>
<p><?= lang("signature")." &amp; ".lang("stamp"); ; ?></p>
</div>

<div class="span4 pull-right"> 
<p>&nbsp;</p>
<p><?= lang("buyer"); ?>: <?php if($customer->company != "-") { echo $customer->company; } else { echo $customer->name; } ?> </p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p style="border-bottom: 1px solid #666;">&nbsp;</p>
<p><?= lang("signature")." &amp; ".lang("stamp"); ; ?></p>
</div>
<div class="clearfix"></div>
</div>
</div>
<div class="no-print">
<div class="well well-sm">
    <?php $grand_total = ($inv->total - $paid) + $inv->shipping;
    if($inv->status != 'paid') { ?>
    <div id="payment_buttons" class="text-center margin010">

        <?php if($paypal->active == "1" && $grand_total != "0.00" ){ 
            if(trim(strtolower($customer->country)) == $biller->country) {
                $paypal_fee = $paypal->fixed_charges+($grand_total*$paypal->extra_charges_my/100);
            } else {
                $paypal_fee = $paypal->fixed_charges+($grand_total*$paypal->extra_charges_other/100);
            }
            ?>
            
            <div style="width:150px;margin-right:10px;display:inline-block;">
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                    <input type="hidden" name="cmd" value="_xclick">
                    <input type="hidden" name="business" value="<?php echo  $paypal->account_email; ?>">
                    <input type="hidden" name="item_name" value="<?php echo  $inv->reference_no; ?>">
                    <input type="hidden" name="item_number" value="<?php echo  $inv->id; ?>">
                    <input type="hidden" name="image_url" value="<?php echo  base_url() . 'uploads/logos/' . ($biller->logo ? $biller->logo : $Settings->logo); ?>">
                    <input type="hidden" name="amount" value="<?php echo  $grand_total+$paypal_fee; ?>">
                    <input type="hidden" name="no_shipping" value="1">
                    <input type="hidden" name="no_note" value="1">
                    <input type="hidden" name="currency_code" value="<?php echo $Settings->currency_prefix; ?>">
                    <input type="hidden" name="bn" value="FC-BuyNow">
                    <input type="image"  id="no-pdf" src="<?= base_url('uploads/btn-paypal.png'); ?>" name="submit" alt="Pay by PayPal" style="border:0 !important;">
                    <input type="hidden" name="rm" value="2">
                    <input type="hidden" name="return" value="<?php echo  site_url('clients/view_invoice?id='.$inv->id); ?>"> 
                    <input type="hidden" name="cancel_return" value="<?php echo  site_url('clients/view_invoice?id='.$inv->id); ?>">
                    <input type="hidden" name="notify_url" value="<?php echo  site_url('payments/paypalipn');?>" /> 
                    <input type="hidden" name="custom" value="<?php echo  $inv->reference_no.'__'.$grand_total.'__'.$paypal_fee; ?>">     
                </form>
            </div>
            <?php  } ?>

            <?php if($skrill->active == "1" && $grand_total != "0.00" ){ 
                if(trim(strtolower($customer->country)) == $biller->country) {
                    $skrill_fee = $skrill->fixed_charges+($grand_total*$skrill->extra_charges_my/100);
                } else {
                    $skrill_fee = $skrill->fixed_charges+($grand_total*$skrill->extra_charges_other/100);
                }
                ?>
                <div style="width:170px;margin-left:10px;display:inline-block;">
                    <form action="https://www.moneybookers.com/app/payment.pl" method="post">
                        <input type="hidden" name="pay_to_email" value="<?php echo $skrill->account_email; ?>">
                        <input type="hidden" name="status_url" value="<?php echo site_url('payments/skrillipn');?>">
                        <input type="hidden" name="cancel_url" value="<?php echo site_url('clients/view_invoice?id='.$inv->id); ?>">
                        <input type="hidden" name="return_url" value="<?php echo site_url('clients/view_invoice?id='.$inv->id); ?>"> 
                        <input type="hidden" name="language" value="EN">
                        <input type="hidden" name="ondemand_note" value="<?php echo  $inv->reference_no; ?>">
                        <input type="hidden" name="merchant_fields" value="item_name,item_number">
                        <input type="hidden" name="item_name" value="<?php echo $inv->reference_no; ?>">
                        <input type="hidden" name="item_number" value="<?php echo $inv->id; ?>">
                        <input type="hidden" name="amount" value="<?php echo $grand_total+$skrill_fee; ?>">
                        <input type="hidden" name="currency" value="<?php echo $Settings->currency_prefix; ?>">
                        <input type="hidden" name="detail1_description" value="<?php echo  $inv->reference_no; ?>">
                        <input type="hidden" name="detail1_text" value="Payment for the sale invoice <?php echo $inv->reference_no . ': '.$grand_total.'(+ fee: '.$skrill_fee.') = '.$grand_total+$skrill_fee; ?>">
                        <input type="hidden" name="logo_url" value="<?php echo base_url() . 'uploads/logos/' . ($biller->logo ? $biller->logo : $Settings->logo); ?>">
                        <input type="image" id="no-pdf" src="<?= base_url('uploads/btn-skrill.png'); ?>" name="submit" alt="Pay by Skrill" style="border:0 !important;">
                    </form>
                </div>
                <?php  } ?>
                <div class="clearfix"></div>
            </div>
            <?php } ?>
            </div>
        </div>
<?php if($Settings->print_payment) { if(!empty($payment)) { ?>
<div class="page-break"></div>
<h4><?= lang("payment_details"); ?> (<?= $page_title." ".lang("no")." ".$inv->id; ?>)</h4>
<table class="table table-bordered table-condensed table-hover table-striped" style="margin-bottom: 5px;">

    <thead> 
    <tr> 
        <th><?= lang("date"); ?></th> 
        <th><?= lang("amount"); ?></th> 
        <th><?= lang("note"); ?></th> 
    </tr> 
    </thead> 
    <tbody>
    <?php foreach ($payment as $p) { ?>
    <tr> 
        <td><?= $this->sim->hrsd($p->date); ?></td> 
        <td><?= $this->sim->formatMoney($p->amount); ?></td> 
        <td><?= $p->note; ?></td> 
    </tr> 
    <?php } ?>
    </tbody>
</table>    
   <?php } else { //echo "<p>".lang("no_payment")."</p>"; 
   } }?> 
   
</body>
</html>