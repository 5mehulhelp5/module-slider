<?php

/**
 * @author Mygento Team
 * @copyright 2026 Mygento (https://www.mygento.com)
 * @package Mygento_Slider
 */

namespace Mygento\Slider\Block;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\Image;
use Magento\Catalog\Block\Product\ImageFactory;
use Magento\Catalog\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\CatalogInventory\Helper\Stock;
use Magento\CatalogWidget\Block\Product\ProductsList;
use Magento\CatalogWidget\Model\Rule;
use Magento\Framework\App\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Rule\Model\Condition\Combine;
use Magento\Rule\Model\Condition\Sql\Builder;
use Magento\Widget\Helper\Conditions;
use Mygento\Slider\Api\Data\ProductSliderInterface;
use Mygento\Slider\Model\Catalog\Sorting;
use Mygento\Slider\Model\Resizer;
use Mygento\Slider\Model\ResourceModel;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ProductSlider extends ProductsList
{
    protected $_template = 'Mygento_Slider::product_slider.phtml';

    /**
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        private ResourceModel\ProductSlider\CollectionFactory $sliderCollectionFactory,
        private Resizer $service,
        private Sorting $sorting,
        private Json $serializer,
        private Config $catalogConfig,
        private Stock $stock,
        private ImageFactory $imageFactory,
        Context $context,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        Http\Context $httpContext,
        Builder $sqlBuilder,
        Rule $rule,
        Conditions $conditionsHelper,
        array $data = [],
        ?Json $json = null,
        ?LayoutFactory $layoutFactory = null,
        ?EncoderInterface $urlEncoder = null,
        ?CategoryRepositoryInterface $categoryRepository = null,
    ) {
        parent::__construct(
            $context,
            $productCollectionFactory,
            $catalogProductVisibility,
            $httpContext,
            $sqlBuilder,
            $rule,
            $conditionsHelper,
            $data,
            $json,
            $layoutFactory,
            $urlEncoder,
            $categoryRepository,
        );
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo(): array
    {
        $cacheKeyInfo = parent::getCacheKeyInfo();
        $cacheKeyInfo[] = $this->getProductSliderIdentifier();

        return $cacheKeyInfo;
    }

    public function getSizes(Product $product): array
    {
        /** @var Image $image */
        $image = $this->imageFactory->create($product, 'category_page_grid');

        $width = $image->getWidth();
        $height = $image->getHeight();

        return [
            'width' => $width,
            'height' => $height,
        ];
    }

    public function createSrcSet(array $items): string
    {
        $images = array_reduce(
            array_keys($items),
            fn($carry, $key) => [...$carry, $items[$key] . ' ' . $key],
            [],
        );

        return implode(', ', $images);
    }

    public function getOptions(): array
    {
        return $this->getSlider()?->getOptions() ?? [];
    }

    public function getProductSliderIdentifier(): string
    {
        return 'product_slider' . $this->getData('product_slider');
    }

    public function getCollection(): Collection
    {
        $options = $this->getSlider()->getOptions();
        $maxCount = $options['products_count'] ?? 12;
        /** @var Collection $collection */
        $collection = $this->productCollectionFactory->create();

        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        $collection
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect($this->catalogConfig->getProductAttributes())
            ->addUrlRewrite()
            ->addStoreFilter();

        $conditions = $this->getSliderConditions();
        $conditions->collectValidatedAttributes($collection);
        $this->sqlBuilder->attachConditionToCollection($collection, $conditions);
        $this->stock->addIsInStockFilterToCollection($collection);

        $collection->setPageSize($maxCount)->setCurPage(1);
        $collection->distinct(true);

        return $this->sorting->applySorting($options['parameters']['sort_order'] ?? '', $collection);
    }

    public function getIntegerOption(array $options, string $key): ?int
    {
        return isset($options[$key]) && $options[$key] !== null ? (int) $options[$key] : null;
    }

    public function getImageData(Product $product): array
    {
        $img = $product->getData('thumbnail');
        $options = $this->getSlider()->getOptions();
        $jpg = isset($options['jpg']) && $options['jpg'] === true;
        $sizes = $this->getSizes($product);
        $width = $sizes['width'] ?? null;
        $height = $sizes['height'] ?? null;

        try {
            return [
                'formats' => $this->buildImageFormats($options, $img ?? null, $width, $height),
                'default' => $this->service->resizeAndConvert($img ?? null, $jpg ? 'jpg' : null, $width, $height),
            ];
        } catch (LocalizedException) {
            return [
                'formats' => [],
                'default' => '',
            ];
        }
    }

    public function getTitle(): string
    {
        return $this->getSlider()?->getTitle() ?? $this->getProductSliderIdentifier();
    }

    protected function _toHtml(): string
    {
        if (!$this->getData('product_slider')) {
            return '';
        }

        $slider = $this->getSlider();
        if (!$slider || !$slider->isActive()) {
            return '';
        }

        return parent::_toHtml();
    }

    protected function _beforeToHtml()
    {
        return $this;
    }

    private function getSlider(): ?ProductSliderInterface
    {
        /** @var ResourceModel\ProductSlider\Collection $collection */
        $collection = $this->sliderCollectionFactory->create();
        $collection->addFieldToFilter(ProductSliderInterface::IDENTITY, $this->getData('product_slider'));
        $entity = $collection->getFirstItem();

        if (!$entity->getId()) {
            return null;
        }

        return $entity;
    }

    private function getSliderConditions(): Combine
    {
        $conditions = $this->serializer->unserialize($this->getSlider()?->getConditions() ?? '[]');

        foreach ($conditions as $key => $condition) {
            if (!empty($condition['attribute'])) {
                if (in_array($condition['attribute'], ['special_from_date', 'special_to_date'])) {
                    $conditions[$key]['value'] = date('Y-m-d H:i:s', strtotime($condition['value']));
                }
            }
        }

        $this->rule->loadPost(['conditions' => $conditions]);

        return $this->rule->getConditions();
    }

    private function resizeImage(?string $ext = null, ?string $image = null, ?int $width = null, ?int $height = null): array
    {
        if ($image === null) {
            return [];
        }
        $result = [];
        for ($i = 1;$i <= 3;$i++) {
            try {
                $file = $this->service->resizeAndConvert(
                    $image,
                    $ext,
                    $width !== null ? $width * $i : null,
                    $height !== null ? $height * $i : null,
                );
                if ($file === null) {
                    continue;
                }
                $result[($width * $i) . 'w'] = $file;
            } catch (LocalizedException) {
                continue;
            }
        }

        return $result;
    }

    private function buildImageFormats(array $options, ?string $image = null, ?int $width = null, ?int $height = null): array
    {
        if (null === $image) {
            return [];
        }

        $result = [];

        foreach (['avif', 'webp', 'jpg'] as $ext) {
            if (!isset($options[$ext]) || $options[$ext] !== true) {
                continue;
            }

            $result[$ext]['image'] = $this->resizeImage($ext, $image, $width, $height);
        }

        return $result;
    }
}
