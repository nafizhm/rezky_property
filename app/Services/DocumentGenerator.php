<?php
namespace App\Services;

use App\Models\Customer;
use App\Models\DocumentTemplate;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentGenerator
{
    public static function convertPlaceholders(string $templatePath): string
    {
        if (!class_exists('ZipArchive')) {
            return $templatePath;
        }

        $zip = new \ZipArchive;
        if ($zip->open($templatePath) !== true) {
            return $templatePath;
        }

        $entries = [];
        $needsConversion = false;

        for ($i = 0; $i < $zip->count(); $i++) {
            $stat = $zip->statIndex($i);
            $name = $stat['name'];

            if (pathinfo($name, PATHINFO_EXTENSION) === 'xml') {
                $content = $zip->getFromIndex($i);
                if (strpos($content, '{$') !== false) {
                    $content = preg_replace_callback('/\{\$(\w+)\}/', function ($m) {
                        return '${' . $m[1] . '}';
                    }, $content);
                    $entries[$name] = $content;
                    $needsConversion = true;
                }
            }
        }

        if (!$needsConversion) {
            $zip->close();
            return $templatePath;
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'tmpl_') . '.docx';
        $zip->close();
        copy($templatePath, $tempPath);

        $zip2 = new \ZipArchive;
        if ($zip2->open($tempPath) === true) {
            foreach ($entries as $name => $content) {
                $zip2->addFromString($name, $content);
            }
            $zip2->close();
            return $tempPath;
        }

        @unlink($tempPath);
        return $templatePath;
    }

    public static function generateFromTemplate(
        DocumentTemplate $template,
        Customer $customer,
        array $extraValues = [],
        ?string $outputFilename = null
    ) {
        $templatePath = public_path('document_templates/' . $template->file_path);

        if (!file_exists($templatePath)) {
            abort(404, 'File template tidak ditemukan: ' . $template->file_path);
        }

        $convertedPath = self::convertPlaceholders($templatePath);

        try {
            $context = DocumentDataContext::getAllForCustomer($customer);
            $context = array_merge($context, $extraValues);

            $templateProcessor = new TemplateProcessor($convertedPath);

            $flattened = [];
            foreach ($context as $key => $value) {
                if (is_string($value) || is_numeric($value) || is_null($value)) {
                    $flattened[$key] = (string) ($value ?? '-');
                }
            }
            $templateProcessor->setValues($flattened);

            $filename = $outputFilename
                ?? $template->kode . '_' . $customer->kode_customer . '.docx';

            $tempFile = tempnam(sys_get_temp_dir(), 'docgen_');
            $templateProcessor->saveAs($tempFile);

            if ($convertedPath !== $templatePath) {
                @unlink($convertedPath);
            }

            return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            if ($convertedPath !== $templatePath) {
                @unlink($convertedPath);
            }
            throw $e;
        }
    }

    public static function generateDocx(
        string $templatePath,
        Customer $customer,
        array $extraValues = [],
        ?string $outputFilename = null
    ) {
        $context = DocumentDataContext::getAllForCustomer($customer);
        $context = array_merge($context, $extraValues);

        $templateProcessor = new TemplateProcessor($templatePath);

        $flattened = [];
        foreach ($context as $key => $value) {
            if (is_string($value) || is_numeric($value) || is_null($value)) {
                $flattened[$key] = (string) ($value ?? '-');
            }
        }
        $templateProcessor->setValues($flattened);

        $filename = $outputFilename
            ?? $customer->kode_customer . '_' . Str::slug($customer->nama_lengkap) . '.docx';

        $tempFile = tempnam(sys_get_temp_dir(), 'docgen_');
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    public static function generateDocxWithBlock(
        string $templatePath,
        Customer $customer,
        string $blockName,
        array $blockData,
        array $extraValues = [],
        ?string $outputFilename = null
    ) {
        $context = DocumentDataContext::getAllForCustomer($customer);
        $context = array_merge($context, $extraValues);

        $templateProcessor = new TemplateProcessor($templatePath);

        $flattened = [];
        foreach ($context as $key => $value) {
            if (is_string($value) || is_numeric($value) || is_null($value)) {
                $flattened[$key] = (string) ($value ?? '-');
            }
        }
        $templateProcessor->setValues($flattened);

        $templateProcessor->cloneBlock($blockName, 0, true, false, $blockData);

        $filename = $outputFilename
            ?? $customer->kode_customer . '_' . Str::slug($customer->nama_lengkap) . '.docx';

        $tempFile = tempnam(sys_get_temp_dir(), 'docgen_');
        $templateProcessor->saveAs($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
