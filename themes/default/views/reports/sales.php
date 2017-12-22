<?php
$v = "?v=1";
if($this->input->post('submit')) {
	if($this->input->post('product')){
		$v .= "&product=".$this->input->post('product');
	} 
	if($this->input->post('user')){
		$v .= "&user=".$this->input->post('user');
	} 
	if($this->input->post('customer')){
		$v .= "&customer=".$this->input->post('customer');
	} 
	if($this->input->post('cf')){
		$v .= "&cf=".$this->input->post('cf');
	} 
	if($this->input->post('status')){
		$v .= "&status=".$this->input->post('status');
	}
	if($this->input->post('start_date')){
		$v .= "&start_date=".$this->input->post('start_date');
	}
	if($this->input->post('end_date')) {
		$v .= "&end_date=".$this->input->post('end_date');
	}

}
?>
<script src="<?= $assets; ?>media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<link href="<?= $assets; ?>style/chosen.css" rel="stylesheet">
<link href="<?= $assets; ?>style/chosen-bootstrap.css" rel="stylesheet">
<style type="text/css">
	.table thead th { text-align:center; font-weight:bold; width: 11%; }
	.table tfoot th:nth-child(9), .table td:nth-child(9), .table tfoot th:nth-child(7), .table td:nth-child(7), .table tfoot th:nth-child(8), .table td:nth-child(8) { text-align: right; width: 8%; }
	.table tfoot th:nth-child(6), .table tfoot th:nth-child(7), .table tfoot th:nth-child(8) { font-weight:bold; }
	.table td:first-child { text-align: center; width: 4%; }
	.table td:last-child { text-align: center; width: 6%; }
	.table td:nth-child(7) { text-transform: capitalize; }
	.table td:last-child span { display: block; }
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

        function status(x) {
            var st = x.split('-');
            switch (st[0]) {
                case 'paid':
                    return '<a id="'+st[1]+'" href="#myModal" role="button" data-toggle="modal" class="st"><span class="label'+st[1]+' label label-success"><?=lang('paid');?></span></a>';
                    break;

                case 'partial':
                    return '<a id="'+st[1]+'" href="#myModal" role="button" data-toggle="modal" class="st"><span class="label'+st[1]+' label label-info"><?=lang('partial');?></span></a>';
                    break;

                case 'pending':
                    return '<a id="'+st[1]+'" href="#myModal" role="button" data-toggle="modal" class="st"><span class="label'+st[1]+' label label-warning"><?=lang('pending');?></span></a>';
                    break;

                case 'overdue':
                    return '<a id="'+st[1]+'" href="#myModal" role="button" data-toggle="modal" class="st"><span class="label'+st[1]+' label label-danger"><?=lang('overdue');?></span></a>';
                    break;

                case 'canceled':
                    return '<a id="'+st[1]+'" href="#myModal" role="button" data-toggle="modal" class="st"><span class="label'+st[1]+' label label-danger"><?=lang('canceled');?></span></a>';
                    break;

                default:
                    return '<a id="'+st[1]+'" href="#myModal" role="button" data-toggle="modal" class="st">'+st[0]+'</a>';

            }
        }

	$('#fileData').dataTable( {
		"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
		"aaSorting": [[ 0, "desc" ]],
		"iDisplayLength": <?= $Settings->rows_per_page; ?>,
		'bProcessing'    : true,
		'bServerSide'    : true,
		'sAjaxSource'    : '<?= site_url('reports/getsales/'.$v); ?>',
		'fnServerData': function(sSource, aoData, fnCallback) {
			aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
			$.ajax({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
		},
		"oTableTools": {
			"sSwfPath": "<?= $assets; ?>media/swf/copy_csv_xls_pdf.swf",
			"aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ]
		},
		"aoColumns": [ null,
		{ "mRender": fsd }, null, null, null, null, { "bSearchable": false, "mRender": currencyFormat }, { "bSearchable": false, "mRender": currencyFormat }, { "bSearchable": false, "mRender": currencyFormat }, { "mRender": status } ],
		"fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
			var rTotal = 0, pTotal = 0, bTotal = 0;
			for ( var i=0 ; i<aaData.length ; i++ ) {
				rTotal += aaData[ aiDisplay[i] ][6]*1;
				pTotal += aaData[ aiDisplay[i] ][7]*1;
				bTotal += aaData[ aiDisplay[i] ][8]*1;
			}
			var nCells = nRow.getElementsByTagName('th');
			nCells[6].innerHTML = currencyFormat(rTotal);
			nCells[7].innerHTML = currencyFormat(pTotal);
			nCells[8].innerHTML = currencyFormat(bTotal);
		}
	}).columnFilter({ aoColumns: [
		{ type: "text", bRegex:true },
		{ type: "text", bRegex:true },
		{ type: "text", bRegex:true },
		{ type: "text", bRegex:true },
		{ type: "text", bRegex:true },
		{ type: "text", bRegex:true },
		{ type: "text", bRegex:true },
		null, null,
        { type: "select", values: [ { value: "paid", label:'<?= lang('paid'); ?>'},{ value: "partial", label:'<?= lang('partial'); ?>'},{ value: "pending", label:'<?= lang('pending'); ?>'}, { value: "overdue", label:'<?= lang('overdue'); ?>'}, { value: "canceled", label:'<?= lang('canceled'); ?>'}] },

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
			<?php $attrib = array('class' => 'form-horizontal'); echo form_open("reports/sales"); ?>
			<div class="form-group">
				<label for="product"><?= lang("product"); ?></label>
				<div class="controls"> <?= form_input('product', (isset($_POST['product']) ? $_POST['product'] : ""), 'class="form-control" id="product"');?> </div>
			</div>
			
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
							echo form_dropdown('customer', $cu, (isset($_POST['customer']) ? $_POST['customer'] : ""), 'class="form-control customer" data-placeholder="'.lang("select")." ".lang("customer").'" id="customer"'); 
							?>
						</div>
					</div>
					<div class="form-group">
					    <?= lang('cfs', 'cf'); ?>
					    <?= form_input('cf', set_value('cf'), 'class="form-control tip" id="cf"'); ?>
					</div>
					<div class="form-group">
						<label for="user"><?= lang("created_by"); ?></label>
						<div class="controls">
							<?php 
							$us[""] = lang("select")." ".lang("user");
							foreach($users as $user){
								$us[$user->id] = $user->first_name.' '.$user->last_name;
							}
							echo form_dropdown('user', $us, (isset($_POST['user']) ? $_POST['user'] : ""), 'class="form-control user" data-placeholder="'.lang("select")." ".lang("user").'" id="user"'); 
							?>
						</div>
					</div>
				</div>

					<div class="col-md-6">
						<div class="form-group">
							<label for="customer"><?= lang("status"); ?></label>
							<div class="controls">
								<?php 
								$st = array(
									'' 		=> lang('select').' '.lang('status'),
									lang('cancelled') => lang('cancelled'),
									lang('overdue') 	=> lang('overdue'),
									lang('paid')		=> lang('paid'),
									lang('partially_paid')		=> lang('partially_paid'),
									lang('pending')	=> lang('pending')
									);

								echo form_dropdown('status', $st, (isset($_POST['status']) ? $_POST['status'] : ""), 'class="status form-control" data-placeholder="'.lang("select")." ".lang("status").'" id="status"'); 
								?>
							</div>
						</div>
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
						<th><?= lang("id"); ?></th>
						<th><?= lang("date"); ?></th>
						<th><?= lang("billing_company"); ?></th>
						<th><?= lang("reference_no"); ?></th>
						<th><?= lang("created_by"); ?></th>
						<th><?= lang("customer"); ?></th>
						<th><?= lang("total"); ?></th>
						<th><?= lang("paid"); ?></th>
						<th><?= lang("balance"); ?></th>
						<th><?= lang("status"); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
					</tr>
				</tbody>

				<tfoot>
					<tr>
						<th><?= lang("id"); ?></th>
						<th><?= lang("date"); ?></th>
						<th><?= lang("billing_company"); ?></th>
						<th><?= lang("reference_no"); ?></th>
						<th><?= lang("created_by"); ?></th>
						<th><?= lang("customer"); ?></th>
						<th><?= lang("total"); ?></th>
						<th><?= lang("paid"); ?></th>
						<th><?= lang("balance"); ?></th>
						<th><?= lang("status"); ?></th>
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
