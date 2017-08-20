 <?php
class VES_PdfProProcessor_SyncController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        if (!Mage::helper('pdfpro')->getDefaultApiKey()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('pdfpro')->__('You need to set the default API Key first.'));
            $this->_redirect('pdfpro_cp/adminhtml_key/index');
        }
        try {
            $api    = Mage::getModel('pdfproprocessor/api', Mage::helper('pdfpro')->getDefaultApiKey());
            $result = $api->loadInformationFromServer();
            if ($result['success']) {
                $date = Mage::getModel('core/date')->timeStamp();
                $fp   = fopen(Mage::getBaseDir('media') . DS . 'ves_pdfpro' . DS . 'pdf.data', 'w');
                fwrite($fp, VES_PdfProProcessor_Model_Processor::encode(json_encode(array(
                    'date' => $date,
                    'pdf' => $result['content']
                )), '3767246fa630666a019d0e0a6dbbbcd5'));
                fclose($fp);
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('pdfproprocessor')->__('Your PDF Templates have been updated successfully.'));
                $this->_redirect('pdfpro_cp/adminhtml_key/index');
            } else {
                throw new Mage_Core_Exception($result['msg']);
            }
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('pdfpro_cp/adminhtml_key/index');
        }
    }
} 