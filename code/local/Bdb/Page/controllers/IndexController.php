<?php

class Bdb_Page_IndexController extends Mage_Core_Controller_Front_Action {          
    public function indexAction() {  
        $this->loadLayout();  
    	$this->renderLayout(); 
    }  
    public function goodbyeAction() {  
    	echo 'Goodbye World!';  
	}

	public function paramsAction() {  
	    echo '<dl>';              
	    foreach($this->getRequest()->getParams() as $key=>$value) {  
	        echo '<dt><strong>Param: </strong>'.$key.'</dt>';  
	        echo '</dl><dl><strong>Value: </strong>'.$value.'</dl>';  
	    }  
	    echo '';  
	}  
 
} 

