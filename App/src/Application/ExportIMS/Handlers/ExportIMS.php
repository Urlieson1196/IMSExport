<?php

namespace IMSExport\Application\ExportIMS\Handlers;

use IMSExport\Application\ExportIMS\Services\ExportById;

class ExportIMS
{
    public function __construct(protected string $method, protected array $data)
    {
    }


    public function run()
    {
        if($this->method == 'id') {
            (new ExportById($this->data))->export();
        }
    }


}