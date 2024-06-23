<?php

namespace Modules\KPI\Services;

use App\Models\KpiItem;
use Illuminate\Support\Arr;

abstract class KPIAbstract
{

    public static $items = null;

    public function getKPIItem($user_id)
    {

        $this->getItems();
        $items = self::$items ?? [];
        $user = Arr::get($items, $user_id) ?? [];
        if (!Arr::get($items, $user_id)) {
            $user = Arr::get($items, '') ?? [];
        }
        return Arr::get($user, $this->methodName()) ?? 0;
    }

    protected function getItems()
    {
        if (self::$items == null) {
            self::$items = $this->withKeyMap(KpiItem::query()->get()->toArray(), 'user_id', 'name');
        }
        return self::$items;
    }

    protected function withKeyMap($collection, $key1, $key2): array
    {

        $item = [];
        foreach ($collection as $index => $collect) {
            $item[$collect[$key1]][$collect[$key2]] = $collect;
        }
        return $item;
    }

    public abstract function methodName();
}