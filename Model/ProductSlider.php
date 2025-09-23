<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Model;

use Magento\Framework\Model\AbstractModel;
use Mygento\Slider\Api\Data\ProductSliderInterface;

class ProductSlider extends AbstractModel implements ProductSliderInterface
{
    /** @inheritDoc */
    protected $_eventPrefix = 'mygento_slider_product_slider';

    public static function getSliderOptions(): array
    {
        return [
            'autoplay' => ['formElement' => 'checkbox', 'notice' => __('Enables Autoplay'), 'default' => false],
            'autoplay_interval' => ['formElement' => 'input', 'notice' => __('Autoplay interval'), 'default' => 4000, 'validation' => ['validate-number' => 1, 'validate-zero-or-greater' => 1]],
            'arrows' => ['formElement' => 'checkbox', 'notice' => __('Prev/Next Arrows'), 'default' => true],
            'dots' => ['formElement' => 'checkbox', 'notice' => __('Show dot indicators'), 'default' => false],
            'per_move' => ['formElement' => 'input', 'notice' => __('# of slides to move'), 'default' => 1, 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'per_page' => ['formElement' => 'input', 'notice' => __('# of slides to show'), 'default' => 4, 'validation' => ['validate-number' => 1, 'validate-greater-than-zero' => 1]],
            'lazyLoad' => ['formElement' => 'checkbox', 'notice' => __('Enables lazy loading'), 'default' => false],
            'infinite' => ['formElement' => 'checkbox', 'notice' => __('Infinite loop'), 'default' => true],
            'avif' => ['formElement' => 'checkbox', 'notice' => __('Enables avif Images'), 'default' => false],
            'webp' => ['formElement' => 'checkbox', 'notice' => __('Enables webp Images'), 'default' => false],
            'jpg' => ['formElement' => 'checkbox', 'notice' => __('Enables jpg Images'), 'default' => false],
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
     * Get conditions
     */
    public function getConditions(): string
    {
        return (string) $this->getData(self::CONDITIONS);
    }

    /**
     * Set conditions
     */
    public function setConditions(string $conditions): self
    {
        return $this->setData(self::CONDITIONS, $conditions);
    }

    /**
     * Get options
     */
    public function getOptions(): ?array
    {
        return json_decode($this->getData(self::OPTIONS) ?? 'null', true);
    }

    /**
     * Set options
     */
    public function setOptions(?array $options): self
    {
        if (null === $options) {
            return $this->setData(self::OPTIONS, json_encode($options));
        }

        $configs = self::getSliderOptions();
        foreach ($options as $g => $e) {
            foreach ($e as $k => $v) {
                $configValue = $configs[$k] ?? null;
                if (is_array($configValue) && ($configValue['formElement'] ?? '') === 'checkbox') {
                    $options[$g][$k] = 'true' === $v;
                }
            }
        }

        return $this->setData(self::OPTIONS, json_encode($options));
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
        $this->_init(ResourceModel\ProductSlider::class);
    }
}
