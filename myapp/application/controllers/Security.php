<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Security extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->load->model('user_model');
        $this->load->library('form_validation');
        $this->load->helper('form');
    }

    // Show login page
    public function index() {
        $data['title'] = 'Sign-In - Homeplace Mechanical';
        $data['js_to_load']=array("https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js");
        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Login/form";

        $email = $this->session->flashdata('emailaddress');

        if(isset($email)){
            $this->user_model->emailaddress = $email;
        }

        $data['currentUser'] = $this->user_model;


        $this->load->view('Layouts/master', $data);
    }
    // Logout from admin page
    public function logout() {

        // Removing session data
        $sess_array = array(
            'username' => ''
        );

        $this->session->unset_userdata('currentUserId', $sess_array);
        $this->session->set_flashdata('message', array("class"=>"danger", "message"=>"Successfully Logout"));

        redirect('/security');
    }

    public function authenticate() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');


        if($this->form_validation->run())
        {
            $email = $this->input->post("email");
            $password = $this->input->post("password");
            $checkuser = $this->user_model->findByAttributes(array("emailAddress"=>$email, "isActive"=>1));

            if(isset($checkuser) && strlen($checkuser->emailAddress) && $checkuser->validatePassword($password)){
                $this->session->set_userdata('currentUserId', $checkuser->pkid);
            } else {
                $this->session->set_flashdata('message', array("class"=>"danger", "message"=>"Unable to authenticate user"));
                $this->session->set_flashdata('emailaddress', $email);

                redirect('/security');
            }
        } else {
            $this->session->set_flashdata('message', array("class"=>"danger", "message"=>validation_errors()));
            redirect('/security');
        }
        redirect('/main');
    }

    public function forgot(){
        $data['title'] = 'Forgot Password- Homeplace Mechanical';
        $data['js_to_load']=array("https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js");
        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Login/forgot";

        $email = $this->session->flashdata('emailaddress');

        if(isset($email)){
            $this->user_model->emailaddress = $email;
        }

        $data['currentUser'] = $this->user_model;


        $this->load->view('Layouts/master', $data);
    }

    public function sendpassword(){

        $this->load->library('form_validation');
        $this->load->library('encrypt');
        $this->load->library('email');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required');

        if($this->form_validation->run())
        {
            $email = $this->input->post("email");
            $this->session->set_flashdata('emailaddress', $email);
            $checkuser = $this->user_model->findByAttributes(array("emailAddress"=>$email, "isActive"=>1));

            if(isset($checkuser) && $checkuser->pkid){
                $this->session->set_flashdata('emailaddress', $email);
                $this->email->from($this->config->item('smtp_user'), 'no-reply');
                $this->email->to($email);

                $this->email->subject('Password Recovery');
                $this->email->message('Contact your system administrator to get a new password.');

                log_message('info', 'Password reset email was sent to '.$email);

                if ($this->email->send()) {
                    $this->session->set_flashdata('message', array("class"=>"info", "message"=>"Successfully send"));
                } else {
                    $this->session->set_flashdata('message', array("class"=>"danger", "message"=>$this->email->print_debugger()));
                }
            } else {
                $this->session->set_flashdata('message', array("class"=>"danger", "message"=>"User not found"));
            }

            redirect('/security/forgot');
        }
    }
}