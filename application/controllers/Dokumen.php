<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dokumen extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load user model
        $this->load->model('dokumen_model');
        $this->load->model('query_helper');
    }
    
    public function index(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Dokumen';
        $data['tipe_dokumen'] = $this->query_helper->fetchAllDataOrderBY('tipe_dokumen','Nama','ASC');
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Dokumen/index_dokumen',$data);
    }
    
    //function untuk datatables ke view
    function jsonData(){
        $url = site_url()."dokumen/download/$1";
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

        $atts3 = array(
             'class'       => 'btn btn-danger btn-sm',
            
        );

        $this->load->library('datatables');
        $this->datatables->select('Nomor, Judul,Keterangan, Id_tipe_dokumen , Id_dokumen as Id');
        $this->datatables->add_column('action',array( anchor("#",'Lihat',$atts1) ." "." ". anchor($url,'Unduh',$atts3)), 'Id');
        $this->db->order_by('modified', 'DESC');
        $this->datatables->where('Status =','1');
        $this->datatables->from('dokumen');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    //function untuk ambil data untuk modal edit
    public function userData(){
        $data = array();
        $id = $this->input->post('Id');
        if(!empty($id)){
            // Fetch user data
            $data['dokumen'] = $this->dokumen_model->getRows(array('Id_dokumen'=>$id));
            // Return data as JSON format
            echo json_encode($data);
        }
    }

    public function uploadDokumen(){

            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $id = substr(str_shuffle($permitted_chars), 0, 10);
            $tipeDokumen = $this->input->post('tipe_dokumen');
			
				$config['upload_path'] = './uploads/dokumen/';
				$config['allowed_types'] = 'docx|doc|ppt|pptx|zip|rar|xls|xlsx|pdf';
                $config['max_size']	= '10000';
                $config['encrypt_name'] = TRUE;
				//$config['max_width']  = '1024';
				//$config['max_height']  = '768';
				
				$this->load->library("upload", $config);
				
				if($this->upload->do_upload("file"))
				{
					$data = $this->upload->data();
					$nama_file= $data['file_name'];
                    
                    $file= $tipeDokumen.'/'.$nama_file ;
                    
					$nomor = $this->input->post('nomor');
					$judul = $this->input->post('judul');				
					
					$data = array(
                         'Id_dokumen' => $id,
                         'Id_tipe_dokumen' => $tipeDokumen,
                         'Nomor' =>$nomor,
                         'Judul' =>$judul,     
                         'File' =>$file,
                    );   
                    
                    $insert = $this->dokumen_model->insert($data);
					if($insert){
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                        redirect('Dokumen');
                    }else{
                        $this->session->set_flashdata('gagal', 'Data gagal di Upload');
                        redirect('Dokumen');
                    }
					
				}
				else
				{
                     $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
                     redirect('Dokumen');
				}
			
    }
    
    public function download(){	
        
        $id = $this->uri->segment(3);
        
        $nama_file = $this->dokumen_model->download($id);
        $nama_file_download = $nama_file['File'] ;
        //echo $nama_file_download;
        //die;
        $this->load->helper('download');
        $data = file_get_contents(FOLDER_DOKUMEN.$nama_file_download); // Read the file's contents
        
        force_download($nama_file_download,$data,false);
        
        //redirect(site_url());
			
    }

    function add(){
        $verr = $status = 0;
        $msg = '';

        $id_dokumen = mt_rand(1000000,9999999);
        $nomor =  $this->input->post('nomor');
        $judul = $this->input->post('judul');
        $tipe = $this->input->post('tipe');
        $deskripsi = $this->input->post('deskripsi');				
            
        $path_folder = FCPATH.'/uploads/dokumen/'.$tipe.'/'; 

        if(empty($judul)){
                $verr = 1;
                $msg .= 'Nama Dokumen Belum di Input !<br/>';
        }
        if(empty($tipe)){
                $verr = 1;
                $msg .= 'Tipe Belum di Input !<br/>';
        }
        if(empty($nomor)){
                $verr = 1;
                $msg .= 'Nomor Dokumen Belum di Input !<br/>';
        }
        if($verr == 0){
            $this->db->select('Count(Nomor) as Total');
            $this->db->from('dokumen');
            $this->db->where ('Nomor',$nomor);
            $query = $this->db->get();
            $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            
            //echo $result[0]['Total'];
            //check udah ada apa belum
            if ($result[0]['Total'] < "1" ){ //create
                if (!file_exists($path_folder)) {
                    mkdir($path_folder, 0777);
                    
                    $config['upload_path'] = $path_folder;
                    $config['allowed_types'] = 'docx|doc|ppt|pptx|zip|rar|xls|xlsx|pdf';
                    $config['max_size']	= '10000';
                    $config['encrypt_name'] = TRUE;
                    //$config['max_width']  = '1024';
                    //$config['max_height']  = '768';
                    $this->upload->initialize($config);
                    $this->load->library("upload", $config);
                    
                    if($this->upload->do_upload('userFile')){
                        $data = $this->upload->data();
                        $name = $data['file_name'];  
                        $nama_file= $tipe.'/'.$name;
                        
                        
                        $data123 = array(
                                'Id_dokumen'=> $id_dokumen,
                                'Id_tipe_dokumen' => $tipe,
                                'Nomor' => $nomor,
                                'Judul' =>$judul,
                                'Keterangan' =>$deskripsi,     
                                'File' =>$nama_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;
                        $insert = $this->query_helper->insert('dokumen',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Dokumen');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Dokumen');
                        }
                        
                       
                    }
                    
                    else{
                        $error = array('error' => $this->upload->display_errors());
                        //$this->load->view('home_v', $error);
                        // echo $error['error'];
                        // echo "ini salah";
                    }
                } 
                else {
                    
                    $config['upload_path'] = $path_folder;
                    $config['allowed_types'] = 'docx|doc|ppt|pptx|zip|rar|xls|xlsx|pdf';
                    $config['max_size']	= '10000';
                    $config['encrypt_name'] = TRUE;
                    //$config['max_width']  = '1024';
                    //$config['max_height']  = '768';
                    $this->upload->initialize($config);
                    $this->load->library("upload", $config);
                    
                    if($this->upload->do_upload('userFile')){
                        $data = $this->upload->data();
                        $name =  $data['file_name'];
                        $nama_file= $tipe.'/'.$name;
                        
                        
                        $data123 = array(
                                'Id_dokumen'=> $id_dokumen,
                                'Id_tipe_dokumen' => $tipe,
                                'Nomor' => $nomor,
                                'Judul' =>$judul,
                                'Keterangan' =>$deskripsi,     
                                'File' =>$nama_file,
                        );   
                        // echo "<pre>";
                        // print_r($data123);
                        // die;

                        $insert = $this->query_helper->insert('dokumen',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Dokumen');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Dokumen');
                        }
                        
                        // Return response as JSON format
                        
                    }
                    else{
                        $error = array('error' => $this->upload->display_errors());
                        //$this->load->view('home_v', $error);
                       // echo $error['error'];
                        //echo "2";
                    }
                }
            }
            else{ //update                    
                //echo "<script>alert('Sudah ada Materi'); history.go(-1);</script>";
                $verr = $status = 0;
                $msg = '';

                $id = $this->input->post('id');
                $nomor =  $this->input->post('nomor');
                $judul = $this->input->post('judul');
                $tipe = $this->input->post('tipe');
                $deskripsi = $this->input->post('deskripsi');	
                $lama = $this->input->post('lama');				
                    
                $path_folder = FCPATH.'/uploads/dokumen/'.$tipe.'/'; 
                
                if(empty($judul)){
                $verr = 1;
                $msg .= 'Nama Dokumen Belum di Input !<br/>';
                }
                if(empty($tipe)){
                        $verr = 1;
                        $msg .= 'Tipe Belum di Input !<br/>';
                }
                if(empty($nomor)){
                        $verr = 1;
                        $msg .= 'Nomor Dokumen Belum di Input !<br/>';
                }
                if($verr == 0){
                
                    $config['upload_path'] = $path_folder;
                    $config['allowed_types'] = 'docx|doc|ppt|pptx|zip|rar|xls|xlsx|pdf';
                    $config['max_size']	= '10000';
                    $config['encrypt_name'] = TRUE;
                    //$config['max_width']  = '1024';
                    //$config['max_height']  = '768';
                    $this->upload->initialize($config);
                    $this->load->library("upload", $config);
                    
                    if($this->upload->do_upload('userFile')){
                        $data = $this->upload->data();
                        $name = $data['file_name'];  
                        $nama_file= $tipe.'/'.$name;
                        
                        $data123 = array(
                                'Id_tipe_dokumen' => $tipe,
                                'Nomor' => $nomor,
                                'Judul' =>$judul,
                                'Keterangan' =>$deskripsi,     
                                'File' =>$nama_file,
                        );  
                        // echo "<pre> do";
                        // print_r($data);
                        // die;
                        $update = $this->query_helper->update('dokumen','Id_dokumen',$id,$data123);
                        if($update){
                            // /unlink(FCPATH.'$lama');
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                            redirect('Dokumen');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Dokumen');
                        }
                    }
                    else{
                        // $error = array('error' => $this->upload->display_errors());
                        // print_r($error);
                        $data = $this->upload->data();
                        $name = $data['file_name'];  
                        $nama_file= $tipe.'/'.$name;
                        
                        $data123 = array(
                                'Id_tipe_dokumen' => $tipe,
                                'Nomor' => $nomor,
                                'Judul' =>$judul,
                                'Keterangan' =>$deskripsi,     
                                'File' =>$lama,
                        );  
                        // echo "<pre> no";
                        // print_r($data123);
                        // die;
                        $update = $this->query_helper->update('dokumen','Id_dokumen',$id,$data123);
                        if($update){
                            // /unlink(FCPATH."$lama");
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                            redirect('Dokumen');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Dokumen');
                        }
                        
                    }
                    
                    
                }
            }
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////

    function index_tipe(){
        // Load the list page view
        $data['title'] = 'Tipe Dokumen';
        $data['colors'] = $this->query_helper->config_color();
        
        $this->load->view('templates/head', $data);
        $this->load->view('Dokumen/index_tipe_dokumen',$data);
    }

    //function untuk datatables ke view
    function jsonDataTipe(){
        $url = site_url()."dokumen/delete_tipe/$1";
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

        $atts3 = array(
             'class'       => 'btn btn-danger btn-sm',
            
        );

        $this->load->library('datatables');
        $this->datatables->select('Nama , Id_tipe_dokumen as Id');
        $this->datatables->add_column('action',array( anchor($url,'Hapus',$atts3)), 'Id');
        $this->db->order_by('modified', 'DESC');
        $this->datatables->where('Status =','1');
        $this->datatables->from('tipe_dokumen');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    function add_tipe(){
        $tipeDokumen = $this->input->post('nomor');
        $namaTipe = $this->input->post('judul');

        $data = array(
                    'Id_tipe_dokumen' => $tipeDokumen,    
                    'Nama' =>$namaTipe,
                );
        $insert = $this->query_helper->insert('tipe_dokumen',$data);
        if($insert){
            $this->session->set_flashdata('sukses', 'Data Berhasil di Simpan');
            redirect('Dokumen/index_tipe');
        }
        else{
            $this->session->set_flashdata('gagal', 'Data Gagal di Simpan');
            redirect('Dokumen/index_tipe');
        }
    }

    function delete_tipe(){
         $id = $this->uri->segment(3);
        $data = array(
                    'Status' => '0',
                );

        $update = $this->query_helper->update('tipe_dokumen','Id_tipe_dokumen',$id,$data);
        // echo $update;
        // die;
        if($update){
            $this->session->set_flashdata('sukses', 'Data Berhasil di Hapus');
            redirect('Dokumen/index_tipe');
        }
        else{
            $this->session->set_flashdata('gagal', 'Data Gagal di Hapus');
            redirect('Dokumen/index_tipe');
        }
    }
}
