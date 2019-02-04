<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Report extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
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

    public function index()
	{
        $data = array();
		$data['title'] = 'Reports - Homeplace Mechanical';
        $data['currentUser'] = $this->getCurrentUser();
        $data['js_to_load']=array("https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"
            , "https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"
            , "https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js"
            , "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js");

        $data['css_to_load']=array("https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.10.18/b-1.5.2/b-colvis-1.5.2/b-html5-1.5.2/b-print-1.5.2/r-2.2.2/datatables.min.css");
        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Report/summary";

        $this->load->view('Layouts/master', $data);
    }

    public function calllog(){
        $data = array();
        $month = $this->input->get("month");
        $type = $this->input->get("type") ? $this->input->get("type") : "full";

        $data['pageTitle'] = "Report Dashboard";
        $data['currentUser'] = $this->getCurrentUser();

        if($type == "nocall"){
            $data['pageTitle'] = $data['pageTitle'] . " - Not Called List";
        } elseif($type == "called") {
            $data['pageTitle'] = $data['pageTitle'] . " - Called List";
        } else {
            $data['pageTitle'] = $data['pageTitle'] . " - Full List";
        }
        $data['pageTitle'] = $data['pageTitle'] . " - " . $month;


        $services = $this->load->model('service_model');

        $data['title'] = 'Reports - Homeplace Mechanical - Customer Call Report';

        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Report/calllog";

        $data["customers"] = $this->service_model->getCallReport($month, $type);

        $this->load->helper('phone');

        $this->load->view('Layouts/master', $data);
    }

    public function nophone(){
        $this->load->model('account_model');

        $data = array();
        $data['title'] = 'Reports - Homeplace Mechanical - Customers with No Phone Number';
        $data['pageTitle'] = "Customers with No Phone Number";
        $data['currentUser'] = $this->getCurrentUser();

        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Report/calllog";
        $data["customers"] = $this->account_model->getNoPhoneFull();

        $this->load->helper('phone');

        $this->load->view('Layouts/master', $data);
    }

    public function noservicecall(){
        $this->load->model('service_model');

        $data = array();
        $data['title'] = 'Reports - Homeplace Mechanical - Customers with No Service Date';
        $data['pageTitle'] = "Customers with No Service Date";
        $data['currentUser'] = $this->getCurrentUser();

        $data['message'] = $this->session->flashdata('message');
        $data['content'] = "Report/calllog";
        $data["customers"] = $this->service_model->getNoServiceDate();

        $this->load->helper('phone');

        $this->load->view('Layouts/master', $data);
    }


    public function monthlist(){

        $json = array("options" => array());

        $start = new DateTime('now');
        $start->modify('first day of this month')->modify('-1 year');

        $end = clone($start);
        $end->modify('+2 years');

        while($start <= $end)
        {
            array_push($json["options"], $start->format('Y-m-d'));
            $start->modify('+1 month');
        }
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function monthlistdata(){
        $json = array();
/*
        $start = new DateTime('now');
        $start->modify('first day of this month')->modify('-1 month');

        $end = clone($start);
        $end->modify('+3 months')->modify('-1 days');

        $this->db->select("COUNT(DISTINCT c1.customerId) AS CALLS , month(s1.dateOfService) ServiceCallMonth , year(s1.dateOfService) ServiceCallYear,
            STR_TO_DATE(CONCAT(month(s1.dateOfService), '/01/', year(s1.dateOfService)), '%m/%d/%Y') as ServiceCallDate, COUNT(s2.customerId) as CALLED");
        $this->db->from("service as s1");
        $this->db->join('customer as c1', "s1.customerId = c1.customerId", "inner");
        $this->db->join("(SELECT DISTINCT customerId FROM service where dateOfService >= '".$end->format('Y-m-d')."' ) as s2", 's2.customerId = c1.customerId', 'left');
        $this->db->where("NULLIF(TRIM(phone), '') is not null", NULL, FALSE);
        $this->db->where('s1.technician', 'Service Call');
        $this->db->where('s1.dateOfService >=', $start->format('Y-m-d'));
        $this->db->where('s1.dateOfService <', $end->format('Y-m-d'));
        $this->db->group_by(array('ServiceCallYear', 'ServiceCallMonth'));
        $this->db->order_by('ServiceCallYear');
        $this->db->order_by('ServiceCallMonth');
        $query = $this->db->get();
        $rows = $query->result_array();
        foreach($rows as &$r) {
            array_push($json, array("date" => $r['ServiceCallDate'], 'totalCall' => (int)$r['CALLS'], 'totalCalled' => (int)$r['CALLED'], 'totalNotCalled' => ((int)$r['CALLS'] - (int)$r['CALLED'])));
        }
*/
        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function customersnophone(){
                $json = array("counts" => 0, "customers" => array());

        $this->db->distinct(TRUE);
        $this->db->select("count(*) as customers");
        $this->db->from("customer as c1");
        $this->db->where("NULLIF(TRIM(phone), '') is null or c1.phone = '6514510001'");
        $query = $this->db->get();
        $rows = $query->result_array();

        $json["counts"] = $rows[0]["customers"];

        $this->db->distinct(TRUE);
        $this->db->select("c1.customerId, c1.firstName, c1.lastName, MAX(s1.dateOfService) AS dateOfService ");
        $this->db->from("customer as c1");
        $this->db->join("service as s1", "c1.customerId = s1.customerId AND s1.technician NOT like '%Service Call%'", "left");
        $this->db->where("NULLIF(TRIM(phone), '') is null or c1.phone = '6514510001'", NULL, FALSE);
        $this->db->group_by(array("c1.customerId", "c1.firstName", "c1.lastName"));
        $this->db->order_by("dateOfService", "desc");
        $this->db->order_by("lastName");
        $this->db->order_by("firstName");
        $this->db->limit(5);


        $query = $this->db->get();
        $json["customers"] = $query->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }

    public function customersnoservice(){
                $json = array("counts" => 0, "customers" => array());
        $start = new DateTime('now');

        /*
        SELECT c1.customerId, c1.firstName, c1.lastName, MAX(s2.dateOfService) AS dateOfService
		FROM customer as c1
			LEFT JOIN service as s1 on c1.customerId = s1.customerId
				AND technician = 'Service Call'
                AND s1.dateOfService >= :dateOfService
        LEFT JOIN service AS s2 on c1.customerId = s2.customerId
				AND s2.technician = 'Service Call'
		WHERE s1.serviceId IS NULL
			and c1.phone != '6514510001'
		GROUP BY c1.customerId, c1.firstName, c1.lastName
		ORDER BY dateOfService, lastName, firstName
        */
        $this->db->select("count(*) as customers")
            ->from("customer as c1")
            ->join("service as s1", "c1.customerId = s1.customerId AND s1.technician = 'Service Call' AND s1.dateOfService >= '". $start->format('Y-m-d')."' ", "left")
            ->join("service AS s2", "c1.customerId = s2.customerId AND s1.technician = 'Service Call'", "left")
            ->where("s1.serviceId IS NULL")
            ->where("c1.phone !=", "6514510001");

        $query = $this->db->get();
        $rows = $query->result_array();

        $json["counts"] = $rows[0]["customers"];

        $this->db->select("c1.customerId, c1.firstName, c1.lastName, MAX(s1.dateOfService) AS dateOfService ");
        $this->db->from("customer as c1");
        $this->db->join("service as s1", "c1.customerId = s1.customerId AND s1.technician = 'Service Call' AND s1.dateOfService >= '". $start->format('Y-m-d')."' ", "left");
        $this->db->join("service AS s2", "c1.customerId = s2.customerId AND s1.technician = 'Service Call'", "left");
        $this->db->where("s1.serviceId IS NULL");
        $this->db->where("c1.phone !=", "6514510001");
        $this->db->group_by(array("c1.customerId", "c1.firstName", "c1.lastName"));
        $this->db->order_by("dateOfService", "desc");
        $this->db->order_by("lastName");
        $this->db->order_by("firstName");
        $this->db->limit(5);

        $query = $this->db->get();
        $json["customers"] = $query->result_array();

        $this->output->set_content_type('application/json')->set_output(json_encode($json));
    }


    public function calldata(){
                $json = array();
        $start = new DateTime('now');
        $start->modify('first day of January')->modify('-1 year');

        $end = clone($start);
        $end->modify('+3 years')->modify("-1 days");

        /*
        SELECT COUNT(DISTINCT c1.customerId) Calls, month(s1.dateOfService) as ServiceCallMonth , year(s1.dateOfService) as ServiceCallYear
		FROM service as s1
			INNER JOIN customer as c1 on s1.customerId = c1.customerId
		WHERE  NULLIF(TRIM(phone), '') is not null
			and s1.technician = 'Service Call'
            AND s1.dateOfService >= :startDate
            AND s1.dateOfService < :endDate
            GROUP BY ServiceCallYear, ServiceCallMonth
		ORDER BY ServiceCallYear, ServiceCallMonth;
        */

        $this->db->select("COUNT(DISTINCT c1.customerId) Calls, month(s1.dateOfService) as ServiceCallMonth , year(s1.dateOfService) as ServiceCallYear");
        $this->db->from("service as s1");
        $this->db->join("customer as c1", "s1.customerId = c1.customerId ", "inner");
        $this->db->where("NULLIF(TRIM(c1.phone), '') is not null", NULL, FALSE);
        $this->db->where("c1.phone !=", '6514510001');
        $this->db->where("s1.technician", 'Service Call');
        $this->db->where("s1.dateOfService >=", $start->format('Y-m-d'));
        $this->db->where("s1.dateOfService <", $end->format('Y-m-d'));
        $this->db->group_by(array("ServiceCallYear", "ServiceCallMonth"));
        $this->db->order_by("ServiceCallYear");
        $this->db->order_by("ServiceCallMonth");
        $query = $this->db->get();
        $rows = $query->result_array();

        foreach($rows as &$r) {
            if (!array_key_exists($r["ServiceCallYear"],$json)){
                $json[$r["ServiceCallYear"]] = array();
            }
            $json[$r["ServiceCallYear"]][$r["ServiceCallMonth"]] = (int)$r["Calls"];
        }
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