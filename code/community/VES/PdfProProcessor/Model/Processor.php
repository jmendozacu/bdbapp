<?php
class VES_PdfProProcessor_Model_Processor
{
    protected $_api_keys;
    protected $_domains;
    protected $_printed_limit;
    public static function decode($encoded, $key)
    {
        $code = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($encoded), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $code;
    }
    public static function encode($code, $key)
    {
        $code = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $code, MCRYPT_MODE_CBC, md5(md5($key))));
        return $code;
    }
    public function getApiKeyInfo($apiKey)
    {
        if (!$this->_api_keys) {
            $this->loadInfo();
        }
        if (isset($this->_api_keys[$apiKey])) {
            return $this->_api_keys[$apiKey];
        } else {
            $errMsg = Mage::helper('pdfproprocessor')->__('Your API key "%s" is not valid. Please see this post for more info (<a href="http://www.easypdfinvoice.com/blog/api_key_not_valid/" target="_blank">http://www.easypdfinvoice.com/blog/api_key_not_valid/</a><br />', $apiKey);
            if (Mage::app()->getLayout()->getArea() == 'adminhtml') {
                throw new Mage_Core_Exception($errMsg);
            } else {
                Mage::log($errMsg, Zend_Log::ERR, 'easypdfinvoice.txt');
                throw new Mage_Core_Exception(Mage::helper('pdfproprocessor')->__('Can not generate PDF file. Please contact administrator about this error.'));
            }
        }
    }
    public function printedTime()
    {
    }
    public function checkDomain($domain)
    {
        if (!$this->_domains) {
            $this->loadInfo();
        }
        if (!in_array($domain, $this->_domains)) {
            $domain1 = trim($domain, 'www.');
            if (!in_array($domain1, $this->_domains) && !in_array($domain, array(
                'localhost',
                '127.0.0.1'
            )) && (strpos($domain, 'stage.') === false) && (strpos($domain, 'staging.') === false) && (strpos($domain, 'demo.') === false) && (strpos($domain, 'dev.') === false) && (strpos($domain, 'test.') === false)) {
                if (!in_array('www.' . $domain1, $this->_domains)) {
                    $errMsg = Mage::helper('pdfproprocessor')->__("Your domain (%s) hasn't been registed yet.<br />Feel free to let me know if you have any question or problem at <a href=\"mailto:support@easypdfinvoice.com\">support@easypdfinvoice.com</a>", $domain);
                    if (Mage::app()->getLayout()->getArea() == 'adminhtml') {
                        throw new Mage_Core_Exception($errMsg);
                    } else {
                        Mage::log($errMsg, Zend_Log::ERR, 'easypdfinvoice.txt');
                        throw new Mage_Core_Exception(Mage::helper('pdfproprocessor')->__('Can not generate PDF file. Please contact administrator about this error.'));
                    }
                }
            }
        }
    }
    public function loadInfo()
    {
        $file = Mage::getBaseDir('media') . DS . 'ves_pdfpro' . DS . 'pdf.data';
        if (file_exists($file)) {
            $info = $this->decode(file_get_contents($file), '3767246fa630666a019d0e0a6dbbbcd5');
            $info = json_decode($info, true);
            if (!is_array($info) || !isset($info['date']) || !isset($info['pdf'])) {
                $errMsg = Mage::helper('pdfproprocessor')->__('Please Sync PDF Template first (Easy PDF -> Sync PDF Template).');
                if (Mage::app()->getLayout()->getArea() == 'adminhtml') {
                    throw new Mage_Core_Exception($errMsg);
                } else {
                    Mage::log($errMsg, Zend_Log::ERR, 'easypdfinvoice.txt');
                    throw new Mage_Core_Exception(Mage::helper('pdfproprocessor')->__('Can not generate PDF file. Please contact administrator about this error.'));
                }
            }
            $lastUpdate           = $info['date'];
            $pdfInfo              = json_decode($info['pdf'], true);
            $this->_domains       = $pdfInfo['domains'];
            $this->_api_keys      = $pdfInfo['api_keys'];
            $this->_printed_limit = $pdfInfo['printed_limit'];
        } else {
            $errMsg = Mage::helper('pdfproprocessor')->__('Please Sync PDF Template first (Easy PDF -> Sync PDF Template).');
            if (Mage::app()->getLayout()->getArea() == 'adminhtml') {
                throw new Mage_Core_Exception($errMsg);
            } else {
                Mage::log($errMsg, Zend_Log::ERR, 'easypdfinvoice.txt');
                throw new Mage_Core_Exception(Mage::helper('pdfproprocessor')->__('Can not generate PDF file. Please contact administrator about this error.'));
            }
        }
    }
    public function process($apiKey, $datas, $type)
    {
        $apiKeyInfo = $this->getApiKeyInfo($apiKey);
        $domain     = Mage::getStoreConfig('web/unsecure/base_url');
        $domain     = parse_url($domain);
        $domain     = $domain['host'];
        $this->checkDomain($domain);
        if ($type == 'all')
            return $this->processAllPdf($datas, $apiKey);
        $sources = array();
        $apiKeys = array();
        foreach ($datas as $data) {
            $tmpData = unserialize($data);
            $pdfInfo = $this->getApiKeyInfo($tmpData['key']);
            if (!is_array($pdfInfo) || !isset($pdfInfo[$type . '_template'])) {
                $errMsg = Mage::helper('pdfproprocessor')->__('Your API key is not valid.');
                if (Mage::app()->getLayout()->getArea() == 'adminhtml') {
                    throw new Mage_Core_Exception($errMsg);
                } else {
                    Mage::log($errMsg, Zend_Log::ERR, 'easypdfinvoice.txt');
                    throw new Mage_Core_Exception(Mage::helper('pdfproprocessor')->__('Can not generate PDF file. Please contact administrator about this error.'));
                }
            }
            if (!isset($apiKeys[$tmpData['key']]))
                $apiKeys[$tmpData['key']] = new Varien_Object($pdfInfo);
            $sources[] = $tmpData;
        }
        $className = Mage::getConfig()->getBlockClassName('pdfproprocessor/invoicepro');
        $block     = new $className;
        $block->setData(array(
            'source' => $sources,
            'type' => $type,
            'api_keys' => $apiKeys
        ))->setArea('adminhtml')->setIsSecureMode(true)->setTemplate('ves_pdfproprocessor/template-pro.phtml');
        if (Mage::getStoreConfig('pdfpro/pdfprocessor/minify')) {
            define("DOMPDF_ENABLE_FONTSUBSETTING", true);
        }
        if (Mage::getStoreConfig('pdfpro/pdfprocessor/dpi')) {
            define("DOMPDF_DPI", Mage::getStoreConfig('pdfpro/pdfprocessor/dpi'));
        }
        define("DOMPDF_TEMP_DIR", Mage::getBaseDir('media') . DS . 'ves_pdfpro' . DS . 'tmp');
        $paths[] = BP . DS . 'app' . DS . 'code' . DS . 'community' . DS . 'VES' . DS . 'PdfProProcessor' . DS . 'Pdf';
        $paths[] = BP . DS . 'app' . DS . 'code' . DS . 'community' . DS . 'VES' . DS . 'PdfProProcessor' . DS . 'Pdf' . DS . 'include';
        $appPath = implode(PS, $paths);
        set_include_path($appPath . PS . get_include_path());
        define("DOMPDF_DEFAULT_PAPER_SIZE", Mage::getStoreConfig('pdfpro/pdfprocessor/page_size'));
        include_once 'dompdf_config.inc.php';
        $dompdf = new DOMPDF();
        $html   = preg_replace('/>\s+</', "><", $block->toHtml());
        $dompdf->load_html($html);
        $pageSize    = Mage::getStoreConfig('pdfpro/pdfprocessor/page_size');
        $orientation = Mage::getStoreConfig('pdfpro/pdfprocessor/orientation');
        $dompdf->set_paper($pageSize, $orientation);
        $dompdf->render();
        $api = Mage::getModel('pdfproprocessor/api', Mage::helper('pdfpro')->getDefaultApiKey());
        try {
            $result = $api->updatePrintedTime(sizeof($datas));
        }catch (Exception $e) {
        }
        
        if ($result !== null && !$result['success']) {
            throw new Exception($result['msg']);
        }
        
        $result = array(
            'success' => true,
            'content' => $dompdf->output()
        );
        Mage::dispatchEvent('ves_pdfpro_pdf_prepare', array(
            'type' => $type,
            'result' => $result,
            'communication' => 'local_pdf_processor'
        ));
        return $result;
    }
    public function processAllPdf($datas, $apiKey)
    {
        throw new Mage_Core_Exception(Mage::helper('pdfproprocessor')->__('This feature is in development...'));
    }
}