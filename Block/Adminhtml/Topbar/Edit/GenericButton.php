<?php

namespace Timoffmax\Topbar\Block\Adminhtml\Topbar\Edit;

use Magento\Backend\Block\Widget\Context;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * GenericButton constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;    
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['topbar_id' => $this->getId()]);
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->context->getRequest()->getParam('id');
    }     
}
