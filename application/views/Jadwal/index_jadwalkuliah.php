<!DOCTYPE html>
<html>

<body class="hold-transition skin-blue sidebar-mini" >
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
        <li class="active">Jadwal Kuliah</li>
        <li class="active"><?php echo $title; ?></li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Initial content -->
      <br>
       <div class="float-right">
          <a href="<?php echo site_url('Jadwalkuliah/delete'); ?>" class="btn btn-danger"><i class="plus"></i>Hapus Jadwal</a>
         &nbsp; &nbsp; &nbsp;
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
           
                    <!-- Data list table --> 
                        <table class="table table-striped table-bordered" id="Kelas" width="100%">
                            <thead class="thead-dark">
                                <tr style="width:100%; "  class="table-dark">
                                    <th >Matakuliah</th>
                                    <th >Kelas</th>
                                    <th >Dosen</th>
                                    <th >Hari</th>
                                    <th >Jam</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    
                    <br> 
                   
        </section>
      </div>
      <!-- /.row -->
    
      <!-- /.row (main row) -->

      
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('templates/footer'); ?>
</div>
<!-- ./wrapper -->


<!-- Script Datatables  -->
<script>
  $(document).ready(function() {
      //datatables
      var table = $('#Kelas').DataTable({ 
              "processing": true, //Feature control the processing indicator.
              "serverSide": true, //Feature control DataTables' server-side processing mode.
              //"pageLength": 5,
              //"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
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
              "bPaginate": false,
              "bLengthChange": false,
              "bFilter": true,
              "bInfo": false,
              "searching": false,
              
              'order': [[3, 'desc']],
              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": '<?php echo site_url('Jadwalkuliah/jsonJadwal'); ?>',
                  "type": "POST"
              },
              "scrollY":        400,
              "scrollCollapse": true,
              "paging":         false,
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
<!-- end Script Datatables  -->

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
