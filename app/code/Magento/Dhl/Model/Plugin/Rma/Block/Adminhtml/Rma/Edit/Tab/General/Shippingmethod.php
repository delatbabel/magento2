<?php
/**
 * @copyright Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 */
namespace Magento\Dhl\Model\Plugin\Rma\Block\Adminhtml\Rma\Edit\Tab\General;

/**
 * Checkout cart shipping block plugin
 */
class Shippingmethod
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @param \Magento\Framework\Object $subject
     * @param bool $result
     * @return bool
     */
    public function afterCanDisplayCustomValue(\Magento\Framework\Object $subject, $result)
    {
        $carrierCode = $subject->getShipment()->getCarrierCode();
        if (!$carrierCode) {
            return (bool)$result || false;
        }
        return (bool)$result || (bool)$carrierCode == \Magento\Dhl\Model\Carrier::CODE;
    }
}
