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
        $useInk = false;

        if (is_null($localeCode) || preg_match('/[^a-zA-Z_]/', $localeCode)) {
            $localeCode = $this->getLocale();
        }

        if ($type == 'email') {
            // try to get file from alternative path app/locale/xx_XX/template/email_responsive/
            $filePath = $this->_getFilePath($file, 'email_responsive', $localeCode);
            if (file_exists($filePath)) {
                $useInk = true;
            } else {
                $filePath = $this->_getFilePath($file, $type, $localeCode);
            }
        } else {
            $filePath = $this->_getFilePath($file, $type, $localeCode);
        }

        $ioAdapter = new Varien_Io_File();
        $ioAdapter->open(array('path' => Mage::getBaseDir('locale')));

        $templateText = (string)$ioAdapter->read($filePath);

        if ($useInk
            && (!(Mage::app()->getRequest()->getControllerName() == 'system_email_template')
            || !(Mage::app()->getRequest()->getActionName() == 'edit'))) {
            // Don't inline styles when loading a template in backend
            $templateText = $this->addInlineStyles($templateText);
        }

        return $templateText;
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

    /**
     * Add additional styles to email content
     *
     * @param string $templateText
     * @return string
     * We will need to implement this again in an "afterLoad" observer of the transactional email template.
     */
    public function addInlineStyles($templateText)
    {
        $subject = '';
        $existingStyles = '';

        if (preg_match('/<!--@styles\s*(.*?)\s*@-->/s', $templateText, $matches)) {
            $existingStyles = $matches[1];
        }

        if (preg_match('/<!--@subject\s*(.*?)\s*@-->/u', $templateText, $matches)) {
            $subject = $matches[1];
        }

        $rawTemplateHtml = $this->_getRawTemplateHtml($templateText);

        $inlinedHtml = Mage::getSingleton('responsive_email/inliner')
            ->getHtmlWithInlinedStyles($rawTemplateHtml, $existingStyles);

        return '<!--@subject ' . $subject . ' @-->' . "\n" . $inlinedHtml;
    }

    /**
     * @param string $templateText
     * @return string
     */
    protected function _getRawTemplateHtml($templateText)
    {
        if (preg_match('/<!--@(.*?)\s*@-->/s', $templateText, $matches)) {
            $templateText = str_replace($matches[0], '', $templateText);
        }

        /**
         * Remove comment lines
         */
        $templateText = preg_replace('#\{\*.*\*\}#suU', '', $templateText);

        return $templateText;
    }
}