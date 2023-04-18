<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SemakanSenaraiHitamController extends Controller
{
    public function submit()
    {
        $icno = '820819025542';
        $vehicleRegno = 'KDW7555';

        $bltype = 'VEH';
        $blcat = 'JPJ';

        if ($bltype == 'VEH') {
            if ($blcat == 'JPJ') {
                $blkListType = '1';
            } elseif($blcat == 'AGENCY') {
                $blkListType = '3';
            }
            
        } elseif ($bltype == 'LIC') {
            $blkListType = '2';
        }

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/enf_jpjblacklist_public";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:enf="http://www.gov.jpj.org/enf_jpjblacklist_public/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                    <enf:checkBlackListStatusInfo>
                                        <!--Optional:-->
                                        <icno>' . $icno . '</icno>
                                        <!--Optional:-->
                                        <vehicleRegno>' . $vehicleRegno . '</vehicleRegno>
                                        <!--Optional:-->
                                        <blkListType>' . $blkListType . '</blkListType>
                                    </enf:checkBlackListStatusInfo>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/enf_jpjblacklist_public/",
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
        $vehicleBlackListStatus = $doc->getElementsByTagName('vehicleBlackListStatus')->item(0)->nodeValue;
        $licBlackListStatus = $doc->getElementsByTagName('licBlackListStatus')->item(0)->nodeValue;
        $agencyBlackListStatus = $doc->getElementsByTagName('agencyBlackListStatus')->item(0)->nodeValue;


        if ($agencyBlackListStatus != null && $agencyBlackListStatus == '1') {
            $message = 'Terdapat rekod senarai hitam pada nombor rujukan pemilik ini (Senarai Hitam PDRM).';
        }
        elseif ($licBlackListStatus != null && $licBlackListStatus == '1') {
            $message = 'Lesen telah disenarai hitam.';
        }
        elseif ($vehicleBlackListStatus != null && $vehicleBlackListStatus == '1') {
            $message = 'Kenderaan ini telah disenarai hitam.';
        }
        else {
            $message = 'Tiada rekod senarai hitam.';
        }


        return response()->json([
            'status' => 200,
            'icno' => $icno,
            'vehicleRegno' => $vehicleRegno,
            'message' => $message
        ]);
    }
}
