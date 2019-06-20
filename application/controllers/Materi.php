<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Materi extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        // Load user model
        $this->load->model('dosen_model');
        $this->load->model('query_helper');
    }
    
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    //Materi untuk Mahasiswa

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    public function index(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Materi Kuliah';
        $data['materi_kuliah'] = $this->query_helper->getMateri($this->session->userdata('NIM'));
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Materi/index_materi_mahasiswa',$data);


    }

    public function viewMateri(){
        $id = $this->uri->segment(3);
        
        // Load the list page view
        $data['title'] = 'Materi Kuliah';
        $data['kelas'] = $this->query_helper->getNamaKelas($id);
        $data['materi'] = $this->query_helper->getRows('materi_kuliah','Id_kelas',array('Id_kelas'=> $id));
        // echo"<pre>";
        // print_r($data);
        // die;
        
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Materi/index_lihat_materi_mahasiswa',$data);

    }
    
    public function download_materi(){
        $id_kelas = $this->uri->segment(3);
        $Pertemuan = $this->uri->segment(4);
       
        $a = array(
            'Pertemuan' =>$Pertemuan,
            'Id_kelas' => $id_kelas
        );
         $nama_file = $this->query_helper->download_materi('materi_kuliah',$a);
        
         $nama_file_download = $nama_file['File'] ;

        $this->load->helper('download');
        // path: uploads/materi/id_kelas/pertemuan
        $data = file_get_contents(FOLDER_MATERI.$nama_file_download); // Read the file's contents 
        
        force_download($nama_file_download,$data,TRUE);
        
    }


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  

    //Materi untuk Dosen

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    public function materi_dosen(){
        // Load the list page view
        $data['title'] = 'Materi dan Tugas Kuliah';
        $data['materi_kuliah'] = $this->query_helper->getMateriDosen($this->session->userdata('Nid'));
        // echo"<pre>";
        // print_r($data);
        // echo $this->session->userdata('Nid');
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Materi/materi_test_dosen',$data);
    }

    public function upload_materi_dosen(){
        $data['params'] = $this->uri->segment(3);
        // Load the list page view
        $data['NamaKelas'] = $this->query_helper->getNamaKelas($data['params']);
        $data['title'] = 'Upload Materi Kuliah';
        $data['materi_kuliah'] = $this->query_helper->getMateriDosen($this->session->userdata('Nid'));
        // echo"<pre>";
        // print_r($data);
        // die;
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Materi/index_upload_materi',$data);
    }

    //function untuk datatables ke view
    function jsonUploadMateri(){
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
        $this->datatables->select('Pertemuan,Deskripsi,File,Id_materi as Id');
        $this->datatables->where('Id_kelas',$id);
        $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->from('materi_kuliah');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    public function EditData(){
        $data = array();
        $id = $this->input->post('Id');
        if(!empty($id)){
            // Fetch user data
           
            $data['materi'] = $this->query_helper->getRows('materi_kuliah','Id_materi',array('Id_materi'=>$id));
            // Return data as JSON format

            echo json_encode($data);
            
        }
    }

    function add(){
        $verr = $status = 0;
        $msg = '';

        $id_materi = mt_rand(10000000,99999999);
        $id_kelas = $this->input->post('id_kelas');
        $pertemuan = $this->input->post('pertemuan');
        $deskripsi = $this->input->post('deskripsi');				
            
        $path_folder = FCPATH.'/uploads/materi/'.$id_kelas.'/'.$pertemuan; // path: uploads/materi/id_kelas/pertemuan
        if(empty($pertemuan)){
                $verr = 1;
                $msg .= 'Pertemuan Belum di Input !<br/>';
        }
        if(empty($deskripsi)){
                $verr = 1;
                $msg .= 'Deskripsi Belum di Input !<br/>';
        }
        if($verr == 0){
            $this->db->select('Count(Pertemuan) as Total');
            $this->db->from('materi_kuliah');
            $this->db->where ('Pertemuan',$pertemuan);
            $query = $this->db->get();
            $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
            
            
            //check udah ada apa belum
            if ($result[0]['Total'] < "1" ){ //create
                if (!file_exists($path_folder)) {
                    mkdir($path_folder, 0777,TRUE);
                    
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
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$name;
                        
                        
                        $data123 = array(
                                'Id_materi'=> $id_materi,
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi,     
                                'File' =>$nama_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;
                        $insert = $this->query_helper->insert('materi_kuliah',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }else{
                            $this->session->set_flashdata('gagal', 'Data gagal di Upload');
                            redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }
                        
                       
                    }
                    
                    else{
                        $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
                        redirect('Materi/upload_materi_dosen/'.$id_kelas);
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
                        $name = $data['file_name'];
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$name;
                        
                        $data123 = array(
                                'Id_materi'=> $id_materi,
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi,     
                                'File' =>$nama_file,
                        );  
                        // echo "<pre>";
                        // print_r($data123);
                        // die;

                        $insert = $this->query_helper->insert('materi_kuliah',$data123);
                        if($insert){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                             redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                             redirect('Materi/upload_materi_dosen/'.$id_kelas);
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

                $id_materi = $this->input->post('id');
                $id_kelas = $this->input->post('id_kelas');
                $pertemuan = $this->input->post('pertemuan');
                $deskripsi = $this->input->post('deskripsi');
                $newfiles = $this->input->post('userFile');
                echo $lama = $this->input->post('lama');				
                    
                $path_folder = FCPATH.'/uploads/materi/'.$id_kelas.'/'.$pertemuan; // path: uploads/materi/id_kelas/pertemuan
                
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
                        $name =  $data['file_name'];
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$name;
                        $data123 = array(
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi,     
                                'File' =>$nama_file,
                        );  
                        // echo "<pre> do";
                        // print_r($data);
                        // die;
                        $update = $this->query_helper->update('materi_kuliah','Id_materi',$id_materi,$data123);
                        if($update){
                            $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                             redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                             redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }
                        
                    }
                    else{
                        $error = array('error' => $this->upload->display_errors());
                        print_r($error);
                        $data = $this->upload->data();
                        $name =  $data['file_name'];
                        $nama_file= $id_kelas.'/'.$pertemuan.'/'.$name;
                        $data123 = array(
                                'Id_kelas' => $id_kelas,
                                'Pertemuan' => $pertemuan,
                                'Deskripsi' =>$deskripsi,     
                                'File' =>$lama,
                        );  
                        // echo "<pre> no";
                        // print_r($data);
                        // die;
                        $update = $this->query_helper->update('materi_kuliah','Id_materi',$id_materi,$data123);
                        if($update){
                           $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                             redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }else{
                            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';$this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                             redirect('Materi/upload_materi_dosen/'.$id_kelas);
                        }
                    }
                    
                    
                }
            }
        }
    }
    
    
}