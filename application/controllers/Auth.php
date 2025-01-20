<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct(); //Untuk memanggil method constructor yang ada di dalam CI_Controller
        $this->load->library("form_validation");
    }

    //Method Login
    public function index()
    {
        //Rules
        $this->form_validation->set_rules("email", "Email", "trim|required|valid_email");
        $this->form_validation->set_rules("password", "Password", "trim|required");

        //Validasi
        if ( $this->form_validation->run() == false )
        {
            $data["title"] = "Aplink User Login";
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/login");
            $this->load->view("templates/auth_footer");

        } else {
            //Validation sukses
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post("email");
        $password = $this->input->post("password");

        $user = $this->db->get_where("user", ["email" => $email])->row_array();

        if( $user )
        {
            //Ada user
            //Jika usee is_active
            if( $user["is_active"] == 1 ){
                //Cek password
                if( password_verify($password, $user["password"])){
                    $data = [
                        "email" => $user["email"],
                        "role_id" => $user["role_id"]
                    ];
                    $this->session->set_userdata($data);
                    if($user["role_id"] == 1) {
                        redirect("admin");
                    } else {
                        redirect("user");
                    }
                } else {
                    //Jika user password salah
                    $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Wrong password!</div');

                    //Jika user password embali ke halaman login
                    redirect("auth");
                }

            } else {
                //Jika user tidak is_active
                $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">This email has not been activated!</div>');

                //Jika user tidak is_active, maka kembali ke halaman login
                redirect("auth");
            }

        } else {
            //tidak ada user dengan email tersebut
            $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Email is not registerd!</div>');

            //Jika user tidak ada, maka kembali ke halaman login
            redirect("auth");
        }

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
                "name" => htmlspecialchars($this->input->post("name", true)),
                "email" => htmlspecialchars($this->input->post("email", true)),
                "image" => "default.jpg",
                //Password tidak perlu htmlspecialchars, karena kemungkinan akan ada characters special di dalamnya
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

    public function logout()
    {
         //Bersihkan session kemudian kembalikan ke halaman login
         $this->session->unset_userdata("email"); //Untuk menghilangkan email
         $this->session->unset_userdata("role_id"); //Untuk menghilangkan role_id

        $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">You have been logged out!</div>');

        //Jika data berhasil di Insert. Kembalikan ke halaman Login
        redirect("auth");


    }

    public function blocked()
    {
        $this->load->view("auth/blocked");
    }

}