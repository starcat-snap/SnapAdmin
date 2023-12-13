<?php declare(strict_types=1);

namespace SnapAdmin\Core\System\User;

use SnapAdmin\Core\Content\Media\MediaCollection;
use SnapAdmin\Core\Content\Media\MediaEntity;
use SnapAdmin\Core\Framework\Api\Acl\Role\AclRoleCollection;
use SnapAdmin\Core\Framework\DataAbstractionLayer\Entity;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityCustomFieldsTrait;
use SnapAdmin\Core\Framework\DataAbstractionLayer\EntityIdTrait;
use SnapAdmin\Core\Framework\Log\Package;
use SnapAdmin\Core\System\Locale\LocaleEntity;
use SnapAdmin\Core\System\User\Aggregate\UserAccessKey\UserAccessKeyCollection;
use SnapAdmin\Core\System\User\Aggregate\UserConfig\UserConfigCollection;
use SnapAdmin\Core\System\User\Aggregate\UserRecovery\UserRecoveryEntity;

#[Package('system-settings')]
class UserEntity extends Entity
{
    use EntityCustomFieldsTrait;
    use EntityIdTrait;

    /**
     * @var string
     */
    protected $localeId;

    /**
     * @var string|null
     */
    protected $avatarId;

    /**
     * @var string
     */
    protected $username;

    /**
     * @internal
     *
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $title;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var bool
     */
    protected $active;

    /**
     * @var bool
     */
    protected $admin;

    /**
     * @var AclRoleCollection
     */
    protected $aclRoles;

    /**
     * @var LocaleEntity|null
     */
    protected $locale;

    /**
     * @var MediaEntity|null
     */
    protected $avatarMedia;

    /**
     * @var MediaCollection|null
     */
    protected $media;

    /**
     * @var UserAccessKeyCollection|null
     */
    protected $accessKeys;

    /**
     * @var UserConfigCollection|null
     */
    protected $configs;

    /**
     * @var UserRecoveryEntity|null
     */
    protected $recoveryUser;

    /**
     * @internal
     *
     * @var string|null
     */
    protected $storeToken;

    /**
     * @var \DateTimeInterface|null
     */
    protected $lastUpdatedPasswordAt;

    protected string $timeZone;

    public function getLocaleId(): string
    {
        return $this->localeId;
    }

    public function setLocaleId(string $localeId): void
    {
        $this->localeId = $localeId;
    }

    public function getAvatarId(): ?string
    {
        return $this->avatarId;
    }

    public function setAvatarId(string $avatarId): void
    {
        $this->avatarId = $avatarId;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @internal
     */
    public function getPassword(): string
    {
        $this->checkIfPropertyAccessIsAllowed('password');

        return $this->password;
    }

    /**
     * @internal
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getLocale(): ?LocaleEntity
    {
        return $this->locale;
    }

    public function setLocale(LocaleEntity $locale): void
    {
        $this->locale = $locale;
    }

    public function getAccessKeys(): ?UserAccessKeyCollection
    {
        return $this->accessKeys;
    }

    public function setAccessKeys(UserAccessKeyCollection $accessKeys): void
    {
        $this->accessKeys = $accessKeys;
    }

    public function getConfigs(): ?UserConfigCollection
    {
        return $this->configs;
    }

    public function setConfigs(UserConfigCollection $configs): void
    {
        $this->configs = $configs;
    }

    public function getRecoveryUser(): ?UserRecoveryEntity
    {
        return $this->recoveryUser;
    }

    public function setRecoveryUser(UserRecoveryEntity $recoveryUser): void
    {
        $this->recoveryUser = $recoveryUser;
    }

    /**
     * @internal
     */
    public function getStoreToken(): ?string
    {
        $this->checkIfPropertyAccessIsAllowed('storeToken');

        return $this->storeToken;
    }

    /**
     * @internal
     */
    public function setStoreToken(?string $storeToken): void
    {
        $this->storeToken = $storeToken;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    public function getAclRoles(): AclRoleCollection
    {
        return $this->aclRoles;
    }

    public function setAclRoles(AclRoleCollection $aclRoles): void
    {
        $this->aclRoles = $aclRoles;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getLastUpdatedPasswordAt(): ?\DateTimeInterface
    {
        return $this->lastUpdatedPasswordAt;
    }

    public function setLastUpdatedPasswordAt(\DateTimeInterface $lastUpdatedPasswordAt): void
    {
        $this->lastUpdatedPasswordAt = $lastUpdatedPasswordAt;
    }

    public function getTimeZone(): string
    {
        return $this->timeZone;
    }

    public function setTimeZone(string $timeZone): void
    {
        $this->timeZone = $timeZone;
    }
}
