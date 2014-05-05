<?php

/**
 * @author kiri
 * @date   5/5/14
 */
class Hackathon_ResponsiveEmail_Model_Observer
{
    /**
     * example implementation
     */
    public function adjustRewrite()
    {
        /** @var Mage_Core_Model_Config $config */
        $config = Mage::app()->getConfig();

        $rewrite = array(
            'global/models/core/rewrite/email_template' => array(
                'Aschroder_SMTPPro'  => 'Hackathon_ResponsiveEmail_Model_Email_Template_Aschroder',
                'Ebizmarts_Mandrill' => 'Hackathon_ResponsiveEmail_Model_Email_Template_Ebizmarts',
                'default'            => 'Hackathon_ResponsiveEmail_Model_Email_Template',
            ),
        );

        foreach ($rewrite as $path => $rewriteConfig) {
            foreach ($rewriteConfig as $moduleName => $className) {
                $module       = $config->getNode('modules/' . $moduleName);
                $rewriteClass = null;
                if ($module && 'true' === (string)$module->active) {
                    $rewriteClass = $className;
                } elseif ('default' === $moduleName) {
                    $rewriteClass = $className;
                }

                if (null !== $rewriteClass) {
                    $config->setNode($path, $rewriteClass);
                    break;
                }
            }
        }
    }
}