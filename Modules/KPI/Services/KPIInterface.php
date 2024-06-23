<?php

namespace Modules\KPI\Services;

interface KPIInterface
{
    public static function calculate(int $agent_user_id);

}