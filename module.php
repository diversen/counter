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
            self::saveExtendedInfo();
            self::saveBasicInfo();
        }
    }
    
    public static function saveBasicInfo() {
        if (strings::strlen($_SERVER['REQUEST_URI'])> 255) {
            return;
        } 
        $hits = db_rb::getBean('counter_hits', 'uri', $_SERVER['REQUEST_URI']);
        if (!$hits->uri) {
            $hits->uri = $_SERVER['REQUEST_URI'];
        }
        $hits->hits++;
        return R::store($hits);
    }
    
    public static function saveExtendedInfo() {
        if (strings::strlen($_SERVER['REQUEST_URI'])> 255) {
            return;
        } 
        $bean = db_rb::getBean('counter', 'uri', $_SERVER['REQUEST_URI']);
        if ($bean->uri) {
            // we only keep one row for every page when not using extended info
            // e.g. small heroku site where we want to be cheap. 5 MB max !
            if (!config::getModuleIni('counter_extended_info')) {
                return;
            }
        }
        
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            $agent = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $agent = 'EMPTY_AGENT';
        }
        
        $bean->agent = $agent;
        $bean->module = moduleloader::$running;
        $bean->uri = $_SERVER['REQUEST_URI'];
        if (!$bean->hitdate) {
            $bean->hitdate = date::getDateNow(array ('hms' => true));
        }
        return R::store($bean);
    }
    
    /**
     * a method which can be used to attach content to a parent module
     * @param array $options info about the parent module. Not used here
     * @return string $str the string will be used by 'parent' module
     */
    public static function subModulePostContent ($options) {
        
        $row = db_q::select('counter_hits')->filter('uri =', $_SERVER['REQUEST_URI'])->fetchSingle();
        
        // first hit
        if (!isset($row['hits'])) {
            $hits = 1;
        } else {
            $hits = $row['hits']++;
        }
        
        $str = lang::translate('This page has been viewed <span class="notranslate">{HITS}</span> times. ', array ('HITS' => $hits));
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
        $row = db_q::select('counter')->
                filter('uri =', $uri)->
                order('hitdate', 'ASC')->
                fetchSingle();
        return $row;
    }
}
