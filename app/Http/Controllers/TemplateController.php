<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpPresentation\Exception\InvalidFileFormatException;
use PhpOffice\PhpPresentation\Slide\SlideTitle;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;

class TemplateController extends Controller
{
    /**
     * @throws InvalidFileFormatException
     */
    public function editPptxFile(Request $request)
    {
        $documentPath = storage_path('app/public/template.docx');

        try {
            $templateProcessor = new TemplateProcessor($documentPath);
        } catch (CopyFileException $e) {
            throw $e;
        } catch (CreateTemporaryFileException $e) {
            throw $e;

        }

        // Modify the document content
        $templateProcessor->setValue('${ok}', 'asdasdasdasd');

        // Save the modified document
        $templateProcessor->saveAs(storage_path('app/public/template2.docx'));

        return response()->download($documentPath);

    }

}
