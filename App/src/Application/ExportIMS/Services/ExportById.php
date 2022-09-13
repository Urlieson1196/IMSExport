<?php

namespace IMSExport\Application\ExportIMS\Services;

class ExportById extends BaseExport
{
    public function export()
    {
        $seedId = $this->data['seedId'];
        $group = $this->createGroup($seedId);
        $processId = $this->registerProcess($group);
        $this->init($group);
        $this->finishProcess($processId);
        print_r(['success' => true, 'message' => 'curso exportado con éxito']);
    }
}