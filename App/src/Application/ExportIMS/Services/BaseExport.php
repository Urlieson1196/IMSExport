<?php

namespace IMSExport\Application\ExportIMS\Services;

use IMSExport\Application\Entities\Group;
use IMSExport\Application\ExportIMS\Repository\Export;
use IMSExport\Application\IMS\Exporter\Cartridge;

abstract class BaseExport
{
    const inProgress = 'inProgress';
    const finished = 'finished';
    const error = 'error';
    protected Group $group;
    protected Export $repository;

    public function __construct(public array $data)
    {
        $this->repository = new Export();
    }

    public abstract function export();

    protected function createGroup($groupId): Group
    {
        return new Group($groupId);
    }

    protected function registerProcess(Group $group)
    {
        $process = $this->repository->init($group->getAttribute('id'), self::inProgress);
        return $process['insertId'];
    }

    protected function finishProcess($processId)
    {
        $this->repository->finish($processId, self::finished);
    }

    protected function init(Group $group)
    {
        (new Cartridge($group))->export();
    }
}