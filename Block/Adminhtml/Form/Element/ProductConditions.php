<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Block\Adminhtml\Form\Element;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;

class ProductConditions extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Mygento_Slider::form/element/conditions.phtml';

    public function __construct(
        private Json $serializer,
        Context $context,
        array $data = [],
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Creates a JSON string containing the configuration for the needed JS components in the mage-init format
     *
     * @return string
     */
    public function getConfigJson(): string
    {
        return $this->serializer->serialize([
            '[data-role=conditions-form-placeholder-' . $this->getData('attribute') . ']' => [
                'Mygento_Slider/js/form/element/conditions-loader' => $this->getConfig(),
            ],
        ]);
    }

    /**
     * Returns an array of arguments to pass to the condition tree UIComponent
     *
     * @return array
     */
    private function getConfig(): array
    {
        $formNamespace = $this->getData('formNamespace');
        $attribute = $this->getData('attribute');
        $jsObjectName = $formNamespace . '_' . $attribute;

        return [
            'formNamespace' => $formNamespace,
            'componentUrl' => $this->getUrl(
                'mygentoslider/form/element_productconditions',
                [
                    'form_namespace' => $formNamespace,
                    'prefix' => $attribute,
                    'js_object_name' => $jsObjectName,
                ],
            ),
            'jsObjectName' => $jsObjectName,
            'childComponentUrl' => $this->getUrl(
                'mygentoslider/form/element_productconditions_child',
                [
                    'form_namespace' => $formNamespace,
                    'prefix' => $attribute,
                    'js_object_name' => $jsObjectName,
                ],
            ),
            'attribute' => $attribute,
        ];
    }
}
