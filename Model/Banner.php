<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model;

use Magento\Framework\Model\AbstractModel;
use Mygento\Slider\Api\Data\BannerInterface;

class Banner extends AbstractModel implements BannerInterface
{
    /** @inheritDoc */
    protected $_eventPrefix = 'mygento_slider_banner';

    /**
     * Get id
     */
    public function getId(): ?int
    {
        return $this->getData(self::ID);
    }

    /**
     * Set id
     * @param int $id
     */
    public function setId($id): self
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * Is active
     */
    public function isActive(): bool
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }

    /**
     * Set active
     */
    public function setActive(bool $isActive): self
    {
        return $this->setData(self::IS_ACTIVE, $isActive);
    }

    /**
     * Get name
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * Set name
     */
    public function setName(?string $name): self
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * Get from date
     */
    public function getFromDate(): ?string
    {
        return $this->getData(self::FROM_DATE);
    }

    /**
     * Set from date
     */
    public function setFromDate(?string $fromDate): self
    {
        return $this->setData(self::FROM_DATE, $fromDate);
    }

    /**
     * Get to date
     */
    public function getToDate(): ?string
    {
        return $this->getData(self::TO_DATE);
    }

    /**
     * Set to date
     */
    public function setToDate(?string $toDate): self
    {
        return $this->setData(self::TO_DATE, $toDate);
    }

    /**
     * Get link
     */
    public function getLink(): ?string
    {
        return $this->getData(self::LINK);
    }

    /**
     * Set link
     */
    public function setLink(?string $link): self
    {
        return $this->setData(self::LINK, $link);
    }

    /**
     * Get image
     */
    public function getImage(): string
    {
        return (string) $this->getData(self::IMAGE);
    }

    /**
     * Set image
     */
    public function setImage(string $image): self
    {
        return $this->setData(self::IMAGE, $image);
    }

    /**
     * Get small image
     */
    public function getSmallImage(): ?string
    {
        return $this->getData(self::SMALL_IMAGE);
    }

    /**
     * Set small image
     */
    public function setSmallImage(?string $smallImage): self
    {
        return $this->setData(self::SMALL_IMAGE, $smallImage);
    }

    /**
     * Get store id
     */
    public function getStoreId(): ?array
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * Set store id
     */
    public function setStoreId(?array $storeId): self
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Banner::class);
    }
}
