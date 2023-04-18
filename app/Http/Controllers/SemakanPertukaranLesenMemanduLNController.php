<?php

namespace App\Http\Controllers;

use App\Mail\CheckEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SemakanPertukaranLesenMemanduLNController extends Controller
{
    public function submit()
    {
        $icno = 'EF7200114CHN';
        $category = '9';
        
        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_inq_con_foreign_lic";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_inq_con_foreign_lic/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <lic:findConversionLicInfo>
                                    <!--Optional:-->
                                    <icno>'.strtoupper($icno).'</icno>
                                    <!--Optional:-->
                                    <category>'.$category.'</category>
                                </lic:findConversionLicInfo>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_inq_con_foreign_lic/",
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
        $category = $doc->getElementsByTagName('category')->item(0)->nodeValue;
        $name = $doc->getElementsByTagName('name')->item(0)->nodeValue;

        $statusCode = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
        $statusMsg = $doc->getElementsByTagName('statusMsg')->item(0)->nodeValue;

        $bil = $doc->getElementsByTagName('licType')->length;
        $i = 0;
        while ($i < $bil) {
            $licType = $doc->getElementsByTagName('licType')->item($i)->nodeValue;
            $licClass = $doc->getElementsByTagName('licClass')->item($i)->nodeValue;
            $applyDate = $doc->getElementsByTagName('applyDate')->item($i)->nodeValue;
            $approveDate = $doc->getElementsByTagName('approveDate')->item($i)->nodeValue;
            $expiryDate = $doc->getElementsByTagName('expiryDate')->item($i)->nodeValue;
            $appStatus = $doc->getElementsByTagName('appStatus')->item($i)->nodeValue;
            $applId = $doc->getElementsByTagName('applId')->item($i)->nodeValue;

            $applInfo[] = array(
                "licType" => $licType, 
                "licClass" => $licClass,
                "applyDate" => $applyDate,
                "approveDate" => $approveDate,
                "expiryDate" => $expiryDate,
                "appStatus" => $appStatus,
                "applId" => $applId,
            );

            $i++;
        }
        
        return response()->json([
            "icno" => $icno, 
            "category" => $category,
            "name" => $name,
            "statusCode" => $statusCode,
            "statusMsg" => $statusMsg,
            "applInfo" => $applInfo,
        ]);
    }

    public function checkemail()
    {
        Mail::to('najhan.mnajib@gmail.com')->send(new CheckEmail());
        dd('chcek');
    }
}
