<?php

// include some common date functions
// date functions are some of the only files needed to be included
// as they are not placed in autoloaded classes
include_once "coslib/date.php";

class counter {
  
    /**
     * this module will run at a run level. 
     * This means any page will run this module
     * @param type $level
     */
    function runLevel ($level) {
        
        if ($level == 6 ) {
            cosRB::connect();
            $bean = cosRB::getBean('counter');
            
            $ary = array ();
            $bean->agent = $_SERVER['HTTP_USER_AGENT'];
            $bean->module = moduleloader::$running;
            $bean->uri = $_SERVER['REQUEST_URI'];
            $bean->hits++;
            $bean->hitdate = dateGetDateNow(array ('hms' => true));
            R::store($bean);
        }
    }
    
    /**
     * a method which can be used to attach content to a parent module
     * @param array $options info about the parent module. Not used here
     * @return string $str the string will be used by 'parent' module
     */
    function subModulePostContent ($options) {
        
        $hits = dbQ::setSelectNumRows('counter')->filter('uri =', $_SERVER['REQUEST_URI'])->fetch();
        ++$hits;
        $str = "This page has viewed $hits times. First hit: ";
        $first = self::getFirstHit($_SERVER['REQUEST_URI']);
        
        if (!empty($first)) {
            $since = time::getDateString($first['hitdate']);
            return $str.=$since;
        } 
    }
    
    /**
     * get first hit on a uri
     * @param type $uri
     * @return type
     */
    function getFirstHit($uri) {
        return $row = dbQ::setSelect('counter')->
                filter('uri =', $uri)->
                order('hitdate', 'ASC')->
                fetchSingle();
    }
}
