<?php

namespace Timoffmax\Topbar\Block\Adminhtml\Topbar\Edit;

class GenericButton
{
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context
    ) {
        $this->context = $context;    
    }
    
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }    
    
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['topbar_id' => $this->getId()]);
    }   
    
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }    
    
    public function getId()
    {
        return $this->context->getRequest()->getParam('id');
    }     
}
