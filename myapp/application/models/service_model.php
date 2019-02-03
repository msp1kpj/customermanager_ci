<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_model extends MY_Model
{
    public $table = 'service';
    public $primaryKey = 'serviceId';

    public $serviceId = 0;
    public $customerId = 0;
    public $dateOfService = "";
    public $amount = 0;
    public $technician = "";
    public $description = "";
    public $note = "";

    function __construct() {
        parent::__construct();
    }


    public function getServices($customerId, $start, $length, $order, $dir)
    {
        if($order !=null) {
            $this->db->order_by($order, $dir);
        }

        $query = $this->db
            ->from("service s1")
            ->where("customerId", $customerId)
            ->limit($start, $length)
            ->get();

        return $query->result_array();
    }

    public function removeServices($customerId)
    {
        //DELETE FROM service WHERE customerID = :customerId ;
        $this->db
            ->where('customerID', $customerId)
            ->delete('service');
    }

    public function getCallReport($month, $type = "full") {
        $customers = [];

        $start = new DateTime($month);

        if(!$this->validateDate($month, "Y-m-d"))
        {
            $month = date('Y-m-01');
        }

        $start = new DateTime($month);
        $end = clone($start);
        $end->modify("first day of this month");
        $end->modify("+1 months");

        $this->db
            ->distinct(TRUE)
            ->select("c1.customerId
			, c1.firstName
			, c1.lastName
			, c1.address
			, c1.city
			, c1.phone
			, c1.state
			, c1.postalCode")
            ->from("customer as c1")
            ->join("service as s1", "c1.customerId = s1.customerId", "INNER")
            ->join("(SELECT customerId, min(dateOfService) as nextServiceDate FROM service where dateOfService >= '". $end->format('Y-m-d')."' GROUP BY customerId ) as s3", "s3.customerId = c1.customerId", "left")
            ->where("NULLIF(TRIM(c1.phone), '') IS NOT NULL")
            ->where("s1.dateOfService IS NOT NULL")
            ->where("s1.technician", 'Service Call')
            ->where("STR_TO_DATE(CONCAT(year(s1.dateOfService),'-',month(s1.dateOfService), '-01' ), '%Y-%m-%d') = ", $month)
            ->order_by("c1.lastName")
            ->order_by("c1.firstName")
            ->order_by("c1.customerId");

        if($type == "called"){
            $this->db->where("s3.nextServiceDate IS NOT NULL");
        } elseif($type == "nocall"){
            $this->db->where("s3.nextServiceDate IS NULL");
        }

        $results_customers = $this->db->get();

        $sql = "SELECT serviceId, customerId, dateOfService, amount, technician, description, note, rownumb
			FROM (
			SELECT serviceId, customerId, dateOfService, amount, technician, description, note
				, (@rn:=if(@prev = customerId, @rn +1, 1)) as rownumb
				, @prev := customerId
			FROM (
				SELECT serviceId, customerId, dateOfService, amount, technician, description, note
				FROM service
				WHERE customerId = ?
					AND  (( technician = 'Service Call' AND STR_TO_DATE(CONCAT(year(dateOfService),'-',month(dateOfService), '-01' ), '%Y-%m-%d')  = ?)
					OR (technician != 'Service Call' AND dateOfService <= ?))
				ORDER BY customerId, dateOfService desc
				LIMIT 9999999
			) as a
				JOIN (SELECT @prev := NULL, @rn := 0) AS vars
			) as tmpservice
            WHERE rownumb <= 5";


        foreach ($results_customers->result_array() as $key => $customer){
            $query=$this->db->query($sql, array($customer["customerId"], $month, $month));
            $customer["services"] = $query->result_array();
            array_push($customers, $customer);
        }

        $results_customers = null;


        return $customers;
    }


    public function getNoServiceDate($month = null){
        $customers = [];

        $start = new DateTime($month);

        if(!$this->validateDate($month, "Y-m-d"))
        {
            $month = date('Y-m-01');
        }
         $start = new DateTime($month);
         $start->modify("last day of this month");

        /*
        SELECT c1.customerId , c1.firstName , c1.lastName , c1.address , c1.city , c1.phone, c1.state, c1.postalCode , s2.dateOfService , s2.description as ServiceDescription ,
				IFNULL(NULLIF(TRIM(s2.note), ''), s1.note) as ServiceNote , s2.amount , s2.technician, null as nextServiceDate
			FROM customer as c1
				LEFT JOIN service as s1 on c1.customerId = s1.customerId
					AND technician = 'Service Call'
					AND s1.dateOfService >= :dateOfService LEFT JOIN service AS s2 on c1.customerId = s2.customerId
				AND s2.technician != 'Service Call'
			WHERE s1.serviceId IS NULL
				and c1.phone != '6514510001'
			ORDER BY dateOfService, lastName, firstName
         */

        $results_customers = $this->db
            ->distinct(TRUE)
            ->select("c1.customerId
			, c1.firstName
			, c1.lastName
			, c1.address
			, c1.city
			, c1.phone
			, c1.state
			, c1.postalCode")
            ->from("customer as c1")
            ->join("service as s1", "c1.customerId = s1.customerId AND s1.technician = 'Service Call' AND s1.dateOfService >= '". $start->format('Y-m-d')."' ", "left")
            ->join("service AS s2", "c1.customerId = s2.customerId AND s1.technician = 'Service Call'", "left")
            ->where("s1.serviceId IS NULL")
            ->where("c1.phone !=", "6514510001")
            ->group_by(array("c1.customerId", "c1.firstName", "c1.lastName"))
            ->order_by("lastName")
            ->order_by("firstName")
            ->get();

        $sql = "SELECT serviceId, customerId, dateOfService, amount, technician, description, note, rownumb
			FROM (
			SELECT serviceId, customerId, dateOfService, amount, technician, description, note
				, (@rn:=if(@prev = customerId, @rn +1, 1)) as rownumb
				, @prev := customerId
			FROM (
				SELECT serviceId, customerId, dateOfService, amount, technician, description, note
				FROM service
				WHERE customerId = ?
					AND  (( technician = 'Service Call' AND STR_TO_DATE(CONCAT(year(dateOfService),'-',month(dateOfService), '-01' ), '%Y-%m-%d')  = ?)
					OR (technician != 'Service Call' AND dateOfService <= ?))
				ORDER BY customerId, dateOfService desc
				LIMIT 9999999
			) as a
				JOIN (SELECT @prev := NULL, @rn := 0) AS vars
			) as tmpservice
            WHERE rownumb <= 5";


        foreach ($results_customers->result_array() as $key => $customer){
            $query=$this->db->query($sql, array($customer["customerId"], $month, $month));
            $customer["services"] = $query->result_array();
            array_push($customers, $customer);
        }

        $results_customers = null;


        return $customers;
    }

    function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}