<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); //Untuk memanggil method constructor yang ada di dalam CI_Controller
        $this->load->library("form_validation");
    }

    public function index()
    {
        $data["title"] = "Aplink User Login";
        $this->load->view("templates/auth_header" $data);
        $this->load->view("auth/login");
        $this->load->view("templates/auth_footer");
    }

    public function registration()
    {
        //Rules
        $this->form_validation->set_rules("name", "Name", "requied|trim");
        $this->form_validation->set_rules("email", "Email", "required|trim|valid_email|is_unique[user.email]", [
            "is_unique" => "This email has already registerd!"
        ]);
        $this->form_validation->set_rules("password1", "Password", "requied|trim|min_length[3]|matches[password2]", [
            "matches" => "Password don't match!",
            "min_length" => "Password too short!"
        ]);
        $this->form_validation->set_rules("password2", "Password", "requied|trim|matches[password1]");

        //Jika form validation gagal tampilkan form registration kembali
        if ( $this->form_validation->run() == false ) {
            $data["title"] = "Aplink User Registration";
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/registration");
            $this->load->view("templates/auth_footer");

        } else {
        
            $data = [
                "name" => htmlspecialchars($this->input->post("name")),
                "email" => htmlspecialchars($this->input->post("email")),
                "image" => "default.jpg",
                "password" => password_hash($this->input->post("password1"), PASSWORD_DEFAULT),
                "role_id" => 2,
                "is_active" => 1,
                "date_created" => time()
            ];

            //Insert ke dalam database
            $this->db->insert("user", $data);

            //Pesan jika berhasil
            $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">Congratulation! your account has been creatd. Please Login</div>');

            //Jika data berhasil di Insert. Kembalikan ke halaman Login
            redirect("auth");

        }

    }

}