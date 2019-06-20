<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pengumuman extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load user model
        $this->load->model('pengumuman_model');
    }
    
    public function index(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Pengumuman Akademik';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Pengumuman/index_pengumuman');
    }
    
    //function untuk datatables ke view
    function jsonData(){
        $url = site_url()."Pengumuman/delete/$1";
        $atts1 = array(
            'class'       => 'btn btn-info btn-sm',
            'data-type'      => 'edit',
            'data-toggle'  => 'modal',
            'data-target'      => '#modalUserAddEdit', 
            'rowID' => '$1' 
        );

        $atts2 = array(
            'class'       => 'btn btn-danger btn-sm',
        );

        $this->load->library('datatables');
        $this->datatables->select('Judul_informasi,Isi,Lampiran,modified, Id_informasi as Id,');
        $this->datatables->add_column('action',array( anchor("#",'Lihat',$atts1)." "." ". anchor($url,'Hapus',$atts2)) , 'Id');
        $this->db->order_by('created', 'DESC');
        $this->datatables->where('Status =','1');
        $this->datatables->from('pengumuman_akademik');
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
            $data['pengumuman'] = $this->pengumuman_model->getRows(array('Id_informasi'=>$id));
            // Return data as JSON format
            echo json_encode($data);
        }
    }

    public function add(){
        $verr = $status = 0;
        $msg = '';
        $Pdata = array();
        
        // config upload
        $config['upload_path']    = FCPATH.'uploads/pengumuman/';
        $config['allowed_types']  = 'jpg|png|jpeg';
        $config['max_size']       = '5000';

        //initialize config file
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        
        
        if(! $this->upload->do_upload('userFile'))
        {
            // $error = array('error' => $this->upload->display_errors());
            // //$this->load->view('home_v', $error);
            // echo $error['error'];
            $this->session->set_flashdata('gagal', 'Ekstensi file tidak sesuai');
            redirect('Pengumuman');
           
        }
        else
        {
            $data = $this->upload->data();
            $nama_file= $data['file_name'];
           
            //base64 img
            $path = $data['full_path'] ;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            //echo $nama_file ;

            $id = mt_rand(10000000,999999999);
            $judul = $this->input->post('judul');
            $isi = $this->input->post('isi');			
            
            // Validate form fields
            if(empty($judul)){
                $verr = 1;
                $msg .= 'Judul Pengumuman Belum di Input !<br/>';
            }
            if(empty($isi)){
                $verr = 1;
                $msg .= 'Isi Pengumuman Belum di Input !<br/>';
            }
            
            if($verr == 0){
                // Prepare user data
                $Pdata = array(
                    'Id_informasi'=> $id,
                    'Judul_informasi'        => $judul,
                    'Isi'    => $isi,
                    'Lampiran'    => $base64,
                );
                
                
                $count = $this->db->count_all('pengumuman_akademik');
                if ($count >="4"){
                    echo " maksimal 4";
                }
                else{
                    $insert = $this->pengumuman_model->insert($Pdata);
                    //cek kondisi insert
                    if($insert){
                        //$status = 1;
                        unlink($path);
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Pengumuman');
                    }else{
                        $this->session->set_flashdata('sukses', 'Data Berhasil di Upload');
                            redirect('Pengumuman');
                    }   
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

        
    }

    public function delete(){
        $kode = $this->uri->segment(3);
        $Pdata = array(
                    'Status'=> '0',
                );
        $delete = $this->pengumuman_model->delete($Pdata,$kode);
        
        if($delete){
            $this->session->set_flashdata('sukses', 'Data Berhasil di Hapus');
            redirect('Pengumuman');
        }else{
            $msg .= 'Terjadi masalah, Silahkan dicoba lagi.';
        }  

    }
    //function get data checked from datatables
    function getSender(){

        $nomor = $this->input->post('id');
        $title = $this->input->post('judul');
        $message = $this->input->post('isi');

        $length = count($nomor);
        for ($i = 0; $i < $length; $i++){
            $penerima[$i]= array(
                "UUID"=> $nomor[$i],
                "Judul"=>$title,
                "Isi"=>$message,
            );
        }
        //print_r ($penerima);
        //echo sizeof($penerima);
        $this->kirimNotifikasi($penerima);
        
    }

    //function untuk push atau kirim pesan ke user terpilih
    public function kirimNotifikasi($penerima= array()){
        echo "<pre>";
        print_r($penerima);
        echo "<pre>";

        //looping penerima berdasarkan array pengiriman
        $count = count($penerima);
        for($i=0; $i < $count;  $i++){
            //judul heading notifikasi 
            $headings = array(
                "en" => $penerima[$i]['Judul']
            ); 

            //isi pesan atau isis dari notifikasi
            $content = array(
                "en" => $penerima[$i]['Isi']
                );
            
            $fields = array(
                'app_id' => APP_ID,
                'filters' => array(
                    array(
                        "field" => "tag", 
                        "key" => "UUID", // uuid
                        "value" => $penerima[$i]['UUID'] // nomor uuid
                    )
                ),
                'contents' => $content,
                'headings' => $headings,
            );
            
            $fields = json_encode($fields);
            print_r("\nJSON sent:\n");
            print_r($fields);
        }
        die;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic '.API_KEY));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
        
        //return print_r($response);
        redirect('Notifikasi');
        
    }
    
}
