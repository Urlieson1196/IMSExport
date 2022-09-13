<?php

namespace IMSExport\Application\IMS\Services\Formats;
use IMSexport\Application\XMLGenerator\Generator;

abstract class IMSQTIFormat extends BaseFormat
{

    protected function createQuestestinterop($children): self
    {
        $this->XMLGenerator->createElement('questestinterop', [
            'xmlns' => 'http://www.imsglobal.org/xsd/ims_qtiasiv1p2',
        ], null,
            $children
        );
        return $this;
    }

    protected function createAssessment(string $identifier, string $title, callable $children): self
    {
        $this->XMLGenerator->createElement(
            'assessment',
            [
                'ident' => $identifier,
                'title' => $title
            ],
            null,
            $children
        );
        return $this;
    }

    protected function qtimetadata(?string $attempt): self
    {
        $this->XMLGenerator
            ->createElement(
            'qtimetadata',
            null,
            null,
            function (Generator $generator) use ($attempt) {
                $generator
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) {
                            $generator->createElement('fieldlabel', null, 'cc_profile', null);
                            $generator->createElement('fieldentry', null, 'cc.exam.v0p1', null);
                        }
                    )
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) {
                            $generator->createElement('fieldlabel', null, 'qmd_scoretype', null);
                            $generator->createElement('fieldentry', null, 'Percentage', null);
                        }
                    )
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) use ($attempt) {
                            $generator->createElement('fieldlabel', null, 'cc_maxattempts', null);
                            $generator->createElement('fieldentry', null, $attempt, null);                
                        }
                    )
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) {
                            $generator->createElement('fieldlabel', null, 'qmd_assessmenttype', null);
                            $generator->createElement('fieldentry', null, 'Examination', null);
                        }
                    )
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) {
                            $generator->createElement('fieldlabel', null, 'qmd_feedbackpermitted', null);
                            $generator->createElement('fieldentry', null, 'No', null);
                        }
                    );
            }
        );
        return $this;
    }

    protected function createInitPresentationMaterial($configurate): self
    {

        $instrucciones = $configurate['instrucciones'];

        $this->XMLGenerator
            ->createElement(
            'presentation_material',
            null,
            null,
            function (Generator $generator) use ( $instrucciones ) {
                $generator
                    ->createElement('flow_mat', null, null, 
                        function (Generator $generator) use ( $instrucciones ) {
                            $generator->createElement('material', null, null, 
                                function (Generator $generator) use ( $instrucciones ) {
                                    $generator->createElement(
                                        "mattext", 
                                        [
                                            "texttype" => "TEXT/HTML",
                                            "xml:space" => "preserve"
                                        ], 
                                        "<![CDATA[{$instrucciones}]]>"
                                    );
                                }
                            );
                        }
                    );  
            }
        );
        return $this;
    }

    protected function qtimetadataSection( $numberofitems, $weighting ): self
    {

        $this->XMLGenerator
            ->createElement(
            'qtimetadata',
            null,
            null,
            function (Generator $generator) use ( $numberofitems, $weighting ) { 
                $generator
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) use ( $numberofitems ) {
                            $generator->createElement('fieldlabel', null, 'qmd_numberofitems', null);
                            $generator->createElement('fieldentry', null, $numberofitems, null);
                        }
                    )
                    ->createElement('qtimetadatafield', null, null, 
                        function (Generator $generator) use ( $weighting ) {
                            $generator->createElement('fieldlabel', null, 'cc_weighting', null);
                            $generator->createElement('fieldentry', null, $weighting, null);
                        }
                    );
            }
        );

        return $this;

    }

    public function createDummy(): self
    {
        $this->XMLGenerator->createElement(
            'Dummy',
            [
                'test' => '0001'
            ],
            'Hello world'
        );
        return $this;
    }
}