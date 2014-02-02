<?php

class Hackathon_ResponsiveEmail_Block_Order_Items extends Mage_Core_Block_Template
{
    /**
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        return $this->getData('order');
    }
}