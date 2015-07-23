<?php

$format = isset($_REQUEST["format"]) ? $_REQUEST["format"] : "xml";
if (!in_array($format, array("xml", "fo", "pdf"))) {
    throw new Exception("Unknown format $format", 450);
}
//1. Get source xml using curl
$ch = curl_init();
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$url = "http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml";
curl_setopt($ch, CURLOPT_URL, $url);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//check http response code
if ($code != 200) {
    throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
}

$headers = array("Content-type: application/xml");
if ($format == "fo" || $format == "pdf") {
    //2. Transform source xml to fo
    $xmldom = new DOMDocument();
    $xmldom->loadXML($res);
    $xsldom = new DomDocument();
    $xsldom->load("eurofxref-daily.xsl");
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsldom);
    $res = $proc->transformToXML($xmldom);
    if ($format == "pdf") {
        //3. Transform fo to pdf
        //fop servlet url
        $url = "http://tomcat-bystrobank.rhcloud.com/fopservlet/fopservlet";
        curl_setopt($ch, CURLOPT_URL, $url);
        //specify mime-type of source data
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
        //post contents - fo souce
        curl_setopt($ch, CURLOPT_POSTFIELDS, $res);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        //check http response code
        if ($code != 200) {
            throw new Exception($res . PHP_EOL . $url . " " . curl_error($ch), 450);
        }
        $attachmentName = "eurofxref-daily.pdf";
        $headers = array(
            "Content-Type: application/pdf",
            "Content-Disposition: inline; filename*=UTF-8''" . $attachmentName
        );
    }
}
curl_close($ch);
foreach ($headers as $h) {
    header($h);
}
echo $res;
