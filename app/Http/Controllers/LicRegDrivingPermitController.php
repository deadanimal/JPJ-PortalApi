<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LicRegDrivingPermitController extends Controller
{
    // https://mobile.jpj.gov.my/jpj-revamp-svc-pvrws/lic_reg_drivingpermit_inq/LicRegDrivingpermitInq.wsdl

    public function submit(){
        $nokp = '971101036398';
        $noplate = 'VHA4667';
        $kategori = '1';

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_reg_drivingpermit_inq";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_reg_drivingpermit_inq/">
        <soapenv:Header/>
        <soapenv:Body>
           <lic:vehicleLicInfoByvehRegno>
              <!--Optional:-->
              <reqInfo>
                 <icno>'.$nokp.'</icno>
                 <vehicleRegno>'.strtoupper($noplate).'</vehicleRegno>
                 <category>'.$kategori.'</category>
              </reqInfo>
           </lic:vehicleLicInfoByvehRegno>
        </soapenv:Body>
     </soapenv:Envelope>';
                                      

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_reg_drivingpermit_inq/",
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
// dd($response);
        $doc = new \DOMDocument();
// dd($doc);
        $doc->loadXML($response);
        // dd($doc);

        $nama = $doc->getElementsByTagName('name')->item(0)->nodeValue;
        $nokp = $doc->getElementsByTagName('icno')->item(0)->nodeValue;
        $kategori = $doc->getElementsByTagName('category')->item(0)->nodeValue;
        $bil = $doc->getElementsByTagName('vehLicInsurance')->length;
        $i = 0;


        $i = 0;
        while ($i < $bil) {
            $insurance = $doc->getElementsByTagName('vehLicInsurance')->item($i)->nodeValue;
            $dateOfCom = $doc->getElementsByTagName('dateOfCommencement')->item($i)->nodeValue;
            $expired = $doc->getElementsByTagName('expiryDate')->item($i)->nodeValue;
            $k[] = array("vehicle_insurance" => $insurance, "date_of_commencement" => $dateOfCom, "expired" => $expired);
            $i++;
        }

        $status_code = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
        $status_msg = $doc->getElementsByTagName('statusMsg')->item(0)->nodeValue;
        $status[] = array("status_code" => $status_code, "status_message" => $status_msg);

        // $userObj->lesen = $k;
        // echo json_encode($userObj);
        return response()->json([
            'status' => 200,
            'user' => $nama,
            'nokp' => $nokp,
            'kategori' => $kategori,
            'bil' => $bil,
            'vehicle_info' => $k,
            'status' => $status,
        ]);
    }
}
