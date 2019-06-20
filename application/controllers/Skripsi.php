<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Skripsi extends CI_Controller {
    
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
        $data['title'] = 'Data Skripsi';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Skripsi/index_skripsi',$data);
    }
    
    //function untuk datatables ke view
    function jsonData(){
        $url = site_url()."skripsi/download/$1";
        $url2 = site_url()."skripsi/delete/$1";
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
        $this->datatables->select('judul_skripsi.Id_skripsi as Id, judul_skripsi.Judul,judul_skripsi.Id_prodi, judul_skripsi.Tahun_lulus, judul_skripsi.File, mahasiswa.NIM,mahasiswa.Nama as Mahasiswa');
        $this->datatables->from('judul_skripsi');
        $this->datatables->join('mahasiswa', 'mahasiswa.NIM = judul_skripsi.NIM');
        if($this->session->userdata('Level')== "Admin"){
            $this->datatables->add_column('action',array(anchor($url2,'Hapus',$atts3) ." "." ".  anchor($url,'Unduh',$atts3)), 'Id');
        }else{
            $this->datatables->add_column('action',array(anchor($url,'Unduh Abstrak',$atts3)), 'Id');
        }
        $this->db->order_by('judul_skripsi.modified', 'DESC');
        $this->datatables->where('judul_skripsi.Status =','1');
        $this->datatables->from('judul_skripsi');
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
            $data['skripsi'] = $this->query_helper->getRows('judul_skripsi','Id_skripsi',array('Id_skripsi'=>$id));
            // Return data as JSON format
            echo json_encode($data);
        }
    }
    
    public function download(){	
        
        $id = $this->uri->segment(3);

        $nama_file = $this->query_helper->download('judul_skripsi','Id_skripsi',$id);
        $nama_file_download = $nama_file['File'] ;
        //$nama_file_hasil = $nama_file['Id_skripsi'] ;
        // echo $nama_file_download;
        // die;
        $this->load->helper('download');
        $data = file_get_contents(FOLDER_SKRIPSI.$nama_file_download,false); // Read the file's contents
        
        force_download($nama_file_download,$data);
        
        //redirect(site_url());
			
    }

    function add(){
        $verr = $status = 0;
        $msg = '';

        $nim = $this->session->userdata('NIM');
        $id_prodi = $this->session->userdata('Id_prodi');

        $id_skripsi = mt_rand(1000000,9999999);;
        $judul = $this->input->post('judul');
        $tahun = $this->input->post('tahun');			
            
        $path_folder = FCPATH.'/uploads/skripsi/'.$tahun.'/'; 

        if(empty($judul)){
                $verr = 1;
                $msg .= 'Nama Skripsi Belum di Input !<br/>';
        }
        if(empty($tahun)){
                $verr = 1;
                $msg .= 'Tahun kelulusan Belum di Input !<br/>';
        }
        if($verr == 0){
            $this->db->select('Count(NIM) as Total');
            $this->db->from('judul_skripsi');
            $this->db->where ('NIM',$nim);
            $query = $this->db->get();
            $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            
            //echo $result[0]['Total'];
            //check udah ada apa belum
            if ($result[0]['Total'] < "1" ){ //create
                if (!file_exists($path_folder)) {
                    mkdir($path_folder, 0777,TRUE);
                    
                    $config['upload_path'] = $path_folder;
                    $config['allowed_types'] = 'pdf';
                    $config['max_size']	= '10000';
                    $config['encrypt_name'] = TRUE;
                    //$config['max_width']  = '1024';
                    //$config['max_height']  = '768';
                    $this->upload->initialize($config);
                    $this->load->library("upload", $config);
                    
                    if($this->upload->do_upload('userFile')){
                        $data = $this->upload->data();
                        $name =  $data['file_name']; 
                        $name_file = $tahun.'/'.$name;
                        $data123 = array(
                                'Id_skripsi'=> $id_skripsi,
                                'Id_prodi' => $id_prodi,
                                'Nim' => $nim,
                                'Judul' =>$judul,
                                'Tahun_lulus' =>$tahun,     
                                'File' =>$name_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;
                        $insert = $this->query_helper->insert('judul_skripsi',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Skripsi');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Skripsi');
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
                    $config['allowed_types'] = 'docx|txt|pdf|doc';
                    $config['max_size']	= '10000';
                    $config['encrypt_name'] = TRUE;
                    //$config['max_width']  = '1024';
                    //$config['max_height']  = '768';
                    $this->upload->initialize($config);
                    $this->load->library("upload", $config);
                    
                    if($this->upload->do_upload('userFile')){
                        $data = $this->upload->data();
                        $name =  $data['file_name'];
                        $name_file = $tahun.'/'.$name;
                        
                        $data123 = array(
                                'Id_skripsi'=> $id_skripsi,
                                'Id_prodi' => $id_prodi,
                                'Nim' => $nim,
                                'Judul' =>$judul,
                                'Tahun_lulus' =>$tahun,     
                                'File' =>$name_file,
                        );   
                        // echo "<pre>";
                        // print_r($data123);
                        // die;

                        $insert = $this->query_helper->insert('judul_skripsi',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Skripsi');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Skripsi');
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

                $nim = $this->session->userdata('NIM');
                $id_prodi = $this->session->userdata('Id_prodi');

                $id = $this->input->post('id');
                $judul = $this->input->post('judul');
                $tahun = $this->input->post('tahun');			
                    
                $path_folder = FCPATH.'/uploads/skripsi/'.$tahun.'/'; 
                
                if(empty($judul)){
                $verr = 1;
                $msg .= 'Nama Skripsi Belum di Input !<br/>';
                }
                if(empty($tahun)){
                        $verr = 1;
                        $msg .= 'Tahun kelulusan Belum di Input !<br/>';
                }
                if($verr == 0){
                
                    $config['upload_path'] = $path_folder;
                    $config['allowed_types'] = 'docx|txt|pdf|doc';
                    $config['max_size']	= '10000';
                    $config['encrypt_name'] = TRUE;
                    //$config['max_width']  = '1024';
                    //$config['max_height']  = '768';
                    $this->upload->initialize($config);
                    $this->load->library("upload", $config);
                    
                    if($this->upload->do_upload('userFile')){
                        $data = $this->upload->data();
                        $name =  $data['file_name'];  
                        $name_file = $tahun.'/'.$name;
                        
                        $data123 = array(
                                'Id_prodi' => $id_prodi,
                                'Nim' => $nim,
                                'Judul' =>$judul,
                                'Tahun_lulus' =>$tahun,     
                                'File' =>$name_file,
                        );  
                        // echo "<pre> do";
                        // print_r($data);
                        // die;
                        $update = $this->query_helper->update('judul_skripsi','Id_skripsi',$id,$data123);
                        if($update){
                            // /unlink(FCPATH.'$lama');
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                            redirect('Skripsi');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Skripsi');
                        }
                    }
                    else{
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                        $data = $this->upload->data(); 
                        $name =  $data['file_name']; 
                        
                        $data123 = array(
                                'Id_prodi' => $id_prodi,
                                'Nim' => $nim,
                                'Judul' =>$judul,
                                'Tahun_lulus' =>$tahun,     
                                'File' =>$name,
                        );  
                        // echo "<pre> no";
                        // print_r($data123);
                        // die;
                        $update = $this->query_helper->update('judul_skripsi','Id_skripsi',$id,$data123);
                        if($update){
                            // /unlink(FCPATH."$lama");
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                            redirect('Skripsi');
                        }else{
                            $this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                             redirect('Skripsi');
                        }
                        
                    }
                    
                    
                }
            }
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////

  
    function delete(){
        $id = $this->uri->segment(3);
        
        $delete = $this->query_helper->delete('judul_skripsi','Id_skripsi',$id);
        if($delete){
            $this->session->set_flashdata('sukses', 'Data Berhasil di Hapus');
            redirect('Skripsi');
        }
        else{
            $this->session->set_flashdata('gagal', 'Data Gagal di Hapus');
            redirect('Skripsi');
        }
    }
}
