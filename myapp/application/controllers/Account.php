<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Account extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->load->model("account_model");
        $this->load->model('user_model');

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

    public function get($customerId)
    {
        $this->load->model('service_model');

        $account = $this->session->flashdata('customer');
        if(!isset($account)){
            $account = $this->account_model->getAccount($customerId);
        } else {
            $account = unserialize($account);
        }

        $data['title'] = 'Customer - Homeplace Mechanical';
        $data['currentUser'] = $this->getCurrentUser();
        $data['content'] = "Account/edit";
        $data['js_to_load']=array("https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js",
            "https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js",
            "https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.js",
            "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js",
            "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js");
        $data['css_to_load']=array("https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.css",
            "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css");

        $data["account"] = $account;
        $data["services"] = $this->service_model->getServices($customerId, null, null, 'dateOfService', 'desc');
        $data['message'] = $this->session->flashdata('message');

        $this->load->view('Layouts/master', $data);
    }

    public function post()
    {
        $customerId = (int)$this->input->post('customerId');

        $this->load->library('form_validation');

        $this->form_validation->set_rules('lastName', 'Last Name', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('postalCode', 'Postal Code', 'required');

        if($this->form_validation->run())
        {
            $data = array(
                "lastName"=>$this->input->post('lastName'),
                "firstName"=>$this->input->post('firstName'),
                "phone"=>$this->input->post('phone'),
                "sourceCode"=>$this->input->post('sourceCode'),
                "address"=>$this->input->post('address'),
                "city"=>$this->input->post('city'),
                "state"=>$this->input->post('state'),
                "postalCode"=>$this->input->post('postalCode'),
                "notes"=>$this->input->post('notes')
            );

            $customerId = $this->account_model->updateAccount($customerId, $data);

            $this->session->set_flashdata('message', array("class"=>"success", "message"=>'Customer ' . $customerId . ' information has been updated.'));
        } else {
            $this->session->set_flashdata('message', array("class"=>"danger", "message"=>validation_errors()));
            $customerId = 0;
        }

        redirect('/account/get/'.$customerId);
    }

    public function put()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('lastName', 'Last Name', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('state', 'State', 'required');
        $this->form_validation->set_rules('postalCode', 'Postal Code', 'required');

        if($this->form_validation->run())
        {
            $data = array(
                "lastName"=>$this->input->post('lastName'),
                "firstName"=>$this->input->post('firstName'),
                "phone"=>$this->input->post('phone'),
                "sourceCode"=>$this->input->post('sourceCode'),
                "address"=>$this->input->post('address'),
                "city"=>$this->input->post('city'),
                "state"=>$this->input->post('state'),
                "postalCode"=>$this->input->post('postalCode')
            );

            $customerId = $this->account_model->newAccount($data);

            if(isset($customerId) && $customerId > 0){
                $this->session->set_flashdata('message', array("class"=>"success", "message"=>'Customer ' . $customerId . ' information has been added.'));
            } else {
                $customerId = 0;
                $this->session->set_flashdata('message', array("class"=>"danger", "message"=>'Customer information has NOT been added.'));
            }
        } else {
            $customerId = 0;
            $this->account_model->customerId = $customerId;
            $this->account_model->lastName = $this->input->post('lastName');
            $this->account_model->firstName = $this->input->post('firstName');
            $this->account_model->phone = $this->input->post('phone');
            $this->account_model->sourceCode = $this->input->post('sourceCode');
            $this->account_model->address = $this->input->post('address');
            $this->account_model->city = $this->input->post('city');
            $this->account_model->state = $this->input->post('state');
            $this->account_model->postalCode = $this->input->post('postalCode');

            $this->session->set_flashdata('customer', serialize($this->account_model));

            $this->session->set_flashdata('message', array("class"=>"danger", "message"=>validation_errors()));
        }
        redirect('/account/get/' . $customerId);
    }

    public function delete($customerId)
    {
        $this->load->model('service_model');
        $this->service_model->removeServices($customerId);
        $this->account_model->removeAccount($customerId);

        $this->session->set_flashdata('message', array("class"=>"success", "message"=>'Customer ' . $customerId . ' and their service data has been deleted from the system.'));
        redirect('/main');
    }

    public function getSourceCodeList() {
        $json = array("options" => array());

        $search = $this->input->get('q');

        $query = $this->account_model->getSourceCodeList($search);

        foreach($query as &$row){
            array_push($json["options"], $row->sourceCode);
        }

        unset($row);

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function getCityList(){
        $json = array("options" => array());

        $search = $this->input->get('q');

        $query = $this->account_model->getCityList($search);

        foreach($query as &$row){
            array_push($json["options"], $row->city);
        }

        unset($row);

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function getTechnicianList(){
        $json = array("options" => array());

        $search = $this->input->get('q');

        $query = $this->account_model->getTechnicianList($search);

        foreach($query as &$row){
            $json["options"] = array_merge($json["options"], array_map('trim', explode(',', $row->technician)));
        }

        sort($json["options"], SORT_NATURAL | SORT_FLAG_CASE);
        $json["options"] = array_values(array_unique($json["options"], SORT_NATURAL | SORT_FLAG_CASE));
        unset($row);



        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }
    public function getServiceList(){
        $json = array("options" => array());

        $search = $this->input->get('q');

        $query = $this->account_model->getServiceList($search);

        foreach($query as &$row){
            $json["options"] = array_merge($json["options"], array_map('trim', explode(',', $row->description)));
        }

        sort($json["options"], SORT_NATURAL | SORT_FLAG_CASE);
        $json["options"] = array_values(array_unique($json["options"], SORT_NATURAL | SORT_FLAG_CASE));
        unset($row);



        $this->output->set_content_type('application/json')->set_output(json_encode($json));
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