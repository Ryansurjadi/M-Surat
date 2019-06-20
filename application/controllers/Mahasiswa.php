<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mahasiswa extends CI_Controller {
    
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
        // Load the list page view
        $data['title'] = 'Mahasiswa';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Mahasiswa/index_mahasiswa');
       
    }

    function jsonDataTI(){
        $url = site_url('Mahasiswa/delete/$1');
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1' 
        );

        $atts2 = array(
            'class'       => 'btn btn-danger btn-sm'
        );

        $conditions = array('Id_prodi' => 'TI', 'Status' => '1');

        $this->load->library('datatables');
        $this->datatables->select('NIM,Nama,Angkatan, UUID as UUID');
        $this->datatables->where($conditions);
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1) ." "." ".anchor($url,'Non Aktif',$atts2)), 'UUID');
        $this->datatables->from('mahasiswa');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    function jsonDataSI(){

        $url = site_url('Mahasiswa/delete/$1');
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1' 
        );

        $atts2 = array(
            'class'       => 'btn btn-danger btn-sm',
        );

        $conditions = array('Id_prodi' => 'SI', 'Status' => '1');

        $this->load->library('datatables');
        $this->datatables->select('NIM,Nama,Angkatan, UUID as UUID');
        $this->datatables->where($conditions);
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1) ." "." ".anchor($url,'Non Aktif',$atts2)), 'UUID');
        $this->datatables->from('mahasiswa');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    public function userData(){
        $data = array();
        $id = $this->input->post('UUID');
        if(!empty($id)){
            // Fetch user data
           
            $data['mahasiswa'] = $this->mahasiswa_model->getRows(array('UUID'=>$id));
            // Return data as JSON format

            echo json_encode($data);
            
        }
    }
    
    public function edit(){
        $verr = $status = 0;
        $msg = '';
        $memData = array();
        
        $id = $this->input->post('id');
       

        if(!empty($id)){
            // Get user's input
            $nomor = $this->input->post('nomor');
            $nama= $this->input->post('nama');
            $prodi = $this->input->post('prodi');
            $angkatan= $this->input->post('angkatan');
            $sks= $this->input->post('sks');
            $ipk= $this->input->post('ipk');
            $semester= $this->input->post('semester');
            $jenis= $this->input->post('jenis');
            $telepon= $this->input->post('telepon');
            $alamat= $this->input->post('alamat');
            
            
            if($verr == 0){
                // Prepare user data
                $memData = array(
                    'Nama'     => $nama,
                    'Id_prodi'  => $prodi,
                    'Jenis_kelamin'     => $jenis,
                    'Angkatan'=> $angkatan,
                    'Alamat'=> $alamat,
                    'No_telepon'=> $telepon,
                    'Semester'=> $semester,
                    'SKS'=> $sks,
                    'IPK'=> $ipk,
                );
                
                // Update user data
                $update = $this->mahasiswa_model->update($memData, $id);
                
                if($update){
                    $status = 1;
                    $msg .= 'Mahasiswa Berhasil diubah.';
                    
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
            $data = array('Status'=>'0');
            $delete = $this->mahasiswa_model->update($data,$id);
            
            if($delete){
                $this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                redirect('Mahasiswa');
            }else{
                $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                    redirect('Mahasiswa');
            }
        }else{
            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                    redirect('Mahasiswa');
        }  
        
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
                    redirect('Mahasiswa');
		    
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
                    $dataexcel[$i - 1]['Nim'] = strtoupper($data['cells'][$i][1]);
                    $dataexcel[$i - 1]['Nama'] = strtoupper($data['cells'][$i][2]);
                    $dataexcel[$i - 1]['Semester'] = $data['cells'][$i][3];
                    $dataexcel[$i - 1]['IPK'] = $data['cells'][$i][4];
                    $dataexcel[$i - 1]['Total_SKS'] = $data['cells'][$i][5];
                };
            }
            elseif($keterangan == '2'){
                for ($i = 2; $i <= $data['numRows']; $i++) {
                    if ($data['cells'][$i][1] == '')
                    break;
                    $dataexcel[$i - 1]['Nim'] = strtoupper($data['cells'][$i][1]);
                    $dataexcel[$i - 1]['Nama'] = strtoupper($data['cells'][$i][2]);
                };
            }
            // echo "<pre>";
	  		// print_r($dataexcel);
	  		

	  		//load model

		    $update = $this->mahasiswa_model->pushUpdate($keterangan,$dataexcel);
		    // die;
		    if($update){
                //delete file
                $file = $upload_data['file_name'];
                $path = FCPATH.'uploads/temp_upload/' . $file;
                unlink($path);

                $this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                redirect('Mahasiswa');
            }
            else{
                $file = $upload_data['file_name'];
                $path = FCPATH.'uploads/temp_upload/' . $file;
                unlink($path);

                $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                redirect('Mahasiswa');
            }
		 }
	  		  
}

}