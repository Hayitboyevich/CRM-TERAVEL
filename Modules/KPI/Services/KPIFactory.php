<?php

namespace Modules\KPI\Services;

class KPIFactory
{
    private string $namespace = 'Modules/KPI/Services/KPIItems/';

    public function calculateByModule(int $agent_user_id, string $method)
    {
        return call_user_func([__NAMESPACE__ . '\KPIItems\\' . $method, 'calculate'], $agent_user_id) * 100;

    }

    public function handle(int $agent_user_id, string $method): float
    {
        $classNames = $this->getClassNames($this->namespace);

        $kpi = 0;
        foreach ($classNames as $name) {
            $kpi += call_user_func([__NAMESPACE__ . '\KPIItems\\' . $name, 'calculate'], $agent_user_id);
        }
        return $kpi;
    }

    public function getClassNames(string $pathModule,): array
    {
        $folderPath = base_path() . '/' . $pathModule;
        $files = scandir($folderPath);
        $classList = [];

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $className = pathinfo($file, PATHINFO_FILENAME);
                $classList[] = $className;
            }
        }

        return $classList;
    }

}