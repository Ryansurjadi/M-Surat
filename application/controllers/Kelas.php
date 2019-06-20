<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kelas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load user model
        $this->load->model('query_helper');
    }
    
    public function index(){
        // Load the list page view
        $data['dosen'] = $this->query_helper->fetchAllDataOrderBY('dosen','Nama','ASC');
        $data['matkul'] = $this->query_helper->fetchAllDataOrderBY('matakuliah','Nama','ASC');
        
        $data['title'] = 'Kelas';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Kelas/index_kelas');
       
    }

    function jsonKelasTI(){
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1' 
        );
        $conditions = array( 'kelas.Status' => '1','matakuliah.Id_prodi' => 'TI');

        $this->load->library('datatables');
        $this->datatables->select('kelas.Id_kelas as Id , kelas.Kelas, kelas.Hari, kelas.Jam_mulai, kelas.Jam_selesai, matakuliah.Nama');
        $this->datatables->from('kelas');
        $this->datatables->join('matakuliah', 'matakuliah.Id_matkul = kelas.Id_matkul');
        $this->datatables->select('dosen.Nama as Dosen');
        $this->datatables->join('dosen', 'dosen.Nid = kelas.Nid');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->where($conditions);
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    }

    function jsonKelasSI(){
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1' 
        );
        $conditions = array( 'kelas.Status' => '1','matakuliah.Id_prodi' => 'SI');

        $this->load->library('datatables');
        $this->datatables->select('kelas.Id_kelas as Id , kelas.Kelas, kelas.Hari, kelas.Jam_mulai, kelas.Jam_selesai, matakuliah.Nama');
        $this->datatables->from('kelas');
        $this->datatables->join('matakuliah', 'matakuliah.Id_matkul = kelas.Id_matkul');
        $this->datatables->select('dosen.Nama as Dosen');
        $this->datatables->join('dosen', 'dosen.Nid = kelas.Nid');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->where($conditions);
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    }
    

    public function EditData(){
        $data = array();
        $id = $this->input->post('Id');
        if(!empty($id)){
            // Fetch user data
           
            $data['kelas'] = $this->query_helper->getRows('kelas','Id_kelas',array('Id_kelas'=>$id));
            // Return data as JSON format

            echo json_encode($data);
            
        }
    }
    
    public function add(){
        $verr = $status = 0;
        $msg = '';
        $memData = array();
        
        // Get user's input
        $kode = $this->input->post('kode');
        $matkul = $this->input->post('matkul');
        $dosen = $this->input->post('dosen');
        $kelas = $this->input->post('kelas');
        $hari = $this->input->post('hari');
        $mulai = $this->input->post('mulai');
        $selesai = $this->input->post('selesai');
        
        
        // Validate form fields
            if(empty($kode)){
                $verr = 1;
                $msg .= 'Kode Kelas Belum di Input !<br/>';
            }
            if(empty($matkul)){
                $verr = 1;
                $msg .= 'Nama Matakuliah Belum di Input !<br/>';
            }
            if(empty($dosen)){
                $verr = 1;
                $msg .= 'Nama Dosen Belum di Input !<br/>';
            }
            if(empty($kelas)){
                $verr = 1;
                $msg .= 'Kelas Belum di Input !<br/>';
            }
            if(empty($hari)){
            $verr = 1;
            $msg .= 'Hari Belum di Input !<br/>';
            }
            if(empty($mulai)){
                $verr = 1;
                $msg .= 'Jam mulai Belum di Input !<br/>';
            }
            if(empty($selesai)){
                $verr = 1;
                $msg .= 'Jam Selesai Belum di Input !<br/>';
            }

        if($verr == 0){
            // Prepare user data
            $memData = array(
                'Id_kelas'=> $kode,
                'Id_matkul'        => $matkul,
                'Nid'    => $dosen,
                'Hari' => $hari,
                'Kelas'    => $kelas,
                'Jam_mulai' => $mulai,
                'Jam_selesai' => $selesai
            );
            
            // echo "<pre>";
            // print_r($memData);
            // die;
            
            $insert = $this->query_helper->insert('kelas',$memData);
            

            //cek kondisi insert
            if($insert){
                //buat folder matakuliah dan kelas
                $path = "./uploads/materi/".$kode;
                mkdir($path,0777,true);

                //kirim status
                $status = 1;
                $msg .= 'Data Berhasil di Tambahkan !';
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
            $matkul = $this->input->post('matkul');
            $dosen = $this->input->post('dosen');
            $kelas = $this->input->post('kelas');
            $hari = $this->input->post('hari');
            $mulai = $this->input->post('mulai');
            $selesai = $this->input->post('selesai');
            
            // Validate form fields
            if(empty($matkul)){
                $verr = 1;
                $msg .= 'Nama Matakuliah Belum di Input !<br/>';
            }
            if(empty($dosen)){
                $verr = 1;
                $msg .= 'Nama Dosen Belum di Input !<br/>';
            }
            if(empty($kelas)){
                $verr = 1;
                $msg .= 'Kelas Belum di Input !<br/>';
            }
            if(empty($hari)){
            $verr = 1;
            $msg .= 'Hari Belum di Input !<br/>';
            }
            if(empty($mulai)){
                $verr = 1;
                $msg .= 'Jam mulai Belum di Input !<br/>';
            }
            if(empty($selesai)){
                $verr = 1;
                $msg .= 'Jam Selesai Belum di Input !<br/>';
            }
            
            if($verr == 0){
                // Prepare user data
                $memData = array(
                    'Id_matkul'        => $matkul,
                    'Nid'    => $dosen,
                    'Hari' => $hari,
                    'Kelas'    => $kelas,
                    'Jam_mulai' => $mulai,
                    'Jam_selesai' => $selesai
                );
                
                // Update user data
                $update = $this->query_helper->update('kelas','Id_kelas', $id,$memData);
                
                if($update){
                    $status = 1;
                    $msg .= 'Data Berhasil diubah.';
                    
                }else{
                    $msg .= 'Terjadi masalah, Silahkan dicoba lagi1.';
                }
            }
        }else{
            $msg .= 'Terjadi masalah, Silahkan dicoba lagi2.';
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

	  if ( ! $this->upload->do_upload('userFile')) 
	  {
        $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
        redirect('Kelas');
	  } 
	  else 
	  {
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
            $keterangan = $this->input->post('keterangan');

            if($keterangan == '1'){
                for ($i = 2; $i <= $data['numRows']; $i++) {
                    if ($data['cells'][$i][1] == '')
                    break;
                    $dataexcel[$i - 1]['Id_matkul'] = strtoupper($data['cells'][$i][1]);
                    $dataexcel[$i - 1]['Id_prodi'] = ucwords($data['cells'][$i][2]);
                    $dataexcel[$i - 1]['Nid'] = ucwords($data['cells'][$i][3]);
                    $dataexcel[$i - 1]['Kelas'] = $data['cells'][$i][4];
                    $dataexcel[$i - 1]['Hari'] = ucwords($data['cells'][$i][5]);
                    $dataexcel[$i - 1]['Jam_mulai'] = $data['cells'][$i][6];
                    $dataexcel[$i - 1]['Jam_selesai'] = $data['cells'][$i][7];
                };

                $insert = $this->query_helper->uploadKelas($dataexcel);
            
                if($insert){
                    //delete file
                    $file = $upload_data['file_name'];
                    $path = FCPATH.'uploads/temp_upload/' . $file;
                    unlink($path);

                    $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                    redirect('Kelas');
                }
                else{
                    //delete file
                    $file = $upload_data['file_name'];
                    $path = FCPATH.'uploads/temp_upload/' . $file;
                    unlink($path);

                $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                    redirect('Kelas');
                }

            }
            elseif($keterangan == '2'){
                 for ($i = 2; $i <= $data['numRows']; $i++) {
                    if ($data['cells'][$i][1] == '')
                    break;
                    $dataexcel[$i - 1]['Id_matkul'] = strtoupper($data['cells'][$i][1]);
                    $dataexcel[$i - 1]['Id_prodi'] = ucwords($data['cells'][$i][2]);
                    $dataexcel[$i - 1]['Nid'] = ucwords($data['cells'][$i][3]);
                    $dataexcel[$i - 1]['Kelas'] = $data['cells'][$i][4];
                    $dataexcel[$i - 1]['Hari'] = ucwords($data['cells'][$i][5]);
                    $dataexcel[$i - 1]['Jam_mulai'] = $data['cells'][$i][6];
                    $dataexcel[$i - 1]['Jam_selesai'] = $data['cells'][$i][7];
                };

                $update = $this->query_helper->uploadUpdateKelas($dataexcel);
            
                if($update){
                    //delete file
                    $file = $upload_data['file_name'];
                    $path = FCPATH.'uploads/temp_upload/' . $file;
                    unlink($path);

                    $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                    redirect('Kelas');
                    
                }
                else{
                    //delete file
                    $file = $upload_data['file_name'];
                    $path = FCPATH.'uploads/temp_upload/' . $file;
                    unlink($path);

                $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                    redirect('Kelas');
                }
            }
            // echo "<pre>";
	  		// print_r($dataexcel);
	  		// die;

	  		//load model

		    
		    
		 }
	  		  
    }

}