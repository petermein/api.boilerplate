<?php

namespace Api\Common\OpenApi\Builders;

use cebe\openapi\spec\Info;

class InfoBuilder
{
    /**
     * @var ContactsBuilder
     */
    private ContactsBuilder $contactsBuilder;
    /**
     * @var LicenseBuilder
     */
    private LicenseBuilder $licenseBuilder;

    /**
     * InfoBuilder constructor.
     * @param ContactsBuilder $contactsBuilder
     * @param LicenseBuilder $licenseBuilder
     */
    public function __construct(ContactsBuilder $contactsBuilder, LicenseBuilder $licenseBuilder)
    {
        $this->contactsBuilder = $contactsBuilder;
        $this->licenseBuilder = $licenseBuilder;
    }

    public function generateInfo($version): Info
    {
        $configInfo = config('openapi.info', []);

        $configInfo['contact'] = $this->contactsBuilder->generateContact();
        $configInfo['license'] = $this->licenseBuilder->generateLicense();
        $configInfo['version'] = $version;

        return new Info($configInfo);
    }
}
