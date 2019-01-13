<?php

namespace Timoffmax\Topbar\Block\Adminhtml\Topbar\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{     
    public function getButtonData()
    {
        if (!$this->getId()) {
            return [];
        }

        return [
            'label' => __('Delete Topbar'),
            'class' => 'delete',
            'on_click' => 'deleteConfirm( \'' . __(
                'Are you sure you want to do this?'
            ) . '\', \'' . $this->getDeleteUrl() . '\')',
            'sort_order' => 20,
        ];
    }
}
