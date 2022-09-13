<?php

namespace IMSExport\Application\ExportIMS\Repository;

use IMSExport\Core\Connection\BaseModel;

class Export extends BaseModel
{
    public function init(string $groupId, string $status): array
    {
        $sql = "             
           insert into group_exports (groupId, status) values (:groupId, :status);
        ";
        return $this->executeOrFail($sql, compact('groupId', 'status'));
    }

    public function finish(int $processId, string $status)
    {
        $sql = "
            update group_exports set finishedAt = current_timestamp() , status = :status where id = :processId ;
        ";
        return $this->executeOrFail($sql, compact('status', 'processId'));
    }
}