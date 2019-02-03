<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Main extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('user_model');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        if(!$this->session->has_userdata('currentUserId')){
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(403);
                exit('No direct script access allowed');
            }
            redirect('/security');
        }

        $this->load->model("account_model");
        // enable profiler for development
        if (ENVIRONMENT == 'development') {
            $this->output->enable_profiler(true);
        }
    }

    public function index()
    {
        $data = array();
        $data['currentUser'] = $this->getCurrentUser();
        $data['title'] = 'Customer List - Homeplace Mechanical';
        $data['js_to_load']=array("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"
            , "https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"
            , "https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js");
        $data['css_to_load']=array("https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.css");
        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Main/home";

        $this->load->view('Layouts/master', $data);
    }

    public function accountlist()
    {
        $this->output->enable_profiler(false);
        $this->load->library('Datatable', array('model' => 'account_dt'));

        $this->datatable->setColumnSearchType('lastName', 'both');
        $this->datatable->setColumnSearchType('firstName', 'both');
        $this->datatable->setColumnSearchType('address', 'both');
        $this->datatable->setColumnSearchType('city', 'both');
        $this->datatable->setColumnSearchType('postalCode', 'both');
        $this->datatable->setColumnSearchType('phone', 'both');


        $this->datatable->setPreResultCallback(
			function(&$json) {
				$rows =& $json['data'];
				foreach($rows as &$r) {
					$r['phone'] = preg_replace("/[^0-9]/", "", $r['phone']);
				}
			}
		);

        $json = $this->datatable->datatableJson();

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function account($customerId){
        $this->output->enable_profiler(false);
        $this->load->model('service_model');

        $data = array();

        $account = $this->account_model->getAccount($customerId);
        $services = $this->service_model->getServices($customerId, 5, null, 'dateOfService', 'desc');

        $data["account"] = array();
        $data["account"]["data"] = $account;

        $data["account"]["info"] = $services;

        //Either you can print value or you can send value to database
        echo json_encode($data);
    }

    public function datatable()
    {
        $this->output->enable_profiler(false);
        $this->load->library('Datatable', array('model' => 'account_dt'));

        $json = $this->datatable->datatableJson();

        $this -> output -> set_content_type('application/json') -> set_output(json_encode($json));
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