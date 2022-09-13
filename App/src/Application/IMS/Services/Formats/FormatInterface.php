<?php

namespace IMSExport\Application\IMS\Services\Formats;

interface FormatInterface
{
    public function export(): bool;

    public function getName(): string;

    public function getFolderName(): string;

    public function getType(): string;
}