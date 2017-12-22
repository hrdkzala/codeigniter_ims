<script>
	$(document).ready(function() {
		$('#fileData').dataTable( {
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"aaSorting": [[ 0, "desc" ]],
			"iDisplayLength": <?= $Settings->rows_per_page; ?>,
			'bProcessing'    : true, 'bServerSide'    : true,
			'sAjaxSource'    : '<?= site_url('customers/getdatatableajax'); ?>',
			'fnServerData': function(sSource, aoData, fnCallback) {
				aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
				$.ajax({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
			},	
			"oTableTools": {
				"sSwfPath": "<?= $assets; ?>media/swf/copy_csv_xls_pdf.swf",
				"aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ] 
			},
			"aoColumns": [ null, null, null, null, null, null, { "bSortable": false }, <?= $this->sim->in_group('admin') ? 'null' : '{"bSortable": false, "bVisible": false }'; ?> ]

		}).columnFilter({ aoColumns: [
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			null,
			<?php if($this->sim->in_group('admin')) { echo 'null'; } else { echo 'null'; } ?>
			]});
	});
</script>

<div class="page-head">
	<h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("list_results"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
	<div class="container">

		<table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th><?= lang("name"); ?></th>
					<th><?= lang("company"); ?></th>
					<th><?= lang("phone"); ?></th>
					<th><?= lang("email_address"); ?></th>
					<th><?= lang("city"); ?></th>
					<th><?= lang("country"); ?></th>
					<th style="width:150px;"><?= lang("actions"); ?></th>
					<th style="width:50px;"><?= lang("login"); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="7" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th><?= lang("name"); ?></th>
					<th><?= lang("company"); ?></th>
					<th><?= lang("phone"); ?></th>
					<th><?= lang("email_address"); ?></th>
					<th><?= lang("city"); ?></th>
					<th><?= lang("country"); ?></th>
					<th style="width:150px;"><?= lang("actions"); ?></th>
					<th style="width:50px;"><?= lang("login"); ?></th>
				</tr>
			</tfoot>
		</table>

		<p><a href="<?= site_url('customers/add');?>" class="btn btn-primary"><?= lang("add_customer"); ?></a></p>
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>

