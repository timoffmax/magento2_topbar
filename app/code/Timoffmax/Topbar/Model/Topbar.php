<?php

namespace Timoffmax\Topbar\Model;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Timoffmax\Topbar\Api\Data\TopbarInterface;

class Topbar extends AbstractModel implements IdentityInterface, TopbarInterface
{
    /**
     * Cache tag
     */
    protected const CACHE_TAG = 'timoffmax_topbar';

    /**
     * @var string
     */
    protected $_idFieldName = self::ID;

    protected function _construct()
    {
        $this->_init('Timoffmax\Topbar\Model\ResourceModel\Topbar');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID);
    }

    /**
     * @param int $id
     * @return Topbar
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @return array|mixed
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @param string $title
     * @return TopbarInterface
     */
    public function setTitle(string $title): TopbarInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->getData(self::CONTENT);
    }

    /**
     * @param string $content
     * @return TopbarInterface
     */
    public function setContent(string $content): TopbarInterface
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @return string|null
     */
    public function getBgColor(): ?string
    {
        return $this->getData(self::BG_COLOR);
    }

    /**
     * @param string $bgColor
     * @return TopbarInterface
     */
    public function setBgColor(string $bgColor): TopbarInterface
    {
        return $this->setData(self::BG_COLOR, $bgColor);
    }

    /**
     * @return int|null
     */
    public function getPriority(): ?int
    {
        return $this->getData(self::PRIORITY);
    }

    /**
     * @param int $priority
     * @return TopbarInterface
     */
    public function setPriority(int $priority): TopbarInterface
    {
        return $this->setData(self::PRIORITY, $priority);
    }

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool
    {
        return $this->getData(self::IS_ACTIVE);
    }

    /**
     * @param bool $isActive
     * @return TopbarInterface
     */
    public function setIsActive(bool $isActive): TopbarInterface
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return TopbarInterface
     */
    public function setCreatedAt(string $createdAt): TopbarInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @param string $updatedAt
     * @return TopbarInterface
     */
    public function setUpdatedAt(string $updatedAt): TopbarInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
