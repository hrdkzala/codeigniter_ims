<script>
	$(document).ready(function() {

		$('#fileData').dataTable( {
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"aaSorting": [[ 1, "desc" ]], "iDisplayLength": <?= $Settings->rows_per_page; ?>,
			'bProcessing'    : true, 'bServerSide'    : true,
			'sAjaxSource'    : '<?= site_url('clients/getquotes'); ?>',
			'fnRowCallback': function(nRow, aData, iDisplayIndex) {
				nRow.id = aData[0];
				nRow.className = "quote_link";
				return nRow;
			},
			'fnServerData': function(sSource, aoData, fnCallback) {
				aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
				$.ajax
				({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
			},
			"aoColumns": [ { "bVisible": false }, { "mRender": fsd },  null, null, null, null, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": currencyFormat } ]

		}).columnFilter(null, { aoColumns: [ { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true } ]});

});

</script>

<div class="page-head">
	<h2><?= $page_title; ?> </h2>
	<span class="page-meta"><?= lang("list_results"); ?></span>
</div>
<div class="clearfix"></div>
<div class="matter">
	<div class="container">
		<div class="table-responsive">
			<table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-hover table-striped" style="margin-bottom: 0px;">
				<thead>
					<tr class="active">
						<th><?= lang("id"); ?></th>
						<th><?= lang("date"); ?></th>
						<th><?= lang("billing_company"); ?></th>
						<th><?= lang("reference_no"); ?></th>
						<th><?= lang("created_by"); ?></th>
						<th><?= lang("customer"); ?></th>
						<th><?= lang("total"); ?></th>
						<th><?= lang("total_tax"); ?></th>
						<th><?= lang("shipping"); ?></th>
						<th><?= lang("discount"); ?></th>
						<th><?= lang("grand_total"); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="9" class="dataTables_empty"><?= lang("loading_data_from_server"); ?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th><?= lang("id"); ?></th>
						<th>yyyy-mm-dd</th>
						<th><?= lang("billing_company"); ?></th>
						<th><?= lang("reference_no"); ?></th>
						<th><?= lang("created_by"); ?></th>
						<th><?= lang("customer"); ?></th>
						<th><?= lang("total"); ?></th>
						<th><?= lang("total_tax"); ?></th>
						<th><?= lang("shipping"); ?></th>
						<th><?= lang("discount"); ?></th>
						<th><?= lang("grand_total"); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
		