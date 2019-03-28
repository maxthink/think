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
            $template= VIEW_PATH.C('Template/use').DIRECTORY_SEPARATOR.substr(get_called_class(), strrpos(get_called_class(),'\\')+1 ).DIRECTORY_SEPARATOR.'index.'.C('Template/file_suffix');
        }else
        {
            //$template= APP_PATH.C('Template/use').__CLASS__.'/index.'.C('Template/file_suffix');
    	    $template= VIEW_PATH.C('Template/use').get_called_class().DIRECTORY_SEPARATOR.__METHOD__.DIRECTORY_SEPARATOR.C('Template/file_suffix');
    	    $template= VIEW_PATH.C('Template/use').DIRECTORY_SEPARATOR.basename( get_called_class() ).DIRECTORY_SEPARATOR.__METHOD__.'.'.DIRECTORY_SEPARATOR.C('Template/file_suffix');
        }
        require $template;
    }
    

}
