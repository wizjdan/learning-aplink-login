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
        if($this->session->userdata("email")){
            redirect("user");
        }

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
        if($this->session->userdata("email")){
            redirect("user");
        }

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

            $email = $this->input->post("email", true);
        
            $data = [
                "name" => htmlspecialchars($this->input->post("name", true)),
                "email" => htmlspecialchars($email),
                "image" => "default.jpg",
                //Password tidak perlu htmlspecialchars, karena kemungkinan akan ada characters special di dalamnya
                "password" => password_hash($this->input->post("password1"), PASSWORD_DEFAULT),
                "role_id" => 2,
                "is_active" => 0,
                "date_created" => time()
            ];

            //Token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                "email" => $email,
                "token" => $token,
                "date_created" => time()
            ];

            //Insert ke dalam database
            $this->db->insert("user", $data);

            //Insert ke dalam database di tabel token
            $this->db->insert("user_token", $user_token);

            //Kirim email untuk user activation
            $this->_sendEmail($token, "verify");

            //Pesan jika berhasil
            $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">Congratulation! your account has been creatd. Please activate your account</div>');

            //Jika data berhasil di Insert. Kembalikan ke halaman Login
            redirect("auth");
        }
    }

    private function _sendEmail($token, $type)
    {
        // $config['protocol'] = 'sendmail';
        // $config['mailpath'] = '/usr/sbin/sendmail';
        // $config['charset'] = 'iso-8859-1';
        // $config['wordwrap'] = TRUE;

        // $this->email->initialize($config);

        $config = [
            "protocol" => "smtp",
            "smtp_host" => "ssl://smtp.googlemail.com",
            "smmtp_user" => "", //Email Pengirim
            "smtp_pass" => "",  //Password email pengirim
            "smtp_post" => 465,
            "mailtype" => "html",
            "charset" => "utf-8",
            "newline" => "\r\n"
        ];

        $this->load->library("email", $config);

        //Parameter 1 : Email pengirim
        //Parameter 2 : Nama pengirim
        $this->email->from("ppngra1@gmail.com", "ajgblasbla");
        $this->email->to($this->input->post("email"));

        if($type == "verify") {
            $this->email->subject("Account Verification");
            $this->email->messege('Click this link to verify your account : <a href="'. base_url() . 'auth/verify?email' . $this->input->post("email") . '&token=' . urlencode($token) .  '">Activate</a>');
        } else if($type == "forgot") {
            $this->email->subject("Reset Password");
            $this->email->messege('Click this link to reset your password : <a href="'. base_url() . 'auth/resetpassword?email' . $this->input->post("email") . '&token=' . urlencode($token) .  '">Reset Password</a>');
        }

    }

    public function verify()
    {
        $email = $this->input->get("email");
        $token = $this->input->get("token");

        $user = $this->db->get_where("user", ["email" => $email])->row_array();

        if($user){
            $user_token = $this->db->get_where("user_token", ["token" => $token])->row_array();

            if($user_token) {
                //Waktu untuk validasi token
                if(time() - $user_token["date_created"] < (60*60*24)) {

                    $this->db->set("is_active", 1);
                    $this->db->where("email", $email);
                    $this->db->update("user");

                    $this->db->delet("user_token", ["email" => $email]);
                    $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">'. $email .' has been activated! Please Login!</div>');
                    redirect("auth");

                } else {

                    $this->db->delete("user", ["email" => $email]);
                    $this->db->delete("user_token", ["email" => $email]);

                    $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Account activation failed ! Token expired!</div>');
                    redirect("auth");
                }
            } else {
                $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Account activation failed ! Wrong token</div>');
                redirect("auth");
            }

        } else {
            $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Account activation failed ! Wrong Email</div>');
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

     public function forgotPassword()
     {
        $this->form_validation->set_rules("email", "Email", "trim|required|valid_email");

        if($this->form_validation->run() == false ) {
            $data["title"] = "Forgot Password";
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/forgotpassword");
            $this->load->view("templates/auth_footer");
        } else {
            $email = $this->input->post();

            $user = $this->db->get_where("user", ["email" => $email, "is_active" => 1])->row_array();

            if($user){

                $token = base64_encode(random_bytes(32));
                $user_token = [
                "email" => $email,
                "token" => $token,
                "date_created" => time()
            ];

            $this->db->insert("user_token", $user_token);
            $this->_sendEmail($token,"forgot");

            $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">Please check your email to reset your password! </div>');
            redirect("auth/forgotpassword");



            } else {
                $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Email is not registered! or activated! </div>');
                redirect("auth/forgotpassword");
            }

        }

     }

     public function resetPassword()
     {
        $email = $this->input->get("email");
        $token = $this->input->get("token");

        $user = $this->db->get_where("user", ["email" => $email])->row_array();

        if($user){
            $user_token = $this->db->get_where("user_token", ["token" => $token])->row_array();

            if($user_token) {
                $this->session->set_userdata("reset_email", $email);
                $this->changePassword();

            } else {
                $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Reset password failed! Wrong token</div>');
            redirect("auth");
            }
        } else {
            $this->session->set_flashdata("messege", '<div class="alert alert-danger" role="alert">Reset password failed! Wrong email</div>');
            redirect("auth");
        }

     }

     public function changePassword()
     {

        if(!$this->session->userdata("reset_email")){
            redirect("auth");
        }

        $this->form_validation->set_rules("password1", "Password", "trim|required|min_length[3]|matches[password2]");
        $this->form_validation->set_rules("password2", "Repeat Password", "trim|required|min_length[3]|matches[password1]");

        if($this->form_validation->run() == false ){
            $data["title"] = "Change Password";
            $this->load->view("templates/auth_header", $data);
            $this->load->view("auth/changepassword");
            $this->load->view("templates/auth_footer");
        } else {
            $password = password_hash($this->input->post("password1"), PASSWORD_DEFAULT);
            $email = $this->session->userdata("reset_email");

            $this->db->set("password", $password);
            $this->db->where("email", $email);
            $this->db->update("user");

            $this->session->unset_userdata("reset_email");

            $this->session->set_flashdata("messege", '<div class="alert alert-success" role="alert">Password has been change ! Please Login!</div>');
            redirect("auth");
        }
     }

}