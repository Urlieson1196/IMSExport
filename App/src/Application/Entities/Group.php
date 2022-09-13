<?php

namespace IMSExport\Application\Entities;

use Exception;
use IMSExport\Application\Constants\Activities;
use IMSExport\Application\Repositories\Group as GroupModel;
use IMSExport\Core\BaseEntity;

/**
 * @property mixed|null $title
 * @property mixed|null $description
 * @property array|null $resources
 */
class Group extends BaseEntity
{
    public function __construct(public string $seedId)
    {
        $this->repository = new GroupModel();
        $this->find();
    }

    /**
     * @throws Exception
     */
    public function find(): self
    {
        $group = $this->repository->firstElement(
            $this->repository->find($this->seedId)
        );
        if ($group) {
            $this->setData($group);
            return $this;
        }
        throw new Exception('Grupo no encontrado');
    }

    public function resources(): array
    {
        return [
            ['id' => 1, 'parent_id' => 0, 'typeActivity' => null, 'type' => 'assets', 'title' => 'Test Exam', 'description' => 'Test Description'],
            ['id' => '152228607', 'parent_id' => 1, 'typeActivity' => 'exam', 'type' => 'assets', 'title' => 'Test Exam', 'description' => 'Test Description'],
            ['id' => '336570223', 'parent_id' => 1, 'typeActivity' => 'exam', 'type' => 'assets', 'title' => 'Test Exam', 'description' => 'Test Description']
        ];
    }

    public function scaffolding()
    {
        if (!$this->getAttribute('scaffolding')) {
            $scaffolding = $this
                ->getScaffolding();
            $newScaffolding = array_map(function ($resource) {
                if ($resource['resourceType'] !== null) {
                    switch ((int)$resource['resourceType']) {
                        case 1:
                            $resource['resourceType'] = Activities::exam;
                            break;
                        case 2:
                            $resource['resourceType'] = Activities::task;
                            break;
                        case 3:
                            $resource['resourceType'] = Activities::announcement;
                            break;
                        case 4:
                            $resource['resourceType'] = Activities::scorm;
                            break;
                        case 5:
                            $resource['resourceType'] = Activities::probe;
                            break;
                        default:
                            $resource['resourceType'] = Activities::post;
                            break;
                    }
                }
                return $resource;
            }, $scaffolding);
            $this
                ->setAttribute('scaffolding', $newScaffolding)
                ->getAttribute('scaffolding');
        }
        return $this->getAttribute('scaffolding');
    }

    protected function getScaffolding(): ?array
    {
        return $this->repository->getData(
            $this->repository->getScaffolding($this->getAttribute('id'))
        );
    }
}
