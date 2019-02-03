<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter My Text Helpers
 *
 * @package    CodeIgniter
 * @subpackage    Helpers
 * @category    Helpers
 * @author    Hermawan Haryanto
 * @link    not yet approved
 */

/**
 * US Phone Number Formating
 *
 * Format Integer into US Phone Number
 * (xxx) xxx-xxxx
 *
 * @access    public
 * @param    string
 * @return    string
 */    
function phone ($str)
{
    $strPhone = ereg_replace("[^0-9]",'', $str);
    if (strlen($strPhone) != 10)
        return $strPhone;
    
    $strArea = substr($strPhone, 0, 3);
    $strPrefix = substr($strPhone, 3, 3);
    $strNumber = substr($strPhone, 6, 4);
    
    $strPhone = "(".$strArea.") ".$strPrefix."-".$strNumber;
    
    return ($strPhone);
}