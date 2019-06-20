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
            <li class="treeview <?=(current_url()==base_url('Jadwalkuliah/Jadwalbaru')|current_url()==base_url('JadwalKuliah'))? 'active':''?>">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Jadwal Kuliah</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(current_url()==base_url('Jadwalkuliah/Jadwalbaru'))? 'active':''?>"><a href="<?php echo site_url('Jadwalkuliah/Jadwalbaru');?>"><i class="fa fa-calendar-plus-o"></i>Buat Jadwal Kuliah</a></li>
                    <li class="<?=(current_url()==base_url('Jadwalkuliah'))? 'active':''?>"><a href="<?php echo site_url('JadwalKuliah');?>"><i class="fa fa-calendar-check-o"></i>Lihat Jadwal</a></li>
                </ul>
            </li>
            <li class="treeview <?=(current_url()==base_url('Jadwalujian/Jadwalbaru')|current_url()==base_url('Jadwalujian'))? 'active':''?>">
                <a href="#">
                    <i class="fa fa-calendar"></i> <span>Jadwal Ujian</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(current_url()==base_url('Jadwalujian/Jadwalbaru'))? 'active':''?>"><a href="<?php echo site_url('Jadwalujian/Jadwalbaru');?>"><i class="fa fa-calendar-plus-o"></i>Buat Jadwal Ujian</a></li>
                    <li class="<?=(current_url()==base_url('Jadwalujian'))? 'active':''?>"><a href="<?php echo site_url('Jadwalujian');?>"><i class="fa fa-calendar-check-o"></i>Lihat Jadwal</a></li>
                </ul>
            </li>
            <li class="<?=(current_url()==base_url('Materi'))? 'active':''?>"><a href="<?php echo site_url('Materi');?>"><i class="fa fa-users"></i> Materi Kuliah</a></li>
            <li class="<?=(current_url()==base_url('Tugas'))? 'active':''?>"><a href="<?php echo site_url('Tugas');?>"><i class="fa fa-users"></i> Tugas Kuliah</a></li>
           
          </ul>
        </li>
                
        <li class="treeview <?=(current_url()==base_url('Surat_KP')|current_url()==base_url('Surat_Keterangan')|current_url()==base_url('Skripsi')|current_url()==base_url('Manageuser')|current_url()==base_url('Pengumuman')|current_url()==base_url('Notifikasi')|current_url()==base_url('Dokumen'))? 'active':''?>">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Administrasi</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="treeview <?=(current_url()==base_url('Surat_KP')|current_url()==base_url('Surat_Keterangan'))? 'active':''?>">
                <a href="#">
                    <i class="fa fa-clipboard"></i> <span>Surat</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?=(current_url()==base_url('Surat_KP'))? 'active':''?>"><a href="<?php echo site_url('Surat_KP');?>"><i class="fa fa-file-text-o"></i>Surat KP</a></li>
                    <li class="<?=(current_url()==base_url('Surat_Keterangan'))? 'active':''?>"><a href="<?php echo site_url('Surat_Keterangan');?>"><i class="fa fa-file-text-o"></i> Surat Keterangan</a></li>
                </ul>
            </li>
            <li class="<?=(current_url()==base_url('Skripsi'))? 'active':''?>"><a href="<?php echo site_url('Skripsi');?>"><i class="fa  fa-file-pdf-o"></i> Skripsi</a></li>
          </ul>
        </li>

      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>