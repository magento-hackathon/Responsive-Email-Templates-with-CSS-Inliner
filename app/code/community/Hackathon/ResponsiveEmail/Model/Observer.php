<?php
/**
 *
 *
 * @category   Hackathon
 * @package    Hackathon_ResponsiveEmail
 * @author     Andreas von Studnitz <avs@integer-net.de>
 */
class Hackathon_ResponsiveEmail_Model_Observer
{
    public function coreAbstractLoadAfter(Varien_Event_Observer $observer)
    {
        if ($observer->getObject() instanceof Mage_Core_Model_Email_Template) {

            if (Mage::app()->getRequest()->getControllerName() == 'system_email_template'
                && Mage::app()->getRequest()->getActionName() == 'edit') {
                // Don't inline Styles when loading a template in backend
                return;
            }

            /** @var Mage_Core_Model_Email_Template $template */
            $template = $observer->getObject();

            if ($this->_shouldInlineCss($template)) {
                $template->setTemplateText(Mage::getSingleton('responsive_email/inliner')
                    ->getHtmlWithInlinedStyles(
                        $template->getTemplateText(),
                        $template->getTemplateStyles()
                    )
                );
            }
        }
    }

    /**
     * @param $template Mage_Core_Model_Email_Template
     */
    protected function _shouldInlineCss($template)
    {
        // I left this in place for backwards compatibility, but I think that
        // some sort of trigger in the template styles field might be a little
        // cleaner.
        if (strpos($template->getTemplateText(), 'ink.css') !== false) {
            return true;
        }

        if (strpos($template->getTemplateStyles(), 'external-responsive-css') !== false) {
            return true;
        }

        return false;
    }
}