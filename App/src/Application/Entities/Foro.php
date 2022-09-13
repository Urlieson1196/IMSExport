<?php
namespace IMSExport\Application\Entities;

use IMSExport\Application\Repositories\Forum as ForumModel;
use IMSExport\Core\BaseEntity;

/**
 * @property mixed|null $forumFile
 * @property mixed|null $title
 * @property mixed|null $description
 */
class Foro extends BaseEntity
{

    public function __construct(public string $id)
    {
        $this->repository = new ForumModel();
        $this->forumByPost();
        $this->forumFile();
    }

    public function forumByPost()
    {
        $data = $this->repository->firstElement($this->repository->forumByPost($this->id));
        if($data) {
            $this->setData($data);
        }
    }

    public function forumFile(): ?array
    {
        $files = $this->repository->getData($this->repository->forumFile($this->id));
        if($files) {
            $this->setAttribute('forumFile', $files);
            return $files;
        }
        return [];
    }
/*
    public function getQuestion()
    {
        return $this->repository->getData($this->repository->getQuestion($this->id));
    }

    public function getAnswer()
    {
        return $this->repository->getData($this->repository->getAnswer($this->id));
    }
*/
}