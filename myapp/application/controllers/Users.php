<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Users extends CI_Controller
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

        if(!$this->session->has_userdata('currentUserId')){
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(403);
                exit('No direct script access allowed');
            }
            redirect('/security');
        }

        // enable profiler for development
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(true);
        }
    }

    public function index()
    {
        $data = array();
		$data['title'] = 'Reports - Homeplace Mechanical';
        $data['currentUser'] = $this->getCurrentUser();

        $data['pageTitle'] = 'Users';
        $data['js_to_load']=array("https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js");

        $data['css_to_load']=array("https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.css");
        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "User/list";
        $data['users'] = $this->user_model->findAllByAttributes(array("isactive"=>1));

        $this->load->view('Layouts/master', $data);
    }

    public function delete($userId){
        $currentUser = $this->getCurrentUser();
        $user = $this->user_model->getByID($userId);
        $message = "User was removed sucessfully";

        if(!$user->remove()){
            $message = "User was not able to be removed";
        }
        $this->session->set_flashdata('message', array("class"=>"danger", "message"=>$message));
        redirect('/users');
    }

    public function user($userId){
        $this->output->enable_profiler(false);
        $currentUser = $this->getCurrentUser();
        $user = $this->user_model->getByID($userId);

        $_userjson["userId"] = $user->pkid;
        $_userjson["firstName"] = $user->firstName;
        $_userjson["lastName"] = $user->lastName;
        $_userjson["emailAddress"] = $user->emailAddress;
        $this->output->set_content_type('application/json')->set_output(json_encode($_userjson));
    }

    public function save(){
        $userId = (int)$this->input->post("userid");
        $date = new DateTime('now');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('firstName', 'First Name', 'trim|required');
        $this->form_validation->set_rules('lastName', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('emailAddress', 'Email Address', 'trim|required');

        if(!$userId)
        {
            $this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
            $this->form_validation->set_rules('userConfirmPassword', 'Confirm Password', 'trim|required|matches[userPassword]');
        }

        if($this->form_validation->run())
        {
            $user = $this->user_model->getByID($userId);


            $user->firstName = $this->input->post("firstName");
            $user->lastName = $this->input->post("lastName");
            $user->emailAddress = $this->input->post("emailAddress");
            $user->UserName = $this->input->post("emailAddress");
            $user->isActive = 1;
            $user->dateLastModified = date_format($date, 'Y-m-d H:i:s');


            if(!$userId){
                $user->dateCreated = date_format($date, 'Y-m-d H:i:s');
                $newpasshash = $user->newHashPassword($this->input->post("userPassword"));
                $user->Password = $newpasshash->passwordHash;
                $user->passwordSalt = $newpasshash->salt;
            }

            $user->save();

        } else {
            $this->session->set_flashdata('message', array("class"=>"danger", "message"=>validation_errors()));
        }

        redirect('/users');


    }

    public function savepassword(){
        $userId = (int)$this->input->post("userid");
        $this->load->library('form_validation');

        $this->form_validation->set_rules('userPassword', 'Password', 'trim|required');
        $this->form_validation->set_rules('userConfirmPassword', 'Confirm Password', 'trim|required|matches[userPassword]');

        if($this->form_validation->run())
        {
            $user = $this->user_model->getByID($userId);

            // create new pasword hash
            $newpasshash = $user->newHashPassword($this->input->post("userPassword"));
            $user->Password = $newpasshash->passwordHash;
            $user->passwordSalt = $newpasshash->salt;

            $user->save();
        } else {
            $this->session->set_flashdata('message', array("class"=>"danger", "message"=>validation_errors()));
            $customerId = 0;
        }

        redirect('/users');


    }

    private function getCurrentUser(){
        $currentUser = $this->user_model;
        if($this->session->has_userdata('currentUserId')){
            $currentUserId = $this->session->userdata('currentUserId');
            $currentUser  = $this->user_model->getByID($currentUserId);
        }
        return $currentUser;
    }
}