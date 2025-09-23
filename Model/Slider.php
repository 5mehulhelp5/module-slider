<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model;

use Magento\Framework\Model\AbstractModel;
use Mygento\Slider\Api\Data\SliderInterface;

class Slider extends AbstractModel implements SliderInterface
{
    /** @inheritDoc */
    protected $_eventPrefix = 'mygento_slider_slider';

    public static function getSliderOptions(): array
    {
        return [
            'autoplay' => ['formElement' => 'checkbox', 'notice' => __('Enables Autoplay'), 'default' => false],
            'autoplay_interval' => ['formElement' => 'input', 'notice' => __('Autoplay interval'), 'default' => 4000, 'validation' => ['validate-number' => 1, 'validate-zero-or-greater' => 1]],
            'arrows' => ['formElement' => 'checkbox', 'notice' => __('Prev/Next Arrows'), 'default' => true],
            'dots' => ['formElement' => 'checkbox', 'notice' => __('Show dot indicators'), 'default' => false],
            'per_page' => ['formElement' => 'input', 'notice' => __('# of slides to show'), 'default' => 1, 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'lazyLoad' => ['formElement' => 'checkbox', 'notice' => __('Enables lazy loading'), 'default' => false],
            'infinite' => ['formElement' => 'checkbox', 'notice' => __('Infinite loop'), 'default' => true],
            'avif' => ['formElement' => 'checkbox', 'notice' => __('Enables avif Images'), 'default' => false],
            'webp' => ['formElement' => 'checkbox', 'notice' => __('Enables webp Images'), 'default' => false],
            'jpg' => ['formElement' => 'checkbox', 'notice' => __('Enables jpg Images'), 'default' => false],
            'breakpoint' => ['formElement' => 'input', 'notice' => __('Image/Small Image Breakpoint'), 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1], 'default' => 1024],
            'height' => ['formElement' => 'input', 'notice' => __('Image height'), 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'width' => ['formElement' => 'input', 'notice' => __('Image width'), 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'height_small' => ['formElement' => 'input', 'notice' => __('Small image height'), 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'width_small' => ['formElement' => 'input', 'notice' => __('Small image width'), 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'preload' => ['formElement' => 'checkbox', 'notice' => __('Enables Image Preload'), 'default' => false],
        ];
    }

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
     * Get identity
     */
    public function getIdentity(): string
    {
        return (string) $this->getData(self::IDENTITY);
    }

    /**
     * Set identity
     */
    public function setIdentity(string $identity): self
    {
        return $this->setData(self::IDENTITY, $identity);
    }

    /**
     * Get title
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * Set title
     */
    public function setTitle(?string $title): self
    {
        return $this->setData(self::TITLE, $title);
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
     * Get options for EntityManager
     */
    public function getOptions(): string
    {
        return $this->getData(self::OPTIONS) ?? '[]';
    }

    /**
     * Get options
     */
    public function getOptionsList(): array
    {
        return json_decode($this->getOptions(), true);
    }

    /**
     * Set options
     */
    public function setOptions(array $options): self
    {
        $configs = self::getSliderOptions();
        foreach ($options as $k => $v) {
            $configValue = $configs[$k] ?? null;
            if (is_array($configValue) && ($configValue['formElement'] ?? '') === 'checkbox') {
                $options[$k] = 'true' === $v;
            }
        }

        return $this->setData(self::OPTIONS, json_encode($options));
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel\Slider::class);
    }
}
