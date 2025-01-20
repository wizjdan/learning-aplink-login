<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        //Pengecekan Email
        // if ( !$this->session->userdata("email")){ //Jika tidak ada session
        //     redirect("auth");
        // }
        is_logged_in();
    }

    public function index()
    {
        $data["title"] = "Menu Management";

        //Karena hanya 1 bari yang di dapatkan. Menggunakan row_array()
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        //Karena banyak menggunakan result_array()
        $data["menu"] = $this->db->get("user_menu")->result_array();

        $this->form_validation->set_rules("menu", "Menu", "require");

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

    public function submenu()
    {
        $data["title"] = "Submenu Management";

        //Karena hanya 1 bari yang di dapatkan. Menggunakan row_array()
        $data["user"] = $this->db->get_where("user", ["email" => $this->session->userdata("email")])->row_array();

        //Karena banyak menggunakan result_array()
        $data["menu"] = $this->db->get("user_menu")->result_array();

        //Load Model
        $this->load->model("Menu_model", "menu");

        $data["subMenu"] = $this->menu->getSubMenu();

        $data["menu"] = $this->db->get("user_menu")->result_array();

        $this->form_validation->set_rules("title", "Title", "require");
        $this->form_validation->set_rules("menu_id", "Menu", "require");
        $this->form_validation->set_rules("url", "URL", "require");
        $this->form_validation->set_rules("icon", "Icon", "require");


        if ($this->form_validation->run() == false ){

            $this->load->view("templates/header", $data);
            $this->load->view("templates/sidebar", $data);
            $this->load->view("templates/topbar", $data);
            $this->load->view("menu/submenu", $data);
            $this->load->view("templates/footer");
        } else {
            $data = [
                "title" => $this->input->post("title"),
                "menu_id" => $this->input->post("menu_id"),
                "url" => $this->input->post("url"),
                "icon" => $this->input->post("icon"),
                "is_active" => $this->input->post("is_active")
            ];

            $this->db->insert("user_sub_menu", $data);
            $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">New submenu added!</div');

            //Arahkan ke controller menu
            redirect("menu/submenu");
        }

    }


}