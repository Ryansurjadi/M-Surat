<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jadwalujian extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        $this->load->model('query_helper');
    }
    
    public function index(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'List Jadwal ujian';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Jadwal/index_jadwalujian');
    }

    public function Jadwalbaru(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Buat Jadwal Ujian';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Jadwal/index_buatjadwalujian');
    }
    
    //function untuk datatables ke view
    function jsonUjian(){

        $conditions = array( 'kelas.Status' => '1');

        $this->load->library('datatables');
        $this->datatables->select('kelas.Id_kelas , kelas.Kelas, matakuliah.Nama');
        $this->datatables->from('kelas');
        $this->datatables->join('matakuliah', 'matakuliah.Id_matkul = kelas.Id_matkul');
        $this->datatables->select('dosen.Nama as Dosen');
        $this->datatables->join('dosen', 'dosen.Nid = kelas.Nid');
        $this->datatables->select('DATE_FORMAT(ujian.Tanggal, "%d %M %Y") as Tanggal, ujian.Jam_mulai, ujian.Jam_selesai, ujian.Id_ujian as Id');
        $this->datatables->join('ujian', 'ujian.Id_kelas = kelas.Id_kelas');
       // $this->datatables->where($conditions);
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    }

    function jsonJadwal(){
        $url = site_url()."Jadwalujian/delete_row/$1";
        $atts1 = array(
             'class'       => 'btn btn-danger btn-sm',
            
        );

       $conditions = array( 'kelas.Status' => '1','jadwal_ujian.NIM' =>$this->session->userdata('NIM') );

        $this->load->library('datatables');
        $this->datatables->select('kelas.Id_kelas , kelas.Kelas, matakuliah.Nama');
        $this->datatables->from('kelas');
        $this->datatables->join('matakuliah', 'matakuliah.Id_matkul = kelas.Id_matkul');
        $this->datatables->select('dosen.Nama as Dosen');
        $this->datatables->join('dosen', 'dosen.Nid = kelas.Nid');
        $this->datatables->select(' DATE_FORMAT(ujian.Tanggal, "%d %M %Y") as Tanggal, ujian.Jam_mulai, ujian.Jam_selesai');
        $this->datatables->join('ujian', 'ujian.Id_kelas = kelas.Id_kelas');
         $this->datatables->select('jadwal_ujian.Id_jadwal_ujian as Id');
        $this->datatables->join('jadwal_ujian', 'jadwal_ujian.Id_ujian = ujian.Id_ujian');
        $this->datatables->where($conditions);
        $this->datatables->add_column('action',array( anchor($url,'Hapus Matakuliah',$atts1)), 'Id');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    }

    //function get data checked from datatables
    function getJadwalUjian(){
        
        $nomor = $this->input->post('id');

        $length = count($nomor);
        for ($i = 0; $i < $length; $i++){
            $jadwal = mt_rand(11111111,99999999);
            $data[$i]= array(
                "Id_jadwal_ujian" => $jadwal,
                "Id_ujian"=> $nomor[$i],
                "NIM" =>$this->session->userdata('NIM')
            );
        }

        $nim = $this->session->userdata('NIM');
        $exist = $this->query_helper->is_exist('jadwal_ujian','NIM',$nim);
        if($exist){
            $this->session->set_flashdata('gagal', 'Jadwal Lama Harus di Hapus dulu !');
            redirect('Jadwalujian');
        }
        else{
            $insert = $this->db->insert_batch('jadwal_ujian',$data);
            if($insert){
                // echo "Sukses<pre>";
                // print_r ($data);
                $this->session->set_flashdata('sukses', 'Jadwal Berhasil di Simpan');
                redirect('Jadwalujian');
            }
            else{
                // echo"gagal";
                $this->session->set_flashdata('gagal', 'Jadwal Gagal di Simpan');
                redirect('Jadwalujian/Jadwalbaru');
            }
        //echo sizeof($penerima);
        }
        
        
    }

    function delete(){
        $nim = $this->session->userdata('NIM');
        
        $delete = $this->query_helper->delete('jadwal_ujian','NIM',$nim);
            if($delete){
                // echo "Sukses<pre>";
                // print_r ($data);
                $this->session->set_flashdata('sukses', 'Jadwal Berhasil di Hapus');
                redirect('Jadwalujian/Jadwalbaru');
            }
            else{
                // echo"gagal";
                $this->session->set_flashdata('gagal', 'Jadwal Gagal di Hapus');
                redirect('Jadwalujian');
            }
    }

    function delete_row(){
        $nim = $this->session->userdata('NIM');
        $row = $this->uri->segment(3);
        $conditions = array('NIM'=>$nim, 'Id_jadwal_ujian'=>$row);
        $this->db->where($conditions);
        $delete = $this->db->delete('jadwal_ujian');

        $delete = $this->query_helper->delete('jadwal_kuliah','NIM',$nim);
            if($delete){
                // echo "Sukses<pre>";
                // print_r ($data);
                $this->session->set_flashdata('sukses', 'Jadwal Berhasil di Hapus');
                redirect('Jadwalujian/Jadwalbaru');
            }
            else{
                // echo"gagal";
                $this->session->set_flashdata('gagal', 'Jadwal Gagal di Hapus');
                redirect('Jadwalujian');
            }
    }
}