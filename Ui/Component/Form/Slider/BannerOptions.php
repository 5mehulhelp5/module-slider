<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Ui\Component\Form\Slider;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\FieldFactory;
use Magento\Ui\Component\Form\Fieldset;
use Mygento\Slider\Model\Slider;

class BannerOptions extends Fieldset
{
    public function __construct(
        private FieldFactory $fieldFactory,
        ContextInterface $context,
        array $components = [],
        array $data = [],
    ) {
        $this->fieldFactory = $fieldFactory;

        parent::__construct($context, $components, $data);
    }

    public function getChildComponents(): array
    {
        foreach ($this->getList() as $key => $config) {
            switch ($config['formElement'] ?? 'error') {
                case 'input':
                case 'textarea':
                    $fieldInstance = $this->addInputField($key, $config);
                    break;
                case 'checkbox':
                    $fieldInstance = $this->addCheckboxField($key, $config);
                    break;
                default:
                    $fieldInstance = $this->addErrorField($key, $config);
                    break;
            }
            $this->addComponent($key, $fieldInstance);
        }

        return parent::getChildComponents();
    }

    protected function getList(): array
    {
        return Slider::getSliderOptions();
    }

    private function addErrorField(string $key, array $config): Field
    {
        /** @var Field $field */
        $field = $this->fieldFactory->create();
        $field->setData([
            'config' => [
                'formElement' => 'textarea',
                'dataType' => 'text',
                'label' => $key,
                'default' => 'Field config error: ' . print_r($config, true),
                'visible' => true,
                'disabled' => true,
            ],
            'name' => $key,
        ]);
        $field->prepare();

        return $field;
    }

    private function addInputField(string $key, array $config): Field
    {
        /** @var Field $field */
        $field = $this->fieldFactory->create();
        $field->setData([
            'config' => array_merge([
                'dataType' => 'text',
                'label' => $key,
                'visible' => true,
                'dataScope' => $key,
            ], $config),
            'name' => $key,
        ]);
        $field->prepare();

        return $field;
    }

    private function addCheckboxField(string $key, array $config): Field
    {
        /** @var Field $field */
        $field = $this->fieldFactory->create();
        $field->setData([
            'config' => array_merge([
                'dataType' => 'boolean',
                'label' => $key,
                'visible' => true,
                'default' => 0,
                'prefer' => 'toggle',
                'valueMap' => ['false' => false, 'true' => true],
                'dataScope' => $key,
            ], $config),
            'name' => $key,
        ]);
        $field->prepare();

        return $field;
    }
}
