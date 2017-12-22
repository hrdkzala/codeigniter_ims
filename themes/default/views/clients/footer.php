<!-- Mainbar ends -->	    	
<div class="clearfix"></div>

</div>

<!-- Content ends -->

<!-- Scroll to top -->
<span class="totop"><a href="#"><i class="fa fa-chevron-up"></i></a></span> 

<div class="modal fade" id="simModal" tabindex="-1" role="dialog" aria-labelledby="simModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="simModal2" tabindex="-1" role="dialog" aria-labelledby="simModalLabel2" aria-hidden="true"></div>

<!-- JS -->
<script src="<?= $assets; ?>js/bootstrap.min.js"></script>
<script src="<?= $assets; ?>js/jquery-ui-1.10.2.custom.min.js"></script>
<script src="<?= $assets; ?>js/bootstrap-datetimepicker.min.js"></script>
<script src="<?= $assets; ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets; ?>js/jquery.dataTables.js"></script> 
<script src="<?= $assets; ?>media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<script type="text/javascript" src="<?= $assets; ?>js/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?= $assets; ?>js/chosen.jquery.js"></script>
<?php 
$v = strtolower($this->router->fetch_method());
?>
<script type="text/javascript"  charset="UTF-8">
	$(document).ready(function() {
		$('#<?= $v; ?>').parent('li').addClass('current');
		$('body').on('click', '.invoice_link', function() {
		    //window.location.href = '<?= site_url('clients/view_invoice/'); ?>?id=' + $(this).attr('id');
		    window.open('<?= site_url('clients/view_invoice/'); ?>?id=' + $(this).attr('id'), "simPopup", "menubar=yes, toolbar=yes, scrollbars=yes, resizable=yes, width=1000, height=650");
		});
		$('body').on('click', '.quote_link', function() {
		    //window.location.href = '<?= site_url('clients/view_quote/'); ?>?id=' + $(this).attr('id');
		    window.open('<?= site_url('clients/view_quote/'); ?>?id=' + $(this).attr('id'), "simPopup", "menubar=yes, toolbar=yes, scrollbars=yes, resizable=yes, width=1000, height=650");
		});
	});
</script>

</body>
</html>
