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
      <!-- <div class="float-right">
          <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah Dosen</a>
         &nbsp; &nbsp; &nbsp;
          <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#upload"><i class="plus"></i>Upload Dosen</a>
      </div> -->
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
        
        <div class="row">
          <div class="col-md-12">
            <?php if(!empty($Surat_KP_saya)) { ?>
                <br>
                <div class="box box-solid box-primary ">
                    <div class="box-header with-border">
                        <h3 class="box-title"><center>Permohonan Surat Kerja Praktik</center></h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <center><h4><b>Anda telah mengajukan Permohonan Surat Kerja Praktik</b></h4></center>
                        <!-- Status & tanggal -->
                        <center>
                            <?php foreach($Surat_KP_master as $row){ $status = $row['Status']; $created = $row['created']; $modified = $row['modified'];}?>
                            <div style="margin-top: 30px;margin-left: 200px; margin-right: 200px;" class="<?php if($status == '0'){echo "alert alert-danger";}else if($status == '1'){echo "alert alert-warning";}else{ echo "alert alert-success";}?>">
                                <h4><i class="icon fa fa-info"></i><b>Status</b></h4>
                                <?php if($status == '0'){
                                    $date = date_format(new DateTime($created),'d F Y');
                                    echo " <b>Surat Belum di Belum di Proses</b><br>";
                                    echo "<b>Diajukan : </b>".$date;
                                }
                                else if($status == '1'){
                                    $date = date_format(new DateTime($modified),'d F Y');
                                    echo " <b>Surat Sedang di Proses</b><br>";
                                    echo "<b>Diproses : </b>".$date;
                                }
                                else if($status == '2'){
                                    $date = date_format(new DateTime($modified),'d F Y');
                                    echo " <b>Surat Dapat di Ambil di Sekretariat R Lantai 11</b><br>";
                                    echo "<b>Selesai : </b>".$date;
                                }
                                ?>
                            </div>
                        </center>

                        <!-- Nama PT -->
                        <center>
                            <?php $i=1; foreach($Surat_KP_saya as $row){ ?>
                                <p><b>Perusahaan </b> </p>
                                <p><?php echo $row['Nama_PT'] ?></p>
                            <?php } ?>
                        </center>

                        <!-- NIM Anggota -->
                        <center>
                            <?php $i=1; foreach($Surat_KP_detail as $row){ ?>
                                <p><?php echo "<b>NIM Anggota Ke-". $i++."</b> : " .$row['NIM'] ?></p>
                            <?php } ?>
                        </center>
                        

                    </div>
                </div>
            <?php }else{ ?>
              <div class="box box-solid box-danger">
                  <div class="box-header with-border">
                    <center><h3 class="box-title">Permohonan Surat Kerja Praktik</h3></center>
                  </div>
                  <div class="box-body">
                    <center><h4 ><b>Anda Belum Mengajukan Surat Kerja Praktik ! </b></h4></center>
                    <center><a href="<?php echo site_url('Surat_KP/Form_KP');?>" class="btn btn-info">Ajukan Surat</a></center>
                  </div>
              </div>
            <?php } ?> 
          </div>
           
        <br>
        
        
        <!-- /.box -->

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
            
            // 'columnDefs': [
            //   {
            //       'targets': 0,
            //       'checkboxes': {
            //         'selectRow': true
            //       }
            //   }
            // ],
            // 'select': {
            //   'style': 'multi'
            // },
            "pageLength": 5,
            "lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
            'order': [[1, 'asc']],
            // Load data for the table's content from an Ajax source
            "ajax": {
                "url": '<?php echo site_url('Dosen/jsonDataDosen'); ?>',
                "type": "POST"
            },
            "columnDefs": [ 
              { "orderable": false, "targets": [0,3]},
              // { "targets": 0,
              //   "render": function(data) {
              //     return '<center><img src="' +data+ '" alt="user image" class="offline" style ="width:40px; height:40px; "></center>'
              //   }
              // }   
            ],
            //Set column definition initialisation properties.
            "columns": [
                {"data":"Nid"},
              //{"data": "UUID"},
                {"data": "Nama"},
                {"data": "Id_prodi"},
                {"data": "action"},
            ],
    });
      // Handle form submission event 
      // $('#form_check').on('submit', function(e){
      //     var form = this;
          
      //     var rows_selected = table.column(0).checkboxes.selected();

      //     // Iterate over all selected checkboxes
      //     $.each(rows_selected, function(index, rowId){
      //       // Create a hidden element 
      //       $(form).append(
      //           $('<input>')
      //               .attr('type', 'hidden')
      //               .attr('name', 'id[]')
      //               .val(rowId)
      //       );
      //     });

      //     $.ajax({
      //      type: "POST",
      //      url: "<?php echo site_url('Manageuser/acc'); ?>",
      //      data: form.serialize(), // serializes the form's elements.
      //    });
          
      // });   
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
