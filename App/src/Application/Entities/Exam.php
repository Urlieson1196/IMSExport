<?php

namespace IMSExport\Application\Entities;

use IMSExport\Application\Repositories\Exam as ExamModel;
use IMSExport\Core\BaseEntity;

/**
 * @property mixed|null $getSection
 * @property mixed|null $getQuestion
 */
class Exam extends BaseEntity
{
    public function __construct(public string $id)
    {
        $this->repository = new ExamModel();
        $this->find();
    }

    public function find()
    {
        $data = $this->repository->firstElement($this->repository->find($this->id));
        if ($data) {
            $this->setData($data);
        }
    }

    public function getSection(): ?array
    {
        return $this->repository->getData($this->repository->getSection($this->id));
    }

    public function getQuestion(): ?array
    {
        return $this->repository->getData($this->repository->getQuestion($this->id));
    }
}