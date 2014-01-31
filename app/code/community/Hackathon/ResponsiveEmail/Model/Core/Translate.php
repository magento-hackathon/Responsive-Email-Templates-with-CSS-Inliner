<?php

/**
 *
 *
 * @category   Hackathon
 * @package    Hackathon_ResponsiveEmail
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
class Hackathon_ResponsiveEmail_Model_Core_Translate extends Mage_Core_Model_Translate
{


    /**
     * Retrive translated template file
     *
     * @param string $file
     * @param string $type
     * @param string $localeCode
     * @return string
     */
    public function getTemplateFile($file, $type, $localeCode = null)
    {
        if (is_null($localeCode) || preg_match('/[^a-zA-Z_]/', $localeCode)) {
            $localeCode = $this->getLocale();
        }

        if ($type == 'email') {
            // try to get file from alternative path app/locale/xx_XX/template/email_responsive/
            $filePath = $this->_getFilePath($file, 'email_responsive', $localeCode);
            if (!file_exists($filePath)) {
                $filePath = $this->_getFilePath($file, $type, $localeCode);
            }
        } else {
            $filePath = $this->_getFilePath($file, $type, $localeCode);
        }

        $ioAdapter = new Varien_Io_File();
        $ioAdapter->open(array('path' => Mage::getBaseDir('locale')));

        return (string)$ioAdapter->read($filePath);
    }

    /**
     * @param string $file
     * @param string $type
     * @param string $localeCode
     * @return string
     */
    protected function _getFilePath($file, $type, $localeCode)
    {
        $filePath = Mage::getBaseDir('locale') . DS
            . $localeCode . DS . 'template' . DS . $type . DS . $file;

        if (!file_exists($filePath)) { // If no template specified for this locale, use store default
            $filePath = Mage::getBaseDir('locale') . DS
                . Mage::app()->getLocale()->getDefaultLocale()
                . DS . 'template' . DS . $type . DS . $file;
        }

        if (!file_exists($filePath)) { // If no template specified as  store default locale, use en_US
            $filePath = Mage::getBaseDir('locale') . DS
                . Mage_Core_Model_Locale::DEFAULT_LOCALE
                . DS . 'template' . DS . $type . DS . $file;
            return $filePath;
        }
        return $filePath;
    }
}