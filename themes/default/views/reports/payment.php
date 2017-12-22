<?php
$v = "?v=1";
if($this->input->post('submit')) {
    if($this->input->post('customer')){
      $v .= "&customer=".$this->input->post('customer');
  } 
  if($this->input->post('cf')){
    $v .= "&cf=".$this->input->post('cf');
  } 
  if($this->input->post('start_date')){
      $v .= "&start_date=".$this->input->post('start_date');
  }
  if($this->input->post('end_date')) {
     $v .= "&end_date=".$this->input->post('end_date');
 }
 if($this->input->post('note')){
  $v .= "&note=".$this->input->post('note');
}

}
?>
<script src="<?= $assets; ?>media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<link href="<?= $assets; ?>style/chosen.css" rel="stylesheet">
<link href="<?= $assets; ?>style/chosen-bootstrap.css" rel="stylesheet">
<style type="text/css">
    .mainbar { min-height:768px; }
    .table thead th { text-align:center; font-weight:bold; }
    .table td { width: 12%; }
    .table td:last-child { width: 40%; }
    .table td:nth-child(2) { text-align: center; }
    .table tfoot th:nth-child(5), .table td:nth-child(5) { text-align: right; }
    .table tfoot th:nth-child(5) { font-weight:bold; }
    .today-datas li {
      width: 16%;
      min-width:130px;
      margin-right:0.5%;
      margin-bottom:10px;
      height:90px;
      vertical-align:middle;
      display: inline-table;
      text-align:center;
      text-transform:uppercase;
  }
  .today-datas li:last-child, .t li:last-child { margin-right:0; }
  .t li { width:32.7%; min-width:150px; }

</style>
<script type="text/javascript" src="<?= $assets; ?>js/chosen.jquery.js"></script>
<script type="text/javascript">

    $(document).ready(function(){

	//$("form select").chosen({no_results_text: "No results matched", disable_search_threshold: 5, allow_single_deselect:true});
	
	$( "#start_date" ).datepicker({
       dateFormat: "<?= $dateFormats['js_sdate']; ?>",
       autoclose: true
   });

  $( "#end_date" ).datepicker({
   dateFormat: "<?= $dateFormats['js_sdate']; ?>",
   autoclose: true
});
  $( "#end_date" ).datepicker("setDate", new Date());

  <?php if($this->input->post('submit')) { echo "$('.form').hide();"; } ?>
  $(".show_hide").slideDown('slow');

  $('.show_hide').click(function(){
     $(".form").slideToggle();
     return false;
 });

$('#fileData').dataTable( {
   "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
   "aaSorting": [[ 0, "desc" ]],
   "iDisplayLength": <?= $Settings->rows_per_page; ?>,
   'bProcessing'    : true,
   'bServerSide'    : true,
   'sAjaxSource'    : '<?= site_url('reports/getpayments/'.$v); ?>',
   'fnServerData': function(sSource, aoData, fnCallback) {
      aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
      $.ajax({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
  },
  "oTableTools": {
      "sSwfPath": "<?= $assets; ?>media/swf/copy_csv_xls_pdf.swf",
      "aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ]
  },
  "aoColumns": [ { "mRender": fsd }, null, null, null, { "mRender": currencyFormat }, null ],
  "fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
     var rTotal = 0, pTotal = 0, bTotal = 0;
     for ( var i=0 ; i<aaData.length ; i++ ) {
        rTotal += aaData[ aiDisplay[i] ][4]*1;
    }
    var nCells = nRow.getElementsByTagName('th');
    nCells[4].innerHTML = currencyFormat(rTotal);
}

}).columnFilter({ aoColumns: [
 { type: "text", bRegex:true },
 { type: "text", bRegex:true },
 { type: "text", bRegex:true },
 { type: "text", bRegex:true },
 { type: "text", bRegex:true },
 { type: "text", bRegex:true }
 ]});

});

</script>

<div class="page-head">
  <h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><a href="#" class="btn btn-primary btn-xs show_hide"><?= lang("show_hide"); ?></a></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
  <div class="container">
    <div class="form">

        <p>Please customise the report below.</p>
        <?php $attrib = array('class' => 'form-horizontal'); echo form_open("reports/payments"); ?>

    <div class="row">
        <div class="col-md-6">
              <div class="form-group">
                <label for="customer"><?= lang("customer"); ?></label>
                <div class="controls">
                  <?php 
                  $cu[""] = lang("select")." ".lang("customer");
                  foreach($customers as $customer){
                      $cu[$customer->id] = $customer->company .' ('.$customer->name.')';
                  }
                  echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control customer" data-placeholder="'.lang("select")." ".lang("customer").'" id="customer"');  ?>
              </div>
          </div>
          <div class="form-group">
              <?= lang('cfs', 'cf'); ?>
              <?= form_input('cf', set_value('cf'), 'class="form-control tip" id="cf"'); ?>
          </div>
            
      </div>

      <div class="col-md-6">
          <div class="form-group">
            <label for="start_date"><?= lang("start_date"); ?></label>
            <div class="controls"> <?= form_input('start_date', (isset($_POST['start_date']) ? $_POST['start_date'] : ""), 'class="form-control" id="start_date"');?> </div>
        </div>
        <div class="form-group">
          <label for="end_date"><?= lang("end_date"); ?></label>
          <div class="controls"> <?= form_input('end_date', (isset($_POST['end_date']) ? $_POST['end_date'] : ""), 'class="form-control" id="end_date"');?> </div>
      </div>
  </div>
</div>
<div class="form-group">
  <label for="start_date"><?= lang("note"); ?></label>
  <div class="controls"> <?= form_input('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="note"');?> </div>
</div>

<div class="form-group">
  <div class="controls"> <?= form_submit('submit', lang("submit"), 'class="btn btn-primary"');?> </div>
</div>
<?= form_close();?>

</div>
<div class="clearfix"></div>
<?php if($this->input->post('submit')) { ?>
<?php if($this->input->post('customer')){ ?>
<div class="widget wlightblue"> 
  <div class="widget-head">
    <div class="pull-left"><?= lang('name').": <strong>".$cus->name."</strong> &nbsp;&nbsp;&nbsp;&nbsp;".lang('email').": <strong>".$cus->email."</strong> &nbsp;&nbsp;&nbsp;&nbsp;".lang('phone').": <strong>".$cus->phone."</strong>"; ?></div>
    <div class="widget-icons pull-right"> <a class="wminimize" href="#"><i class="icon-chevron-up"></i></a> <a class="wclose" href="#"><i class="icon-remove"></i></a> </div>
    <div class="clearfix"></div>
</div>

<div class="widget-content">
    <div class="padd">
      <ul class="today-datas">
        <li class="bviolet"> <span class="bold" style="font-size:24px;">
          <?php /* echo $Settings->currency_prefix." ".$total['total_amount']; */ ?>
          <?= $total; ?></span><br>
          <?= lang('total'); ?> <?= lang('invoices'); ?>
          <div class="clearfix"></div>
      </li>
      <li class="bgreen"> <span class="bold" style="font-size:24px;">
          <?php /* echo $Settings->currency_prefix." ".$paid['total_amount'];*/ ?>
          <?= $paid; ?></span><br>
          <?= lang('paid'); ?>
          <div class="clearfix"></div>
      </li>
      <li class="bblue"> <span class="bold" style="font-size:24px;"><?= $pp; ?></span><br>
          <?= lang('partially_paid'); ?>
          <div class="clearfix"></div>
      </li>
      <li class="borange"> <span class="bold" style="font-size:24px;"><?= $pending; ?></span><br>
          <?= lang('pending'); ?>
          <div class="clearfix"></div>
      </li>
      <li class="bred"> <span class="bold" style="font-size:24px;"><?= $overdue; ?></span><br>
          <?= lang('overdue'); ?>
          <div class="clearfix"></div>
      </li>
      <li class="bred" style="background:#000 !important;"> <span class="bold" style="font-size:24px;"><?= $cancelled; ?></span><br>
          <?= lang('cancelled'); ?>
          <div class="clearfix"></div>
      </li>
  </ul>
  <hr />
  <ul class="today-datas t">
    <li class="bviolet"> <span class="bold" style="font-size:24px;">
      <?php /* echo $Settings->currency_prefix." ".$total['total_amount']; */ ?>
      <?= $this->sim->formatMoney($tpp->total); ?></span><br>
      <?= lang('total'); ?> <?= lang('amount'); ?>
      <div class="clearfix"></div>
  </li>
  <li class="bgreen"> <span class="bold" style="font-size:24px;">
      <?php /* echo $Settings->currency_prefix." ".$paid['total_amount'];*/ ?>
      <?= $this->sim->formatMoney($tpp->paid); ?></span><br>
      <?= lang('paid'); ?> <?= lang('amount'); ?>
      <div class="clearfix"></div>
  </li>
  <li class="borange"> <span class="bold" style="font-size:24px;"><?= $this->sim->formatMoney(($tpp->total - $tpp->paid)); ?></span><br>
      <?= lang('balance'); ?> <?= lang('amount'); ?>
      <div class="clearfix"></div>
  </li>
</ul>
</div>
</div>
</div>
<?php }	?>

<table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
  <thead>
    <tr class="active">
      <th><?= lang("date"); ?></th>
      <th><?= lang("invoice").' '.lang("no"); ?></th>
      <th><?= lang("customer"); ?></th>
      <th><?= lang("added_by"); ?></th>
      <th><?= lang("amount"); ?></th>
      <th><?= lang("note"); ?></th>
  </tr>
</thead>
<tbody>
 <tr>
   <td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
</tr>
</tbody>

<tfoot>
    <tr>
     <th><?= lang("date"); ?></th>
     <th><?= lang("invoice").' '.lang("no"); ?></th>
     <th><?= lang("customer"); ?></th>
     <th><?= lang("added_by"); ?></th>
     <th><?= lang("amount"); ?></th>
     <th><?= lang("note"); ?></th>
 </tr>
</tfoot>
</table>
<?php 
}
?>
<div class="clearfix"></div>
</div>
<div class="clearfix"></div>
</div>
