<?php

namespace IMSExport\Application\IMS\Exporter;

use IMSExport\Application\Entities\Exam;
use IMSExport\Application\Entities\Group;
use IMSExport\Application\IMS\Services\Formats\BaseFormat;
use IMSExport\Application\IMS\Services\Formats\IMSQTIFormat;

class QTI extends IMSQTIFormat
{
    protected Exam $exam;

    public function __construct(protected Group $group, protected array $data)
    {
//        find exam
        $this->exam = new Exam($this->data['id']);
        $this->exam->find();
        parent::__construct();
    }

    public function export()
    {
        $this
            ->createDummy()
            ->finish();
    }

    protected function finish(): BaseFormat
    {
        $this->XMLGenerator->finish();
        return $this;
    }

    public function getName(): string
    {
        return "{$this->data['identifierRef']}.xml";
    }

    public function getFolderName(): string
    {
        return "{$this->group->groupId}/{$this->data['identifierRef']}";
    }

    public function getType(): string
    {
        return 'imsqti_xmlv1p2/imscc_xmlv1p1/assessment';
    }
}