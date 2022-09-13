<?php

namespace IMSExport\Application\IMS\Exporter;

use Exception;
use IMSExport\Application\Entities\Group;
use IMSExport\Application\IMS\Services\Formats\Cartridge as Format;
use IMSexport\Application\XMLGenerator\Generator;
use IMSExport\Helpers\Collection;

class Cartridge extends Format
{
    protected Collection $resources;
    protected array $queue = [];

    public function __construct(public Group $group)
    {
        $this->resources = Collection::createCollection($this->group->scaffolding());
        parent::__construct();
    }

    public function getFolderName(): string
    {
        return $this->group->seedId;
    }

    protected function createResources(): self
    {
        foreach ($this->queue as $resource) {
            print_r($resource);
            $driver = Factory::getDriver($this->group, $resource['resourceType'], $resource);
            if ($driver) {
                $driver->export();
                $href = "{$resource['identifierRef']}/{$driver->getName()}";
                $this->XMLGenerator->createElement(
                    'resources',
                    null,
                    null,
                    function () use ($resource, $driver, $href) {
                        $this->XMLGenerator->createElement(
                            'resource',
                            [
                                'identifier' => $resource['identifierRef'],
                                'type' => $driver->getType()
                            ],
                            null,
                            function (Generator $generator) use ($href) {
                                $generator->createElement(
                                    'file',
                                    compact('href')
                                );
                            }
                        );
                    }
                );
            }
        }
        return $this;
    }

    public function export(): bool
    {
        try {
            $self = $this;
            $this->createManifest(function () use ($self) {
                $self
                    ->createMetadata($self->group->title, $self->group->description)
                    ->createOrganizationsStructure()
                    ->createResources();

            })
                ->finish();
            return true;
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    protected function createOrganizationsStructure(): self
    {
        $roots = $this->resources
            ->where('parent_id', 0)
            ->toArray();
        foreach ($roots as $root) {
            $self = $this;
            $this->createOrganizations(function () use ($self, $root) {
                $identifier = $this
                    ->identifierCreator
                    ->getIdentifier('organization');
                $self->createOrganization($identifier, function () use ($self, $root) {
                    $self->createItemResource($root);
                });
            });
        }
        return $this;
    }

    protected function createItemResource($parent): void
    {
        $resources = $this
            ->resources
            ->where('parent_id', $parent['id'])
            ->toArray();
        if ($resources && count($resources)) {
            foreach ($resources as $resource) {
                $identifier = $this
                    ->identifierCreator
                    ->getIdentifier('item');
                $identifierRef = $this
                    ->identifierRefCreator
                    ->getIdentifier($resource['resourceType']);
                $resource = array_merge($resource, compact('identifier', 'identifierRef'));
                $self = $this;
                $this->XMLGenerator->createElement(
                    'item',
                    [
                        'identifier' => $identifier,
                        'identifierref' => $identifierRef
                    ],
                    null,
                    static function (Generator $generator) use ($resource, $self) {
                        $generator->createElement(
                            'title',
                            null,
                            $resource['title']
                        );
                        $self->createItemResource($resource);
                    }
                );
            }
        } else {
            $this->queue[] = $parent;
        }
    }

    public function getName(): string
    {
        return 'imsmanifest.xml';
    }

    public function getType(): string
    {
        return 'ims_cartridge';
    }
}