<?php $v = '';
if ($customer_id) {$v .= '&customer_id=' . $customer_id;}
?>
<script src="<?=$assets;?>/media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<style type="text/css">
    .text_filter {
        width: 100% !important;
        border: 0 !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        padding:0 !important;
        margin:0 !important;
    }
    .select_filter {
        width: 100% !important;
        padding:0 !important;
        height: auto !important;
        margin:0 !important;
    }
    .table td {
        width:9%;
        vertical-align: middle !important;
    }
    .table td:nth-child(11), .table td:nth-child(12) {
        width:5%; text-align: center;
    }
    .table tfoot th:nth-child(9), .table td:nth-child(9), .table tfoot th:nth-child(7), .table td:nth-child(7), .table tfoot th:nth-child(8), .table td:nth-child(8) {
        text-align: right; width: 7%;
    }
    .table td:nth-child(8) {
        text-transform: capitalize;
    }
    .table td:nth-child(2), .table td:nth-child(4), .table td:nth-child(10) {
        width: 6%;
    }
    .table td:first-child {
        width: 2%; text-align: center;
    }
    .table td:nth-child(11) span { display:block; }
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

        function recurring(x) {
            if( x == '' || x == 0 || x == null) {
                return '<i class="fa fa-times"></i>';
            } else if(x == -1) {
                return '<i class="fa fa-check"></i>';
            } else if(x == 1) {
                return '<?=lang('daily');?>';
            } else if(x == 2) {
                return '<?=lang('weekly');?>';
            } else if(x == 3) {
                return '<?=lang('monthly');?>';
            } else if(x == 4) {
                return '<?=lang('quarterly');?>';
            } else if(x == 5) {
                return '<?=lang('semiannually');?>';
            } else if(x == 6) {
                return '<?=lang('annually');?>';
            } else if(x == 7) {
                return '<?=lang('biennially');?>';
            } else {
                return '<i class="fa fa-times"></i>';
            }
        }


        var inv_id;

        $('#fileData').dataTable( {
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "aaSorting": [[ 0, "desc" ], [ 1, "desc" ]],
            "iDisplayLength": <?=$Settings->rows_per_page;?>,
            'bProcessing'    : true, 'bServerSide'    : true,
            'sAjaxSource'    : '<?=site_url('sales/getdatatableajax/' . $v);?>',
            'fnServerData': function(sSource, aoData, fnCallback) {
                aoData.push( { "name": "<?=$this->security->get_csrf_token_name();?>", "value": "<?=$this->security->get_csrf_hash()?>" } );
                $.ajax ({ 'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback });
            },
            "oTableTools": {
                "sSwfPath": "<?=$assets;?>media/swf/copy_csv_xls_pdf.swf",
                "aButtons": [ "csv", "xls", { "sExtends": "pdf", "sPdfOrientation": "landscape", "sPdfMessage": "" }, "print" ]
            },
            "aoColumns": [ null, { "mRender": fsd }, null, null, null, null, { "bSearchable": false, "mRender": currencyFormat }, { "bSearchable": false, "mRender": currencyFormat }, { "bSearchable": false, "mRender": currencyFormat }, { "mRender": fsd }, { "mRender": status }, { "mRender": recurring }, { "bSortable": false } ],
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
            { type: "text", bRegex:true },
            { type: "text", bRegex:true },
            { type: "text", bRegex:true },
            { type: "select", values: [ { value: "paid", label:'<?=lang('paid');?>'},{ value: "partial", label:'<?=lang('partial');?>'},{ value: "pending", label:'<?=lang('pending');?>'}, { value: "overdue", label:'<?=lang('overdue');?>'}, { value: "canceled", label:'<?=lang('canceled');?>'}] },
            null

        ]});

        $('#fileData').on("click", ".st", function(){
            inv_id = $(this).attr('id');
        });

        var inv_st;
        $('#myModal').on('show.bs.modal', function () {
            inv_st = $('.label'+inv_id).text();
            if(inv_st == '<?=lang('paid');?>') {
                var r = confirm("<?=lang('paid_status_change');?>");
                if (r == false) {
                    return false;
                }
            }
            $('#new_status').val(inv_st);
        })

        $('#myModal').on("click", "#update_status", function(){
            $('#update_status').text('Loading...');
            var new_status = $('#new_status').val();
            if(new_status != inv_st) {
                $.ajax({
                    type: "post",
                    url: "<?=site_url('sales/update_status');?>",
                    data: { id: inv_id, status: new_status, <?=$this->security->get_csrf_token_name();?>: '<?=$this->security->get_csrf_hash()?>' },
            success: function(data) {
                location.reload();
            },
            error: function(){
                alert('<?=lang('ajax_error');?>');
                $('#update_status').text('<?=lang('update');?>');
            }
        });
    } else { alert('<?=lang('same_status');?>'); $(this).text('<?=lang('update');?>'); //$('#myModal').modal('hide');
        return false; }
    });

    $('#fileData').on('click', '.add_payment', function() {
        var vid = $(this).attr('id');
        var cid = $(this).attr('data-customer');
        $('#vid').val(vid);
        $('#cid').val(cid);
        $('#payModal').modal();
        return false;
    });

    $('#payModal').on('click', '#add-payment', function() {
        $(this).text('Loading...');
        var vid = $('#vid').val();
        var cid = $('#cid').val();
        var note = $('#note').val();
        var date = $('#date').val();
        var amount = $('#amount').val();
        if(amount != '') {
            $.ajax({
                type: "post",
                url: "<?=site_url('sales/add_payment');?>",
                data: { invoice_id: vid, customer_id: cid, amount: amount, note: note, date:date, <?=$this->security->get_csrf_token_name();?>: '<?=$this->security->get_csrf_hash()?>' },
        success: function(data) {
            location.reload();
        },
        error: function(){
            alert('<?=lang('ajax_error');?>');
        }
    });
    } else { alert('<?=lang('no_amount');?>'); $(this).text('<?=lang('add_payment');?>'); //$('#payModal').modal('hide');
        return false; }
    });

    $('#fileData').on('click', '.email_inv', function() {
        var id = $(this).attr('id');
        var cid = $(this).attr('data-customer');
        var bid = $(this).attr('data-company');
        $.getJSON( "<?=site_url('sales/getCE');?>", { cid: cid, bid: bid, <?=$this->security->get_csrf_token_name();?>: '<?=$this->security->get_csrf_hash()?>' }).done(function( json ) {
        $('#customer_email').val(json.ce);
        $('#subject').val('<?=lang("invoice_from");?> '+json.com);
    });
    $('#emailModalLabel').text('<?=lang("email") . " " . lang("invoice") . " " . lang("no");?> '+id);
    //$('#subject').val('<?=lang("invoice") . " from " . $Settings->site_name;?>');
    $('#inv_id').val(id);
    $('#emailModal').modal();
    return false;
    });

    $('#emailModal').on('click', '#email_now', function() {
        $(this).text('Sending...');
        var vid = $('#inv_id').val();
        var to = $('#customer_email').val();
        var subject = $('#subject').val();
        var note = $('#message').val();
        var cc = $('#cc').val();
        var bcc = $('#bcc').val();

        if(to != '') {
            $.ajax({
                type: "post",
                url: "<?=site_url('sales/send_email');?>",
                data: { id: vid, to: to, subject: subject, note: note, cc: cc, bcc: bcc, <?=$this->security->get_csrf_token_name();?>: '<?=$this->security->get_csrf_hash()?>' },
        success: function(data) {
            alert(data);
        },
        error: function(){
            alert('<?=lang('ajax_error');?>');
        }

    });
    } else { alert('<?=lang('to');?>'); }
    $('#emailModal').modal('hide');
    $(this).text('<?=lang('send_email');?>');
    return false;

    });

    $( "#date" ).datepicker({
        dateFormat: "<?=$dateFormats['js_sdate'];?>",
        autoclose: true
    });
    $( "#date" ).datepicker("setDate", new Date());

    });
</script>

<div class="page-head">
    <h2 class="pull-left"><?=$page_title;?> <span class="page-meta"><?=lang("list_results");?></span> </h2>
</div>
<div class="clearfix"></div>
<div class="matter">
    <div class="container">
        <div class="table-responsive">
            <table id="fileData" cellpadding=0 cellspacing=10 class="table table-bordered table-condensed table-hover table-striped" style="margin-bottom: 5px;">
                <thead>
                <tr class="active">
                    <th style="width:25px;"><?=lang("id");?></th>
                    <th><?=lang("date");?></th>
                    <th><?=lang("billing_company");?></th>
                    <th><?=lang("reference_no");?></th>
                    <th><?=lang("created_by");?></th>
                    <th><?=lang("customer");?></th>
                    <th><?=lang("total");?></th>
                    <th><?=lang("paid");?></th>
                    <th><?=lang("balance");?></th>
                    <th><?=lang("due_date");?></th>
                    <th><?=lang("status");?></th>
                    <th><?=lang("recurring");?></th>
                    <th style="min-width:170px; text-align:center;"><?=lang("actions");?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td colspan="13" class="dataTables_empty"><?=lang('loading_data_from_server');?></td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <th style="width:25px;"><?=lang("id");?></th>
                    <th>yyyy-mm-dd</th>
                    <th><?=lang("billing_company");?></th>
                    <th><?=lang("reference_no");?></th>
                    <th><?=lang("created_by");?></th>
                    <th><?=lang("customer");?></th>
                    <th><?=lang("total");?></th>
                    <th><?=lang("paid");?></th>
                    <th><?=lang("balance");?></th>
                    <th>yyyy-mm-dd</th>
                    <th><?=lang("status");?></th>
                    <th><?=lang("recurring");?></th>
                    <th style="min-width:170px; text-align:center;"><?=lang("actions");?></th>
                </tr>
                </tfoot>
            </table>
        </div>
        <p><a href="<?=site_url('sales/add');?>" class="btn btn-primary"><?=lang("add_invoice");?></a></p>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><?=lang('update_invoice_status');?></h4>
                    </div>
                    <div class="modal-body">
                        <p class="red"><?=lang("status_change_x_payment");?></p>
                        <div class="control-group">
                            <label class="control-label" for="new_status"><?=lang("new_status");?></label>
                            <div class="controls" id="change_status">
                                <?php

$st = array(
    '' => lang("select") . " " . lang("status"),
    'canceled' => lang('canceled'),
    'overdue' => lang('overdue'),
    'paid' => lang('paid'),
    'pending' => lang('pending'),
);

echo form_dropdown('new_status', $st, '', 'class="new-status span4" id="new_status"');?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=lang('close');?></button>
                        <button class="btn btn-primary" id="update_status"><?=lang('update');?></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="payModal" tabindex="-1" role="dialog" aria-labelledby="payModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><?=lang('add_payment');?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="date"><?=lang("date");?></label>
                            <div class="controls"> <?=form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control" id="date"');?> </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="amount"><?=lang("amount_paid");?></label>
                            <div class="controls"> <?=form_input('amount', '', 'class="input-block-level" id="amount"');?> </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="note"><?=lang("note");?></label>
                            <div class="controls"> <?=form_textarea('note', '', 'class="input-block-level" id="note" style="height:100px;"');?> </div>
                        </div>
                        <input type="hidden" name="cid" value="" id="cid" />
                        <input type="hidden" name="vid" value="" id="vid" />
                    </div>
                    <div class="modal-footer">
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=lang('close');?></button>
                        <button class="btn btn-primary" id="add-payment"><?=lang('add_payment');?></button>
                    </div>
                </div>
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
                            <label for="customer_email"><?=lang("to");?></label>
                            <div class="controls"> <?=form_input('to', '', 'class="form-control" id="customer_email"');?></div>
                        </div>
                        <div id="extra" style="display:none;">
                            <div class="form-group">
                                <label for="cc"><?=lang("cc");?></label>
                                <div class="controls"> <?=form_input('cc', '', 'class="form-control" id="cc"');?></div>
                            </div>
                            <div class="form-group">
                                <label for="bcc"><?=lang("bcc");?></label>
                                <div class="controls"> <?=form_input('bcc', '', 'class="form-control" id="bcc"');?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject"><?=lang("subject");?></label>
                            <div class="controls">
                                <?=form_input('subject', '', 'class="form-control" id="subject"');?> </div>
                        </div>
                        <div class="form-group">
                            <label for="message"><?=lang("message");?></label>
                            <div class="controls"> <?=form_textarea('note', lang("find_attached_invoice"), 'id ="message" class="form-control" placeholder="' . lang("add_note") . '" rows="3" style="margin-top: 10px; height: 100px;"');?> </div>
                        </div>
                        <input type="hidden" id="inv_id" value="" />
                    </div>
                    <div class="modal-footer">
                        <button class="btn pull-left" id="sh-btn"><?=lang('show_hide_cc');?></button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=lang('close');?></button>
                        <button class="btn btn-primary" id="email_now"><?=lang('send_email');?></button>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#sh-btn').click(function(event) {
                                $('#extra').toggle();
                                $('#cc').val('<?=$this->session->userdata('email');?>');
                            });
                        });
                    </script>
                </div>

                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
