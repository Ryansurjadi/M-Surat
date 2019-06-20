<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_Keterangan extends CI_Controller {

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
		$data['title'] = 'Surat Keterangan';
		$nim = $this->session->userdata('NIM');
		
		//Fetch data dari surat kp detail untuk cari data surat kp saya
			$conditions1 = array('NIM'=> $nim );
			$data['Surat_Keterangan_saya'] = $this->query_helper->fetchSurat('surat_keterangan_detail',$conditions1);
		/////////////////////////////////

		//Fetch data dari surat kp master
			if(!empty($data['Surat_Keterangan_saya'])){
				$kd_file = $data['Surat_Keterangan_saya'][0]['Kd_file'];
				$conditions2 = array('Kd_file'=> $kd_file );
				$data['Surat_Keterangan_master'] = $this->query_helper->fetchSurat('surat_keterangan',$conditions2);
			}
		////////////////////////////////

		//Fetch data dari surat kp detail
			if(!empty($data['Surat_Keterangan_saya'])){
				$kd_file = $data['Surat_Keterangan_saya'][0]['Kd_file'];
				$conditions3 = array('Kd_file'=> $kd_file );
				$data['Surat_Keterangan_detail'] = $this->query_helper->fetchSurat('surat_keterangan_detail',$conditions3);
			}
		////////////////////////////////

		// echo "<pre>";
		// print_r($data);
		// die;
		$data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Surat_Keterangan/index_surat_Keterangan',$data);
    }
    
    function jsonDataSaya(){
		
        $atts1 = array(
            'class'       => 'btn btn-success btn-sm',
        );

        $atts3 = array(
             'class'       => 'btn btn-danger btn-sm',
            
        );

        $this->load->library('datatables');
		$this->datatables->select('surat_keterangan.No_surat_keterangan as Nomor , surat_keterangan.File , surat_keterangan.Kd_file as Id ,surat_keterangan_detail.Keterangan, surat_keterangan.Status, mahasiswa.Nama as Mahasiswa');
		$this->datatables->from('surat_keterangan');
        $this->datatables->join('surat_keterangan_detail','surat_keterangan_detail.Kd_file = surat_keterangan.Kd_file');
        $this->datatables->join('mahasiswa','surat_keterangan_detail.NIM = mahasiswa.NIM');
		$this->db->order_by('surat_keterangan_detail.modified', 'DESC');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
	}

	public function Form_Keterangan(){
		$data['title'] = 'Form Pengajuan Surat Keterangan';
		$data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Surat_Keterangan/index_form_Keterangan',$data);
    }

	public function generateSurat(){
        $nim= $this->session->userdata('NIM');
        $nama1 = $this->session->userdata('Nama');
        $nama = ucwords($nama1);
        if($this->session->userdata('Id_prodi')=='TI'){
            $prodi = 'Teknik Informatika';
        }
        else{
            $prodi = 'Sistem Informasi';
        }
        $keterangan = $this->input->post('keterangan');
        $created = date("Y-m-d H:i:s");
		$modified = date("Y-m-d H:i:s");
		
		$bulan = $this->query_helper->BulanSurat(date('m'));
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


		//Generate doc dengan PHP word
		 $this->load->library("Phpword");
        // surat keterangan mahasiswa aktif
        if($keterangan == "Mahasiswa Aktif"){
			// DB untuk ambil file template surat
			$this->db->select('*');
			$this->db->from('dokumen');
			$this->db->where('judul','Surat Keterangan Mahasiswa Aktif');
			$query1 = $this->db->get();
			$result1 = $query1->result_array();
			$nama_template = $result1[0]['File'];
        //generate word//////////////////////////////////
            echo "<pre>";
            //$bulan = $this->query_helper->BulanSurat(date('m'));
            //$nosurat = '364-D/836/FTI-UNTAR/'.$bulan.'/'.date('Y');
			$template = new \PhpOffice\PhpWord\TemplateProcessor(FOLDER_DOKUMEN.$nama_template);

			$template->setValue('nosurat',$nosurat);
			$template->setValue('nama',$nama);
			$template->setValue('nim',$nim);
			$template->setValue('prodi',$prodi);
			$template->setValue('tanggal',date('d F Y'));
            //var_dump($data);
	         
	        $namafile = 'Surat_Keterangan_Mahasiswa_Aktif-'.$nim.'.docx' ;
	    
			$path_folder = FCPATH.'/uploads/dokumen/GenerateSuratMahasiswa/'.$nim.'/'; 
			if (!file_exists($path_folder)) {
					mkdir($path_folder, 0777,TRUE);
					$template->saveAs($path_folder.$namafile); 
			}
			else{
				$template->saveAs($path_folder.$namafile);
			}
		}
		elseif($keterangan == "Skripsi"){
			// DB untuk ambil file template surat
			$this->db->select('*');
			$this->db->from('dokumen');
			$this->db->where('judul','Surat Keterangan Skripsi');
			$query1 = $this->db->get();
			$result1 = $query1->result_array();
			$nama_template = $result1[0]['File'];
        //generate word//////////////////////////////////
            echo "<pre>";
            //$bulan = $this->query_helper->BulanSurat(date('m'));
            //$nosurat = '364-D/836/FTI-UNTAR/'.$bulan.'/'.date('Y');
			$template = new \PhpOffice\PhpWord\TemplateProcessor(FOLDER_DOKUMEN.$nama_template);

			$template->setValue('nosurat',$nosurat);
			$template->setValue('nama',$nama);
			$template->setValue('nim',$nim);
			$template->setValue('prodi',$prodi);
			$template->setValue('keterangan','Skripsi');
			$template->setValue('tanggal',date('d F Y'));
            //var_dump($data);
	         
	        $namafile = 'Surat_Keterangan_Skripsi-'.$nim.'.docx' ;
	    
			$path_folder = FCPATH.'/uploads/dokumen/GenerateSuratMahasiswa/'.$nim.'/'; 
			if (!file_exists($path_folder)) {
					mkdir($path_folder, 0777,TRUE);
					$template->saveAs($path_folder.$namafile); 
			}
			else{
				$template->saveAs($path_folder.$namafile);
			}
		}
		elseif($keterangan == "Survey"){
			// DB untuk ambil file template surat
			$this->db->select('*');
			$this->db->from('dokumen');
			$this->db->where('judul','Surat Keterangan Survey');
			$query1 = $this->db->get();
			$result1 = $query1->result_array();
			$nama_template = $result1[0]['File'];
        //generate word//////////////////////////////////
            echo "<pre>";
            //$bulan = $this->query_helper->BulanSurat(date('m'));
            //$nosurat = '364-D/836/FTI-UNTAR/'.$bulan.'/'.date('Y');
			$template = new \PhpOffice\PhpWord\TemplateProcessor(FOLDER_DOKUMEN.$nama_template);

			$template->setValue('nosurat',$nosurat);
			$template->setValue('nama',$nama);
			$template->setValue('nim',$nim);
			$template->setValue('prodi',$prodi);
			$template->setValue('keterangan','Survey');
			$template->setValue('tanggal',date('d F Y'));
            //var_dump($data);
	         
	        $namafile = 'Surat_Keterangan_Survey-'.$nim.'.docx' ;
	    
			$path_folder = FCPATH.'/uploads/dokumen/GenerateSuratMahasiswa/'.$nim.'/'; 
			if (!file_exists($path_folder)) {
					mkdir($path_folder, 0777,TRUE);
					$template->saveAs($path_folder.$namafile); 
			}
			else{
				$template->saveAs($path_folder.$namafile);
			}
		}
			
		 ///////////////////////////////////////////////////////////////////////////////////////	
            $kd_file = mt_rand(10000000,99999999);
			$dataSurat_master = array(
				'No_surat_Keterangan' =>$nosurat,
				'Kd_file' => $kd_file,
				'File' => $nim.'/'.$namafile,
			);
            
		$insertMaster = $this->query_helper->insert('surat_keterangan',$dataSurat_master);
		if($insertMaster){
            $id = mt_rand(10000000,99999999);
				$dataSurat_detail = array(
                    'id_surat' => $id,
                    'Kd_file' => $dataSurat_master['Kd_file'],
					'No_surat_keterangan' => $nosurat,
					'NIM' =>$nim,
					'Keterangan' => $keterangan
				);
			$insertDetail = $this->query_helper->insert('surat_keterangan_detail',$dataSurat_detail);

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
                redirect('Surat_Keterangan');
			}
			else{
				$this->session->set_flashdata('gagal', 'Surat Gagal di Ajukan');
                redirect('Surat_Keterangan');
			}
		}

	}

	////////////////////////////////////////////////////////////////////////////////////////////////////////

	//Admin Function

	///////////////////////////////////////////////////////////////////////////////////////////////////////

	public function pemohon_Keterangan(){
        $data['title'] = 'Daftar Pemohon Surat Keterangan';
       
		// Load the list page view
		$data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Surat_Keterangan/index_pemohon_surat_Keterangan',$data);
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
		$url_download = site_url()."Surat_Keterangan/download/$1";
		$url_selesai = site_url()."Surat_Keterangan/update/$1";
        $atts1 = array(
            'class'       => 'btn btn-success btn-sm',
        );

        $atts3 = array(
             'class'       => 'btn btn-danger btn-sm',
            
        );

        $this->load->library('datatables');
		$this->datatables->select('surat_keterangan.No_surat_keterangan as Nomor , surat_keterangan.File , surat_keterangan.Kd_file as Id ,surat_keterangan_detail.Keterangan, surat_keterangan.Status, mahasiswa.Nama as Mahasiswa');
		$this->datatables->from('surat_keterangan');
        $this->datatables->join('surat_keterangan_detail','surat_keterangan_detail.Kd_file = surat_keterangan.Kd_file');
        $this->datatables->join('mahasiswa','surat_keterangan_detail.NIM = mahasiswa.NIM');
		$this->db->order_by('surat_keterangan_detail.modified', 'DESC');
        $this->datatables->add_column('action',array( anchor($url_download,'Unduh',$atts3) ." "." ". anchor($url_selesai,'Selesai',$atts1)), 'Id');
        
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
	}
	
	function download(){
		$id = $this->uri->segment(3);
       
        $nama_file = $this->query_helper->getRows('surat_keterangan','Kd_file',array('Kd_file'=>$id));
        
        $nama_file_download = $nama_file[0]['File'] ;
		//$no_surat_keterangan = $nama_file[0]['No_surat_keterangan'];

		$dataUpdate = array('Status'=>'1');
		$update = $this->query_helper->update('surat_keterangan','Kd_file',$id,$dataUpdate);
		if($update){
			$this->load->helper('download');
			$data = file_get_contents(FOLDER_SURAT_KETERANGAN.$nama_file_download); 
			force_download($nama_file_download, $data);
		}

	}

	function update(){
		$id = $this->uri->segment(3);
		//$nama_file = $this->query_helper->getRows('surat_keterangan','Kd_file',array('Kd_file'=>$id));
		//$no_surat_keterangan = $nama_file[0]['No_surat_KP'];

		$dataUpdate = array('Status'=>'2');
		$update = $this->query_helper->update('surat_kp','Kd_file',$id,$dataUpdate);
		if($update){
			$this->session->set_flashdata('sukses', 'Data Berhasil di Perbaharui');
                redirect('Surat_Keterangan/pemohon_Keterangan');
		}
		else{
			$this->session->set_flashdata('gagal', 'Data Gagal di Perbaharui');
                redirect('Surat_Keterangan/pemohon_Keterangan');
		}
	}

}

/* End of file Surat.php */
/* Location: ./application/controllers/Surat.php */