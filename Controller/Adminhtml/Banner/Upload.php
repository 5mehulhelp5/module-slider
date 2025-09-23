<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Controller\Adminhtml\Banner;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Mygento\Slider\Model\Uploader;

class Upload extends \Magento\Backend\App\Action
{
    public function __construct(
        private Uploader $imageUploader,
        private JsonFactory $resultJsonFactory,
        Action\Context $context,
    ) {
        parent::__construct($context);
    }

    public function execute(): Json
    {
        $imageId = $this->_request->getParam('param_name', 'image');

        try {
            $result = $this->imageUploader->saveFileToTmpDir($imageId);
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }

        return $this->resultJsonFactory->create()->setData($result);
    }
}
