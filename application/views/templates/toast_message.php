<!-- toastr JS -->
<script src="<?php echo base_url('assets/js/toastr.js') ?>"></script>
<script type="text/javascript">
		<?php 
		if($this->session->flashdata('sukses')){ ?>
			var opts = {
				"closeButton": true,
				"debug": false,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
	   		toastr.success("<?php echo $this->session->flashdata('sukses'); ?>","",opts);
	   	<?php }

	   	elseif($this->session->flashdata('gagal')){ ?>
			var opts = {
				"closeButton": true,
				"debug": false,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "5000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};
	   		toastr.error("<?php echo $this->session->flashdata('gagal'); ?>","Failed",opts);
	   	<?php }?>
</script>