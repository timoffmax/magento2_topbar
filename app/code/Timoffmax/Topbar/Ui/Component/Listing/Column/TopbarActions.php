<?php

namespace Timoffmax\Topbar\Ui\Component\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;

/**
 * Class TopbarActions
 */
class TopbarActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Url path
     */
    const URL_PATH_EDIT = 'timoffmax_topbar/index/edit';
    const URL_PATH_DELETE = 'timoffmax_topbar/index/delete';

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Constructor
     *
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource["data"]["items"])) {
            foreach ($dataSource["data"]["items"] as & $item) {
                $name = $this->getData("name");
                $title = $this->getEscaper()->escapeHtml($item['title']);
                $id = $item["topbar_id"] ?? "X";

                $item[$name] = [
                    'edit' => [
                        'href' => $this->urlBuilder->getUrl(
                            static::URL_PATH_EDIT,
                            [
                                'topbar_id' => $id
                            ]
                        ),
                        'label' => __('Edit')
                    ],
                    'delete' => [
                        'href' => $this->urlBuilder->getUrl(
                            static::URL_PATH_DELETE,
                            [
                                'topbar_id' => $id
                            ]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete %1', $title),
                            'message' => __('Are you sure you want to delete a %1 record?', $title)
                        ],
                        'post' => true
                    ]
                ];
            }
        }

        return $dataSource;
    }

    /**
     * Get instance of escaper
     *
     * @return Escaper
     */
    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }

        return $this->escaper;
    }
}
