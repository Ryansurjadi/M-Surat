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
        <li><a href="#"><i class="fa fa-dashboard"></i> Kuliah</a></li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      <br>
      <div class="float-right">
          <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah Kelas</a>
           &nbsp; &nbsp; &nbsp;
          <a href="#" class="btn btn-info"  data-toggle="modal" data-target="#upload"><i class="plus"></i>Upload Kelas</a>
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Kelas Teknik Informasi</a></li>
              <li><a href="#tab_2" data-toggle="tab">Kelas Sistem Informasi</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1" >
                  <!-- Data list table --> 
                  <table class="table table-striped table-bordered" id="KelasTI" width="100%">
                      <thead class="thead-dark">
                          <tr style="width:100%; ">
                              <th >Matakuliah</th>
                              <th >Kelas</th>
                              <th >Dosen</th>
                              <th >Hari</th>
                              <th >Jam</th>
                              <th>Action</th>
                          </tr>
                      </thead>
                      <tbody></tbody>
                  </table>
                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2" >
                  <!-- Data list table --> 
                    <table class="table table-striped table-bordered" id="KelasSI" width="100%">
                      <thead class="thead-dark">
                          <tr style="width:100%; ">
                              <th >Matakuliah</th>
                              <th >Kelas</th>
                              <th >Dosen</th>
                              <th >Hari</th>
                              <th >Jam</th>
                              <th>Action</th>
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
                        <h4 class="modal-title"><span id="hlabel">Tambah</span> Kelas</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <form role="form">
                        <input type="hidden" class="form-control" name="id" id="id"/>
                            <div class="form-group">
                              <label>Kode Kelas</label>
                              <input type="text" class="form-control" name="kode" id="kode" placeholder="Masukan Kode Kelas" >
                            </div>
                            <div class="form-group">
                              <label>Nama Matakuliah</label>
                              <select class="form-control" id="matkul" name="matkul">
                                <option value="">-- Nama Matakuliah --</option>
                              <?php foreach($matkul as $m){ ?>
                                <option value="<?php echo $m['Id_matkul']; ?>"><?php echo $m['Nama']; ?></option>
                              <?php }?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Dosen Pengajar</label>
                              <select class="form-control" id="dosen" name="dosen">
                                <option value="">-- Dosen Pengajar --</option>
                              <?php foreach($dosen as $each){ ?>
                                <option value="<?php echo $each['Nid']; ?>"><?php echo $each['Nama']; ?></option>
                              <?php }?>
                              </select>
                            </div>
                            <div class="form-group">
                                <label>Kelas</label>
                                <div class="row">
                                  <div class="col-md-6">
                                      <div class="custom-control custom-radio custom-control-inline">
                                          <input type="radio" id="kelas1" name="kelas" class="custom-control-input" value="A" checked="checked" >
                                          <label class="custom-control-label" for="kelas1">Kelas A</label>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="kelas2" name="kelas" class="custom-control-input" value="B" >
                                        <label class="custom-control-label" for="kelas2">Kelas B</label>
                                    </div>
                                  </div>
                                </div>
                            </div>    
                            <div class="form-group">
                              <label>Hari</label>
                                <select class="form-control" id="hari" name="hari">
                                <option value="">-- Hari --</option>
                                  <option value="Senin">Senin</option>
                                  <option value="Selasa">Selasa</option>
                                  <option value="Rabu">Rabu</option>
                                  <option value="Kamis">Kamis</option>
                                  <option value="Jumat">Jumat</option>
                                  <option value="Sabtu">Sabtu</option>
                                </select>
                            </div>
                            <div class="form-group">
                              <div class="row">
                                    <div class="col-md-6">
                                      <label>Jam Mulai</label>
                                      <input type="text" autocomplete="off" class="form-control timepicker" name="mulai" id="mulai" >
                                    </div>
                                    <div class="col-md-6">
                                      <label>Jam Selesai</label>
                                      <input type="text" autocomplete="off" class="form-control timepicker" name="selesai" id="selesai" >
                                    </div>
                              </div>
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
                      <h4 class="modal-title"><span id="hlabel">Upload File Excel</span> Kelas</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <!-- Modal Body -->
                  <div class="modal-body">
                    <div class="statusMsg"></div>
                     <?php echo form_open_multipart('Kelas/UploadAction/','role ="form" ');?>
                        <div class="form-group">
                              <label>Pilih Keterangan</label>
                              <select class="form-control" id="keterangan" name="keterangan">
                                <option value="">-- Keterangan --</option>
                                <option value="1">Tambah Kelas</option>
                                <option value="2">Update Kelas</option>
                              </select>
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

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>$.widget.bridge('uibutton', $.ui.button);</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<!-- Morris.js charts -->
<script src="<?php echo base_url('bower_components/raphael/raphael.min.js')?>"></script>
<script src="<?php echo base_url('bower_components/morris.js/morris.min.js')?>"></script>
<!-- bootstrap time picker -->
<script src="<?php echo base_url('plugins/timepicker/bootstrap-timepicker.min.js')?>"></script>
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



<!-- Script CRUD -->
<script>
    // Update the members data list
    function getDataTI() {
        $('#KelasTI').DataTable().ajax.reload();
    }
    function getDataSI() {
        $('#KelasSI').DataTable().ajax.reload();
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
            url: '<?php echo site_url('Kelas/'); ?>'+ type,
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
                    getDataTI();
                    getDataSI();
                }
                frmElement.find('form').css("opacity", "");
            }
        });
    }

    // Fill the user's data in the edit form
    function editUser(id) {
     // document.getElementById("nomor").setAttribute("readonly", true);
      //document.getElementById("nama").setAttribute("readonly", true);
        $.post("<?php echo base_url('Kelas/EditData'); ?>", {
            Id: id
        }, function (data) {
            $('#id').val(id);
            $('#kode').val(id);
            $('#kelas').val(data['kelas'][0].Kelas);
            $('#hari').val(data['kelas'][0].Hari);
            $('#dosen').val(data['kelas'][0].Nid);
            $('#matkul').val(data['kelas'][0].Id_matkul);
            $('#mulai').val(data['kelas'][0].Jam_mulai);
            $('#selesai').val(data['kelas'][0].Jam_selesai);
            $('input:radio[name="kelas"]').filter('[value="' + data['kelas'][0].Kelas + '"]').attr('checked', true);
        }, "json");
        getDataTI();
        getDataSI();
    }

    // Actions on modal show and hidden events
    $(function () {
        $('#modalUserAddEdit').on('show.bs.modal', function (e) {
            var type = $(e.relatedTarget).attr('data-type');
            var userFunc = "userAction('add');";
            $('#hlabel').text('Tambah');
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
    $('#mulai').timepicker({
      showMeridian: false,
      maxHours:24,
      showInputs: false,
      defaultTime: false,
    });

    $('#selesai').timepicker({
      showMeridian: false,
      maxHours:24,
      showInputs: false,
      defaultTime: false
    });

    //datatables mahasiswa TI
    var table = $('#KelasTI').DataTable({ 
              "processing": true, //Feature control the processing indicator.
              "serverSide": true, //Feature control DataTables' server-side processing mode.
              'order': [[0, 'asc']],
              "pageLength": 5,
              'searching': false,
              "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": '<?php echo site_url('Kelas/jsonKelasTI'); ?>',
                  "type": "POST"
              },
              "columnDefs": [ 
                { "orderable": false, "targets": [5]},  
              ],
              
              //Set column definition initialisation properties.
              "columns": [
                  {"data": "Nama"},
                  {"data": "Kelas"},
                  {"data": "Dosen"},
                  {"data": "Hari"},
                  {"data": null,
                    render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    return data.Jam_mulai+' - '+data.Jam_selesai;
                    }
                  },
                  {"data": "action"},
              ],
    });
    
    //datatables mahasiswa SI
    var table1 = $('#KelasSI').DataTable({ 
              "processing": true, //Feature control the processing indicator.
              "serverSide": true, //Feature control DataTables' server-side processing mode.
              'order': [[0, 'asc']],
              "pageLength": 5,
              'searching': false,
              "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": '<?php echo site_url('Kelas/jsonKelasSI'); ?>',
                  "type": "POST"
              },
              "columnDefs": [ 
                { "orderable": false, "targets": [5]},  
              ],
              //Set column definition initialisation properties.
              "columns": [
                  {"data": "Nama"},
                  {"data": "Kelas"},
                  {"data": "Dosen"},
                  {"data": "Hari"},
                  {"data": null,
                    render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    return data.Jam_mulai+' - '+data.Jam_selesai;
                    }
                  },
                  {"data": "action"},
              ],
    });

});
</script>
<?php $this->load->view('templates/toast_message'); ?>


</body>
</html>
