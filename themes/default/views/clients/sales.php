<style type="text/css">

	.table tfoot th:nth-child(6), .table td:nth-child(6), .table tfoot th:nth-child(7), .table td:nth-child(7), .table tfoot th:nth-child(8), .table td:nth-child(8) {
		text-align: right;
	}
	.table td:nth-child(7) {
		text-transform: capitalize;
	}
	.table td {
		width:12%;
	}
	.table td:first-child {
		width: 6%;
	}
	.table td:nth-child(9) {
		text-align:center;
	}
	.table td:nth-child(9) span { display:block; }
	.table td:nth-child(3), .table td:nth-child(6), .table td:nth-child(7), .table td:nth-child(8), .table td:nth-child(9) {
		width:7%;
	}
</style>
<script>
	$(document).ready(function() {
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

		var inv_id;

		$('#fileData').dataTable( {
			"aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]], "aaSorting": [[ 1, "desc" ]],
			"iDisplayLength": <?= $Settings->rows_per_page; ?>, 'bProcessing': true, 'bServerSide': true,
			'sAjaxSource'    : '<?= site_url('clients/getSales'); ?>?customer_id=<?= $customer_id; ?>',
			'fnRowCallback': function(nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
                nRow.className = "invoice_link";
                return nRow;
            },
			'fnServerData': function(sSource, aoData, fnCallback) {
				aoData.push( { "name": "<?= $this->security->get_csrf_token_name(); ?>", "value": "<?= $this->security->get_csrf_hash() ?>" } );
				$.ajax
				({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
			},
			"aoColumns": [ { "bVisible": false }, { "mRender": fsd }, null, null, null, null, { "bSearchable": false, "mRender": currencyFormat }, { "bSearchable": false, "mRender": currencyFormat }, { "bSearchable": false, "mRender": currencyFormat }, { "mRender": status },
			],

			"fnFooterCallback": function ( nRow, aaData, iStart, iEnd, aiDisplay ) {
				var rTotal = 0, pTotal = 0, bTotal = 0;
				for ( var i=0 ; i<aaData.length ; i++ ) {
					rTotal += aaData[ aiDisplay[i] ][6]*1;
					pTotal += aaData[ aiDisplay[i] ][7]*1;
					bTotal += aaData[ aiDisplay[i] ][8]*1;
				}
				var nCells = nRow.getElementsByTagName('th');
				nCells[5].innerHTML = currencyFormat(rTotal);
				nCells[6].innerHTML = currencyFormat(pTotal);
				nCells[7].innerHTML = currencyFormat(bTotal);
			}

		} ).columnFilter({ aoColumns: [
			null, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, { type: "text", bRegex:true }, null, null, { type: "select", values: [ '<?= lang('paid'); ?>','<?= lang('partially_paid'); ?>','<?= lang('pending'); ?>', '<?= lang('overdue'); ?>', '<?= lang('cancelled'); ?>'] }

			]});

		$('#fileData').on("click", ".st", function(){
			inv_id = $(this).attr('id');
		});



	});
</script>

<div class="page-head">
	<h2><?= $page_title; ?></h2>
	<span class="page-meta"><?= lang("list_results"); ?></span>
</div>
<div class="clearfix"></div>
<div class="matter">
	<div class="container">
		<div class="table-responsive">
			<table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-condensed table-hover table-striped" style="margin-bottom: 0px;">
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
						<th><?= lang("paid"); ?></th>
						<th><?= lang("balance"); ?></th>
						<th><?= lang("status"); ?></th>
					</tr>
				</tfoot>
			</table>
		</div>

