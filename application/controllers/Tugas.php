<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tugas extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load user model
        $this->load->model('query_helper');
    }
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    //tugas untuk Mahasiswa

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    public function index(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Tugas Kuliah';
        $data['tugas_kuliah'] = $this->query_helper->getMateri($this->session->userdata('NIM'));
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Tugas/index_tugas',$data);


    }

    public function viewTugas(){
        $id = $this->uri->segment(3);
        
        // Load the list page view
        $data['title'] = 'Tugas Kuliah';
        $data['kelas'] = $this->query_helper->getNamaKelas($id);
        $data['tugas'] = $this->query_helper->getRows('tugas_kuliah','Id_kelas',array('Id_kelas'=> $id));
        
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Tugas/index_lihat_tugas_mahasiswa',$data);

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
        
        force_download($nama_file_download,$data);
        
    }

    public function upload_tugas_mahasiswa(){
        $id_tugas_mhs = mt_rand(10000000,99999999);
        $id_tugas = $this->input->post('id_tugas');
        $id_kelas = $this->input->post('id_kelas');
        $pertemuan = $this->input->post('pertemuan');
        $deskripsi = $this->input->post('deskripsi');
        $nim = $this->session->userdata('NIM');
        $tgl_akhir= $this->query_helper->getDeadline('tugas_kuliah',$id_tugas);
        $tgl_hariini = date("Y-m-d");
        
        $path_folder = FCPATH.'/uploads/tugas/'.$id_kelas.'/'.$pertemuan.'/'.$id_tugas.'/'; // path: uploads/materi/id_kelas/pertemuan
        $penanda = ($tgl_hariini <= $tgl_akhir[0]['Tanggal_mulai']) || ($tgl_hariini == $tgl_akhir[0]['Tanggal_mulai']); 
        
        if($tgl_hariini >= $tgl_akhir[0]['Tanggal_selesai']){
            //echo "lewat deadline";
            echo "<script>alert('Lewat deadline,Tidak bisa Kumpul'); history.go(-1);</script>";
        }
        
        // else if($penanda){
        //     echo "<script>alert('Belum bisa Kumpul'); history.go(-1);</script>";
        // }
        else{
            $data123 = array(
                    'Id_tugas_mhs' =>$id_tugas_mhs,
                    'Id_tugas' => $id_tugas,
                    'NIM' => $nim,
                    'Deskripsi' =>$deskripsi, 
            );  
            echo"<pre>";
            print_r($data123);
            //die;
           
                if (!file_exists($path_folder)) {
                    mkdir($path_folder, 0777,true);
                    
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
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$id_tugas.'/'.$data['file_name'];
                        
                        
                        $data123 = array(
                                'Id_tugas_mhs' =>$id_tugas_mhs,
                                'Id_tugas' => $id_tugas,
                                'NIM' => $nim,
                                'File' => $nama_file,
                                'Deskripsi' =>$deskripsi, 
                        );   
                        // echo "<pre>";
                        // print_r($data123);
                        // die;
                        $insert = $this->query_helper->insert('tugas_kuliah_mahasiswa',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas');
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas');
                        }
                        
                    }
                    
                    else{
                        $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
                        redirect('Tugas');
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
                         $nama_file= $id_kelas.'/'.$pertemuan.'/'.$id_tugas.'/'.$data['file_name'];
                        
                        
                        $data123 = array(
                                'Id_tugas_mhs' =>$id_tugas_mhs,
                                'Id_tugas' => $id_tugas,
                                'NIM' => $nim,
                                'File' => $nama_file,
                                'Deskripsi' =>$deskripsi, 
                        ); 
                        // echo "<pre>";
                        // print_r($data123);
                        // die;

                        $insert = $this->query_helper->insert('tugas_kuliah_mahasiswa',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas');
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas');
                        }
                    }
                    else{
                        $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
                    }
                }
            
        }
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    //Function upload Tugas untuk Mahasiswa dari Dosen

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    
    public function upload_tugas_dosen(){
        $data['params'] = $this->uri->segment(3);
        // Load the list page view
        
        $data['NamaKelas'] = $this->query_helper->getNamaKelas($data['params']);
        $data['title'] = 'Upload Tugas Kuliah';
        $data['materi_kuliah'] = $this->query_helper->getMateriDosen($this->session->userdata('Nid'));
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Tugas/index_upload_tugas_dosen',$data);
    }

    //function untuk datatables ke view
    function jsonUploadTugas(){
        $id = $this->uri->segment(3);
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
        $this->datatables->select('Pertemuan,Deskripsi,File,Id_tugas as Id');
        $this->datatables->where('Id_kelas',$id);
        $this->db->order_by('Pertemuan','ASC');
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->from('tugas_kuliah');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    public function EditData(){
        $data = array();
        $id = $this->input->post('Id');
        if(!empty($id)){
            // Fetch user data
           
            $data['tugas'] = $this->query_helper->getRows('tugas_kuliah','Id_tugas',array('Id_tugas'=>$id));
            // Return data as JSON format

            echo json_encode($data);
            
        }
    }

    function add(){
        $verr = $status = 0;
        $msg = '';

        $id_tugas = mt_rand(10000000,99999999);
        $id_kelas = $this->input->post('id_kelas');
        $pertemuan = $this->input->post('pertemuan');
        $deskripsi = $this->input->post('deskripsi');
        //$mulai = $this->input->post('mulai');
        $selesai = $this->input->post('selesai');				
            

        $path_folder = FCPATH.'/uploads/tugas/'.$id_kelas.'/'.$pertemuan; // path: uploads/materi/id_kelas/pertemuan
        
        if(empty($pertemuan)){
                $verr = 1;
                $msg .= 'Pertemuan Belum di Input !<br/>';
        }
        if(empty($deskripsi)){
                $verr = 1;
                $msg .= 'Deskripsi Belum di Input !<br/>';
        }
        // if(empty($mulai)){
        //         $verr = 1;
        //         $msg .= 'Tanggal Mulai Belum di Input !<br/>';
        // }
        if(empty($selesai)){
                $verr = 1;
                $msg .= 'Tanggal Selesai Belum di Input !<br/>';
        }

        if($verr == 0){
            $this->db->select('Count(Pertemuan) as Total');
            $this->db->from('tugas_kuliah');
            $this->db->where ('Pertemuan',$pertemuan);
            $query = $this->db->get();
            $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            
            //check udah ada apa belum
            if ($result[0]['Total'] < "1" ){ //create
                
                if (!file_exists($path_folder)) {
                    mkdir($path_folder, 0777,true);
                    
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
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$data['file_name'];
                        
                        
                        $data123 = array(
                                'Id_tugas'=> $id_tugas,
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi, 
                                'Tanggal_selesai' => $selesai,    
                                'File' =>$nama_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;
                        $insert = $this->query_helper->insert('tugas_kuliah',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }
                    }
                    
                    else{
                        $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
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
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$data['file_name'];
                        
                        
                        $data123 = array(
                                'Id_tugas'=> $id_tugas,
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi, 
                                'Tanggal_selesai' => $selesai,    
                                'File' =>$nama_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;

                        $insert = $this->query_helper->insert('tugas_kuliah',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }
                    }
                    else{
                        $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
                    }
                }
            }
            else{ //update                    
                //echo "<script>alert('Sudah ada Materi'); history.go(-1);</script>";
                $verr = $status = 0;
                $msg = '';

                $id_tugas = $this->input->post('id');
                $id_kelas = $this->input->post('id_kelas');
                $pertemuan = $this->input->post('pertemuan');
                $deskripsi = $this->input->post('deskripsi');
                $newfiles = $this->input->post('userFile');
                echo $lama = $this->input->post('lama');				
                    
                $path_folder = FCPATH.'/uploads/tugas/'.$id_kelas.'/'.$pertemuan; // path: uploads/materi/id_kelas/pertemuan
                
                if(empty($pertemuan)){
                        $verr = 1;
                        $msg .= 'Pertemuan Belum di Input !<br/>';
                }
                if(empty($deskripsi)){
                        $verr = 1;
                        $msg .= 'Deskripsi Belum di Input !<br/>';
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
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$data['file_name'];
                        
                        $data123 = array(
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi, 
                                //'Tanggal_mulai' => $mulai,
                                'Tanggal_selesai' => $selesai,    
                                'File' =>$nama_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;

                        
                        //$this->db->where('Status', '0');
                        //$update=$this->db->update('notifikasi', $data123);
                        $update = $this->query_helper->update('tugas_kuliah','Id_tugas',$id_tugas,$data123);

                        if($update){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }else{
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }
                    }
                    else{
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);

                        $data = $this->upload->data();
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$lama;
                        
                        $data123 = array(
                                'Id_tugas'=> $id_tugas,
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi, 
                               // 'Tanggal_mulai' => $mulai,
                                'Tanggal_selesai' => $selesai,    
                                'File' =>$lama
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // // die;
                        echo $update = $this->query_helper->update('tugas_kuliah','Id_tugas',$id_tugas,$data123);
                        if($update){
    
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }else{
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Tugas/upload_tugas_dosen/'.$id_kelas);
                        }
                        
                        
                    }
                    
                    
                }
            }
        }
    }
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    //Function Lihat Tugas dari Mahasiswa 

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    function jsonHasilTugasMahasiswa(){
        $id_tugas = $this->uri->segment(4);
        $atts1 = array(
            'class'       => 'btn btn-danger btn-sm',
           
            'rowID' => '$1' 
        );

        $atts2 = array(
            'class'       => 'btn btn-danger btn-sm',
            'onclick' => "return confirm('Hapus Data?')"
        );
    
        $conditions = array('tugas_kuliah_mahasiswa.Id_tugas' => $id_tugas);
        $this->load->library('datatables');
        $this->datatables->select('mahasiswa.Nama, tugas_kuliah_mahasiswa.NIM, tugas_kuliah_mahasiswa.Id_tugas_mhs as Id');
        $this->datatables->from('tugas_kuliah_mahasiswa');
        $this->datatables->join('mahasiswa','mahasiswa.NIM = tugas_kuliah_mahasiswa.NIM');
        $this->datatables->where($conditions);
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    public function download_hasil_tugas(){
        $id_kelas = $this->uri->segment(3);
        $pertemuan = $this->uri->segment(4);
        $id_tugas = $this->uri->segment(5);
       
        //zip folder baru download
        $this->load->library('zip');
        $path = './uploads/tugas/'.$id_kelas.'/'.$pertemuan.'/'.$id_tugas; // idkelas/pertemuan/idtugas
        $test = $this->zip->read_dir($path,FALSE);
        $datenow= date('d-m-Y');
        $this->zip->download($datenow.' Tugas pertemuan-'.$pertemuan.'.zip'); 
        
    }

    public function lihat_tugas_pertemuan(){
        $id = $this->uri->segment(3);

        // Load the list page view
        $data['title'] = 'Tugas Pertemuan';
        $data['kelas'] = $this->query_helper->getNamaKelas($id);
        $data['pertemuan'] = $this->query_helper->getRows('tugas_kuliah','Id_kelas',array('Id_kelas'=> $id));
        $data['materi_kuliah'] = $this->query_helper->getMateriDosen($this->session->userdata('Nid'));
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Tugas/index_lihat_tugas_pertemuan',$data);
    }

    public function hasil_tugas_pertemuan(){
        $data['params1'] = $this->uri->segment(3);
        $data['params2'] = $this->uri->segment(4); //id tugas

        $id_kelas = $this->uri->segment(3);

        // Load the list page view
        $data['title'] = 'Daftar Tugas Mahasiswa';
        $data['NamaKelas'] = $this->query_helper->getNamaKelas($id_kelas);
        $data['pertemuan'] = $this->query_helper->getRows('tugas_kuliah','Id_kelas',array('Id_kelas'=> $id_kelas));
        $data['materi_kuliah'] = $this->query_helper->getMateriDosen($this->session->userdata('Nid'));
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Tugas/index_hasil_tugas_pertemuan',$data);
    }
}