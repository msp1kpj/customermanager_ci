<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account_dt extends Account_model implements DatatableModel
{
    public function appendToSelectStr() {
            return array(
                'city_state_zip' => 'concat(c1.state, \'  \', c1.city, \'  \', c1.postalCode)'
            );

    }

    public function fromTableStr() {
        return 'customer c1';
    }



    public function joinArray(){
        return NULL;
    }

    public function whereClauseArray(){
        return NULL;
    }

}