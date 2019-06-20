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
        Profil Mahasiswa
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Profil</a></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $this->session->userdata('Foto') ;?>" alt="User profile picture" style="width: 125px;height: 125px;">

              <h3 class="profile-username text-center"><?php echo $this->session->userdata('Nama') ;?></h3>

              <p class="text-muted text-center"><?php echo $this->session->userdata('Level') ;?></p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Program Studi</b> <a class="pull-right"><?php echo $profil[0]['Id_prodi'] ?></a>
                </li> 
                <li class="list-group-item">
                  <b>Semester / IPK</b> <a class="pull-right"><?php echo $profil[0]['Semester'] ." / ".$profil[0]['IPK'] ;?></a>
                </li>
                <li class="list-group-item">
                  <b>Angkatan</b> <a class="pull-right"><?php echo $profil[0]['Angkatan'] ;?></a>
                </li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#profil" data-toggle="tab">Profil Mahasiswa</a></li>
              <li><a href="#user" data-toggle="tab">User</a></li>
              <li><a href="#foto" data-toggle="tab">Foto</a></li>
            </ul>
            <div class="tab-content">
              <div class=" active tab-pane" id="profil">
                <form class="form-horizontal" method="POST" action="<?php echo site_url('Profil/Update1_Mahasiswa'); ?>">
                  <?php foreach($mahasiswa as $m){
                        $alamat = $m['Alamat'];
                        $telpon = $m['No_telepon'];
                    } ?>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Nomor Telepon</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" name="telepon" id="telepon" placeholder="No telepon" value="<?php echo $telpon ;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Alamat</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" rows="3" name="alamat" id="alamat" placeholder="Masukan Alamat"> <?php echo $alamat ;?></textarea>                     
                    </div>
                  </div>
          
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                  </div>
                </form>
              </div>  

              <div class="tab-pane" id="user">
                <form class="form-horizontal" method="POST" action="<?php echo site_url('Profil/Update2_Mahasiswa'); ?>">
                    <?php foreach($user as $u){
                        $email = $u['Email'];
                        $pass = $u['Password'];
                    } ?>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $email?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Kata Sandi</label>
                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="password" name="password" placeholder="Kata Sandi" value="<?php echo $pass?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-success">Simpan</button>
                    </div>
                  </div>
                </form>
              </div>

              <div class="tab-pane" id="foto">
                <?php echo form_open_multipart('Profil/Update_Foto','class="form-horizontal"');?>
                      <?php foreach($user as $u){
                          $Foto = $u['Foto'];
                      } ?>
                      <div class="form-group">
                          <label for="inputEmail" class="col-sm-2 control-label">Foto</label>
                          <div class="col-sm-10">
                              <input type="file" class="form-control" id="userFile" name="userFile">
                          </div>
                      </div>
                      <input type="hidden" class="form-control" id="lama" name="lama" value="<?php echo $Foto?>">
                      <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10">
                          <input type="submit" class="btn btn-info remove" id="submitUpload" value="Simpan" >
                      </div>
                      </div>
                  </form>
              </div>
              <!-- /.tab-pane -->

              

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('templates/footer'); ?>

  
</div>
<!-- ./wrapper -->
<?php $this->load->view('templates/toast_message'); ?>
<script>

</script>
 

<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>

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
