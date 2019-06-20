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
        <?php echo $title ."<br>"; ?>
      </h1>
      <h4>
        <?php echo $NamaKelas->Matakuliah ."- Kelas(".$NamaKelas->Kelas.")"; ?>
      </h4>
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
          <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah Materi</a>
         &nbsp; &nbsp; &nbsp;
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
            <table class="table table-striped table-bordered" id="userData" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Pertemuan</th>
                        <th>Deskripsi</th>
                        <th>File</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </section>
      </div>
      <!-- /.row (main row) -->

      <!-- Modal Add and Edit Form  user-->
        <div class="modal fade" id="modalUserAddEdit" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title"><span id="hlabel">Ubah</span> Materi</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <?php echo form_open_multipart('Materi/add','role="form" ');?>
                        <input type="hidden" class="form-control" name="id" id="id"/>
                        <input type="hidden" class="form-control" name="id_kelas" id="id_kelas" value="<?php echo $params;?>"/>
                            <div class="form-group">
                              <label>Pertemuan</label>
                                <select class="form-control" id="pertemuan" name="pertemuan">
                                <option value="">-- Pertemuan Ke --</option>
                                  <option value="1">Pertemuan 1</option>
                                  <option value="2">Pertemuan 2</option>
                                  <option value="3">Pertemuan 3</option>
                                  <option value="4">Pertemuan 4</option>
                                  <option value="5">Pertemuan 5</option>
                                  <option value="6">Pertemuan 6</option>
                                  <option value="7">Pertemuan 7</option>
                                  <option value="8">Pertemuan 8</option>
                                  <option value="9">Pertemuan 9</option>
                                  <option value="10">Pertemuan 10</option>
                                  <option value="11">Pertemuan 11</option>
                                  <option value="12">Pertemuan 12</option>
                                  <option value="13">Pertemuan 13</option>
                                  <option value="14">Pertemuan 14</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Keterangan Dokumen</label>
                                <textarea class="form-control" rows="3" name="deskripsi" id="deskripsi" placeholder="Masukan Keterangan"></textarea>                     
                            </div>
                            <div class="form-group">
                                <label>File</label><br>
                                <span>*File yang dapat di upload ber-ekstensi .pdf .xls .doc .ppt</span>
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
            url: '<?php echo site_url('Materi/'); ?>'+ type,
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
        document.getElementById("pertemuan").setAttribute("readonly", true);
        $.post("<?php echo base_url('Materi/EditData/'); ?>", {
            Id: id
        }, function (data) {
            $('#id').val(id);
            $('#pertemuan').val(data['materi'][0].Pertemuan);
            $('#deskripsi').val(data['materi'][0].Deskripsi);
            $('#lama').val(data['materi'][0].File);
        }, "json");
        getUsers();
    }

    // Actions on modal show and hidden events
    $(function () {
        $('#modalUserAddEdit').on('show.bs.modal', function (e) {
            var type = $(e.relatedTarget).attr('data-type');
            var userFunc = "userAction('add');";
            document.getElementById("pertemuan").removeAttribute("readonly");
            $('.show123').hide();
            $('#hlabel').text('Tambah');
            if (type == 'edit') {
                userFunc = "userAction('edit');";
                var rowId = $(e.relatedTarget).attr('rowID');
                $('.show123').show();
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

<!-- script upload -->
<!-- <script>
  $('#submitUpload').on('click', function(e){
    
    console.log('klik');
    $.ajax({
      type:'POST',
      url:'',
      data: $('#form_upload').serialize(),
      //success: function (resp) {}
    })
  });
  
</script> -->
<!-- endscript upload -->

<!-- Script Datatables -->
<script>
$(document).ready(function() {
    //datatables
    var table = $('#userData').DataTable({ 
            "processing": true, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            'order': [[0, 'asc']],
            'searching': false, //'paging': false, 'info': false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('Materi/jsonUploadMateri/'.$params); ?>',
                "type": "POST"
            },
            "columnDefs": [ { "orderable": false, "targets": [3]}],
            //Set column definition initialisation properties.
            "columns": [
                //{"data": "UUID"},
                {"data": null,
                    render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    return 'Pertemuan Ke-'+data.Pertemuan;
                    }
                },
                {"data": null,
                    render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    return data.Deskripsi
                    }
                },
                {"data": null,
                    render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                    return data.File;
                    }
                },
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
