<?php

class Mywords
{
    function __construct($class = NULL)
    {
        // include path for Zend Framework
        // alter it accordingly if you have put the 'Zend' folder elsewhere
        ini_set('include_path',
        ini_get('include_path') . PATH_SEPARATOR . APPPATH . '/libraries');

        if ($class)
        {
            require_once (string) $class . '.php';
            log_message('debug', "Words Class $class Loaded");
        }
        else
        {
            log_message('debug', "Words Class Initialized");
        }
    }

    function load($class)
    {
        require_once (string) $class . '.php';
        log_message('debug', "Words Class $class Loaded");
    }

    public function to_words($amt='')
    {
        if (!$amt) {
            return '';
        }
        $nw = new Numbers_Words();
        $val = $nw->toWords($amt, WORDS_LANG);
        return $val;
    }
}
