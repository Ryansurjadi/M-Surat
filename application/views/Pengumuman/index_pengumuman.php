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
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      <br>
      <div class="float-right">
          <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah Pengumuman</a>
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
        
          <!-- Data list table --> 
            <table class="table table-striped table-bordered" id="userData" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th>Judul Pengumuman</th>
                        <th>Isi Pengumuman</th>
                        <th>Lampiran Pengumuman</th>
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
                        <h4 class="modal-title"><span id="hlabel">Tambah</span> Pengumuman</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <?php echo form_open_multipart('Pengumuman/add','role="form" ');?>
                        <input type="hidden" class="form-control" name="id" id="id"/>
                            <div class="form-group">
                                <label>Judul Pengumuman</label>
                                <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukan Nama" >
                            </div>
                            <div class="form-group">
                                <label>Isi Pengumuman</label>
                                <textarea class="form-control" rows="3" name="isi" id="isi" placeholder="Masukan Isi Pengumuman" ></textarea>
                            </div>
                            <div class="form-group remove">
                                <label>Lampiran Pengumuman</label>
                                <span>*File yang dapat di upload ber-ekstensi .jpg|.jpeg|.png</span>
                                <input type="file" class="form-control" name="userFile"  placeholder="Masukan Lampiran" >
                            </div>
                            <div class="form-group show123">
                                <center><img id="imgshow" alt="user image" class="offline" style ="width:540px; height:340px; "></center>
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
    function getData() {
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
            userData = 'Id=' + id;
        }

        frmElement.find('.statusMsg').html('');
        $.ajax({
            type: "POST",
            url: '<?php echo site_url('Pengumuman/'); ?>'+ type,
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
      
        $.post("<?php echo base_url('Pengumuman/userData/'); ?>", {
            Id: id
        }, function (data) {
            $('#id').val(id);
            $('#judul').val(data['pengumuman'].Judul_informasi).attr('readonly', true);
            $('#isi').val(data['pengumuman'].Isi).attr('readonly', true);
            document.getElementById("imgshow").src = data['pengumuman'].Lampiran;
            $('#img').val(data['pengumuman'].Lampiran);
        }, "json");
        getData();
    }

    // Actions on modal show and hidden events
    $(function () {
        $('#modalUserAddEdit').on('show.bs.modal', function (e) {
            var type = $(e.relatedTarget).attr('data-type');
            var userFunc = "userAction('add');";
            $('#hlabel').text('Tambah');
            $(".show123").hide();
            $(".remove").show();
            document.getElementById("judul").removeAttribute("readonly");
            document.getElementById("isi").removeAttribute("readonly");

            if (type == 'edit') {
                userFunc = "userAction('edit');";
                $(".remove").hide();
                $(".show123").show();
                var rowId = $(e.relatedTarget).attr('rowID');
                editUser(rowId);
                $('#hlabel').text('Lihat');
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
            // 'order': [[1, 'asc']],
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('Pengumuman/jsonData'); ?>',
                "type": "POST"
            },
            //Set column definition initialisation properties.
            "columnDefs": [ 
              { "orderable": false, "targets": [0,1,2,3]},
              { "targets": 2,
                "render": function(data) {
                  return '<center><img src="' +data+ '" alt="user image" class="offline" style ="width:200px; height:150px; "></center>'
                }
              }   
            ],
            "columns": [
                //{"data": "UUID"},
                {"data": "Judul_informasi"},
                {"data": "Isi"},
                {"data": "Lampiran"},
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
