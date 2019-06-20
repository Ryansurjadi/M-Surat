<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notifikasi extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
        // Load user model
        $this->load->model('user_model');
        $this->load->model('query_helper');
        // /$this->output->enable_profiler(TRUE);
    }
    
    public function index(){
        $conditions['returnType'] = '';
       
        // Load the list page view
        $data['title'] = 'Notifikasi';
        $data['colors'] = $this->query_helper->config_color();
        $this->load->view('templates/head', $data);
        $this->load->view('Notifikasi/index_notifikasi');
    }
    
    //function untuk datatables ke view
    function jsonMahasiswa(){

        $this->load->library('datatables');
        $this->datatables->select('NIM,Nama,Angkatan, UUID as UUID');
       // $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'UUID');
        $this->datatables->from('mahasiswa');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    function jsonDosen(){
        
        $this->load->library('datatables');
        $this->datatables->select('Nid,Nama, UUID as UUID');
       // $this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'UUID');
        $this->datatables->from('dosen');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    function jsonDataKelas(){
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

        $conditions = array( 'matakuliah.Status' => '1');

        $this->load->library('datatables');
        $this->datatables->select('Id_kelas as Id ,matakuliah.Nama as Nama,Nid,Kelas,Hari');
        $this->datatables->join('matakuliah', 'matakuliah.Id_matkul = kelas.Id_matkul');

        $this->datatables->where($conditions);
        //$this->datatables->add_column('action',array( anchor("#",'Ubah',$atts1)), 'Id');
        $this->datatables->from('kelas');
        $result = $this->datatables->generate();
        //print json array
        return print_r($result);
    
    }

    //menampilkan search dari select2 
    function getList(){
        $json = [];
        $this->load->database();
		if(!empty($this->input->get('no_induk'))){
			$this->db->like('NIM', $this->input->get('no_induk'));
			$query = $this->db->select('UUID,NIM,Nama')
						->limit(5)
						->get("mahasiswa");
			$json = $query->result();
		}

		echo json_encode($json);
    }

    //function get data checked from datatables
    function getSender(){

        $nomor = $this->input->post('id');
        $title = $this->input->post('judul');
        $message = $this->input->post('isi');
        $created = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        $length = count($nomor);
        for ($i = 0; $i < $length; $i++){
            $id_notif = mt_rand(100000000,999999999);
            $penerima[$i]= array(
                "Id_notifikasi" => $id_notif,
                "UUID"=> $nomor[$i],
                "Id_kelas"=> "-",
                "Judul"=>$title,
                "Isi"=>$message,
                "created" => $created,
                "modified" => $modified
            );
        }
        // echo"<pre>";
        // print_R($penerima);
        // die;
        $this->benchmark->mark('code_start');
        $insert = $this->db->insert_batch('notifikasi', $penerima);
        //print_r ($penerima);
        //echo sizeof($penerima);
        $this->kirimNotifikasi($penerima);
        
        
    }

    function getSenderKelas(){

        
        $nomor = $this->input->post('id');
        $title = $this->input->post('judul');
        $message = $this->input->post('isi');
        $created = date("Y-m-d H:i:s");
        $modified = date("Y-m-d H:i:s");

        
        //echo $nomor."<br>";
        // echo $title."<br>";
        // echo $message."<br>";
        $length = count($nomor);
        for ($i = 0; $i < $length; $i++){
            $id_notif = mt_rand(100000000,999999999);
            $penerima[$i]= array(
                "Id_notifikasi" => $id_notif,
                "UUID"=> "-",
                "Id_kelas"=> $nomor[$i],
                "Judul"=>$title,
                "Isi"=>$message,
                "created" => $created,
                "modified" => $modified
            );
        }
        // echo"<pre>";
        // print_r($penerima);
        // die;
        $this->db->insert_batch('notifikasi', $penerima);
        //print_r ($penerima);
        //echo sizeof($penerima);
        //$this->kirimNotifikasi($penerima);
        
    }

    function CheckStatus(){
        $this->db->select('UUID,Judul,Isi,Keterangan');
        $this->db->from('notifikasi');
        $this->db->where('Status','0');
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
        $count = count($result);
        
        // echo "<pre>";
        // print_r($result);

        for ($i = 0; $i < $count; $i++){
            $penerima[$i]= array(
                "UUID"=> $result[$i]['UUID'],
                "Judul"=>$result[$i]['Judul'],
                "Isi"=>$result[$i]['Isi'],
            );
        }
        // echo "<pre>";
        // print_r($penerima);
        $this->kirimNotifikasi($penerima);
    }

    //function untuk push atau kirim pesan ke user terpilih
    public function kirimNotifikasi($penerima= array()){
        // echo "<pre>";
        // print_r($penerima);
        
        //looping penerima berdasarkan array pengiriman
        //$this->benchmark->mark('code_start');

        //<!-- Progress bar holder -->
        echo'<div style="position: fixed;top: 50%;left: 50%; transform: translate(-50%, -50%); display:flex; align-items:center; justify-content:center;">';
        echo '<div id="progress" style="width:500px;border:1px solid #ccc;"></div><br>';
        //<!-- Progress information -->
        echo '<div id="information" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);display: flex;align-items: center;justify-content: center;width: 100%;"></div>';
        echo '<center><div id="show123" style="visibility:hidden; padding-top: 25%;position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);display: flex;align-items: center;justify-content: center;width: 100%;">
        <a href="'.site_url('Notifikasi').'"class="btn btn-danger">Kembali</a>
        </div></center>';
        echo '</div>';

        
        $count = count($penerima);
        for($i=0; $i < $count;  $i++){
            $percent = intval($i/$count * 100)."%";
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
            // echo "<pre>";
            // print_r("\nJSON sent:\n");
            // echo "<pre>";
            // print_r($fields);

            //die;
            
            $modified =date("Y-m-d H:i:s");;
            $dataset = array(
                    'Status'=>'1',
                    'modified'=>$modified
            );

            $this->db->where('Status', '0');
            $update=$this->db->update('notifikasi', $dataset);
            
            // echo "<pre>";
            // print_r($fields);
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
            // echo "<pre>";
            // print_r($response);
            echo '<script language="javascript">
            document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#00a65a;\">&nbsp;</div>";
            document.getElementById("information").innerHTML=" Sedang Mengirim Notifikasi,Mohon menunggu ....";
            </script>';
            // echo '<script language="javascript">
            // document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#00a65a;\">&nbsp;</div>";
            // document.getElementById("information").innerHTML=" Notifikasi di Kirimkan Ke -'.$i.' user(s).";
            // </script>';
            // This is for the buffer achieve the minimum size in order to flush data
            echo str_repeat(' ',1024*64);
            // Send output to browser immediately
            flush();
            // Sleep one second so we can see the delay
            sleep(1);
        }
        echo '<script language="javascript">
        document.getElementById("information").innerHTML="Proses Mengirim Selesai";
        document.getElementById("show123").style.removeProperty("visibility")';
        echo'</script>';
        // die;
        // $this->benchmark->mark('code_end');

        // echo $this->benchmark->elapsed_time('code_start', 'code_end');
        // $this->output->enable_profiler(TRUE);
        //redirect('Notifikasi');
        
    }
    
    //function untuk kirim Email
    public function kirimEmail() { 

         //$to_email = $this->input->post('email'); 
         $to_email = "ryansurjadi88@gmail.com";
         $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://jkt03.rapidwhm.com',
                'smtp_port' => 465,
                'smtp_user' => EMAIL,
                'smtp_pass' => PASS_EMAIL,
                'mailtype'  => 'html', 
                'charset'   => 'iso-8859-1'
        );

            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");   

         $this->email->from(EMAIL, NAMA_EMAIL); 
         $this->email->to($to_email);
         $this->email->subject('Test Pengiriman Email'); 
         $this->email->message('Coba mengirim Email dengan CodeIgniter.'); 

         //Send mail 
         if($this->email->send()){
                echo "sukses";
         }else {
               echo "tidak sukses";
               show_error();
         } 
    }

    
}
