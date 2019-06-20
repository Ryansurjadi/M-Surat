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
          <a href="#" class="btn btn-info"  data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah Matakuliah</a>
           &nbsp; &nbsp; &nbsp;
          <a href="#" class="btn btn-info"  data-toggle="modal" data-target="#upload"><i class="plus"></i>Upload Matakuliah</a>
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
         <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Teknik Informatika</a></li>
              <li><a href="#tab_2" data-toggle="tab">Sistem Informasi</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1" >
                <!-- Data list table --> 
                <table class="table table-striped table-bordered" id="userData" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Kode Matakuliah</th>
                            <th>Nama Matakuliah</th>
                            <th>SKS</th>
                            <th>Semester</th>
                            <th>Action</th>
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
                            <th>Kode Matakuliah</th>
                            <th>Nama Matakuliah</th>
                            <th>SKS</th>
                            <th>Semester</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
      </div>
      <!-- /.row (main row) -->

      <!-- Modal Add and Edit Form  user-->
        <div class="modal fade" id="modalUserAddEdit" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="hlabel">Tambah</span> Matakuliah</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <form role="form" enctype="multipart/form-data">
                        <input type="hidden" class="form-control" name="id" id="id" placeholder="Masukan Kode Matakuliah" required>
                            <div class="form-group">
                                <label>Kode Matakuliah</label>
                                <input type="text" class="form-control" name="kode" id="kode" placeholder="Masukan Kode Matakuliah" required>
                            </div>
                            <div class="form-group">
                                <label>Nama Matakuliah</label>
                                <input type="text" class="form-control" name="nama" id="nama" placeholder="Masukan Nama Matakuliah" required>
                            </div>
                            <div class="form-group">
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
                            <div class="form-group">
                                <label>SKS</label>
                                <input type="text" class="form-control" name="sks" id="sks" placeholder="Masukan SKS" required >
                            </div>
                            <div class="form-group">
                                <label>Semester</label>
                                <input type="text" class="form-control" name="semester" id="semester" placeholder="Masukan Semester" required >
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
                      <h4 class="modal-title"><span id="hlabel">Upload File Excel</span> Matakuliah</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <!-- Modal Body -->
                  <div class="modal-body">
                    <div class="statusMsg"></div>
                     <?php echo form_open_multipart('Matakuliah/UploadAction/','role ="form" ');?>
                        <div class="form-group">
                                <label>Prodi</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="level1" name="prodi" class="custom-control-input" value="TI" checked="checked" >
                                            <label class="custom-control-label" for="level1">TI</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="level2" name="prodi" class="custom-control-input" value="SI" >
                                            <label class="custom-control-label" for="level2">SI</label>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="form-group"  ">
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
    function getData() {
        $('#userData').DataTable().ajax.reload();
        $('#userData1').DataTable().ajax.reload();
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
            url: '<?php echo site_url('Matakuliah/'); ?>'+ type,
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
                    getData();
                }
                frmElement.find('form').css("opacity", "");
            }
        });
    }

    // Fill the user's data in the edit form
    function editUser(id) {
       document.getElementById("kode").setAttribute("readonly", true);
       document.getElementById("nama").setAttribute("readonly", true);
        $.post("<?php echo base_url('matakuliah/EditData'); ?>", {
            Id: id
        }, function (data) {
            $('#id').val(id);
            $('#kode').val(data['matakuliah'][0].Kd_matkul);
            $('#nama').val(data['matakuliah'][0].Nama);
            $('input:radio[name="prodi"]').filter('[value="' + data['matakuliah'][0].Id_prodi + '"]').attr('checked', true);
            $('#sks').val(data['matakuliah'][0].Sks);
            $('#semester').val(data['matakuliah'][0].Semester);
        }, "json");
        getData();
    }

    // Actions on modal show and hidden events
    $(function () {
        $('#modalUserAddEdit').on('show.bs.modal', function (e) {
            var type = $(e.relatedTarget).attr('data-type');
            var userFunc = "userAction('add');";
            $('#hlabel').text('Tambah');
            document.getElementById("kode").removeAttribute("readonly");
            document.getElementById("nama").removeAttribute("readonly");
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
    //datatables
    var table = $('#userData').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            'order': [[3, 'asc']],
             "pageLength": 5,
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('matakuliah/jsonDataTI'); ?>',
                "type": "POST"
            },
            "columnDefs": [ { "orderable": false, "targets": [4]}],
            //Set column definition initialisation properties.
            "columns": [
                //{"data": "UUID"},
                {"data": "Kd_matkul"},
                {"data": "Nama"},
                {"data": "Sks"},
                {"data": "Semester"},
                {"data": "action"},
            ],
    });

    var table1 = $('#userData1').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            'order': [[3, 'asc']],
             "pageLength": 5,
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('matakuliah/jsonDataSI'); ?>',
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columns": [
                //{"data": "UUID"},
                {"data": "Kd_matkul"},
                {"data": "Nama"},
                {"data": "Sks"},
                {"data": "Semester"},
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
