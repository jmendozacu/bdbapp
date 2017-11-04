<?php
class Webskills_Shippingrestriction_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Shippingrestriction"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("shippingrestriction", array(
                "label" => $this->__("Shippingrestriction"),
                "title" => $this->__("Shippingrestriction")
		   ));

      $this->renderLayout(); 
	  
    }

	public function FrontAction() 
	{
		$collection = Mage::getModel('shippingrestriction/shippingzip')->getCollection();
		$data = array();
		foreach ($collection as $value)
		{
			$data[] = $value->zipcode;
		}
		$address = $_REQUEST['zipcode'];	

		if (!$this->_checkZipcode($address,$data)) {
			echo '<span style="color:#E84100">很抱歉，您目前所在邮编还未开放。</span>';
		}
		else
		{
			echo '<span style="color:#5A9800">恭喜你！您所在邮编可以送货上门！</span>';
		}
		// if (in_array($address,$data))
		// {
		// 	echo '<span style="color:#E84100">Oops!Shipping is not available for your location</span>';
		// }
		// else
		// {
		// 	echo '<span style="color:#5A9800">Congratulations!Shipping is Available for your location</span>';
		// }	
		
	}

	public function _checkZipcode($zipcode,$zipcodes)
    {
        foreach ($zipcodes as $value)
        {
            if (strstr($value,"*"))
            {
                $newvalue = str_replace("*", "", $value);
                $zipcode_space = str_replace(" ", "", $zipcode);
                $zipcode_up = strtoupper($zipcode_space);
                if(strpos($zipcode_up,$newvalue) === 0)
                {
                    return true;
                }
            }
            else
            {
                $zipcode_space = str_replace(" ", "", $zipcode);
                $zipcode_up = strtoupper($zipcode_space);
                if ($zipcode_up == $value) {
                    return true;
                }
            }
        }
        return false;
    }
}
