<?php declare(strict_types=1);

namespace SwagMigrationAssistant\Profile\Shopware\Gateway\Local;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\Currency\CurrencyEntity;
use SwagMigrationAssistant\Migration\EnvironmentInformation;
use SwagMigrationAssistant\Migration\MigrationContextInterface;
use SwagMigrationAssistant\Migration\Profile\ReaderInterface;
use SwagMigrationAssistant\Migration\RequestStatusStruct;
use SwagMigrationAssistant\Profile\Shopware\DataSelection\DataSet\ShopwareDataSet;
use SwagMigrationAssistant\Profile\Shopware\Exception\DatabaseConnectionException;
use SwagMigrationAssistant\Profile\Shopware\Gateway\Connection\ConnectionFactoryInterface;
use SwagMigrationAssistant\Profile\Shopware\Gateway\ShopwareGatewayInterface;
use SwagMigrationAssistant\Profile\Shopware\Gateway\TableCountReaderInterface;
use SwagMigrationAssistant\Profile\Shopware\Gateway\TableReaderInterface;
use SwagMigrationAssistant\Profile\Shopware\ShopwareProfileInterface;

class ShopwareLocalGateway implements ShopwareGatewayInterface
{
    public const GATEWAY_NAME = 'local';

    /**
     * @var ReaderRegistry
     */
    private $readerRegistry;

    /**
     * @var ReaderInterface
     */
    private $localEnvironmentReader;

    /**
     * @var TableReaderInterface
     */
    private $localTableReader;

    /**
     * @var TableCountReaderInterface
     */
    private $localTableCountReader;

    /**
     * @var ConnectionFactoryInterface
     */
    private $connectionFactory;

    /**
     * @var EntityRepositoryInterface
     */
    private $currencyRepository;

    public function __construct(
        ReaderRegistry $readerRegistry,
        ReaderInterface $localEnvironmentReader,
        TableReaderInterface $localTableReader,
        TableCountReaderInterface $localTableCountReader,
        ConnectionFactoryInterface $connectionFactory,
        EntityRepositoryInterface $currencyRepository
    ) {
        $this->readerRegistry = $readerRegistry;
        $this->localEnvironmentReader = $localEnvironmentReader;
        $this->localTableReader = $localTableReader;
        $this->localTableCountReader = $localTableCountReader;
        $this->connectionFactory = $connectionFactory;
        $this->currencyRepository = $currencyRepository;
    }

    public function getName(): string
    {
        return self::GATEWAY_NAME;
    }

    public function supports(MigrationContextInterface $migrationContext): bool
    {
        return $migrationContext->getProfile() instanceof ShopwareProfileInterface;
    }

    public function read(MigrationContextInterface $migrationContext): array
    {
        /** @var ShopwareDataSet $dataSet */
        $dataSet = $migrationContext->getDataSet();

        $reader = $this->readerRegistry->getReader($migrationContext);

        return $reader->read($migrationContext, $dataSet->getExtraQueryParameters());
    }

    public function readEnvironmentInformation(MigrationContextInterface $migrationContext, Context $context): EnvironmentInformation
    {
        $connection = $this->connectionFactory->createDatabaseConnection($migrationContext);
        $profile = $migrationContext->getProfile();

        try {
            $connection->connect();
        } catch (\Exception $e) {
            $error = new DatabaseConnectionException();

            return new EnvironmentInformation(
                $profile->getSourceSystemName(),
                $profile->getVersion(),
                '-',
                [],
                [],
                new RequestStatusStruct($error->getErrorCode(), $error->getMessage())
            );
        }
        $connection->close();
        $environmentData = $this->localEnvironmentReader->read($migrationContext);

        /** @var CurrencyEntity $targetSystemCurrency */
        $targetSystemCurrency = $this->currencyRepository->search(new Criteria([Defaults::CURRENCY]), $context)->get(Defaults::CURRENCY);
        if (!isset($environmentData['defaultCurrency'])) {
            $environmentData['defaultCurrency'] = $targetSystemCurrency->getIsoCode();
        }

        $totals = $this->readTotals($migrationContext, $context);

        return new EnvironmentInformation(
            $profile->getSourceSystemName(),
            $profile->getVersion(),
            $environmentData['host'],
            $totals,
            $environmentData['additionalData'],
            new RequestStatusStruct(),
            false,
            [],
            $targetSystemCurrency->getIsoCode(),
            $environmentData['defaultCurrency']
        );
    }

    public function readTotals(MigrationContextInterface $migrationContext, Context $context): array
    {
        return $this->localTableCountReader->readTotals($migrationContext, $context);
    }

    public function readTable(MigrationContextInterface $migrationContext, string $tableName, array $filter = []): array
    {
        return $this->localTableReader->read($migrationContext, $tableName, $filter);
    }
}
