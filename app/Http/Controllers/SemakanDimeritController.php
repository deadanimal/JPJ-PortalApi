<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SemakanDimeritController extends Controller
{
    public function submit()
    {
        $icno = '920801035599';
        $category = '1';
        
        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/enf_inquiry_demerit";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:enf="http://www.example.org/enf_inquiry_demerit/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <enf:inquiryDemeritPoint>
                                    <!--Optional:-->
                                    <icNo>'.$icno.'</icNo>
                                    <!--Optional:-->
                                    <category>'.$category.'</category>
                                </enf:inquiryDemeritPoint>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/enf_inquiry_demerit/",
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
        $name = $doc->getElementsByTagName('name')->item(0)->nodeValue;
        $idNo = $doc->getElementsByTagName('idNo')->item(0)->nodeValue;
        $category = $doc->getElementsByTagName('category')->item(0)->nodeValue;
        $kejaraPoint = $doc->getElementsByTagName('kejaraPoint')->item(0)->nodeValue;

        return response()->json([
            'status' => 200,
            'name' => $name,
            'idNo' => $idNo,
            'category' => $category,
            'kejaraPoint' => $kejaraPoint
        ]);
    }
}
