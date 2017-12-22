   	
<div class="clearfix"></div>
</div>
<span class="totop"><a href="#"><i class="fa fa-chevron-up"></i></a></span> 
<div class="modal fade" id="simModal" tabindex="-1" role="dialog" aria-labelledby="simModalLabel" aria-hidden="true"></div>
<div class="modal fade" id="simModal2" tabindex="-1" role="dialog" aria-labelledby="simModalLabel2" aria-hidden="true"></div>

<script src="<?= $assets; ?>js/bootstrap.min.js"></script>
<script src="<?= $assets; ?>js/bootstrap-datetimepicker.min.js"></script>
<script src="<?= $assets; ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets; ?>js/jquery.dataTables.js"></script> 
<script src="<?= $assets; ?>media/js/jquery.dataTables.columnFilter.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8" src="<?= $assets; ?>media/js/ZeroClipboard.js"></script> 
<script type="text/javascript" charset="utf-8" src="<?= $assets; ?>media/js/TableTools.js"></script> 
<script type="text/javascript" src="<?= $assets; ?>js/DT_bootstrap.js"></script>
<script type="text/javascript" src="<?= $assets; ?>js/chosen.jquery.js"></script>
<script type="text/javascript" src="<?= $assets; ?>js/jquery-ui.js"></script>
<?php 
$m = strtolower($this->router->fetch_class());
$v = strtolower($this->router->fetch_method());
?>
<script type="text/javascript"  charset="UTF-8">
	$(document).ready(function() {
		<?php if($m == 'home') { ?>
			$('.mm_<?= $m; ?>').parent('li').addClass('current');
			<?php } else { ?>
				$('.mm_<?= $m; ?>').parent('li').addClass('current');
				$('.mm_<?= $m; ?>').click();
				$('#<?= $m; ?>_<?= $v; ?>').addClass('active');
				<?php } ?>
			});
</script>

</body>
</html>
