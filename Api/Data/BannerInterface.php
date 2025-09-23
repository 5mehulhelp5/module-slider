<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Api\Data;

/**
 * @api
 */
interface BannerInterface
{
    public const ID = 'id';
    public const IS_ACTIVE = 'is_active';
    public const NAME = 'name';
    public const FROM_DATE = 'from_date';
    public const TO_DATE = 'to_date';
    public const LINK = 'link';
    public const IMAGE = 'image';
    public const SMALL_IMAGE = 'small_image';
    public const STORE_ID = 'store_id';

    /**
     * Get id
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Set id
     * @param int $id
     * @return $this
     */
    public function setId($id): self;

    /**
     * Is active
     * @return bool
     */
    public function isActive(): bool;

    /**
     * Set active
     * @return $this
     */
    public function setActive(bool $isActive): self;

    /**
     * Get name
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set name
     * @return $this
     */
    public function setName(?string $name): self;

    /**
     * Get from date
     * @return string|null
     */
    public function getFromDate(): ?string;

    /**
     * Set from date
     * @return $this
     */
    public function setFromDate(?string $fromDate): self;

    /**
     * Get to date
     * @return string|null
     */
    public function getToDate(): ?string;

    /**
     * Set to date
     * @return $this
     */
    public function setToDate(?string $toDate): self;

    /**
     * Get link
     * @return string|null
     */
    public function getLink(): ?string;

    /**
     * Set link
     * @return $this
     */
    public function setLink(?string $link): self;

    /**
     * Get image
     * @return string
     */
    public function getImage(): string;

    /**
     * Set image
     * @return $this
     */
    public function setImage(string $image): self;

    /**
     * Get small image
     * @return string|null
     */
    public function getSmallImage(): ?string;

    /**
     * Set small image
     * @return $this
     */
    public function setSmallImage(?string $smallImage): self;

    /**
     * Get store id
     * @return array|null
     */
    public function getStoreId(): ?array;

    /**
     * Set store id
     * @return $this
     */
    public function setStoreId(?array $storeId): self;
}
