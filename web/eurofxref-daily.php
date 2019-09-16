<?php

$format = isset($_REQUEST["format"]) ? $_REQUEST["format"] : "xml";
if (!in_array($format, array("xml", "fo", "pdf"))) {
    throw new Exception("Unknown format $format", 450);
}

$headers = array();
$output = null;
$erofxref = new Eurofxref();
$ratesxml = $erofxref->getRates();


switch ($format) {
    case "fo":
        $headers = array("Content-type: application/xml");
        $output = $erofxref->transformFo($ratesxml);
        break;
    case "pdf":
        $attachmentName = "eurofxref-daily.pdf";
        $headers = array(
            "Content-Type: application/pdf",
            "Content-Disposition: inline; filename*=UTF-8''" . $attachmentName
        );
        $fo = $erofxref->transformFo($ratesxml);
        $output = $erofxref->transformPdf($fo);
        break;
    default:
        $output = $ratesxml;
}



foreach ($headers as $h) {
    header($h);
}
echo $output;

class Eurofxref {

    function getRatesRemote() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
        curl_setopt($ch, CURLOPT_URL, $url);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
        }
        curl_close($ch);
    }
    function getRates() {
        return file_get_contents("eurofxref-daily.xml");
    }

    function transformFo($xml) {
        $xmldom = new DOMDocument();
        $xmldom->loadXML($xml);
        $xsldom = new DomDocument();
        $xsldom->load("eurofxref-daily.xsl");
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsldom);
        $res = $proc->transformToXML($xmldom);
        return $res;
    }

    function transformPdf($xml) {
        $url = "http://www.demo.ilb.ru/fopservlet/fopservlet";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if ($code != 200) {
            throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
        }
        curl_close($ch);
        return $res;
    }

}
