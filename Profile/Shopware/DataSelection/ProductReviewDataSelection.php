<?php declare(strict_types=1);

namespace SwagMigrationAssistant\Profile\Shopware\DataSelection;

use SwagMigrationAssistant\Migration\DataSelection\DataSelectionInterface;
use SwagMigrationAssistant\Migration\DataSelection\DataSelectionStruct;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\CustomerAttributeDataSet;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\CustomerDataSet;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\ManufacturerAttributeDataSet;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\ProductDataSet;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\ProductReviewDataSet;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\PropertyGroupOptionDataSet;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\TranslationDataSet;
use SwagMigrationAssistant\Profile\Shopware\ShopwareProfileInterface;

class ProductReviewDataSelection implements DataSelectionInterface
{
    public const IDENTIFIER = 'productReviews';

    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $migrationContext->getProfile() instanceof ShopwareProfileInterface;
    }

    public function getData(): DataSelectionStruct
    {
        return new DataSelectionStruct(
            self::IDENTIFIER,
            $this->getEntityNames(),
            'swag-migration.index.selectDataCard.dataSelection.productReviews',
            250,
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityNames(): array
    {
        return [
            ManufacturerAttributeDataSet::getEntity(),
            ProductDataSet::getEntity(),
            PropertyGroupOptionDataSet::getEntity(),
            TranslationDataSet::getEntity(),
            CustomerAttributeDataSet::getEntity(),
            CustomerDataSet::getEntity(),
            ProductReviewDataSet::getEntity(),
        ];
    }
}
