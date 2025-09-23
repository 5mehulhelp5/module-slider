<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Form\Element;

use Magento\Backend\App\Action\Context;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Rule\Model\Condition\Combine;

class ProductConditions extends \Magento\CatalogWidget\Controller\Adminhtml\Product\Widget
{
    public function __construct(
        private Rule $rule,
        private Json $serializer,
        Context $context,
    ) {
        $this->rule = $rule;
        $this->serializer = $serializer;
        parent::__construct($context);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $prefix = $this->getRequest()->getParam('prefix', 'conditions');
        $conditionsEncoded = $this->getRequest()->getParam('conditions');
        $conditions = $this->rule->getConditions();
        $conditions->setData('prefix', $prefix);
        // The rule class expects something to be set in the prefix field before the conditions are loaded
        $conditions->setData($prefix, []);
        $this->rule->loadPost(['conditions' => $this->serializer->unserialize($conditionsEncoded)]);
        $formName = $this->getRequest()->getParam('form_namespace');
        // Combine class recursively sets jsFormObject so we don't need to
        $conditions->setJsFormObject($this->getRequest()->getParam('js_object_name'));
        // The Combine class doesn't need the data attribute on children but we do.
        $this->configureConditionsFormName($conditions, $formName);
        $result = $conditions->asHtmlRecursive();
        $this->getResponse()->setBody($result);
    }

    /**
     * Recursively set form name for data-form-part to be set on all conditions HTML
     *
     * @param Combine $conditions
     * @param string $formName
     * @return void
     */
    private function configureConditionsFormName(Combine $conditions, string $formName): void
    {
        $conditions->setFormName($formName);

        foreach ($conditions->getConditions() as $condition) {
            if ($condition instanceof Combine) {
                $this->configureConditionsFormName($condition, $formName);
            } else {
                $condition->setFormName($formName);
            }
        }
    }
}
