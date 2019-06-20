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
        <li><a href="#"><i class="fa fa-dashboard"></i> Akademik</a></li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      <br>
      <div class="float-right">
          <!-- <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah User</a>
         &nbsp; &nbsp; &nbsp; -->
          <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#upload"><i class="plus"></i>Upload Data</a>
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Mahasiswa Teknik Informasi</a></li>
              <li><a href="#tab_2" data-toggle="tab">Mahasiswa Sistem Informasi</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1" >
                  <!-- Data list table --> 
                  <table class="table table-striped table-bordered" id="userData" width="100%">
                      <thead class="thead-dark">
                          <tr>
                              <th>NIM</th>
                              <th>Nama</th>
                              <!-- <th>Prodi</th> -->
                              <th>Angkatan</th>
                              <th>Aksi</th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>    
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2" >
                  <!-- Data list table --> 
                    <table class="table table-striped table-bordered" id="userData1" width="100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>NIM</th>
                                <th>Nama</th>
                                <!-- <th>Prodi</th> -->
                                <th>Angkatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>   
                </div>
                <!-- /.tab-pane -->
            </div>
        </div>
      </div>
      <!-- /.row (main row) -->

      <!-- Modal Add and Edit Form mahasiswa-->
        <div class="modal fade" id="modalUserAddEdit" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="hlabel">Tambah</span> Mahasiswa</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <form role="form">
                        <input type="hidden" class="form-control" name="id" id="id"/>
                            <div class="form-group">
                                <label>Nomor Induk</label>
                                <input type="text" class="form-control" name="nomor" id="nomor" placeholder="Masukan Nomor Induk" >
                            </div>
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukan Nama" >
                            </div>
                            <div class="form-group">
                              <div class="row">
                                    <div class="col-md-6">
                                    <label>Prodi</label>
                                      <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="prodi1" name="prodi" class="custom-control-input" value="TI" checked="checked" >
                                                <label class="custom-control-label" for="prodi1">TI</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                          <div class="custom-control custom-radio custom-control-inline">
                                              <input type="radio" id="prodi2" name="prodi" class="custom-control-input" value="SI" >
                                              <label class="custom-control-label" for="prodi2">SI</label>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Angkatan</label>
                                        <input type="text" class="form-control" name="angkatan" id="angkatan" placeholder="Masukan Angkatan" >
                                    </div>
                              </div>
                            </div>    
                            <div class="form-group">
                              <div class="row">
                                    <div class="col-md-6">
                                      <label>SKS</label>
                                      <input type="text" class="form-control" name="sks" id="sks" placeholder="Masukan SKS" >
                                    </div>
                                    <div class="col-md-6">
                                      <label>Semester</label>
                                      <input type="text" class="form-control" name="semester" id="semester" placeholder="Masukan Semester" >
                                    </div>
                              </div>
                            </div>
                            <div class="form-group">
                                <label>IPK</label>
                                <input type="text" class="form-control" name="ipk" id="ipk" placeholder="Masukan IPK" >
                            </div>
                            <div class="form-group">
                            <label>Jenis kelamin</label>
                                <div class="row">
                                  <div class="col-md-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jenis1" name="jenis" class="custom-control-input" value="Pria" checked="checked" >
                                        <label class="custom-control-label" for="jenis1">Pria</label>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="jenis2" name="jenis" class="custom-control-input" value="Wanita" >
                                        <label class="custom-control-label" for="jenis2">Wanita</label>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control" name="telpon" id="telpon" placeholder="Masukan Nomor Telepon" >
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Masukan Alamat" >
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="userSubmit">Submit</button>
                    </div>
                </div>
            </div>
        </div>
      <!-- End Modal Form  -->

      <!-- Modal upload -->
        <div class="modal fade" id="upload" role="dialog">
          <div class="modal-dialog">
              <div class="modal-content">
                  <!-- Modal Header -->
                  <div class="modal-header">
                      <h4 class="modal-title"><span id="hlabel">Upload File Excel</span> Mahasiswa</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <!-- Modal Body -->
                  <div class="modal-body">
                    <div class="statusMsg"></div>
                     <?php echo form_open_multipart('Mahasiswa/UploadAction/','class="form-horizontal form-groups-bordered" ');?>
                          <div class="form-group" style="padding:15px; ">
                              <label>Pilih Keterangan</label>
                              <select class="form-control" id="keterangan" name="keterangan">
                                <option value="">-- Keterangan --</option>
                                <option value="1">Update Semester, IPK dan total SKS</option>
                                <option value="2">Update Status Mahasiswa</option>
                              </select>
                          </div>
                          <div class="form-group" style="padding:15px; ">
                           <span><label>*File yang bisa di upload hanya file Excel berekstensi .xls</label><span>
                              <div class="custom-control custom-radio custom-control-inline">
                                  <input type="file" name="userFile" >
                              </div>
                          </div>
                      
                  </div>
                  <!-- Modal Footer -->
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <input type="submit" class="btn btn-info" id="submitUpload" value="Submit" >
                  </div>
              </div>
              </form>
          </div>
        </div>
      <!-- end Modal upload -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('templates/footer'); ?>
</div>
<!-- ./wrapper -->

<!-- Script CRUD -->
<script>
    // Update the members data list
    function getUsers() {
        $('#userData').DataTable().ajax.reload();
    }

    // Send CRUD requests to the server-side script
    function userAction(type, id) {
        id = (typeof id == "undefined") ? '' : id;
        var userData = '',
            frmElement = '';

        if (type == 'add') {
            frmElement = $("#modalUserAddEdit");
            userData = frmElement.find('form').serialize();
        } 
        else if (type == 'edit') {
            frmElement = $("#modalUserAddEdit");
            userData = frmElement.find('form').serialize();
        } 
        else {
            frmElement = $(".row");
            userData = 'UUID=' + id;
        }

        frmElement.find('.statusMsg').html('');
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('Mahasiswa/'); ?>'+ type,
            dataType: 'JSON',
            data: userData,
            beforeSend: function () {
                frmElement.find('form').css("opacity", "0.5");
            },
            success: function (resp) {
                frmElement.find('.statusMsg').html(resp.msg);
                if (resp.status == 1) {
                    if (type == 'add') {
                        frmElement.find('form')[0].reset();
                    }
                    getUsers();
                }
                frmElement.find('form').css("opacity", "");
            }
        });
    }

    // Fill the user's data in the edit form
    function editUser(id) {
      document.getElementById("nomor").setAttribute("readonly", true);
        $.post("<?php echo base_url('Mahasiswa/userData/'); ?>", {
            UUID: id
        }, function (data) {
            $('#id').val(id);
            $('#nomor').val(data['mahasiswa'].NIM);
            $('#nama').val(data['mahasiswa'].Nama);
            $('#angkatan').val(data['mahasiswa'].Angkatan);
            $('#sks').val(data['mahasiswa'].SKS);
            $('#ipk').val(data['mahasiswa'].IPK);
            $('#semester').val(data['mahasiswa'].Semester);
            $('#telpon').val(data['mahasiswa'].No_telepon);
            $('input:radio[name="prodi"]').filter('[value="' + data['mahasiswa'].Id_prodi + '"]').attr('checked', true);
            $('#alamat').val(data['mahasiswa'].Alamat);
            $('input:radio[name="jenis"]').filter('[value="' + data['mahasiswa'].Jenis_Kelamin + '"]').attr('checked', true);
        }, "json");
        getUsers();
    }

    // Actions on modal show and hidden events
    $(function () {
        $('#modalUserAddEdit').on('show.bs.modal', function (e) {
            var type = $(e.relatedTarget).attr('data-type');
            var userFunc = "userAction('add');";
            $('#hlabel').text('Tambah');
            document.getElementById("nomor").removeAttribute("readonly");
            if (type == 'edit') {
                userFunc = "userAction('edit');";
                var rowId = $(e.relatedTarget).attr('rowID');
                editUser(rowId);
                $('#hlabel').text('Ubah');
            }
            $('#userSubmit').attr("onclick", userFunc);
        });

        $('#modalUserAddEdit').on('hidden.bs.modal', function () {
            $('#userSubmit').attr("onclick", "");
            $(this).find('form')[0].reset();
            $(this).find('.statusMsg').html('');
        });
    });

</script>
<!-- end Script Crud -->

<!-- Script Datatables -->
<script>
$(document).ready(function() {
    //datatables mahasiswa TI
    var table = $('#userData').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "language": {"processing": "<i class='fa fa-refresh fa-spin'></i>"},
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            'order': [[0, 'asc']],
            "pageLength": 5,
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('Mahasiswa/jsonDataTI'); ?>',
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columns": [
                //{"data": "UUID"},
                {"data": "NIM"},
                {"data": "Nama"},
                //{"data": "Prodi"},
                {"data": "Angkatan"},
                {"data": "action"},
            ],
    });

    //datatables mahasiswa SI
    var table1 = $('#userData1').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            'order': [[0, 'asc']],
            "pageLength": 5,
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('Mahasiswa/jsonDataSI'); ?>',
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columns": [
                //{"data": "UUID"},
                {"data": "NIM"},
                {"data": "Nama"},
                //{"data": "Prodi"},
                {"data": "Angkatan"},
                {"data": "action"},
            ],
    }); 
});
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
