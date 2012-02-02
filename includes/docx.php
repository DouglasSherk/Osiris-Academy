<?php

function odt2text($filename) {
    return readZippedXML($filename, "content.xml");
}

function docx2text($filename) {
    return readZippedXML($filename, "word/document.xml");
}

function readZippedXML($archiveFile, $dataFile) {
    // Create new ZIP archive
    $zip = new ZipArchive;

    // Open received archive file
    if (true === $zip->open($archiveFile)) {
        // If done, search for the data file in the archive
        if (($index = $zip->locateName($dataFile)) !== false) {
            // If found, read it to the string
            $data = $zip->getFromIndex($index);
            // Close archive file
            $zip->close();
            // Load XML from a string
            // Skip errors and warnings
            $xml = DOMDocument::loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            // Return data without XML formatting tags
            return strip_tags($xml->saveXML());
        }
        $zip->close();
    }

    // In case of failure return empty string
    return "";
}

?>