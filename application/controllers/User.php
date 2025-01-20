<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        //Pengecekan Email
        if ( !$this->session->userdata("email")){ //Jika tidak ada session
            redirect("auth");
        }
    }

    public function index()
    {
        $data["title"] = "My Profile";
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();
        
        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar", $data);
        $this->load->view("templates/topbar", $data);
        $this->load->view("user/index", $data);
        $this->load->view("templates/footer");
    }

}