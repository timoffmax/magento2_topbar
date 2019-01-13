<?php

namespace Timoffmax\Topbar\Controller\Adminhtml\Index;

use Timoffmax\Topbar\Model\TopbarRepository;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Timoffmax_Topbar::manage';
    
    /**
     * @var \Timoffmax\Topbar\Model\TopbarRepository
     */
    protected $topbarRepository;

    /**
     * Delete constructor.
     * @param \Timoffmax\Topbar\Model\TopbarRepository $topbarRepository
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        TopbarRepository $topbarRepository,
        Context $context
    ) {
        $this->topbarRepository = $topbarRepository;

        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('topbar_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                // delete model
                $this->topbarRepository->deleteById($id);
                // display success message
                $this->messageManager->addSuccessMessage(__('You have deleted the topbar.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['topbar_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can not find an topbar to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
