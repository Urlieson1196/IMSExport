<?php

namespace IMSExport\Application\IMS\Services\Formats;

use IMSexport\Application\XMLGenerator\Generator;
use IMSExport\Helpers\File;

abstract class IMSDiscussionFormat extends BaseFormat
{

    public function createDiscussion($children)
    {
        $this->XMLGenerator->createElement(
            'dt:topic',
            [
                'xmlns' => 'dt=http://www.imsglobal.org/xsd/imsccv1p1/imscp_v1p1',
                'xmlns:xsi' => 'http://www.w3.org/2001/XMLSchema-instance'
            ],
            null,
            $children);
    }

    public function createDetail(string $title, string $description, $attach): self
    {
        $this->XMLGenerator->createElement('dt:title', null, $title ?? $description)
            ->createElement('dt:text', ['textype' => 'text/html'], htmlentities($description, ENT_QUOTES, "UTF-8"));
        $archivos = $attach->toarray();
        if ($archivos && count($archivos)) {
            $this->XMLGenerator->createElement('dt:attachments', null, null, function (Generator $generator) use ($archivos) {
                foreach ($archivos as $archivo) {
                    $file = $archivo['attach'];
                    $download = File::downloadFile($file, $this->getFolderName());
                    if($download) {
                        $file = File::getFileNameFromUrl($file);
                    }
                    $generator->createElement('dt:attachment', ['href' => $file]);
                }
            });
        }

        return $this;
    }
}