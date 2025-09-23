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
interface SliderInterface
{
    public const ID = 'id';
    public const IS_ACTIVE = 'active';
    public const IDENTITY = 'identity';
    public const TITLE = 'title';
    public const FROM_DATE = 'from_date';
    public const TO_DATE = 'to_date';
    public const OPTIONS = 'options';

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
     * Get options json
     * @return string
     */
    public function getOptions(): string;

    /**
     * Get options
     * @return array
     */
    public function getOptionsList(): array;

    /**
     * Set options
     * @return $this
     */
    public function setOptions(array $options): self;
}
