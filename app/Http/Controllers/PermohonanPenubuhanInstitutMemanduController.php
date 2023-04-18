<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PermohonanPenubuhanInstitutMemanduController extends Controller
{
    public function submit()
    {
        $applicationNo = '040120220411003';

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_inquiry_application_permit";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.example.org/lic_inquiry_application_permit/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                    <lic:inquiryPermitApplicationStatus>
                                        <!--Optional:-->
                                        <applicationNo>' . $applicationNo . '</applicationNo>
                                    </lic:inquiryPermitApplicationStatus>
                                </soapenv:Body>
                            </soapenv:Envelope>';


        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_inquiry_application_permit/",
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

        $statusCode = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
        $statusMsg = $doc->getElementsByTagName('statusMsg')->item(0)->nodeValue;

        $application_id = $doc->getElementsByTagName('licpaApplId')->item(0)->nodeValue;
        $application_type = $doc->getElementsByTagName('licpaApplType')->item(0)->nodeValue;
        $current_stage = $doc->getElementsByTagName('licpaCurrentStage')->item(0)->nodeValue;
        $current_status = $doc->getElementsByTagName('licpaCurrentTxnStatus')->item(0)->nodeValue;
        $name = $doc->getElementsByTagName('licpaName')->item(0)->nodeValue;
        $entity_id = $doc->getElementsByTagName('licpaEntityId')->item(0)->nodeValue;

        return response()->json([
            'status' => 200,
            'statusCode' => $statusCode,
            'statusMsg' => $statusMsg,
            'application_id' => $application_id,
            'application_type' => $application_type,
            'current_stage' => $current_stage,
            'current_status' => $current_status,
            'name' => $name,
            'entity_id' => $entity_id,
        ]);
    }
}
