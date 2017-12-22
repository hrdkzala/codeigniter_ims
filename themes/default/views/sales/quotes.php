<script src="<?= $assets; ?>media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<style type="text/css">
	.text_filter {
		width: 100% !important;
		border: 0 !important;
		box-shadow: none !important;
		border-radius: 0 !important;
		padding:0 !important;
		margin:0 !important;
		font-size: 1em !important;
	}
	.select_filter {
		width: 100% !important;
		padding:0 !important;
		height: auto !important;
		margin:0 !important;
	}
	.table td { width: 14%; vertical-align: middle !important; }
	.table td span.label { display: block; }
	.table td:nth-child(2), .table td:nth-child(7), .table td:nth-child(8), .table td:nth-child(9), .table td:nth-child(10), .table td:nth-child(11) { width: 6%; }
	.table td:first-child { width: 2%; max-width: 35px; }
</style>
<script>
	$(document).ready(function() {
		function status(x) {
			switch (x) {
				case 'sent':
				return '<span class="label label-success"><?=lang('sent');?></span>';
				break;

				case 'ordered':
				return '<span class="label label-success"><?=lang('ordered');?></span>';
				break;

				case 'pending':
				return '<span class="label label-default"><?=lang('pending');?></span>';
				break;

				default:
				return '<span class="label'+x+' label label-default">'+x+'</span>';

			}
		}
		$('#fileData').dataTable( {
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"aaSorting": [[ 0, "desc" ],[ 1, "desc" ]],
			"iDisplayLength": <?= $Settings->rows_per_page; ?>,
			'bProcessing'    : true, 'bServerSide'    : true,
			'sAjaxSource'    : '<?= site_url('sales/getquotes'); ?>',
			'fnServerData': function(sSource, aoData, fnCallback) {
				aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
				$.ajax ({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
			},
			"oTableTools": {
				"sSwfPath": "<?= $assets; ?>media/swf/copy_csv_xls_pdf.swf",
				"aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ]
			},
			"aoColumns": [ null, { "mRender": fsd }, null, null, null, null, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": currencyFormat }, { "mRender": status }, { "bSortable": false } ]
		}).columnFilter({ aoColumns: [
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			{ type: "text", bRegex:true },
			null
			]});


		$('#fileData').on('click', '.email_inv', function() {
			var id = $(this).attr('id');
			var cid = $(this).attr('data-customer');
			var bid = $(this).attr('data-company');
			$.getJSON( "<?= site_url('sales/getCE'); ?>", { cid: cid, bid: bid, <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash() ?>' }).done(function( json ) {
				$('#customer_email').val(json.ce);
				$('#subject').val('<?= lang("invoice_from"); ?> '+json.com);
			});

			$('#emailModalLabel').text('<?= lang("email")." ".lang("quote")." ".lang("no"); ?> '+id);
							//$('#subject').val('<?= lang("quote")." from ".$Settings->site_name; ?>');
							$('#qu_id').val(id);
							$('#emailModal').modal();
							return false;
						});

		$('#emailModal').on('click', '#email_now', function() {
			$(this).text('Sending...');
			var vid = $('#qu_id').val();
			var to = $('#customer_email').val();
			var subject = $('#subject').val();
			var note = $('#message').val();
			var cc = $('#cc').val();
			var bcc = $('#bcc').val();

			if(to != '') {
				$.ajax({
					type: "post",
					url: "<?= site_url('sales/send_quote'); ?>",
					data: { id: vid, to: to, subject: subject, note: note, cc: cc, bcc: bcc, <?= $this->security->get_csrf_token_name(); ?>: '<?= $this->security->get_csrf_hash() ?>' },
					success: function(data) {
						alert(data);
					},
					error: function(){
						alert('<?= lang('ajax_error'); ?>');
					}
				});
			} else { alert('<?= lang('to'); ?>'); }
			$('#emailModal').modal('hide');
			$(this).text('<?= lang('send_email'); ?>');
			return false;

		});

	});

</script>

<div class="page-head">
	<h2 class="pull-left"><?= $page_title; ?> <span class="page-meta"><?= lang("list_results"); ?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
	<div class="container">
		<div class="table-responsive">
			<table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">
				<thead>
					<tr class="active">
						<th style="max-width:35px; text-align:center;"><?= lang("id"); ?></th>
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
						<th><?= lang("status"); ?></th>
						<th style="min-width:175px; text-align:center;"><?= lang("actions"); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="13" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th style="max-width:35px; text-align:center;"><?= lang("id"); ?></th>
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
						<th><?= lang("status"); ?></th>
						<th style="min-width:175px; text-align:center;"><?= lang("actions"); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>
		<p><a href="<?= site_url('sales/add_quote');?>" class="btn btn-primary"><?= lang("add_quote"); ?></a></p>

		<div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel"><?= lang('add_payment'); ?></h4>
					</div>
					<div class="modal-body">
						<div class="control-group">
							<label class="control-label" for="amount"><?= lang("amount_paid"); ?></label>
							<div class="controls"> <?= form_input('amount', '', 'class="input-block-level" id="amount"');?> </div>
						</div>
						<div class="control-group">
							<label class="control-label" for="note"><?= lang("note"); ?></label>
							<div class="controls"> <?= form_textarea('note', '', 'class="input-block-level" id="note" style="height:100px;"');?> </div>
						</div>
						<input type="hidden" name="cid" value="" id="cid" />
						<input type="hidden" name="vid" value="" id="vid" />
					</div>
					<div class="modal-footer">
						<button class="btn" data-dismiss="modal" aria-hidden="true"><?= lang('close'); ?></button>
						<button class="btn btn-primary" id="add-payment"><?= lang('add_payment'); ?></button>
					</div>
				</div>

				<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<h4 class="modal-title" id="emailModalLabel"></h4>
							</div>
							<div class="modal-body">

								<div class="form-group">
									<label for="customer_email"><?= lang("to"); ?></label>
									<div class="controls"> <?= form_input('to', '', 'class="form-control" id="customer_email"');?></div>
								</div>
								<div id="extra" style="display:none;">
									<div class="form-group">
										<label for="cc"><?= lang("cc"); ?></label>
										<div class="controls"> <?= form_input('cc', '', 'class="form-control" id="cc"');?></div>
									</div>
									<div class="form-group">
										<label for="bcc"><?= lang("bcc"); ?></label>
										<div class="controls"> <?= form_input('bcc', '', 'class="form-control" id="bcc"');?></div>
									</div>
								</div>
								<div class="form-group">
									<label for="subject"><?= lang("subject"); ?></label>
									<div class="controls">
										<?= form_input('subject', '', 'class="form-control" id="subject"');?> </div>
									</div>
									<div class="form-group">
										<label for="message"><?= lang("message"); ?></label>
										<div class="controls"> <?= form_textarea('note', lang("find_attached_quote"), 'id ="message" class="form-control" placeholder="'.lang("add_note").'" rows="3" style="margin-top: 10px; height: 100px;"');?> </div>
									</div>
									<input type="hidden" id="qu_id" value="" />  
								</div>
								<div class="modal-footer">
									<button class="btn pull-left" id="sh-btn"><?= lang('show_hide_cc'); ?></button>
									<button class="btn" data-dismiss="modal" aria-hidden="true"><?= lang('close'); ?></button>
									<button class="btn btn-primary" id="email_now"><?= lang('send_email'); ?></button>
								</div>
								<script type="text/javascript">
									$(document).ready(function() {
										$('#sh-btn').click(function(event) {
											$('#extra').toggle();
											$('#cc').val('<?= $this->session->userdata('email'); ?>');
										});
									});
								</script>
							</div>

							<div class="clearfix"></div>
						</div>
						<div class="clearfix"></div>
					</div>
