<?php
/**
 *
 *
 * @category   Hackathon
 * @package    Hackathon_ResponsiveEmail
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
class Hackathon_ResponsiveEmail_Model_Inliner
{

    /**
     * @param $rawTemplateHtml
     * @param $existingStyles
     * @return string
     */
    public function getHtmlWithInlinedStyles($rawTemplateHtml, $existingStyles)
    {
        $newStyles = $this->_getAdditionalCss();
        $cssToInlineStyles = new TijsVerkoyen_CssToInlineStyles(
            $rawTemplateHtml,
            $existingStyles . "\n" . $newStyles
        );

        $htmlWithInlineCss = $cssToInlineStyles->convert();
        $html = "<style type='text/css'>" . $this->_getAdditionalCssNotInline() . "</style>" . $htmlWithInlineCss;

        return $html;
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