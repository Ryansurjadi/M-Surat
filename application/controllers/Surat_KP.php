<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_KP extends CI_Controller {

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
		$data['title'] = 'Surat Kerja Praktik';
		$nim = $this->session->userdata('NIM');
		
		//Fetch data dari surat kp detail untuk cari data surat kp saya
			$conditions1 = array('NIM'=> $nim );
			$data['Surat_KP_saya'] = $this->query_helper->fetchSurat('surat_kp_detail',$conditions1);
		/////////////////////////////////

		//Fetch data dari surat kp master
			if(!empty($data['Surat_KP_saya'])){
				$nosurat = $data['Surat_KP_saya'][0]['Kd_file'];
				$conditions2 = array('Kd_file'=> $nosurat );
				$data['Surat_KP_master'] = $this->query_helper->fetchSurat('surat_kp',$conditions2);
			}
		////////////////////////////////

		//Fetch data dari surat kp detail
			if(!empty($data['Surat_KP_saya'])){
				$nosurat = $data['Surat_KP_saya'][0]['Kd_file'];
				$conditions3 = array('Kd_file'=> $nosurat );
				$data['Surat_KP_detail'] = $this->query_helper->fetchSurat('surat_kp_detail',$conditions3);
			}
		////////////////////////////////

		// echo "<pre>";
		// print_r($data);
		// die;
		$data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Surat_KP/index_surat_KP',$data);
    }
	
	public function Form_KP(){
		$data['title'] = 'Form Pengajuan Surat Kerja Praktik';
		$data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Surat_KP/index_form_KP',$data);
    }

	public function generateSuratKP(){
		$namapt = $this->input->post('namapt');
		$alamatpt = $this->input->post('alamatpt');
		$kotapt = $this->input->post('kotapt');
		
		$nim=$this->input->post('nim');
		$created = date("Y-m-d H:i:s");
		$modified = date("Y-m-d H:i:s");

		echo "<pre>";
		$bulan = $this->query_helper->BulanSurat(date('m'));
		//$nosurat = '013-KTU/176/FTI-UNTAR/'.$bulan.'/'.date('Y');
		
		//DB untuk ambil index surat dan totalsurat
			$this->db->select('Konfig_Value');
			$this->db->from('Konfigurasi');
			$this->db->where('Konfig_key' ,'Index_surat');
			$query2 = $this->db->get();
			$result1 = $query2->result_array();
			$index_surat = $result1[0]['Konfig_Value'];

			$this->db->select('Konfig_Value');
			$this->db->from('Konfigurasi');
			$this->db->where('Konfig_key','Total_surat');
			$query3 = $this->db->get();
			$result1 = $query3->result_array();
			$total_surat = $result1[0]['Konfig_Value'];

			$nosurat = $index_surat.'-KTU/'.$total_surat.'/'.'FTI-UNTAR/'.$bulan.'/'.date('Y');;

		/////////////////////////////////////////


		// DB untuk ambil data Mahasiswa
			if($nim[0] != NUll && $nim[1] == NUll && $nim[2] == NUll){
				$condition = "NIM='".$nim[0]."'";
			}
			elseif($nim[0] != NUll && $nim[1] != NUll && $nim[2] == NUll){
				$condition = "NIM='".$nim[0]."' OR NIM='".$nim[1]."' ";
			}
			elseif($nim[0] != NUll && $nim[1] != NUll && $nim[2] != NUll){
				$condition = "NIM='".$nim[0]."' OR NIM='".$nim[1]."' OR NIM='".$nim[2]."'";
			}
			$this->db->select('*');
			$this->db->from('mahasiswa');
			$this->db->where($condition);
			$query = $this->db->get();
			$result = $query->result_array();

			
		///////////////////////////////////////////
		
		// DB untuk ambil file template surat KP
			$this->db->select('*');
			$this->db->from('dokumen');
			$this->db->where('judul','Surat KP');
			$query1 = $this->db->get();
			$result1 = $query1->result_array();
			$nama_template = $result1[0]['File'];
        //////////////////////////////////////////

		
		 $this->load->library("Phpword");
		 //Generate doc dengan PHP word
			$template = new \PhpOffice\PhpWord\TemplateProcessor(FOLDER_DOKUMEN.$nama_template);

			$template->setValue('nosurat',$nosurat);
			$template->setValue('namaperusahaan',$namapt);
			$template->setValue('alamatperusahaan',$alamatpt);
			$template->setValue('kotaperusahaan',$kotapt);
			$template->cloneRow('nim',count($nim));
			
			//looping seluruh data 
			for($i=0; $i < count($nim);$i++){
				$data['anggota'.$i]['nim_'] = $result[$i]['NIM'];
			 	$data['anggota'.$i]['nama_'] = $result[$i]['Nama'];
			 	$data['anggota'.$i]['sks_'] = $result[$i]['SKS'];
			 	$data['anggota'.$i]['ipk_'] = $result[$i]['IPK'];
			 	$data['anggota'.$i]['alamat_'] = $result[$i]['Alamat'];

				$j= $i+1;
				$template->setValue('no#'.$j,$j);

			 	$template->setValue('nim#'.$j,$data['anggota'.$i]['nim_']);
		 		$template->setValue('nama#'.$j, $data['anggota'.$i]['nama_']);
	 			$template->setValue('sks#'.$j, $data['anggota'.$i]['sks_']);
 				$template->setValue('ipk#'.$j, $data['anggota'.$i]['ipk_']);
 				$template->setValue('alamat#'.$j, $data['anggota'.$i]['alamat_']);

 				
			}
            //var_dump($data);
	         
	        $namafile = 'Surat-Keterangan KP-'.$nim[0].'.docx' ;
	        //header("Content-Disposition: attachment; filename=".$namafile);
			

			$path_folder = FCPATH.'/uploads/dokumen/GenerateSuratKP/'; 
			if (!file_exists($path_folder)) {
					mkdir($path_folder, 0777);
					$template->saveAs($path_folder.$namafile); 
			}
			else{
				$template->saveAs($path_folder.$namafile);
			}

			
		 ///////////////////////////////////////////////////////////////////////////////////////	
		
			$kd_file = mt_rand(10000000,99999999);
			$dataSuratKP_master = array(
				'No_surat_KP' =>$nosurat,
				'Kd_file' => $kd_file,
				'File' => $namafile,
			);

			
			
			// print_r($dataSuratKP_detail);
			// print_r($dataSuratKP_master);
		
		// die;
		$insertMaster = $this->query_helper->insert('surat_kp',$dataSuratKP_master);
		if($insertMaster){
			for($i=0; $i < count($nim);$i++){
				$id = mt_rand(10000000,99999999);
				$dataSuratKP_detail[$i] = array(
					'id_surat' => $id,
					'Kd_file' => $dataSuratKP_master['Kd_file'],
					'No_surat_kp' => $nosurat,
					'NIM' =>$nim[$i],
					'Nama_PT' => $namapt,
					'Alamat_PT' => $alamatpt.",".$kotapt,
					'created' => $created,
					'modified'=> $modified
				);
			}
			$insertDetail = $this->db->insert_batch('surat_kp_detail',$dataSuratKP_detail);

			//DB untuk update index surat dan totalsurat

				$count_index_surat = $index_surat + 1;
				$this->db->set('Konfig_value', $count_index_surat);
				$this->db->where('Konfig_key', 'Index_surat');
				$this->db->update('Konfigurasi');

				$count_total_surat = $total_surat + 1;
				$this->db->set('Konfig_value', $count_total_surat);
				$this->db->where('Konfig_key', 'Total_surat');
				$this->db->update('Konfigurasi');

			///////////////////////////////////////////////////

			if($insertDetail){
				$this->session->set_flashdata('sukses', 'Surat Berhasil di Ajukan');
                redirect('Surat_KP');
			}
			else{
				$this->session->set_flashdata('gagal', 'Surat Gagal di Ajukan');
                redirect('Surat_KP');
			}
		}

	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Admin Function

	///////////////////////////////////////////////////////////////////////////////////////////////////////

	public function pemohon_KP(){
        $data['title'] = 'Daftar Pemohon Surat Kerja Praktik';
       
		// Load the list page view
		$data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Surat_KP/index_pemohon_surat_KP',$data);
    }
	
	public function detail(){
        $data = array();
		$id = $this->uri->segment(3);
        if(!empty($id)){
			// Fetch user data
			$data['master_surat'] = $this->query_helper->getRows('surat_kp','Kd_file',array('Kd_file'=>$id));
            $data['detail_surat'] = $this->query_helper->getRows('surat_kp_detail','No_surat_KP',array('No_surat_KP'=>$data['master_surat'][0]['No_surat_KP']));
			// Return data as JSON format
			// echo "<pre>";
			// print_r($data);
            // echo json_encode($data);
        }
    }

    //function untuk datatables ke view
    function jsonData(){
		$url_download = site_url()."Surat_KP/download/$1";
		$url_selesai = site_url()."Surat_KP/update/$1";
        $atts1 = array(
            'class'       => 'btn btn-success btn-sm',
        );

        $atts3 = array(
             'class'       => 'btn btn-danger btn-sm',
            
        );

        $this->load->library('datatables');
		$this->datatables->select(' Distinct( surat_kp.Kd_file) as Id,surat_kp.No_surat_kp as Nomor , surat_kp.File  ,surat_kp_detail.Nama_PT as Perusahaan, surat_kp.Status');
		$this->datatables->from('surat_kp');
		$this->datatables->join('surat_kp_detail','surat_kp_detail.Kd_file = surat_kp.Kd_file');
		$this->db->order_by('surat_kp_detail.modified', 'DESC');
        $this->datatables->add_column('action',array( anchor($url_download,'Unduh',$atts3) ." "." ". anchor($url_selesai,'Selesai',$atts1)), 'Id');
        
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
	}
	
	function download(){
		$id = $this->uri->segment(3);
       
        $nama_file = $this->query_helper->getRows('surat_kp','Kd_file',array('Kd_file'=>$id));
        
        $nama_file_download = $nama_file[0]['File'] ;
		//$no_surat_kp = $nama_file[0]['No_surat_KP'];

		$dataUpdate = array('Status'=>'1');
		$update = $this->query_helper->update('surat_kp','Kd_file',$id,$dataUpdate);
		if($update){
			$this->load->helper('download');
			$data = file_get_contents(FOLDER_SURAT_KP.$nama_file_download); 
			force_download($nama_file_download, $data);
		}

	}

	function update(){
		$id = $this->uri->segment(3);
		// $nama_file = $this->query_helper->getRows('surat_kp','Kd_file',array('Kd_file'=>$id));
		// $no_surat_kp = $nama_file[0]['No_surat_KP'];

		$dataUpdate = array('Status'=>'2');
		$update = $this->query_helper->update('surat_kp','Kd_file',$id,$dataUpdate);
		if($update){
			$this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                redirect('Surat_KP/pemohon_KP');
		}
		else{
			$this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                redirect('Surat_KP/pemohon_KP');
		}
	}

}

/* End of file Surat.php */
/* Location: ./application/controllers/Surat.php */