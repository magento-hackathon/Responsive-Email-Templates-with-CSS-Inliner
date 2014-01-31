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
        if ($observer->getObject() instanceof Mage_Core_Model_Email_Template){

            /** @var Mage_Core_Model_Email_Template $template */
            $template = $observer->getObject();

            if (strpos($template->getTemplateText(), 'ink.css') !== false) {
                $template->setTemplateText(Mage::getSingleton('responsive_email/inliner')
                    ->getHtmlWithInlinedStyles(
                        $template->getTemplateText(),
                        $template->getTemplateStyles()
                    )
                );
            }
        }
    }
}