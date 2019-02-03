<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Service extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        $this->load->model("service_model");
        $this->load->model('user_model');

        if(!$this->session->has_userdata('currentUserId')){
            if ($this->input->is_ajax_request()) {
                $this->output->set_status_header(403);
                exit('No direct script access allowed');
            }
            redirect('/security');
        }
    }

    public function save()
    {

        $customerId = (int)$this->input->post("customerId");
        $this->load->library('form_validation');

        $this->form_validation->set_rules('technician', 'Technician', 'trim|required');

        if($this->input->post("technician") != "Service Call"){
           $this->form_validation->set_message('serviceVerify', 'At Least One server field needs to be populated');
           $this->form_validation->set_message('validateDate', 'Must be a valid date');
           $this->form_validation->set_rules('dateOfService', 'Date of Service', 'trim|callback_validateDate[m/d/Y]');
           $this->form_validation->set_rules('description[]', 'Description', 'trim|callback_serviceVerify[description]');
        }

        if($this->form_validation->run())
        {
            $dateOfService = DateTime::createFromFormat("m/d/Y", $this->input->post("dateOfService"));

            if( strtolower(trim($this->input->post("technician"))) == strtolower("Service Call")){
                $service = new $this->service_model;
                $service->serviceId = ((int)$this->input->post("serviceId"));
                $service->note = ($this->input->post("notes"));
                $service->technician = $this->input->post("technician");
                $service->dateOfService = $dateOfService->format("Y-m-d");
                $service->customerId = ($customerId);
                $service->save();
                $service = null;
            } else {
                if(is_array($this->input->post("description[]"))) {
                    foreach($this->input->post("description[]") as $key => $value){
                        if(trim($value) != ''){
                            $service = new $this->service_model;
                            $value = explode(",", $value);
                            $technician = explode(",", $this->input->post("technician"));
                            $service->serviceId = ((int)$this->input->post("serviceId"));
                            $service->customerId = ($customerId);
                            $service->dateOfService = $dateOfService->format("Y-m-d");
                            $service->amount = ((float)$this->input->post("amount")[$key]);
                            $service->technician = implode(", ", $technician );
                            $service->description = implode(", ", $value);
                            $service->note = ($this->input->post("notes"));
                            $service->save();
                            $service = null;
                        }
                    }
                }
            }
        } else {
            $this->session->set_flashdata('message', array("class"=>"danger", "message"=>validation_errors()));
        }
        redirect('/account/get/'.$customerId);
    }


    public function serviceVerify($value, $otherField)
    {
        return ($value != '' || implode("", $this->input->post($otherField)) != '');
    }

    function validateDate($date, $format = 'm/d/Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function remove($serviceId){
        $serviceModel = new $this->service_model;
        $service = $serviceModel->getByID($serviceId);
        $customerId = $service->customerId;
        $serviceModel->serviceId = $service->serviceId;
        $serviceModel->remove();
        $service = null;
        $serviceModel = null;
        redirect('/account/get/'.$customerId);
    }
}
