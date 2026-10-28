<?php
namespace App\Utils;

use ZipArchive;

/**
 * Lightweight native PHP XLSX generator without heavy external dependencies.
 * Produces valid OpenXML (.xlsx) files with formatting, multiple sheets, and styles.
 */
class SimpleXLSXWriter
{
    private array $sheets = [];

    public function addSheet(string $sheetName, array $headers, array $rows, array $colWidths = []): void
    {
        $this->sheets[] = [
            'name' => $sheetName,
            'headers' => $headers,
            'rows' => $rows,
            'widths' => $colWidths
        ];
    }

    public function writeToFile(string $filename): bool
    {
        $zip = new ZipArchive();
        if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return false;
        }

        // [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $contentTypes .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' . "\n";
        $contentTypes .= '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' . "\n";
        $contentTypes .= '<Default Extension="xml" ContentType="application/xml"/>' . "\n";
        $contentTypes .= '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' . "\n";
        $contentTypes .= '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' . "\n";
        foreach ($this->sheets as $idx => $s) {
            $sheetId = $idx + 1;
            $contentTypes .= "<Override PartName=\"/xl/worksheets/sheet{$sheetId}.xml\" ContentType=\"application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml\"/>\n";
        }
        $contentTypes .= '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // _rels/.rels
        $rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $rootRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
        $rootRels .= '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' . "\n";
        $rootRels .= '</Relationships>';
        $zip->addFromString('_rels/.rels', $rootRels);

        // xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wbRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
        $wbRels .= '<Relationship Id="rIdStyles" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' . "\n";
        foreach ($this->sheets as $idx => $s) {
            $sheetId = $idx + 1;
            $relId = 'rId' . ($sheetId);
            $wbRels .= "<Relationship Id=\"{$relId}\" Type=\"http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet\" Target=\"worksheets/sheet{$sheetId}.xml\"/>\n";
        }
        $wbRels .= '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // xl/styles.xml
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $styles .= '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . "\n";
        $styles .= '<fonts count="3">' . "\n";
        $styles .= '<font><sz val="11"/><name val="Calibri"/></font>' . "\n"; // 0: Normal
        $styles .= '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>' . "\n"; // 1: Header bold white
        $styles .= '<font><b/><sz val="12"/><name val="Calibri"/></font>' . "\n"; // 2: Bold
        $styles .= '</fonts>' . "\n";
        $styles .= '<fills count="3">' . "\n";
        $styles .= '<fill><patternFill patternType="none"/></fill>' . "\n";
        $styles .= '<fill><patternFill patternType="gray125"/></fill>' . "\n";
        $styles .= '<fill><patternFill patternType="solid"><fgColor rgb="FF1E3A8A"/></patternFill></fill>' . "\n"; // 2: Deep blue header
        $styles .= '</fills>' . "\n";
        $styles .= '<borders count="2">' . "\n";
        $styles .= '<border><left/><right/><top/><bottom/></border>' . "\n";
        $styles .= '<border><left style="thin"><color rgb="FFD1D5DB"/></left><right style="thin"><color rgb="FFD1D5DB"/></right><top style="thin"><color rgb="FFD1D5DB"/></top><bottom style="thin"><color rgb="FFD1D5DB"/></bottom></border>' . "\n";
        $styles .= '</borders>' . "\n";
        $styles .= '<cellXfs count="3">' . "\n";
        $styles .= '<xf numFmtId="0" fontId="0" fillId="0" borderId="1" xfId="0"/>' . "\n"; // 0: Default data cell
        $styles .= '<xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>' . "\n"; // 1: Header
        $styles .= '<xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0" applyFont="1"/>' . "\n"; // 2: Bold cell
        $styles .= '</cellXfs>' . "\n";
        $styles .= '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // xl/workbook.xml
        $wb = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wb .= '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' . "\n";
        $wb .= '<sheets>' . "\n";
        foreach ($this->sheets as $idx => $s) {
            $sheetId = $idx + 1;
            $relId = 'rId' . ($sheetId);
            $cleanName = htmlspecialchars($s['name'], ENT_XML1, 'UTF-8');
            $wb .= "<sheet name=\"{$cleanName}\" sheetId=\"{$sheetId}\" r:id=\"{$relId}\"/>\n";
        }
        $wb .= '</sheets>' . "\n";
        $wb .= '</workbook>';
        $zip->addFromString('xl/workbook.xml', $wb);

        // Worksheets
        foreach ($this->sheets as $idx => $s) {
            $sheetId = $idx + 1;
            $sheetXml = $this->buildSheetXml($s);
            $zip->addFromString("xl/worksheets/sheet{$sheetId}.xml", $sheetXml);
        }

        $zip->close();
        return true;
    }

    private function buildSheetXml(array $sheet): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . "\n";

        // Columns width
        if (!empty($sheet['widths'])) {
            $xml .= '<cols>' . "\n";
            foreach ($sheet['widths'] as $cIdx => $w) {
                $colNum = $cIdx + 1;
                $xml .= "<col min=\"{$colNum}\" max=\"{$colNum}\" width=\"{$w}\" customWidth=\"1\"/>\n";
            }
            $xml .= '</cols>' . "\n";
        }

        $xml .= '<sheetData>' . "\n";
        $rowNum = 1;

        // Headers
        if (!empty($sheet['headers'])) {
            $xml .= "<row r=\"{$rowNum}\" ht=\"28\" customHeight=\"1\">\n";
            foreach ($sheet['headers'] as $colIdx => $h) {
                $cellRef = $this->colIndexToLetter($colIdx) . $rowNum;
                $val = htmlspecialchars((string)$h, ENT_XML1, 'UTF-8');
                $xml .= "<c r=\"{$cellRef}\" t=\"inlineStr\" s=\"1\"><is><t>{$val}</t></is></c>\n";
            }
            $xml .= "</row>\n";
            $rowNum++;
        }

        // Data Rows
        foreach ($sheet['rows'] as $r) {
            $xml .= "<row r=\"{$rowNum}\" ht=\"20\" customHeight=\"1\">\n";
            foreach ($r as $colIdx => $val) {
                $cellRef = $this->colIndexToLetter($colIdx) . $rowNum;
                if (is_numeric($val) && !str_starts_with((string)$val, '0')) {
                    $xml .= "<c r=\"{$cellRef}\" s=\"0\"><v>{$val}</v></c>\n";
                } else {
                    $cleanVal = htmlspecialchars((string)$val, ENT_XML1, 'UTF-8');
                    $xml .= "<c r=\"{$cellRef}\" t=\"inlineStr\" s=\"0\"><is><t>{$cleanVal}</t></is></c>\n";
                }
            }
            $xml .= "</row>\n";
            $rowNum++;
        }

        $xml .= '</sheetData>' . "\n";
        $xml .= '</worksheet>';
        return $xml;
    }

    private function colIndexToLetter(int $colIndex): string
    {
        $letter = '';
        while ($colIndex >= 0) {
            $letter = chr($colIndex % 26 + 65) . $letter;
            $colIndex = intdiv($colIndex, 26) - 1;
        }
        return $letter;
    }
}
