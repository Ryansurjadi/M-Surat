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
          <a href="#" class="btn btn-success" data-type="add" data-toggle="modal" data-target="#modalUserAddEdit"><i class="plus"></i>Tambah User</a>
         &nbsp; &nbsp; &nbsp;
          <a href="#" class="btn btn-success"  data-toggle="modal" data-target="#upload"><i class="plus"></i>Upload User</a>
      </div>
      <br>
      <!-- Main row -->
      <div class="row">
        <section class="col-lg-12">
        
          <!-- Data list table --> 
            <table class="table table-striped table-bordered" id="userData" width="100%">
                <thead class="thead-dark">
                    <tr>
                        <th></th>
                        <th>Email</th>
                        <th>Level</th>
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
                        <h4 class="modal-title"><span id="hlabel">Tambah</span> User</h4>
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
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="Masukan Email" >
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Masukan Password" >
                            </div>
                            <div class="form-group">
                                <label>Level</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="level1" name="level" class="custom-control-input" value="Mahasiswa" checked="checked" >
                                    <label class="custom-control-label" for="level1">Mahasiswa</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="level2" name="level" class="custom-control-input" value="Dosen" >
                                    <label class="custom-control-label" for="level2">Dosen</label>
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
                      <h4 class="modal-title"><span id="hlabel">Upload File Excel</span> User</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <!-- Modal Body -->
                  <div class="modal-body">
                    <div class="statusMsg"></div>
                     <?php echo form_open_multipart('Manageuser/UploadAction/','class="form-horizontal form-groups-bordered" ');?>
                          <!-- <div class="form-group" style="padding:15px; ">
                              <label>Pilih Keterangan</label>
                              <select class="form-control" id="keterangan" name="keterangan">
                                <option value="">-- Keterangan --</option>
                                <option value="Tambah">Tambah User</option>
                                <option value="Update">Update User</option>
                              </select>
                          </div> -->
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
            url: '<?php echo site_url('Manageuser/'); ?>'+ type,
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
      document.getElementById("nama").setAttribute("readonly", true);
        $.post("<?php echo base_url('Manageuser/userData/'); ?>", {
            UUID: id
        }, function (data) {
            $('#id').val(id);
            if(data['user'].Level === 'Mahasiswa'){
               $('#nomor').val(data['mahasiswa'].NIM);
               $('#nama').val(data['mahasiswa'].Nama);
            }
            else{
              $('#nomor').val(data['dosen'].NID);
              $('#nama').val(data['dosen'].Nama);
            }
           
            $('#email').val(data['user'].Email);
            $('#password').val(data['user'].Password);
            $('input:radio[name="level"]').filter('[value="' + data['user'].Level + '"]').attr('checked', true);
        }, "json");
        getUsers();
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
                "url": '<?php echo site_url('Manageuser/jsonDatatables'); ?>',
                "type": "POST"
            },
            "columnDefs": [ 
              { "orderable": false, "targets": [0,3]},
              { "targets": 0,
                "render": function(data) {
                  return '<center><img src="' +data+ '" alt="user image" class="offline" style ="width:40px; height:40px; "></center>'
                }
              }   
            ],
            //Set column definition initialisation properties.
            "columns": [
                {"data":"Foto"},
              //{"data": "UUID"},
                {"data": "Email"},
                {"data": "Level"},
                {"data": "action"},
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
           url: "<?php echo site_url('Manageuser/acc'); ?>",
           data: form.serialize(), // serializes the form's elements.
         });
          
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
