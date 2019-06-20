<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dosen extends CI_Controller {
    
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
        $data['title'] = 'Dosen';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Dosen/index_dosen');
       
    }

    function jsonDataDosen(){
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

        $conditions = array('dosen.Status' => '1');

        $this->load->library('datatables');
        $this->datatables->select('');
        
        $this->datatables->select('prodi.Nama as Prodi,dosen.Nama as Nama , Nid, UUID as UUID ');
        $this->datatables->join('prodi', 'prodi.Id_prodi=dosen.Id_prodi');
        $this->datatables->where($conditions);
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'UUID');
        $this->datatables->from('dosen');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    public function userData(){
        $data = array();
        $id = $this->input->post('UUID');
        if(!empty($id)){
            // Fetch user data
           
            $data['dosen'] = $this->dosen_model->getRows(array('UUID'=>$id));
            // Return data as JSON format

            echo json_encode($data);
            
        }
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
        $password = $this->input->post('password');
        $level = $this->input->post('level');
        
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
                'Level'    => $level
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
                'Nama'=> $nama
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
            $password = $this->input->post('password');
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



}