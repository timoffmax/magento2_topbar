<?php

namespace Timoffmax\Topbar\Controller\Adminhtml\Index;

use Timoffmax\Topbar\Model\TopbarRepository;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 * @package Timoffmax\Topbar\Controller\Adminhtml\Index
 */
class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Timoffmax_Topbar::manage';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    
    /**
     * @var \Timoffmax\Topbar\Model\TopbarRepository
     */
    protected $objectRepository;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Timoffmax\Topbar\Model\TopbarRepository $objectRepository
     */
    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor,
        TopbarRepository $objectRepository
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->objectRepository = $objectRepository;
        
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            if (empty($data['topbar_id'])) {
                $data['topbar_id'] = null;
            }

            /** @var \Timoffmax\Topbar\Model\Topbar $model */
            $model = $this->_objectManager->create('Timoffmax\Topbar\Model\Topbar');

            $id = $this->getRequest()->getParam('topbar_id');

            if ($id) {
                $model = $this->objectRepository->getById($id);
            }

            $model->setData($data);

            try {
                $this->objectRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the thing.'));
                $this->dataPersistor->clear('timoffmax_topbar');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['topbar_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the data.'));
            }

            $this->dataPersistor->set("timoffmax_topbar", $data);

            return $resultRedirect->setPath('*/*/edit', ['topbar_id' => $this->getRequest()->getParam('topbar_id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }    
}
