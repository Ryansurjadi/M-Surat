<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profil extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
         if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load model
        $this->load->model('query_helper');
    }

    public function index(){
        
        $level = $this->session->userdata('Level');
        if($level == "Mahasiswa"){
            $nim = $this->session->userdata('NIM');
            $uuid = $this->session->userdata('UUID');
            $data['profil'] = $this->query_helper->getRows('mahasiswa','NIM',array('NIM'=>$nim)); 
            $data['mahasiswa'] = $this->query_helper->getRows('mahasiswa','UUID',array('UUID'=>$uuid));
            $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$uuid));
            
            // echo "<pre>";
            // print_r($data);
            // die;
            $data['colors'] = $this->query_helper->config_color();
            $this->load->view('templates/head');
            $this->load->view('Profil/index_profil',$data);   
        }
        else{
            $nid = $this->session->userdata('Nid');
            $uuid = $this->session->userdata('UUID');
            $data['profil'] = $this->query_helper->getRows('dosen','Nid',array('Nid'=>$nid)); 
            $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$uuid));
            
            // echo "<pre>";
            // print_r($data);
            // die;
            $data['colors'] = $this->query_helper->config_color();
            $this->load->view('templates/head');
            $this->load->view('Profil/index_profil_Dosen',$data); 
        }      
    }

    //function untuk ambil data untuk modal edit
    public function EditDataUser(){
        $data = array();
        $id = $this->input->post('UUID');
        if(!empty($id)){
            // Fetch user data
            $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$id));
            // Return data as JSON format
            echo json_encode($data);
        }
    }

    public function Update1_Mahasiswa(){
        $uuid = $this->session->userdata('UUID');

        $alamat = $this->input->post('alamat');
        $telepon = $this->input->post('telepon');
        $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$uuid));
        
            $data123 = array('Alamat' => $alamat,'No_telepon'=>$telepon);
            $update = $this->query_helper->update('mahasiswa','UUID',$uuid,$data123);
            if($update){
                $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                redirect('Profil');
            }
            else{
                $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                redirect('Profil');
            }
    }

    public function Update2_Mahasiswa(){
        $uuid = $this->session->userdata('UUID');

        $email = $this->input->post('email');
        $pass = $this->input->post('password');
        $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$uuid));
        
        $exist = $this->query_helper->is_exist('users','Email',$email);

        if($data['user'][0]['Email'] == $email){
            if($data['user'][0]['Password'] == $pass ){
                $data123 = array('Email' => $email);
                $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                if($update){
                    $sess_data['Email'] = $email;
                    $this->session->set_userdata($sess_data);
                    $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                    redirect('Profil');
                }
                else{
                    $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                    redirect('Profil');
                }
            }else{
                $data123 = array('Email' => $email , 'Password' => md5($pass));
                $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                if($update){
                    $sess_data['Email'] = $email;
                    $this->session->set_userdata($sess_data);
                    $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                    redirect('Profil');
                }
                else{
                    $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                    redirect('Profil');
                }
            }
        }
        else{ //email beda
            if($data['user'][0]['Password'] == $pass ){ //email beda pss sama
                if($exist){
                    $this->session->set_flashdata('gagal', 'Email sudah digunakan');
                    redirect('Profil');
                }
                else{
                    $data123 = array('Email' => $email);
                    $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                    if($update){
                        $sess_data['Email'] = $email;
                        $this->session->set_userdata($sess_data);
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                        redirect('Profil');
                    }
                    else{
                        $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                        redirect('Profil');
                    }
                }
            }else{ //email beda pass beda
                if($exist){
                    $this->session->set_flashdata('gagal', 'Email sudah digunakan');
                    redirect('Profil');
                }
                else{
                    $data123 = array('Email' => $email , 'Password' => md5($pass));
                    $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                    if($update){
                        $sess_data['Email'] = $email;
                        $this->session->set_userdata($sess_data);
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                        redirect('Profil');
                    }
                    else{
                        $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                        redirect('Profil');
                    }
                }
            }
        }

    }

    public function Update1_Dosen(){
        $uuid = $this->session->userdata('UUID');

        $keterangan = $this->input->post('keterangan');
        $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$uuid));
        
            $data123 = array('Keterangan'=>$keterangan);
            $update = $this->query_helper->update('dosen','UUID',$uuid,$data123);
            if($update){
                $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                redirect('Profil');
            }
            else{
                $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                redirect('Profil');
            }
    }

    public function Update_Dosen(){
        $uuid = $this->session->userdata('UUID');

        $email = $this->input->post('email');
        $pass = $this->input->post('password');
        $data['user'] = $this->query_helper->getRows('users','UUID',array('UUID'=>$uuid));

        
        $exist = $this->query_helper->is_exist('users','Email',$email);
        if($data['user'][0]['Email'] == $email){
            if($data['user'][0]['Password'] == $pass ){
                $data123 = array('Email' => $email);
                $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                if($update){
                    $sess_data['Email'] = $email;
                    $this->session->set_userdata($sess_data);
                    $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                    redirect('Profil');
                }
                else{
                    $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                    redirect('Profil');
                }
            }else{
                $data123 = array('Email' => $email , 'Password' => md5($pass));
                $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                if($update){
                    $sess_data['Email'] = $email;
                    $this->session->set_userdata($sess_data);
                    $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                    redirect('Profil');
                }
                else{
                    $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                    redirect('Profil');
                }
            }
        }
        else{ //email beda
            if($data['user'][0]['Password'] == $pass ){ //email beda pss sama
                if($exist){
                    $this->session->set_flashdata('gagal', 'Email sudah digunakan');
                    redirect('Profil');
                }
                else{
                    $data123 = array('Email' => $email);
                    $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                    if($update){
                        $sess_data['Email'] = $email;
                        $this->session->set_userdata($sess_data);
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                        redirect('Profil');
                    }
                    else{
                        $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                        redirect('Profil');
                    }
                }
            }else{ //email beda pass beda
                if($exist){
                    $this->session->set_flashdata('gagal', 'Email sudah digunakan');
                    redirect('Profil');
                }
                else{
                    $data123 = array('Email' => $email , 'Password' => md5($pass));
                    $update = $this->query_helper->update('users','UUID',$uuid,$data123);
                    if($update){
                        $sess_data['Email'] = $email;
                        $this->session->set_userdata($sess_data);
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Ubah');
                        redirect('Profil');
                    }
                    else{
                        $this->session->set_flashdata('gagal', 'Data Gagal di Ubah');
                        redirect('Profil');
                    }
                }
            }
        }

    }

    public function Update_Foto(){
       
        $uuid = $this->session->userdata('UUID');
        $config['upload_path'] = './uploads/temp_upload/';
        $config['allowed_types'] = 'png|jpg|jpeg';
        $config['max_size']	= '10000';
        //$config['max_width']  = '1024';
        //$config['max_height']  = '768';
        $this->upload->initialize($config);
        $this->load->library("upload", $config);

         //echo "test1";
        if($this->upload->do_upload('userFile')){
            $data = $this->upload->data();
            $path = site_url('./uploads/temp_upload/'.$data['file_name']);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            
            $data123 = array(     
                    'Foto' =>$base64,
            );  
            // echo "<pre>";
            // print_r($data123);
            // die;

            $insert = $this->query_helper->update('users','UUID',$uuid,$data123);
            if($insert){
                $sess_data['Foto'] = $base64;
                $this->session->set_userdata($sess_data);
                $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                redirect('Profil');
            }else{
                $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                    redirect('Profil');
            }
            
            
        }
        
        else{
            $error = array('error' => $this->upload->display_errors());
            //$this->load->view('home_v', $error);
            // echo $error['error'];
            // echo "ini salah";
        }
    }
}