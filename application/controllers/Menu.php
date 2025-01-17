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

        $this->form_validation->set_rules("menu", "Menu", "require")

        if( $this->form_validation->run() == false ) {

            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("menu/index", $data);
            $this->load->view("templates/footer");
        } else {
            //Jika berhasil akan menambahkan data baru
            $this->db->insert("user_menu", ["menu" => $this->input->post("menu")]);

            $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">New mwnu added!</div');

            //Arahkan ke controller menu
            redirect("menu");
        }
        
    }


}