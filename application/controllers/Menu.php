<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller 
{

    public function index()
    {
        $data["title"] = "Menu Management";

        //Karena hanya 1 bari yang di dapatkan. Menggunakan row_array()
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        //Karena banyak menggunakan result_array()
        $data["menu"] = $this->db->get("user_menu")->result_array();
        
        $this->load->view("templates/header", $data);
        $this->load->view("templates/sidebar", $data);
        $this->load->view("templates/topbar", $data);
        $this->load->view("menu/index", $data);
        $this->load->view("templates/footer");
    }
    

}