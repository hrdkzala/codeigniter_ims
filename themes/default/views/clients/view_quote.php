<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= $page_title." ".lang("no")." ".$inv->id; ?></title>
<link rel="shortcut icon" href="<?= $assets; ?>/img/favicon.ico">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="<?= $assets; ?>style/font-awesome.min.css">
<link href="<?= $assets; ?>/style/bootstrap2.css" rel="stylesheet">
<link href="<?= $assets; ?>/style/rwd-table.css" rel="stylesheet">
<link href="<?= $assets; ?>/style/style.css" rel="stylesheet">
<script type="text/javascript" src="<?= $assets; ?>/js/jquery.js"></script>
<style type="text/css">
html, body { height: 100%; padding:0; margin: 0; }
#wrap { padding: 20px; }
td, th { padding: 3px 6px; }
.word_text { text-transform: capitalize; }
</style>
</head>

<body>
<div id="wrap">
<img src="<?= base_url(); ?>uploads/<?= $biller->logo ? $biller->logo : $Settings->logo; ?>" alt="<?= $biller->company ? $biller->company : $Settings->site_name ?>" />
<div class="row-fluid">    
<div class="span6">
    
    <h3><?= $biller->company; ?></h3>
    <?= $biller->address.",<br />".$biller->city.", ".$biller->postal_code.", ".$biller->state.",<br />".$biller->country;

    echo "
    <br />".lang("tel").": ".$biller->phone."<br />".lang("email").": ".$biller->email; 
    
    if($biller->cf1 && $biller->cf1 != "-") { echo "<br />".lang("cf1").": ".$biller->cf1; }
    if($biller->cf2 && $biller->cf2 != "-") { echo "<br />".lang("cf2").": ".$biller->cf2; }
    if($biller->cf3 && $biller->cf3 != "-") { echo "<br />".lang("cf3").": ".$biller->cf3; }
    if($biller->cf4 && $biller->cf4 != "-") { echo "<br />".lang("cf4").": ".$biller->cf4; }
    if($biller->cf5 && $biller->cf5 != "-") { echo "<br />".lang("cf5").": ".$biller->cf5; }
    if($biller->cf6 && $biller->cf6 != "-") { echo "<br />".lang("cf6").": ".$biller->cf6; }

    
    ?>
    
    </div>
  
    <div class="span6">
    
   <?= lang("quoted_to"); ?>:
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
<p>&nbsp;</p>
<div style="clear: both;"></div>
<div class="row-fluid"> 
<div class="span6">     
<h3 class="inv"><?= lang("quote")." ". lang("no") ." ".$inv->id; ?> <a class="no-print" href="<?php echo site_url('clients/pdf_quote/'); ?>?id=<?php echo $inv->id; ?>"><i class="fa fa-download"></i></a></h3>
</div>
<div class="span6">

<p style="font-weight:bold;"><?= lang("reference_no"); ?>: <?= $inv->reference_no; ?></p>

<p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sim->hrsd($inv->date); ?></p>
<?php if($inv->expiry_date && $inv->expiry_date != '0000-00-00') { ?>
<p style="font-weight:bold;"><?= lang("expiry_date"); ?>: <?= $this->sim->hrsd($inv->expiry_date); ?></p>
<?php } ?>    
   </div>
   <p>&nbsp;</p>
   <div style="clear: both;"></div>

    <table class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

    <thead> 

    <tr> 

        <th style="text-align:center; vertical-align:middle;"><?= lang("no"); ?></th> 
        <th style="vertical-align:middle;"><?= lang("description"); ?></th> 
        <?php if($Settings->default_tax_rate) { ?><th style="text-align:center; vertical-align:middle;"><?= lang("tax"); ?></th> <?php } ?>    
        <th style="text-align:center; vertical-align:middle;"><?= lang("quantity"); ?></th>
        <th style="padding-right:20px; text-align:center; vertical-align:middle;"><?= lang("unit_price"); ?></th> 
        <?php if($Settings->default_tax_rate) { ?><th style="padding-right:20px; text-align:center; vertical-align:middle;"><?= lang("tax_value"); ?></th><?php } ?> 
        <th style="padding-right:20px; text-align:center; vertical-align:middle;"><?= lang("subtotal"); ?></th> 
    </tr> 

    </thead> 

    <tbody> 
    
    <?php $r = 1; foreach ($rows as $row):?>
            <tr>
                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                <td style="vertical-align:middle;"><?= $row->product_name; ?></td>
                <?php if($Settings->default_tax_rate) { ?><td style="width: 100px; text-align:center; vertical-align:middle;"><?= $row->tax; ?></td><?php } ?> 
                <td style="width: 100px; text-align:center; vertical-align:middle;"><?= $row->quantity; ?></td>
                <td style="width: 100px; text-align:right; padding-right:20px; vertical-align:middle;"><?= $this->sim->formatMoney($row->unit_price); ?></td>
                <?php if($Settings->default_tax_rate) { ?><td style="width: 100px; text-align:right; padding-right:20px; vertical-align:middle;"><?= $this->sim->formatMoney($row->val_tax); ?></td><?php } ?> 
                <td style="width: 100px; text-align:right; padding-right:20px; vertical-align:middle;"><?= $this->sim->formatMoney($row->gross_total); ?></td> 
            </tr> 
    <?php 
        $r++; 
        endforeach;
    ?>

<?php if($Settings->display_words) { ?>

<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->inv_total != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($this->sim->formatDecimal($inv->inv_total))); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("total"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->inv_total); ?></td>
</tr>
<?php if ($inv->total_tax != 0) { ?>
<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->total_tax != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->total_tax)); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("tax"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_tax); ?></td></tr>
<?php } ?>
<?php if ($inv->shipping != 0) { ?>
<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->shipping != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->shipping)); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("shipping"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->shipping); ?></td></tr>
<?php } ?>

<?php if ($inv->total_discount != 0) { ?>
<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if (($inv->total+$inv->shipping) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal((($inv->total+$inv->shipping+$inv->total_discount)))); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("quote_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping+$inv->total_discount)); ?></td></tr>

<tr>
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if ($inv->total_discount != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal($inv->total_discount)); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("discount").' ('.$inv->discount.')'; ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_discount); ?></td></tr>

<tr class="info">
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if (($inv->total_discount) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal((($inv->total+$inv->shipping)))); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>

<?php } else { ?>
<tr class="info">
<td class="word_text" colspan="<?= $Settings->default_tax_rate ? '4' : '2'; ?>"><?php if (($inv->total+$inv->shipping) != 0) { $exp = explode($Settings->decimals_sep, $this->sim->formatDecimal(($inv->total+$inv->shipping))); echo ($this->mywords->to_words($exp[0]))." ".$Settings->major; if(isset($exp[1]) && $exp[1]!=0) { echo " & ". $this->mywords->to_words($exp[1]) ." ".$Settings->minor; } } ?></td>
<td colspan="2" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>
<?php } ?>

<?php } else { ?>

<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("total"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->inv_total); ?></td>
</tr>
<?php if ($inv->total_tax != 0) { ?>
<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("tax"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_tax); ?></td></tr>
<?php } ?>
<?php if ($inv->shipping != 0) { ?>
<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("shipping"); ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->shipping); ?></td></tr>
<?php } ?>

<?php if ($inv->total_discount != 0) { ?>
<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("quote_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping+$inv->total_discount)); ?></td></tr>

<tr>
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("discount").' ('.$inv->discount.')'; ?> (<?= $Settings->currency_prefix; ?>)</td><td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney($inv->total_discount); ?></td></tr>

<tr class="info">
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>

<?php } else { ?>
<tr class="info">
<td colspan="<?= $Settings->default_tax_rate ? '6' : '4'; ?>" style="text-align:right; padding-right:20px; font-weight:bold;"><?= lang("grand_total"); ?> (<?= $Settings->currency_prefix; ?>)</td>
<td style="text-align:right; padding-right:20px; font-weight:bold;"><?= $this->sim->formatMoney(($inv->total+$inv->shipping)); ?></td></tr>
<?php } ?>


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
</div>
</body>
</html>