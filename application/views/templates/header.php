<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo base_url('Beranda');?>" class="logo" style="background-color: <?php echo $colors;?>">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>F</b>TI</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Digital Student</b> FTI</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" style="background-color: <?php echo $colors;?>;border-right: 0.1px solid #696969;">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <!-- <span class="label label-warning">10</span> -->
            </a>
            <ul class="dropdown-menu">
              <li class="header"><b>Notifikasi</b></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php 
                    $conditions = array('UUID'=>$this->session->userdata('UUID'), 'Status'=>'1');
                    $this->db->select('*');
                    $this->db->from('notifikasi');
                    $this->db->where($conditions);
                    $query = $this->db->get();
                  ?>
                  <?php if(!empty($query->result_array())){ ?>
                    <?php foreach ($query->result_array() as $n) { ?>
                      <li class="list-group-item">
                        <b><?php echo $n['Judul'];?></b><br>
                        <p><?php echo $n['Isi'];?></p>
                      </li>
                    <?php } ?>
                  <?php }
                  else{ ?>
                      <li class="list-group-item">  
                        <b>Tidak ada notifikasi</b>  
                      </li>
                 <?php } ?>
                </ul>
              </li>
              <li class="footer"></li>
            </ul>
          </li>
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo $this->session->userdata('Foto') ;?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo $this->session->userdata('Nama') ;?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header" style="background-color: <?php echo $colors;?>">
                <img src="<?php echo $this->session->userdata('Foto') ;?>" class="img-circle" alt="User Image">

                <p>
                  <?php echo $this->session->userdata('Nama') ;?>
                  <small><?php echo $this->session->userdata('Level') ;?></small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left" >
                  <a href="<?php echo site_url('Profil');?>" class="btn btn-default btn-flat" <?=($this->session->userdata('Level')=="Admin")? 'style="display:none"':''?>>Profil</a>
                </div>
                <div class="pull-right">
                  <a href="<?php echo site_url('Auth/logout');?>" class="btn btn-default btn-flat">Keluar</a>
                </div>
              </li>
            </ul>
          </li>

          <!-- Control Sidebar Toggle Button -->
          <!-- <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li> -->
        </ul>
      </div>
    </nav>
  </header>