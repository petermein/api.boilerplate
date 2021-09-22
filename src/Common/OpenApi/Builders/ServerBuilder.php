<?php


namespace Api\Common\OpenApi\Builders;


use cebe\openapi\spec\Server;
use cebe\openapi\spec\ServerVariable;

class ServerBuilder
{

    public function generateGlobalServerlist(): array
    {
        $configServers = config('openapi.servers', []);
        $servers = [];

        foreach ($configServers as $server) {

            $server['variables'] = $this->buildServerVariables($server['variables'] ?? []);
            $servers[] = $this->buildServer($server);
        }

        return $servers;
    }

    public function buildServerVariables(array $serverVariables): array
    {
        $variables = [];
        foreach ($serverVariables as $key => $serverVariable) {
            $variables[$key] = new ServerVariable($serverVariable);
        }
        return $variables;
    }

    public function buildServer($serverData): Server
    {
        return new Server($serverData);
    }

}
