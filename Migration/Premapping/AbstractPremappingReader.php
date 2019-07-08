<?php declare(strict_types=1);

namespace SwagMigrationAssistant\Migration\Premapping;

use SwagMigrationAssistant\Migration\MigrationContextInterface;

abstract class AbstractPremappingReader implements PremappingReaderInterface
{
    /**
     * @var array
     */
    protected $connectionPremappingDictionary = [];

    protected function fillConnectionPremappingDictionary(MigrationContextInterface $migrationContext): void
    {
        if ($migrationContext->getConnection()->getPremapping() === null) {
            return;
        }

        foreach ($migrationContext->getConnection()->getPremapping() as $premapping) {
            if ($premapping['entity'] === static::getMappingName()) {
                foreach ($premapping['mapping'] as $mapping) {
                    $this->connectionPremappingDictionary[$mapping['sourceId']] = $mapping;
                }
            }
        }
    }
}
