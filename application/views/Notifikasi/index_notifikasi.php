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
        Notifikasi
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
     
        <!-- <div class="float-right">
            <a href="#" class="btn btn-success" data-type="add1" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Kirim Notifikasi Mahasiswa</a>
            &nbsp;
            <a href="#" class="btn btn-success" data-type="add2" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Kirim Notifikasi Dosen</a>
            &nbsp;
            <a href="<?php echo base_url('notifikasi/sendMessage');?>" class="btn btn-info"><i class="plus"></i>Push</a>
        </div> -->
      <br>
      <!-- Main row -->
      <div class="row">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Mahasiswa</a></li>
              <li><a href="#tab_2" data-toggle="tab">Dosen</a></li>
              <!-- <li><a href="#tab_3" data-toggle="tab">Kelas</a></li> -->
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1" >
                <form id="form_check" action="<?php echo site_url('Notifikasi/getSender'); ?>" method="POST">
                  <div class="form-group">
                    <label>Judul Notifikasi</label>
                    <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukan Judul" >
                  </div>
                  <div class="form-group">
                    <label>Isi Notifikasi</label>
                    <input type="text" class="form-control" name="isi" id="isi" placeholder="Masukan Isi Notifikasi" >
                  </div>
                  <br>
                  <input type="submit" class="btn btn-info " id="submit" value="Kirim Notifikasi" style=" float: right; padding: 5px; margin-bottom:5px;">
                  <br> <br> <br>
                  <div class="form-group">
                    <label>Penerima</label>
                  <!-- Data list table --> 
                  <table class="table table-striped table-bordered" id="userMahasiswa" width="100%">
                    <thead class="thead-dark" >
                      <tr>
                        <th></th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Angkatan</th>
                        <!-- <th>Action</th> -->
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
			         <div class="tab-pane" id="tab_2" >
                <form id="form_check1" action="<?php echo site_url('Notifikasi/getSender'); ?>" method="POST">
                  <div class="form-group">
                    <label>Judul Notifikasi</label>
                    <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukan Judul" >
                  </div>
                  <div class="form-group">
                    <label>Isi Notifikasi</label>
                    <input type="text" class="form-control" name="isi" id="isi" placeholder="Masukan Isi Notifikasi" >
                  </div>
                  <br>
                  <input type="submit" class="btn btn-info" id="submit" value="Kirim Notifikasi" style=" float: right; padding: 5px; margin-bottom:5px;">
                  <br> <br> <br>
                  <div class="form-group">
                    <label>Penerima</label>
                    <!-- Data list table --> 
                    <table class="table table-striped table-bordered" id="userDosen" width="100%">
                      <thead class="thead-dark">
                        <tr>
                          <th ></th>
                          <th >Nid</th>
                          <th >Nama</th>
                          <!-- <th>Action</th> -->
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
             
              <div class="tab-pane" id="tab_3">
                <form id="form_check2" action="<?php echo site_url('Notifikasi/getSenderKelas'); ?>" method="POST">
                  
                  <div class="form-group">
                    <label>Judul Notifikasi</label>
                    <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukan Judul" >
                  </div>
                  <div class="form-group">
                    <label>Isi Notifikasi</label>
                    <input type="text" class="form-control" name="isi" id="isi" placeholder="Masukan Isi Notifikasi" >
                  </div>
                  <br>
                  <input type="submit" class="btn btn-info" id="submit" value="Kirim Notifikasi" style=" float: right; padding: 5px; margin-bottom:5px;">
                  <br> <br> <br>
                  <div class="form-group">
                    <label>Penerima</label>
                  <!-- Data list table --> 
                  <table class="table table-striped table-bordered" id="userKelas" width="100%">
                    <thead class="thead-dark">
                      <tr style="width:100%; ">
                        <th style="width:30%; "></th>
                        <th style="width:30%; ">Nama</th>
                        <th style="width:30%; ">Kelas</th>
                        <th style="width:30%; ">Hari</th>
                        <!-- <th>Action</th> -->
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                  </div>
                </form>
              </div>
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        <!-- /.col -->
      </div>
      <!-- /.row -->
    
      <!-- /.row (main row) -->

      <!-- Modal Add and Edit Form -->  
        <div class="modal fade" id="modalUserAddEdit" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Notifikasi <span id="hlabel"> Individu</span></h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <div class="statusMsg"></div>
                        <form role="form">
                        <input type="hidden" class="form-control" name="form_id" id="form_id"/>
                            <div class="form-group" id=form_nomor>
                                <label>Nomor Induk</label><br>
                                <select class="form-control select2" style="width:100%;" id='nomor' name="nomor">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Judul Notifikasi</label>
                                <input type="text" class="form-control" name="judul" id="judul" placeholder="Masukan Judul" >
                            </div>
                            <div class="form-group">
                                <label>Isi Notifikasi</label>
                                <input type="text" class="form-control" name="isi" id="isi" placeholder="Masukan Isi Notifikasi" >
                            </div>
                        </form>
                    </div>
                    
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="userSubmit" >Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <?php $this->load->view('templates/footer'); ?>
</div>
<!-- ./wrapper -->

<?php $this->load->view('templates/toast_message'); ?>

<!-- Select2 Nomor induk -->
<script>
    $('#nomor').select2({
    placeholder: '--- Pilih Nomor Induk ---',
    ajax: {
        url: '<?php echo base_url('Notifikasi/getList'); ?>',
        dataType: 'json',
        delay: 250,
        data: function(params){
            return{
                no_induk: params.term
            };
        },
        processResults: function (data) {
            var results = [];
                $.each(data, function(index, item){
                    results.push({
                        id: item.UUID,
                        text: item.NIM +" - "+ item.Nama
                    });
                });
                return{
                    results: results
                };
        },
        cache: true
    }
    });
    
</script>
<!-- end select2 -->

<!-- Script Datatables mahasiswa -->
<script>
  $(document).ready(function() {
      //datatables
      var table = $('#userMahasiswa').DataTable({ 
              "processing": true, //Feature control the processing indicator.
              "serverSide": true, //Feature control DataTables' server-side processing mode.
              //"pageLength": 5,
              //"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
              'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                      'selectRow': true
                    }
                }
              ],
              'select': {
                'style': 'multi'
              },
              'order': [[1, 'asc']],
              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": '<?php echo site_url('Notifikasi/jsonMahasiswa'); ?>',
                  "type": "POST"
              },
              "scrollY":        200,
              "scrollCollapse": true,
              "paging":         false,
              //Set column definition initialisation properties.
              "columns": [
                  {"data": "UUID"},
                  {"data": "NIM"},
                  {"data": "Nama"},
                  {"data": "Angkatan"},
                  //{"data": "action"},
              ],
      });

      var table1 = $('#userDosen').DataTable({ 
              "processing": true, //Feature control the processing indicator.
              "serverSide": true, //Feature control DataTables' server-side processing mode.
              //"pageLength": 5,
              //"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
              'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                      'selectRow': true
                    }
                }
              ],
              'select': {
                'style': 'multi'
              },
              'order': [[2, 'asc']],
              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": '<?php echo site_url('Notifikasi/jsonDosen'); ?>',
                  "type": "POST"
              },
              "scrollY":        200,
              "scrollCollapse": true,
              "paging":         false,
              //Set column definition initialisation properties.
              "columns": [
                  {"data": "UUID"},
                  {"data": "Nid"},
                  {"data": "Nama"},
                  //{"data": "action"},
              ],
      });

      var table2 = $('#userKelas').DataTable({ 
              "processing": true, //Feature control the processing indicator.
              "serverSide": true, //Feature control DataTables' server-side processing mode.
              //"pageLength": 5,
              //"lengthMenu": [[5,10, 25, 50, -1], [5,10, 25, 50, "All"]],
              'columnDefs': [
                {
                    'targets': 0,
                    'checkboxes': {
                      'selectRow': true
                    }
                }
              ],
              'select': {
                'style': 'multi'
              },
              'order': [[1, 'asc']],
              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": '<?php echo site_url('Notifikasi/jsonDataKelas'); ?>',
                  "type": "POST"
              },
              "scrollY":        200,
              "scrollCollapse": true,
              "paging":         false,
              //Set column definition initialisation properties.
              "columns": [
                  {"data": "Id"},
                  {"data": "Nama"},
                  {"data": "Kelas"},
                  {"data": "Hari"},
                  //{"data": "action"},
              ],
      });

      // Handle form submission event 
      $('#form_check').on('submit', function(e){
          var form = this;
          
          var rows_selected = table.column(0).checkboxes.selected();

          // Iterate over all selected checkboxes
          $.each(rows_selected, function(index, rowId){
            // Create a hidden element 
            $(form).append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'id[]')
                    .val(rowId)
            );
          });

          $.ajax({
          type: "POST",
          url: "<?php echo site_url('Notifikasi/getSender'); ?>",
          data: form.serialize(), // serializes the form's elements.
        });
          // // FOR DEMONSTRATION ONLY
          // // The code below is not needed in production
          
          // // Output form data to a console     
          // $('#example-console-rows').text(rows_selected.join(","));
          
          // // Output form data to a console     
          // $('#example-console-form').text($(form).serialize());
          
          // // Remove added elements
          // $('input[name="id\[\]"]', form).remove();
          
          // // Prevent actual form submission
          // e.preventDefault();
      });
      
       $('#form_check1').on('submit', function(e){
          var form1 = this;
          
          var rows_selected1 = table1.column(0).checkboxes.selected();

          // Iterate over all selected checkboxes
          $.each(rows_selected1, function(index, rowId){
            // Create a hidden element 
            $(form1).append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'id[]')
                    .val(rowId)
            );
          });

          $.ajax({
          type: "POST",
          url: "<?php echo site_url('Notifikasi/getSender'); ?>",
          data: form1.serialize(), // serializes the form's elements.
        });
          // // FOR DEMONSTRATION ONLY
          // // The code below is not needed in production
          
          // // Output form data to a console     
          // $('#example-console-rows').text(rows_selected.join(","));
          
          // // Output form data to a console     
          // $('#example-console-form').text($(form).serialize());
          
          // // Remove added elements
          // $('input[name="id\[\]"]', form).remove();
          
          // // Prevent actual form submission
          // e.preventDefault();
      });
      
      $('#form_check2').on('submit', function(e){
          var form2 = this;
          
          var rows_selected2 = table2.column(0).checkboxes.selected();

          // Iterate over all selected checkboxes
          $.each(rows_selected2, function(index, rowId){
            // Create a hidden element 
            $(form2).append(
                $('<input>')
                    .attr('type', 'hidden')
                    .attr('name', 'id[]')
                    .val(rowId)
            );
          });

          $.ajax({
          type: "POST",
          url: "<?php echo site_url('Notifikasi/getSenderKelas'); ?>",
          data: form2.serialize(), // serializes the form's elements.
        });
          // // FOR DEMONSTRATION ONLY
          // // The code below is not needed in production
          
          // // Output form data to a console     
          // $('#example-console-rows').text(rows_selected.join(","));
          
          // // Output form data to a console     
          // $('#example-console-form').text($(form).serialize());
          
          // // Remove added elements
          // $('input[name="id\[\]"]', form).remove();
          
          // // Prevent actual form submission
          // e.preventDefault();
      });
  });
</script>
<!-- end Script Datatables mahasiswa -->





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
