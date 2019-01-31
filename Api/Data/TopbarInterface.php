<?php

namespace Timoffmax\Topbar\Api\Data;

/**
 * Interface TopbarInterface
 * @api
 */
interface TopbarInterface
{
    public const ID = 'topbar_id';
    public const TITLE = 'title';
    public const CONTENT = 'content';
    public const BG_COLOR = 'bg_color';
    public const PRIORITY = 'priority';
    public const IS_ACTIVE = 'is_active';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param int $id
     * @return TopbarInterface
     */
    public function setId($id);

    /**
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * @param string $title
     * @return TopbarInterface
     */
    public function setTitle(string $title): TopbarInterface;

    /**
     * @return string|null
     */
    public function getContent(): ?string;

    /**
     * @param string $content
     * @return TopbarInterface
     */
    public function setContent(string $content): TopbarInterface;

    /**
     * @return string|null
     */
    public function getBgColor(): ?string;

    /**
     * @param string $bgColor
     * @return TopbarInterface
     */
    public function setBgColor(string $bgColor): TopbarInterface;

    /**
     * @return int|null
     */
    public function getPriority(): ?int;

    /**
     * @param int $priority
     * @return TopbarInterface
     */
    public function setPriority(int $priority): TopbarInterface;

    /**
     * @return bool|null
     */
    public function getIsActive(): ?bool;

    /**
     * @param bool $isActive
     * @return TopbarInterface
     */
    public function setIsActive(bool $isActive): TopbarInterface;

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * @param string $createdAt
     * @return TopbarInterface
     */
    public function setCreatedAt(string $createdAt): TopbarInterface;

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * @param string $updatedAt
     * @return TopbarInterface
     */
    public function setUpdatedAt(string $updatedAt): TopbarInterface;
}
