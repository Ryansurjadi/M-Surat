<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prodi extends CI_Controller {
    
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
        $data = array();
        
        // Get prodi data
        $data['prodi'] = $this->query_helper->getRows('prodi','Status',array('Status'=>'0'));

        // Load the list page view
        $data['title'] = 'Program Studi';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Prodi/index_prodi', $data);
       
    }
    
    //function untuk datatables ke view
    function jsonProdi(){
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1' 
        );
        
        $this->load->library('datatables');
        $this->datatables->select('Id_prodi as Id,Nama');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->from('prodi');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    public function EditData(){
        $data = array();
        $id = $this->input->post('Id');
        if(!empty($id)){
            // Fetch user data
            $data['prodi'] = $this->query_helper->getRows('prodi','Id_prodi',array('Id_prodi'=>$id));
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
        
        $memData = array(
                'Id_prodi'        => $kode,
                'Nama' => $nama,
            );
        $count = $this->db->count_all('prodi');
                if ($count >="2"){
                    $msg .= 'Maksimal hanya 2 Program studi';
                }
                else{
            $insert = $this->query_helper->insert('prodi',$memData);
            if($insert){
                $status = 1;
                $msg .= 'Data berhasil di Input';
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
        // Get user's input
        $kode = $this->input->post('kode');

        if(!empty($kode)){
            $nama = $this->input->post('nama');
            
                $memData = array(
                    'Nama' => $nama,
                );
            
                $insert = $this->query_helper->update('prodi','Id_prodi',$kode,$memData);
                
            
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
    
}