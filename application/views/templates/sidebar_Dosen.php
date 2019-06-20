<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="<?=(current_url()==base_url('Beranda')) ? 'active':''?>">
            <a href="<?php echo site_url('Beranda')?>">
                <i class="fa fa-dashboard"></i> 
                <span>Beranda</span>
            </a>
        </li>
        
        

        <li class="treeview <?=(current_url()==base_url('Jadwalkuliah/Jadwalbaru')|current_url()==base_url('JadwalKuliah')|current_url()==base_url('Jadwalujian/Jadwalbaru')|current_url()==base_url('Jadwalujian')|current_url()==base_url('Tugas')|current_url()==base_url('Materi/materi_dosen')|current_url()==base_url('Materi')|current_url()==base_url('Kelas')|current_url()==base_url('Ujian'))? 'active':''?>">
          <a href="#">
            <i class="fa fa-laptop"></i>
            <span>Kuliah</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?=(current_url()==base_url('Materi/materi_dosen'))? 'active':''?>"><a href="<?php echo site_url('Materi/materi_dosen');?>"><i class="fa fa-users"></i>Tugas & Bahan Ajar</a></li>           
          </ul>
        </li>
                
        <li class="treeview <?=(current_url()==base_url('Skripsi')|current_url()==base_url('Manageuser')|current_url()==base_url('Pengumuman')|current_url()==base_url('Notifikasi')|current_url()==base_url('Dokumen'))? 'active':''?>">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Administrasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
           <li class="<?=(current_url()==base_url('Dokumen'))? 'active':''?>"><a href="<?php echo site_url('Dokumen');?>"><i class="fa fa-folder-open-o"></i> Dokumen</a></li>
           <li class="<?=(current_url()==base_url('Skripsi'))? 'active':''?>"><a href="<?php echo site_url('Skripsi');?>"><i class="fa  fa-file-pdf-o"></i> Skripsi</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>