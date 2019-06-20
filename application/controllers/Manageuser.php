<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manageuser extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load user model
        $this->load->model('user_model');
        $this->load->model('mahasiswa_model');
        $this->load->model('dosen_model');
    }
    
    public function index(){
        $data = array();
        
        // Get rows count
        $conditions['returnType']     = 'count';
        $rowsCount = $this->user_model->getRows($conditions);
        
        // Get user data
        $conditions['returnType'] = '';
        $data['user'] = $this->user_model->getRows($conditions);


        
        
        // Load the list page view
        $data['title'] = 'Kelola User';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('ManageUser/index_user', $data);
       
    }

    function jsonDataTables(){
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1'  
        );

        $atts2 = array(
            'class'       => 'btn btn-danger btn-sm',
            'onclick' => "return confirm('Hapus Data?')"
        );

        $this->load->library('datatables');
        $this->datatables->select('Foto,Email,Level, UUID as UUID');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'UUID');
        $this->datatables->from('users');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }
     
    public function userData(){
        $data = array();
        $id = $this->input->post('UUID');
        if(!empty($id)){
            // Fetch user data
            $data['user'] = $this->user_model->getRows(array('UUID'=>$id));
            $data['mahasiswa'] = $this->mahasiswa_model->getRows(array('UUID'=>$id));
            // Return data as JSON format
            echo json_encode($data);
        }
    }

    public function listView(){
        // Load the list view
        $this->load->view('ManageUser/user_view');
    }

    public function add(){
        $verr = $status = 0;
        $msg = '';
        $memData = array();
        
        // Get user's input
        $UUID = uniqid();
        $nomor = $this->input->post('nomor');
        $nama = $this->input->post('nama');
        $email = $this->input->post('email');
        $password = htmlspecialchars(md5($this->input->post('password', TRUE)));
        $level = $this->input->post('level');
        
        //base64 img
        $path = site_url('assets/images/default_profile.png');
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

        // Validate form fields
        if(empty($nomor)){
            $verr = 1;
            $msg .= 'Nomor Induk Belum di Input !<br/>';
        }
        if(empty($nama)){
            $verr = 1;
            $msg .= 'Nama Belum di Input !<br/>';
        }
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $verr = 1;
            $msg .= 'Email yang dimasukan Tidak Valid !<br/>';
        }
        if(empty($password)){
            $verr = 1;
            $msg .= 'Password Belum di Input !<br/>';
        }
        if(empty($level)){
            $verr = 1;
            $msg .= 'Level Belum di Input !<br/>';
        }
        
        if($verr == 0){
            // Prepare user data
            $memData = array(
                'UUID'=> $UUID,
                'Email'        => $email,
                'Password'    => $password,
                'Level'    => $level,
                'Foto' => $base64 
            );
            // prepare mahasiswa data
            $mahasiswaData = array(
                'UUID'=> $UUID,
                'NIM' => $nomor,
                'Nama'    => $nama
            );
            // prepare dosen data 
            $dosenData = array(
                'UUID'=> $UUID,
                'NID' => $nomor,
                'Nama'=> $nama,
            );
            
            // Insert user data ketabel user
            if($level === "Mahasiswa"){
                $insert1 = $this->user_model->insert($memData);
                $insert2 = $this->mahasiswa_model->insert($mahasiswaData);
            }
            else{
                $insert1 = $this->user_model->insert($memData);
                $insert2 = $this->dosen_model->insert($dosenData);
            }

            //cek kondisi insert
            if($insert1 && $insert2){
                $status = 1;
                $msg .= 'User Berhasil di Tambahkan !';
            }else{
                $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';
            }
        }
        
        // Return response as JSON format
        $alertType = ($status == 1)?'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode($response);
        
    }
    
    public function edit(){
        $verr = $status = 0;
        $msg = '';
        $memData = array();
        
        $id = $this->input->post('id');
        
        if(!empty($id)){
            // Get user's input
            $email = $this->input->post('email');
            $password = htmlspecialchars(md5($this->input->post('password', TRUE)));
            $level = $this->input->post('level');
            
            // Validate form fields
            if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
            $verr = 1;
            $msg .= 'Email yang dimasukan Tidak Valid !<br/>';
            }
            if(empty($password)){
                $verr = 1;
                $msg .= 'Password Belum di Input !<br/>';
            }
            if(empty($level)){
                $verr = 1;
                $msg .= 'Level Belum di Input !<br/>';
            }
            
            if($verr == 0){
                // Prepare user data
                $memData = array(
                    'Email'     => $email,
                    'Password'  => $password,
                    'Level'     => $level
                );
                
                // Update user data
                $update = $this->user_model->update($memData, $id);
                
                if($update){
                    $status = 1;
                    $msg .= 'User Berhasil diubah.';
                    
                }else{
                    $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';
                }
            }
        }else{
            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';
        }
        
        // Return response as JSON format
        $alertType = ($status == 1)?'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode($response);
    }
    
    public function delete(){
        $msg = '';
        $status = 0;
        
        $id = $this->uri->segment(3);
        
        // Check whether user id is not empty
        if(!empty($id)){
            // Delete user
            $delete = $this->user_model->delete($id);
            
            if($delete){
                $status = 1;
                $msg .= 'User berhasil di Hapus.';
            }else{
                $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';
            }
        }else{
            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';
        }  
        
        // Return response as JSON format
        $alertType = ($status == 1)?'alert-success':'alert-danger';
        $statusMsg = '<p class="alert '.$alertType.'">'.$msg.'</p>';
        $response = array(
            'status' => $status,
            'msg' => $statusMsg
        );
        echo json_encode($response);
    }

    public function UploadAction(){
    
	  // config upload
	  $config['upload_path']    = FCPATH.'uploads/temp_upload/';
	  $config['allowed_types']  = 'xls';
	  $config['max_size']       = '5000';

	  //initialize config file
	  $this->upload->initialize($config);
	  $this->load->library('upload', $config);

	  if ( ! $this->upload->do_upload('userFile')) {
		    $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
        redirect('Manageuser');
		    
	  } 
	  else {
        
		    // jika berhasil upload ambil data dan masukkan ke database
		    $upload_data = $this->upload->data();

		    // load library Excell_Reader
		    $this->load->library('excel_reader');

		    //tentukan file
		    $this->excel_reader->setOutputEncoding('UTF-8');
		    $file = $upload_data['full_path'];
		    $this->excel_reader->read($file);
		    error_reporting(E_ALL ^ E_NOTICE);

		    // array data
		    $data = $this->excel_reader->sheets[0];
		    $dataexcel = Array();
		    for ($i = 2; $i <= $data['numRows']; $i++) {
		    	if ($data['cells'][$i][1] == '')
		        break;
		         $dataexcel[$i - 1]['NomorInduk'] = strtoupper($data['cells'][$i][1]);
                 $dataexcel[$i - 1]['Nama'] = ucwords($data['cells'][$i][2]);
                 $dataexcel[$i - 1]['Prodi'] = $data['cells'][$i][3];
                 $dataexcel[$i - 1]['Angkatan'] = $data['cells'][$i][4];
		         $dataexcel[$i - 1]['Email'] = ucwords($data['cells'][$i][5]);
                 $dataexcel[$i - 1]['Password'] = $data['cells'][$i][6];
                 $dataexcel[$i - 1]['Level'] = $data['cells'][$i][7];
	  		};
            
            // echo "<pre>";
	  		// print_r($dataexcel);
	  		// die;

	  		//load model

            
            $keterangan = $this->input->post('keterangan');

		    $insertt=$this->user_model->pushInsert($dataexcel);
		    if($insertt){
                //delete file
                $file = $upload_data['file_name'];
                $path = FCPATH.'uploads/temp_upload/' . $file;
                unlink($path);

                $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                redirect('Manageuser');
            }
            else{
                $file = $upload_data['file_name'];
                $path = FCPATH.'uploads/temp_upload/' . $file;
                unlink($path);

                $this->session->set_flashdata('sukses', 'Data gagal di Upload');
                redirect('Manageuser');
            }
         }
      
	  		  
    }

}