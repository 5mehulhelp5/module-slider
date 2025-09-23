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
interface ProductSliderInterface
{
    public const ID = 'id';
    public const IDENTITY = 'identity';
    public const TITLE = 'title';
    public const IS_ACTIVE = 'is_active';
    public const CONDITIONS = 'conditions';
    public const OPTIONS = 'options';
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
     * Get identity
     * @return string
     */
    public function getIdentity(): string;

    /**
     * Set identity
     * @return $this
     */
    public function setIdentity(string $identity): self;

    /**
     * Get title
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Set title
     * @return $this
     */
    public function setTitle(?string $title): self;

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
     * Get conditions
     * @return string
     */
    public function getConditions(): string;

    /**
     * Set conditions
     * @return $this
     */
    public function setConditions(string $conditions): self;

    /**
     * Get options
     * @return array|null
     */
    public function getOptions(): ?array;

    /**
     * Set options
     * @return $this
     */
    public function setOptions(?array $options): self;

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
