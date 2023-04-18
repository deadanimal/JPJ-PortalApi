<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DrivingLicenseController extends Controller
{
    public function submit(){
        $nokp = '980410025195';
        $kategori = '1';

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_appeal_expired_drivinglicense";

        // $soapUser = "username";  //  username
        // $soapPassword = "password"; // password
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                     <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_appeal_expired_drivinglicense/">
                                         <soapenv:Header/>
                                         <soapenv:Body> <lic:findDrivingLicenseExpDate> <icno>' . strtoupper($nokp) . '</icno>
                                         <category>' . $kategori . '</category>
                                         </lic:findDrivingLicenseExpDate>
                                         </soapenv:Body>
                                      </soapenv:Envelope>'; // data from the form, e.g. some ID number

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
        // dd($doc);

        $nama = $doc->getElementsByTagName('name')->item(0)->nodeValue;
        $nokp = $doc->getElementsByTagName('icno')->item(0)->nodeValue;
        $kategori = $doc->getElementsByTagName('category')->item(0)->nodeValue;
        $bil = $doc->getElementsByTagName('licType')->length;
        $i = 0;


        $i = 0;
        while ($i < $bil) {
            $jenis_lesen = $doc->getElementsByTagName('licType')->item($i)->nodeValue;
            $expired = $doc->getElementsByTagName('expiryDate')->item($i)->nodeValue;
            $k[] = array("jenis_lesen" => $jenis_lesen, "tempoh_tamat" => $expired);
            $i++;
        }


        // $userObj->lesen = $k;
        // echo json_encode($userObj);
        return response()->json([
            'status' => 200,
            'user' => $nama,
            'nokp' => $nokp,
            'bil' => $bil,
            'lesen' => $k,
        ]);
    }
}
