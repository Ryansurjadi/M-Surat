<!DOCTYPE html>
<html>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php $this->load->view('templates/header'); ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php $this->load->view('templates/sidebar_'.$this->session->userdata('Level')); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?php echo $title; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Administrasi</a></li>
        <li >Surat Keterangan</li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
        
            <!--  -->
						<br><br>
						
						<form id = "form_kp" method="POST" action ="<?php echo site_url('Surat_Keterangan/generateSurat');?>" class="form-horizontal form-groups-bordered" >
							<div id="fomr_container0">
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Jenis Surat Keterangan</label>
									
									<div class="col-sm-5">
										<select class="form-control" id="keterangan" name="keterangan">
                      <option value="">-- Jenis Surat Keterangan --</option>
                      <option value="Mahasiswa Aktif">Mahasiswa Aktif</option>
                      <option value="Survey">Survey</option>
                      <option value="Skripsi">Skripsi</option>
                    </select>
									</div>
								</div>
							</div>
							
							
							<div class="form-group">
								<center>
                                <div class="col-sm-offset-3 col-sm-5">
									<input type="submit" class="btn btn-success" value="Simpan">
								</div>
                                
							</div>
							
						</form>

        </section>
      </div>
      <!-- /.row (main row) -->

     

     
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('templates/footer'); ?>
</div>
<!-- ./wrapper -->

<script type="text/javascript">
    function goBack() {
    window.history.go(-1);
    }

	function add_form()
	{		
		 for ($formno=0; $formno<3; $formno++){
		 	$formno=$formno+1;
		 }
			 
		 $("#form_kp #form_container:last").after
		 ("<div id='form_container"+$formno+"'><hr style='border-top:1px solid #303641'><div align='right'><input type='button' value='Hapus' class='btn btn-danger' onclick=delete_form('form_container"+$formno+"')></div><br><div class='form-group'><label for='field-1' class='col-sm-3 control-label'>NIM</label><div class='col-sm-5'><input type='text' class='form-control' id='nim' name='nim[]' placeholder='Nomor Induk Mahasiswa' required=''></div></div></div>");
	}
	
	function delete_form(formno)
	{
	 $('#'+formno).remove();
	}
</script>

<?php $this->load->view('templates/toast_message'); ?>


<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>$.widget.bridge('uibutton', $.ui.button);</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url('bower_components/raphael/raphael.min.js')?>"></script>
<script src="<?php echo base_url('bower_components/morris.js/morris.min.js')?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('bower_components/jquery-sparkline/dist/jquery.sparkline.min.js')?>"></script>
<!-- jvectormap -->
<script src="<?php echo base_url('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')?>"></script>
<script src="<?php echo base_url('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')?>"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url('bower_components/jquery-knob/dist/jquery.knob.min.js')?>"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url('bower_components/moment/min/moment.min.js')?>"></script>
<script src="<?php echo base_url('bower_components/bootstrap-daterangepicker/daterangepicker.js')?>"></script>
<!-- datepicker -->
<script src="<?php echo base_url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')?>"></script>
<!-- Slimscroll -->
<script src="<?php echo base_url('bower_components/jquery-slimscroll/jquery.slimscroll.min.js')?>"></script>
<!-- FastClick -->
<script src="<?php echo base_url('bower_components/fastclick/lib/fastclick.js')?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('dist/js/adminlte.min.js')?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php echo base_url('dist/js/pages/dashboard.js')?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('dist/js/demo.js')?>"></script>



</body>
</html>
