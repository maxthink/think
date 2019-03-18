<?php
/**
 * 核心控制器
 *
 * @author maxthink
 */

namespace Think\core;

class Controller {
    //put your code here
    protected $viewData;
    
    public function __construct() 
    {
        
    }
    
    public function index()
    {
        $this->display();
    }
    
    public function setData($key,$val)
    {
        $this->viewData[] = [$key,$val];
    }
    
    public function display($template='')
    {
        if($template=='')
        {
            $template= APP_PATH.APP_MODULE.'/view/'.C('Template/use').'/'.basename( get_called_class() ).'/index.'.C('Template/file_suffix');
        }else
        {
            //$template= APP_PATH.C('Template/use').__CLASS__.'/index.'.C('Template/file_suffix');
	    $template= APP_PATH.APP_MODULE.'/'.C('Template/use').get_called_class().'/'.__METHOD__.'/'.C('Template/file_suffix');
	    $template= APP_PATH.APP_MODULE.'/view/'.C('Template/use').'/'.basename( get_called_class() ).'/'.__METHOD__.'./'.C('Template/file_suffix');
        }
        require $template;
    }
    

}
