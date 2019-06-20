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
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
          <div class="row">
            <div class="col-md-6">
              <div class="box box-solid box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Pengumuman Akademik</h3>
                  </div>
                <div style="margin:10px">
                <?php if(!empty($pengumuman)){ ?>
                  <?php foreach($pengumuman as $p){ ?>
                    <div class="box box-solid box-primary collapsed-box ">
                      <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $p['Judul_informasi']; ?></h3>
                        <div class="box-tools pull-right">
                          <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div><!-- /.box-tools -->
                      </div><!-- /.box-header -->
                      <div class="box-body">
                      <center><img style="display:block; width:100%;height:320px;" id="base64image" src="<?php echo $p['Lampiran']; ?>"/></center>
                      <br>
                      <p><?php echo $p['Isi']; ?></p>
                      </div><!-- /.box-body -->
                    </div><!-- /.box -->
                  <?php } ?>
                <?php }else{ ?>
                 <center><h4><b>Belum Ada Pengumuman</b></h4></center>
                <?php } ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <!-- Calendar -->
              <div class="box box-solid bg-aqua-gradient">
                <div class="box-header">
                  <i class="fa fa-calendar"></i>
                  <h3 class="box-title">Calendar</h3>
                </div>
                <div class="box-body no-padding">
                  <!--The calendar -->
                  <div id="calendar" style="width: 100%"></div>
                </div>
              </div>
            </div>
          </div>
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
                        <form role="form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Judul Pengumuman</label>
                                <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukan Nama" >
                            </div>
                            <div class="form-group">
                                <label>Isi Pengumuman</label>
                                <input type="text" class="form-control" name="isi" id="isi" placeholder="Masukan Isi Pengumuman" >
                            </div>
                            <div class="form-group">
                                <label>Lampiran Pengumuman</label>
                                <input type="file" class="form-control" name="lampiran" id="lampiran" placeholder="Masukan Lampiran" >
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
            userData = 'UUID=' + id;
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
                    getUsers();
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
            $('#judul').val(data['pengumuman'].Judul_informasi);
            $('#isi').val(data['pengumuman'].Isi);
            $('#lampiran').val(data['pengumuman'].Lampiran);
        }, "json");
        getData();
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
