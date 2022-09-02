<?php 

namespace IMSExport\Application\IMS\Services\Question;

use IMSExport\Application\Entities\Exam;
use IMSExport\Application\Entities\Group;
use IMSexport\Application\XMLGenerator\Generator;
use IMSExport\Helpers\Collection;

class Boolean extends BaseQuestion
{
	public function export()
    {
    	try {
            $self = $this;
            $this->createItem(function () use ( $self ) {
                $self->createPresentation
                (
                	function () use ( $self )
                	{
                		$self
                			->createMaterial($self->getText())
                			->createRenderChoice('single', function () use ( $self ) {
                				$self
                					->createAnswerChoice();
                			});
                	}
                )
                ->tagParent
                (
                	"resprocessing",
                	function () use ( $self )
                	{
                		$self
	                		->tagParent
	                		(
	                			"outcomes",
			                	function () use ( $self )
			                	{
			                		$self->decvar
			                		(
			                			"SCORE",
			                			"Decimal"
			                		);
			                	}
	                		)
                			->tagParent
                			(
	                			"respcondition",
			                	function () use ( $self )
			                	{
			                		$self
			                			->tagParent
			                			(
			                				"conditionvar",
						                	function () use ( $self )
						                	{
						                		$self->varequal("response_{$self->data['idPreguntas']}", $self->corrects[0]);
						                	}
			                			)
			                			->setvar("Set", 1);

			                		if($self->data['retroalimentacion']) {

			                			$self
			                			->displayfeedback("Response", "Correct");

			                		}		                		

			                	}
	                		);
                	}
                );


                if($self->data['retroalimentacion']) 
                {
	                $self->itemfeedback
	                (
	                	"Correct",
	                	function () use ( $self )
	                	{
	                		$self
				                ->tagParent(
				                	"flow_mat", 
				                	function () use ( $self )
	                				{

	                					print_r("retro");
	                					print_r();

	                					$self->createMaterial(
	                						$self->data['retroalimentacion']
	                					);
	                				}
	                			);
	                	}
	                );
	            }
            });
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }
}