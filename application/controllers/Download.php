<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends CI_Controller {

    function __construct() {
        
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        
        // Load user model
        $this->load->model('dokumen_model');
        $this->load->model('query_helper');
    }

    public function download_dokumen(){	 // download file dokumen
        
        $id = $this->uri->segment(3); //id dokumen
        
        $nama_file = $this->dokumen_model->download($id);
        $nama_file_download = $nama_file['File'] ;
        echo $nama_file_download;
        //die;
        $this->load->helper('download');
        $data = file_get_contents(FOLDER_DOKUMEN.$nama_file_download); // Read the file's contents
        
        force_download($nama_file_download,$data,TRUE);
        
        //redirect(site_url());
			
    }

    public function download_materi(){ //download file materi
        $id_kelas = $this->uri->segment(3);
        $Pertemuan = $this->uri->segment(4);
       
        $a = array(
            'Pertemuan' =>$Pertemuan,
            'Id_kelas' => $id_kelas
        );
         $nama_file = $this->query_helper->download_materi('materi_kuliah',$a);
        
         $nama_file_download = $nama_file['File'] ;

        $this->load->helper('download');
        // path: uploads/materi/id_kelas/pertemuan/namafile
        $data = file_get_contents(FOLDER_MATERI.$nama_file_download); // Read the file's contents 
        
        force_download($nama_file_download,$data,TRUE);
        
    }

    public function download_tugas(){
        $id_kelas = $this->uri->segment(3);
        $Pertemuan = $this->uri->segment(4);
       
        $a = array(
            'Pertemuan' =>$Pertemuan,
            'Id_kelas' => $id_kelas
        );
         $nama_file = $this->query_helper->download_materi('tugas_kuliah',$a);
        
         $nama_file_download = $nama_file['File'] ;

        $this->load->helper('download');
        // path: uploads/materi/id_kelas/pertemuan
        $data = file_get_contents(FOLDER_TUGAS.$nama_file_download); // Read the file's contents 
        
        force_download($nama_file_download,$data,TRUE);
        
    }

    public function download_skripsi(){	
        
        $id = $this->uri->segment(3);

        $nama_file = $this->query_helper->download('judul_skripsi','Id_skripsi',$id);
        $nama_file_download = $nama_file['File'] ;
        //$nama_file_hasil = $nama_file['Id_skripsi'] ;
        // echo $nama_file_download;
        // die;
        $this->load->helper('download');
        $data = file_get_contents(FOLDER_SKRIPSI.$nama_file_download); // Read the file's contents
        
        force_download($nama_file_download,$data,TRUE);
        
        //redirect(site_url());
			
    }

    public function download_aplikasi(){	
        
    
        $nama_file_download = 'DigitalStudent.apk';
        //$nama_file_hasil = $nama_file['Id_skripsi'] ;
        // echo $nama_file_download;
        // die;
        $this->load->helper('download');
        $data = file_get_contents(FOLDER_APLIKASI.$nama_file_download); // Read the file's contents
        
        force_download($nama_file_download,$data,TRUE);
        
        //redirect(site_url());
			
    }
}