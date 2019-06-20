<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Matakuliah extends CI_Controller {
    
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
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Matakuliah';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Matakuliah/index_matakuliah');
    }
    
    //function untuk datatables ke view
    function jsonDataTI(){
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
        $this->datatables->select('Kd_matkul,Id_prodi,Nama,Sks,Semester, Id_matkul as Id');
        $this->datatables->where('Id_prodi','TI');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->from('matakuliah');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    //function untuk datatables ke view
    function jsonDataSI(){
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
        $this->datatables->select('Kd_matkul,Id_prodi,Nama,Sks,Semester, Id_matkul as Id');
        $this->datatables->where('Id_prodi','SI');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->from('matakuliah');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    //function untuk ambil data untuk modal edit
    public function EditData(){
        $data = array();
        $id = $this->input->post('Id');
        if(!empty($id)){
            // Fetch user data
            $data['matakuliah'] = $this->query_helper->getRows('matakuliah','Id_matkul',array('Id_matkul'=>$id));
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
        $nama = $this->input->post('nama');
        $prodi = $this->input->post('prodi');
        $sks = $this->input->post('sks');
        $semester = $this->input->post('semester');
        $rand = mt_rand(10000000,99999999);

            $memData = array(
                'Id_matkul'=> $rand,
                'kd_matkul'=> $kode,
                'Id_prodi'        => $prodi,
                'Nama' => $nama,
                'Sks'    => $sks,
                'Semester'    => $semester,
            );
        
            $insert = $this->query_helper->insert('matakuliah',$memData);
            
        
        if($insert){
            $status = 1;
             $msg .= 'Data berhasil di Input';
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
    
    public function edit(){
        $verr = $status = 0;
        $msg = '';
        $memData = array();
        

        // Get user's input
        $id = $this->input->post('id');
        

        if(!empty($id)){
            $kode = $this->input->post('kode');
            $nama = $this->input->post('nama');
            $prodi = $this->input->post('prodi');
            $sks = $this->input->post('sks');
            $semester = $this->input->post('semester');
            

                $memData = array(
                    'Kd_matkul' => $kode,
                    'Id_prodi'        => $prodi,
                    'Nama' => $nama,
                    'Sks'    => $sks,
                    'Semester'    => $semester,
                );
            
                $insert = $this->query_helper->update('matakuliah','Id_matkul',$id,$memData);
                
            
            if($insert){
                $status = 1;
                $msg .= 'Data berhasil di Ubah';
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
    
        $prodi = $this->input->post('prodi');

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
            redirect('matakuliah');
		    
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
		    for ($i = 2; $i <= $data['numRows']; $i++) {
		    	if ($data['cells'][$i][1] == '')
		        break;
		         $dataexcel[$i - 1]['Kd_matkul'] = strtoupper($data['cells'][$i][1]);
                 $dataexcel[$i - 1]['Nama'] = ucwords($data['cells'][$i][2]);
                 $dataexcel[$i - 1]['Sks'] = $data['cells'][$i][3];
                 $dataexcel[$i - 1]['Semester'] = $data['cells'][$i][4];
	  		};
            
            // echo "<pre>";
	  		// print_r($dataexcel);
	  		// die;

	  		//load model

		    $insert = $this->query_helper->uploadMatkul($dataexcel,$prodi);
		    // die;
            
            if($insert){
                //delete file
                $file = $upload_data['file_name'];
                $path = FCPATH.'uploads/temp_upload/' . $file;
                unlink($path);

                $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                redirect('matakuliah');
            }
            else{
                //delete file
                $file = $upload_data['file_name'];
                $path = FCPATH.'uploads/temp_upload/' . $file;
                unlink($path);

                $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                redirect('matakuliah');
            }
		    
		 }
	  		  
    }
}
