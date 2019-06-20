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
      <!-- <div class="float-right">
          <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Upload Dokumen Baru</a>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="<?php echo site_url('Dokumen/index_tipe');?>" class="btn btn-success" ><i class="plus"></i>Tipe Dokumen</a>
      </div> -->
      <br>
      <!-- Main row -->
      <div class="row">
        <?php if(!empty($Surat_Keterangan_saya)) { ?>
            <section class="col-lg-12">
             <div class="float-right">
                <a href="<?php echo site_url('Surat_Keterangan/Form_Keterangan');?>" class="btn btn-info" ><i class="plus"></i>Ajukan Surat Keterangan</a>
            </div>
            <br>
            <!-- Data list table --> 
                <table class="table table-striped table-bordered" id="userData" width="100%">
                    <thead class="thead-dark">
                        <tr>
                            <th>Nomor Surat</th>
                            <th>Jenis Surat Keterangan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            
            </section>
        <?php }else{ ?>
            <div class="row" style="padding:30px;">
            <div class="col-md-12">
                <div class="box box-solid box-danger">
                    <div class="box-header with-border">
                        <center><h3 class="box-title">Permohonan Surat Keterangan</h3></center>
                    </div>
                    <div class="box-body">
                        <center><h4 ><b>Anda Belum Mengajukan Surat Keterangan ! </b></h4></center>
                        <center><a href="<?php echo site_url('Surat_Keterangan/Form_Keterangan');?>" class="btn btn-info">Ajukan Surat</a></center>
                    </div>
                </div>
            </div>
            </div>
         <?php } ?> 
      </div>
      <!-- /.row (main row) -->

     
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
      
        $.post("<?php echo base_url('Dokumen/userData/'); ?>", {
            Id: id
        }, function (data) {
            $('#id').val(id);
            $('#nomor').val(data['dokumen'].Nomor);
            $('#tipe').val(data['dokumen'].Id_tipe_dokumen);
            $('#judul').val(data['dokumen'].Judul);

            $('#deskripsi').val(data['dokumen'].Keterangan);
            $('#lama').val( data['dokumen'].File);
        }, "json");
        getData();
    }

    // Actions on modal show and hidden events
    $(function () {
        $('#modalUserAddEdit').on('show.bs.modal', function (e) {
            var type = $(e.relatedTarget).attr('data-type');
            var userFunc = "userAction('add');";
            $('#hlabel').text('Tambah');
            $('.show123').hide();

            if (type == 'edit') {
                userFunc = "userAction('edit');";
                var rowId = $(e.relatedTarget).attr('rowID');
                //  editUser(rowId);
                $('.show123').show();
                //$('#hlabel').text('View');
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
            'order': [[2, 'asc']],
            'searching': false,
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('Surat_Keterangan/jsonDataSaya'); ?>',
                "type": "POST"
            },
            "bInfo" : false,
            //Set column definition initialisation properties.
            "columns": [
                //{"data": "UUID"},
                {"data": "Nomor"},
                {"data": "Keterangan"},
                {"data": null,
                    render: function ( data, type, row ) {
                    // Combine the first and last names into a single table field
                        if(data.Status == '0'){
                            return '<center><label style="padding: 5px;margin-top: 6px;display: block;" class="label bg-red">Belum di Proses</label></center>';
                        }
                        else if(data.Status == '1'){
                            return '<center><label style="padding: 5px;margin-top: 6px;display: block;" class="label bg-yellow">Sedang di Proses</label></center>';
                        }
                        else if(data.Status == '2'){
                            return '<center><label style="padding: 5px;margin-top: 6px;display: block;" class="label bg-green">Selesai di Proses</label></center>';
                        }
                    }
                }
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
