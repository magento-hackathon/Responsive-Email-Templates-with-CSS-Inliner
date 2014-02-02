<?php

class Hackathon_ResponsiveEmail_Model_Email_Template extends Hackathon_ResponsiveEmail_Model_Email_Template_Abstract
{
    public function getProcessedTemplate(array $variables = array())
    {
        $html = parent::getProcessedTemplate($variables);
        if (! $this->_shouldInlineCss()) {
            return $html;
        }

        $cssToInline = $this->_getAdditionalCss();

        $cssToInlineStyles = new TijsVerkoyen_CssToInlineStyles($html, $cssToInline);
        $htmlWithInlineCss = $cssToInlineStyles->convert();

        $html = "<style type='text/css'>" . $this->_getAdditionalCssNotInline() . "</style>" . $htmlWithInlineCss;

        return $html;
    }

    protected function _shouldInlineCss()
    {
        // I left this in place for backwards compatibility, but I think that
        // some sort of trigger in the template styles field might be a little
        // cleaner.
        if (strpos($this->getTemplateText(), 'ink.css') !== false) {
            return true;
        }

        if (strpos($this->getTemplateStyles(), 'external-responsive-css') !== false) {
            return true;
        }

        return false;
    }

    protected function _getTemplateContents($templatePath)
    {
        $locale = Mage::app()->getLocale()->getLocaleCode();

        $templateText = Mage::app()->getTranslator()->getTemplateFile(
            $templatePath, 'email', $locale
        );

        return $templateText;
    }

    /**
     * @return string
     */
    protected function _getAdditionalCss()
    {
        $css = '';
        $inlineCssFiles = Mage::helper('responsive_email')->getInlineCssFilesArray();
        foreach ($inlineCssFiles as $file) {
            $css .= $this->_getCssFileContent($file) . "\n";
        }

        return $css;
    }

    protected function _getAdditionalCssNotInline()
    {
        $css = '';
        $cssFiles = Mage::helper('responsive_email')->getNotInlineCssFilesArray();
        foreach ($cssFiles as $file) {
            $css .= $this->_getCssFileContent($file) . "\n";
        }

        return $css;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function _getCssFileContent($filename)
    {
        $filename = Mage::getDesign()->getFilename(
            'css' . DS . 'responsive_email' . DS . $filename,
            array(
                '_type' => 'skin',
                '_default' => false,
            )
        );

        return file_get_contents($filename);
    }
}