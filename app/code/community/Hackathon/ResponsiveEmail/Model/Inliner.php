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
        $html = $this->_fixTemplateVariables($html);

        return $html;
    }

    /**
     * The inliner appears to be url encoding things inside of <a href="..."> or <img src="...">
     * which is breaking the template variables, for example {{var logo_url}}.  So what I'm doing
     * here is just undoing that.  It's a bit of a hack, but hey, that's what hackathons are for!
     *
     * @param $html
     * @return mixed
     */
    protected function _fixTemplateVariables($html)
    {
        preg_match_all("/" . '(href|src)="(.*?)"' . '/', $html, $matches);
        if (empty($matches) || !isset($matches[2])) {
            return $html;
        }

        foreach ($matches[2] as $match) {
            $html = str_replace($match, urldecode($match), $html);
        }

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