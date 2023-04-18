<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SemakanPermohonanRayuanLesenController extends Controller
{
    public function semak_status()
    {
        $icno = '800810125602';
        $licType = 'GDL';
        $category = '1';

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_appeal_expired_drivinglicense";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <lic:findExpiredDrivingLicInfo>
                                    <!--Optional:-->
                                    <icno>' . $icno . '</icno>
                                    <!--Optional:-->
                                    <licType>' . $licType . '</licType>
                                    <!--Optional:-->
                                    <category>' . $category . '</category>
                                </lic:findExpiredDrivingLicInfo>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $url = $soapUrl;

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);

        curl_close($ch);

        $doc = new \DOMDocument();

        $doc->loadXML($response);

        $icno = $doc->getElementsByTagName('icno')->item(0)->nodeValue;
        $name = $doc->getElementsByTagName('name')->item(0)->nodeValue;
        $category = $doc->getElementsByTagName('category')->item(0)->nodeValue;
        $lictype = $doc->getElementsByTagName('lictype')->item(0)->nodeValue;
        $effectiveDate = $doc->getElementsByTagName('effectiveDate')->item(0)->nodeValue;
        $expiryDate = $doc->getElementsByTagName('expiryDate')->item(0)->nodeValue;
        $classType = $doc->getElementsByTagName('classType')->item(0)->nodeValue;
        $licStatus = $doc->getElementsByTagName('licStatus')->item(0)->nodeValue;

        $statusCode = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
        $statusMsg = $doc->getElementsByTagName('statusMsg')->item(0)->nodeValue;


        return response()->json([
            "icno" => $icno,
            "name" => $name,
            "category" => $category,
            "lictype" => $lictype,
            "effectiveDate" => $effectiveDate,
            "expiryDate" => $expiryDate,
            "classType" => $classType,
            "licStatus" => $licStatus,
            "statusCode" => $statusCode,
            "statusMsg" => $statusMsg,
        ]);
    }

    public function cetak_surat()
    {
        $icno = '800810125602';
        $licType = 'GDL';
        $category = '1';

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_appeal_expired_drivinglicense";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <lic:appealPrintLetter>
                                    <!--Optional:-->
                                    <icno>'.$icno.'</icno>
                                    <!--Optional:-->
                                    <category>'.$category.'</category>
                                    <!--OptionAl:-->
                                    <licType>'.$licType.'</licType>
                                </lic:appealPrintLetter>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $url = $soapUrl;

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);

        curl_close($ch);

        $doc = new \DOMDocument();

        $doc->loadXML($response);

        $fileName = $doc->getElementsByTagName('fileName')->item(0)->nodeValue;
        $fileSizeBytes = $doc->getElementsByTagName('fileSizeBytes')->item(0)->nodeValue;

        $statusCode = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
        $statusMsg = $doc->getElementsByTagName('statusMsg')->item(0)->nodeValue;


        return response()->json([
            "fileName" => $fileName,
            "fileSizeBytes" => $fileSizeBytes,
            "statusCode" => $statusCode,
            "statusMsg" => $statusMsg,
        ]);
    }

    public function permohonan()
    {
        $icno = '800810125602';
        $licType = 'GDL';
        $category = '1';
        $failedReason = 'INVALID';

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_appeal_expired_drivinglicense";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <lic:createExpiredDrivingLicense>
                                    <!--Optional:-->
                                    <reqInfo>
                                        <category>'.$category.'</category>
                                        <icno>'.$icno.'</icno>
                                        <failedReason>'.$failedReason.'</failedReason>
                                        <licType>'.$licType.'</licType>
                                    </reqInfo>
                                </lic:createExpiredDrivingLicense>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/",
            "Content-length: " . strlen($xml_post_string),
        ); //SOAPAction: your op URL

        $url = $soapUrl;

        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // converting
        $response = curl_exec($ch);

        curl_close($ch);

        $doc = new \DOMDocument();

        $doc->loadXML($response);

        $fileName = $doc->getElementsByTagName('fileName')->item(0)->nodeValue;
        $fileSizeBytes = $doc->getElementsByTagName('fileSizeBytes')->item(0)->nodeValue;

        $statusCode = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
        $statusMsg = $doc->getElementsByTagName('statusMsg')->item(0)->nodeValue;


        return response()->json([
            "fileName" => $fileName,
            "fileSizeBytes" => $fileSizeBytes,
            "statusCode" => $statusCode,
            "statusMsg" => $statusMsg,
        ]);
    }
}
