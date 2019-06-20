<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        
        // Load model
        $this->load->model('query_helper');
    }

    public function index(){
        
        $this->load->view('templates/head_auth');
        $this->load->view('Auth/login');
    }

    public function login() {
        $data = array(
            'Email' => htmlspecialchars($this->input->post('email', TRUE)),
            'Password' => htmlspecialchars(md5($this->input->post('password', TRUE)))
        );

        $is_exist = $this->query_helper->user_exist($data);
        if ($is_exist->num_rows() == 1) {
            foreach ($is_exist->result() as $sess) {
                $sess_data['logged'] = TRUE;
                $sess_data['UUID'] = $sess->UUID;
                $sess_data['Email'] = $sess->Email;
                $sess_data['Level'] = $sess->Level;
                $sess_data['Foto'] = $sess->Foto;
            }
            
            // echo "<pre>";
            // print_r($sess_data);
            // echo "<pre>";

            $this->session->set_userdata($sess_data);

			if ($this->session->userdata('Level')=='Dosen') {
                $params = array('UUID'=> $sess_data['UUID']);
                $dosen = $this->query_helper->getRows('dosen','UUID',$params);
                foreach ($dosen as $m) {
                    $sess_data['Nid'] = $m['Nid'];
                    $sess_data['Nama'] = $m['Nama'];
                }
                // echo "<pre>";
                // print_r($sess_data);
                // echo "<pre>";`
                $this->session->set_userdata($sess_data);
                $this->session->set_flashdata('sukses', 'Selamat datang kembali , '.$this->session->userdata('Nama'));
				redirect('Beranda');
			}
			elseif ($this->session->userdata('Level')=='Mahasiswa') {
                $params = array('UUID'=> $sess_data['UUID']);
                $mahasiswa = $this->query_helper->getRows('mahasiswa','UUID',$params);
                foreach ($mahasiswa as $m) {
                    $sess_data['NIM'] = $m['NIM'];
                    $sess_data['Nama'] = $m['Nama'];
                    $sess_data['Id_prodi'] = $m['Id_prodi'];
                    $sess_data['Angkatan'] = $m['Angkatan'];
                    $sess_data['Semester'] = $m['Semester'];
                    $sess_data['IPK'] = $m['IPK'];
                }
                // echo "<pre>";
                // print_r($sess_data);
                // echo "<pre>";
                // die;
                $this->session->set_userdata($sess_data);
                $this->session->set_flashdata('sukses', 'Selamat datang kembali , '.$this->session->userdata('Nama'));
				redirect('Beranda');
            }
            elseif ($this->session->userdata('Level')=='Admin') {
                 $this->session->set_userdata($sess_data);
                 $this->session->set_flashdata('sukses', 'Selamat datang kembali , '.$this->session->userdata('UUID'));
                 redirect('Beranda');
            }
        }
        else {
			echo "<script>alert('Gagal login: Cek username, password!');history.go(-1);</script>";
		}
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('auth');
    }

    public function forgot_password(){
        $this->load->view('templates/head_auth');
        $this->load->view('Auth/form_forgotpass');
    }

    public function kirimEmail(){ 

        $params = $this->load->library('email');
        $data123 = array('Email' => $this->input->post('email')); 
        
        $is_exist = $this->query_helper->user_exist($data123);
        if ($is_exist->num_rows() == 1) {

            $to_email = $this->input->post('email'); 
            $config = Array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'ssl://jkt03.rapidwhm.com',
                    'smtp_port' => 465,
                    'smtp_user' => EMAIL,
                    'smtp_pass' => PASS_EMAIL,
                    'mailtype'  => 'html', 
                    'charset'   => 'utf-8',
                    'wordwrap' => TRUE,
            );
            $body = $this->load->view('templates/forgotpass_email','',true);
            $this->email->initialize($config);

            $this->email->set_newline("\r\n");   
            
            $this->email->from(EMAIL, NAMA_EMAIL); 
            $this->email->to($to_email);
            $this->email->subject('Konfirmasi Lupa Password Digistudent FTI UNTAR'); 
            $this->email->message($body); 
            
            // echo $this->email->print_debugger();
            //Send mail 
            if($this->email->send()){
                $this->session->set_flashdata('sukses', 'Email Telah Dikirim');
                redirect('Auth');

            }else {
                // echo "tidak sukses";
                echo "<script>alert('Email Tidak Terkirim'); history.go(-1);</script>";
            } 
        }
        else{
            echo "<script>alert('Email Tidak Terdaftar'); history.go(-1);</script>";
        }

        
    }

    public function AturSandiBaru(){
        $this->load->view('templates/head_auth');
        $this->load->view('Auth/form_setpassword');
    }

    public function SetSandiBaru(){
        $email = $this->input->post('email');
        $passwordbaru = htmlspecialchars(md5($this->input->post('password', TRUE)));
        $data123 = array('Password' => $passwordbaru);
        $update = $this->query_helper->update('users','email',$email,$data123);

        if($update){
            $this->session->set_flashdata('sukses', 'Kata Sandi Berhasil di Perbaharui');
            redirect('Auth');
        }
        else{
            // echo"gagal";
            $this->session->set_flashdata('gagal', 'Kata Sandi Gagal di Perbaharui');
            redirect('Auth');
        }
        
    }

}