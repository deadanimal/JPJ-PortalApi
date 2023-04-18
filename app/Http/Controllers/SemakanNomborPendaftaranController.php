<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SemakanNomborPendaftaranController extends Controller
{
    public function submit(){
        $areaCode = 'C';
        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/vel_inq_current_regn_number";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:vel="http://www.gov.jpj.org/vel_inq_current_regn_number/">
        <soapenv:Header/>
        <soapenv:Body>
           <vel:findLatestRegnNumberByAreaCode>
              <!--Optional:-->
              <areaCode>'.$areaCode.'</areaCode>
           </vel:findLatestRegnNumberByAreaCode>
        </soapenv:Body>
     </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/vel_inq_current_regn_number/",
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
        // dd($doc);
        $areaName = $doc->getElementsByTagName('areaName')->item(0)->nodeValue;
        $regnNo = $doc->getElementsByTagName('regnNo')->item(0)->nodeValue;
        
        return response()->json([
            'status' => 200,
            'areaName' => $areaName,
            'regnNo' => $regnNo
        ]);
    }
}
