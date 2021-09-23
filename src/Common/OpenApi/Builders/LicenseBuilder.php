<?php

namespace Api\Common\OpenApi\Builders;

use cebe\openapi\spec\License;

class LicenseBuilder
{
    public function generateLicense(): License
    {
        $configLicense = config('openapi.license', []);

        return new License($configLicense);
    }
}
