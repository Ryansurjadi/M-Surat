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
        <li >Surat KP</li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      <br>
      <div class="float-right">
          <a type="button" class="btn btn-info" onclick="add_form();">Tambah Anggota</a>
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
        
            <!--  -->
						<br><br>
						
						<form id = "form_kp" method="POST" action ="<?php echo site_url('Surat_KP/generateSuratKP');?>" class="form-horizontal form-groups-bordered" >
							<div id="fomr_container0">
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Nama PT</label>
									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="namapt" name="namapt" placeholder="Nama Perusahaan" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Alamat</label>
									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="alamatpt" name="alamatpt" placeholder="Alamat Perusahaan" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">Kota</label>
									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="kotapt" name="kotapt" placeholder="Kota Perusahaan" required="">
									</div>
								</div>
							</div>
							
							<hr style="border-top:1px solid #303641">

							<div id="form_container">

								<div class="form-group">
									<label for="field-1" class="col-sm-3 control-label">NIM</label>
									
									<div class="col-sm-5">
										<input type="text" class="form-control" id="nim" name="nim[]" placeholder="Nomor Induk Mahasiswa" required="">
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

      <!-- Modal Add and Edit Form  user-->
        <div class="modal fade" id="modalUserAddEdit" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="hlabel">Tambah</span> Skripsi</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <?php echo form_open_multipart('Skripsi/add','role="form" ');?>
                        <input type="hidden" class="form-control" name="id" id="id"/>
                            <div class="form-group">
                                <label>Judul Skripsi</label>
                                <textarea class="form-control" rows="3" name="judul" id="judul" placeholder="Masukan Judul Skripsi"></textarea>                     
                            </div>
                            <div class="form-group">
                                <label>Tahun Lulus</label>
                                <input type="text" class="form-control" name="tahun" id="tahun" placeholder="Masukan Tahun Kelulusan" >
                            </div>
                            <div class="form-group">
                                <label>File Dokumen</label><br>
                                <span>*File yang dapat di upload ber-ekstensi .pdf .xls .doc</span>
                                <input type="file" class="form-control" name="userFile" id="userFile" >
                            </div>
                            <div class="form-group show123">
                                <label>File yang ter-upload</label><br>
                                <input type="text" class="form-control" name="lama" id="lama" readonly>
                            </div>

                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" class="btn btn-info remove" id="submitUpload" value="Submit" >
                    </div>
                </div>
              </form>
            </div>
        </div>
      <!-- End Modal Form  -->

     
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
