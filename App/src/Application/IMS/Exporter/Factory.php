<?php

namespace IMSExport\Application\IMS\Exporter;

use IMSExport\Application\Constants\Activities;
use IMSExport\Application\Entities\Group;
use IMSExport\Application\IMS\Services\Formats\FormatInterface;

class Factory
{
    public static function getDriver(Group $group, ?string $type, $data): ?FormatInterface
    {
        switch ($type) {
            case Activities::exam:
            case Activities::probe:
                return new QTI($group, $data);
            case Activities::post:
                return new Discussion($group, $data); //RACH: Factory.php discussion->forum y generar o crear recurso
        }
        return null;
    }
}