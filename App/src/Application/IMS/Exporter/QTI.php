<?php

namespace IMSExport\Application\IMS\Exporter;

use Exception;
use IMSExport\Application\Entities\Exam;
use IMSExport\Application\Entities\Group;
use IMSExport\Application\IMS\Services\Formats\BaseFormat;
use IMSExport\Application\IMS\Services\Formats\IMSQTIFormat;
use IMSExport\Application\IMS\Services\Question\Factory;
use IMSExport\Helpers\Collection;

class QTI extends IMSQTIFormat
{
    protected Exam $exam;
    protected $configurate;
    protected Collection $section;
    protected Collection $question;

    public function __construct(protected Group $group, protected array $data)
    {
//        find exam
        $this->exam = new Exam($this->data['id']);
        $this->configurate = $this->exam->toArray();
        $this->section = Collection::createCollection($this->exam->getSection);
        $this->question = Collection::createCollection($this->exam->getQuestion);
        parent::__construct();
    }

    public function export(): bool
    {
        try {
            $self = $this;
            $this->createQuestestinterop(function () use ($self) {
                $self
                    ->createAssessment($this->data['identifierRef'], $this->data['title'], function () use ($self) {
                        $self
                            ->qtimetadata($this->configurate['intentos'])
                            ->createInitPresentationMaterial($this->configurate)
                            ->createAllSection();
                    });
            })
            ->finish();
            return true;
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    public function finish(): BaseFormat
    {
        $this->XMLGenerator->finish();
        return $this;
    }

    protected function createAllSection()
    {
        $sections = $this
            ->section
            ->toArray();
        if ($sections && count($sections)) {
            foreach ($sections as $section) {
                $identifier = $this
                    ->identifierCreator
                    ->getIdentifier('section');
                $self = $this;

                $identifierall = "{$this->data['identifierRef']}_{$identifier}";

                $this->XMLGenerator->createElement(
                    'section',
                    [
                        'ident' => $identifierall
                    ],
                    null,
                    function () use ($section, $self, $identifierall) {
                        $self
                            ->qtimetadataSection($section['numero_preguntas'], $section['ponderacion'])
                            ->createItemSection($section['idExamenSeccion'], $identifierall);
                    }
                );
            }
        }
    }

    protected function createItemSection($id, $identifierall)
    {
        $questions = $this
            ->question
            ->where('fk_idExamenSeccion', $id)
            ->toArray();

        $identifier = $identifierall;

        if ($questions && count($questions)) {
            $root = $this;
            foreach ($questions as $question) {
                if ($question['tipo'] == 6 || $question['tipo'] == 1 || $question['tipo'] == 5 || $question['tipo'] == 4 || $question['tipo'] == 9) {

                    $question = array_merge($question, compact('identifier'));

                    $driver = Factory::getDriver(
                        $question['tipo'],
                        $question,
                        $root
                    );
                    $driver->export();
                }
            }
        }
    }

    public function getName(): string
    {
        return "{$this->data['identifierRef']}.xml";
    }

    public function getFolderName(): string
    {
        return "{$this->group->seedId}/{$this->data['identifierRef']}";
    }

    public function getType(): string
    {
        return 'imsqti_xmlv1p2/imscc_xmlv1p1/assessment';
    }
}