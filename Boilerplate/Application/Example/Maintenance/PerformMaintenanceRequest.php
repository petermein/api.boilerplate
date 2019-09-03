<?php declare(strict_types=1);

namespace Boilerplate\Application\Service\Example\Maintenance;

use Illuminate\Support\Carbon;

class PerformMaintenanceRequest
{
    protected $laneNumber;
    protected $maintenanceDateTime;

    public function __construct($laneNumber, $maintenanceDateTime = null)
    {
        $this->laneNumber = $laneNumber;
        $this->maintenanceDateTime = Carbon::parse($maintenanceDateTime) ?? Carbon::now();
    }

    public function getLaneNumber()
    {
        return $this->laneNumber;
    }

    public function getMaintenanceDate()
    {
        return $this->maintenanceDateTime;
    }
}