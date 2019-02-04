<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_model extends CI_Model
{
    public $customerId = 0;
	public $firstName = "";
	public $lastName = "";
	public $address = "";
	public $city = "";
	public $state = "";
	public $postalCode = "";
	public $phone = "";
	public $sourceCode = "";
    public $notes = "";

    public function __toString()
    {
        return $this->firstname . " " . $this->lastname;
    }

    public function setPhone($string)
    {
        $this->phone =  reg_replace('/[^0-9]/i', '', $string);
    }


    public function getTotalAccounts()
    {
        $query = $this->db->select("COUNT(*) as num")->get("customer");
        $result = $query->row();
        if(isset($result)) return $result->num;
        return 0;
    }

    public function getAccounts($start, $length, $order, $dir)
    {
        if($order !=null) {
            $this->db->order_by($order, $dir);
        }

        $query = $this->db
            ->from("customer c1")
            ->limit($length, $start)
            ->get();

        return $query->result('Account_Model');
    }

    public function getAccount($customerId)
    {
        $return = $this;
        $query = $this->db->get_where("customer", array('customerId'=>$customerId));

        $results = $query->result('Account_Model');
        if(isset($results) && is_array($results) && count($results)){
            $return = $results[0];
        }

        return $return;
    }

    public function removeAccount($customerId)
    {
        //DELETE FROM customer WHERE customerId = :customerId LIMIT 1;
        $this->db
            ->where('customerID', $customerId)
            ->limit(1, null)
            ->delete('customer');
    }

    public function newAccount($data)
    {
        $this->db->insert('customer', $data);
        return $this->db->insert_id();
    }

    public function updateAccount($id, $data)
    {
        $this->db->where('customerID', $id);
        $this->db->update('customer', $data);
        return $id;
    }

    public function getSourceCodeList($search){
        //SELECT sourceCode FROM customer WHERE sourceCode like :sourceCode GROUP BY sourceCode ORDER BY sourceCode
        $this->db->select("TRIM(sourceCode) as sourceCode");
        $this->db->group_by('sourceCode');
        $this->db->order_by('sourceCode');
        $this->db->where("trim(sourceCode) != '' ");
        $this->db->like('sourceCode', $search);
        $query = $this->db->get("customer");

        return $query->result();
    }

    public function getCityList($search){

        //SELECT city FROM customer WHERE city like :city GROUP BY city ORDER BY city
        $this->db->select("city");
        $this->db->group_by('city');
        $this->db->order_by('city');
        $this->db->like('city', $search);
        $query = $this->db->get("customer");

        return $query->result();
    }

    public function getTechnicianList($search){

        //SELECT GROUP_CONCAT(DISTINCT technician ORDER BY technician SEPARATOR ',') AS technician FROM service WHERE technician like :technician
        $this->db->select("GROUP_CONCAT(DISTINCT technician ORDER BY technician SEPARATOR ',') AS technician");
        $this->db->order_by('technician');
        $this->db->where("trim(technician) != '' ");
        $this->db->like('technician', $search);
        $query = $this->db->get("service");

        return $query->result();
    }

    public function getServiceList($search){
        $this->db->select("GROUP_CONCAT(DISTINCT description ORDER BY description SEPARATOR ',') AS description");
        $this->db->order_by('description');
        $this->db->where("trim(description) != '' ");
        $this->db->like('description', $search);
        $query = $this->db->get("service");

        return $query->result();
    }

    public function getNoPhoneFull($month = null){
        if(!$this->validateDate($month, "m-d-Y"))
        {
            $month = date('Y-m-01');
        }

        $customers = [];
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
            ->from("customer c1")
            ->where("NULLIF(TRIM(phone), '') is null or NULLIF(TRIM(phone), '') = '6514510001' ")
            ->order_by('lastName')
            ->order_by('firstName')
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
