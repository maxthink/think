<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author mljm
 */
class Controller {
    //put your code here
    protected $viewData;
    
    public function __construct() 
    {
        
    }
    
    public function assign($key,$val)
    {
        $this->viewData[] = [$key,$val];
    }
    
    public function display($template='')
    {
        if($template=='')
        {
            $template= APP_PATH.C('template/use').__CLASS__.'/index.'.C('template/file_suffix');
        }
    }
    

}
