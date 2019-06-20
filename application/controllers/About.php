<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        if (!$this->session->userdata['logged'])
        {
        	redirect('Auth'); //redirect to login page
        } 
        
    }
    
    public function index(){
        $this->load->view('templates/head');
        $this->load->view('About/index_about');
    }
    
}
