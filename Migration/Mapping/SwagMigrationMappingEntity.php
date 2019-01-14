<?php declare(strict_types=1);

namespace SwagMigrationNext\Migration\Mapping;

use DateTime;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SwagMigrationNext\Migration\Profile\SwagMigrationProfileEntity;

class SwagMigrationMappingEntity extends Entity
{
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $profileId;

    /**
     * @var SwagMigrationProfileEntity
     */
    protected $profile;

    /**
     * @var string|null
     */
    protected $entity;

    /**
     * @var string|null
     */
    protected $oldIdentifier;

    /**
     * @var string|null
     */
    protected $entityUuid;

    /**
     * @var array|null
     */
    protected $additionalData;

    /**
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @var DateTime|null
     */
    protected $updatedAt;

    public function getProfile(): SwagMigrationProfileEntity
    {
        return $this->profile;
    }

    public function setProfile(SwagMigrationProfileEntity $profile): void
    {
        $this->profile = $profile;
    }

    public function getProfileId(): string
    {
        return $this->profileId;
    }

    public function setProfileId(string $profileId): void
    {
        $this->profileId = $profileId;
    }

    public function getEntity(): ?string
    {
        return $this->entity;
    }

    public function setEntity(string $entity): void
    {
        $this->entity = $entity;
    }

    public function getOldIdentifier(): ?string
    {
        return $this->oldIdentifier;
    }

    public function setOldIdentifier(string $oldIdentifier): void
    {
        $this->oldIdentifier = $oldIdentifier;
    }

    public function getEntityUuid(): ?string
    {
        return $this->entityUuid;
    }

    public function setEntityUuid(string $entityUuid): void
    {
        $this->entityUuid = $entityUuid;
    }

    public function getAdditionalData(): ?array
    {
        return $this->additionalData;
    }

    public function setAdditionalData(array $additionalData): void
    {
        $this->additionalData = $additionalData;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}