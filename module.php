<?php

// include some common date functions
// date functions are some of the only files needed to be included
// as they are not placed in autoloaded classes

class counter {
  
    /**
     * this module will run at a run level. 
     * This means any page will run this module
     * @param type $level
     */
    function runLevel ($level) {
        db_rb::connect();
        if ($level == 6 ) {
            
            $bean = db_rb::getBean('counter');
            $bean->agent = $_SERVER['HTTP_USER_AGENT'];
            $bean->module = moduleloader::$running;
            $bean->uri = $_SERVER['REQUEST_URI'];
            
            $bean->hitdate = date::getDateNow(array ('hms' => true));
            R::store($bean);
            
            $hits = db_rb::getBean('counter_hits', 'uri', $_SERVER['REQUEST_URI']);
            if (!$hits->uri) {
                $hits->uri = $_SERVER['REQUEST_URI'];
            }
            $hits->hits++;
            R::store($hits);
            
        }
    }
    
    /**
     * a method which can be used to attach content to a parent module
     * @param array $options info about the parent module. Not used here
     * @return string $str the string will be used by 'parent' module
     */
    public static function subModulePostContent ($options) {
        
        //print_r($_SERVER);
        $row = db_q::select('counter_hits')->filter('uri =', $_SERVER['REQUEST_URI'])->fetchSingle();
        if (!isset($row['hits'])) {
            return;
        }
        $hits = $row['hits']++;
        $str = lang::translate('This page has viewed <span class="notranslate">{HITS}</span> times. ', array ('HITS' => $hits));
        $first = self::getFirstHit($_SERVER['REQUEST_URI']);
        
        if (!empty($first)) {
            $hit = time::getDateString($first['hitdate']);
            $since = lang::translate('First hit: <span class="notranslate">{FIRST_HIT}</span>', 
                    array ('FIRST_HIT' => $hit));
            return $str.=$since;
        } 
    }
    
    /**
     * used when updateing to 2.41
     * MySQL innoDB does not like counting many rows
     * Add a row to table counter_hits with uri and number of hits. 
     */
    public static function updateCounterHits () {
        db_rb::connect();
        $db = new db();
        $rows = $db->selectQuery("SELECT distinct(uri) FROM counter");

        foreach($rows as $row) {
            $hits = db_q::numRows('counter')->filter('uri =', $row['uri'])->fetch();
            $bean = db_rb::getBean('counter_hits', 'uri', $row['uri']);
            $bean->uri = $row['uri'];
            $bean->hits = $hits;
            R::store($bean);
        }
    }

    
    /**
     * get first hit on a uri
     * @param type $uri
     * @return type
     */
    public static function getFirstHit($uri) {
        return $row = db_q::select('counter')->
                filter('uri =', $uri)->
                order('hitdate', 'ASC')->
                fetchSingle();
    }
}
