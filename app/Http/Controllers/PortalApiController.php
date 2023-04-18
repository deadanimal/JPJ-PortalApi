<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalApiController extends Controller
{
    public function semakan_tarikh_luput_lesen_kenderaan_motor(Request $request)
    {
        $nokp = $request->nokp;
        $noplate = $request->noplate;
        $kategori = $request->kategori;

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_reg_drivingpermit_inq";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.gov.jpj.org/lic_reg_drivingpermit_inq/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <lic:vehicleLicInfoByvehRegno>
                                    <!--Optional:-->
                                    <reqInfo>
                                        <icno>' . $nokp . '</icno>
                                        <vehicleRegno>' . strtoupper($noplate) . '</vehicleRegno>
                                        <category>' . $kategori . '</category>
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

        $doc = new \DOMDocument();

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

    public function semakan_nombor_pendaftaran(Request $request)
    {
        try {
            $areaCode = $request->areaCode;
            $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/vel_inq_current_regn_number";

            $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                                    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:vel="http://www.gov.jpj.org/vel_inq_current_regn_number/">
                                    <soapenv:Header/>
                                    <soapenv:Body>
                                    <vel:findLatestRegnNumberByAreaCode>
                                        <!--Optional:-->
                                        <areaCode>' . $areaCode . '</areaCode>
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
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 980410,
                'message' => 'Salah mat'
            ]);
        }
        
    }

    public function semakan_status_permohonan_penubuhan_institut_memandu(Request $request)
    {
        $applicationNo = $request->applicationNo;

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

    // public function semakan_permohonan_rayuan_lesen_memandu_tamat_tempoh(Request $request)
    // {
    //     # code...
    // }

    public function semak_status_permohonan(Request $request)
    {
        $icno = $request->icno;
        $licType = $request->licType;
        $category = $request->category;

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

    public function cetak_surat_permohonan(Request $request)
    {
        $icno = $request->icno;
        $licType = $request->licType;
        $category = $request->category;

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

    public function permohonan_rayuan(Request $request)
    {
        $icno = $request->icno;
        $licType = $request->licType;
        $category = $request->category;
        $failedReason = $request->failedReason;

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

    // above are for the semakan permohonan/rayuan lesen tamat tempoh api

    public function semakan_tarikh_luput_lesen_memandu(Request $request)
    {
        $nokp = $request->nokp;
        $kategori = $request->kategori;

        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_appeal_expired_drivinglicense";

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

        return response()->json([
            'status' => 200,
            'user' => $nama,
            'nokp' => $nokp,
            'bil' => $bil,
            'lesen' => $k,
        ]);
    }

    public function semakan_status_senarai_hitam(Request $request)
    {
        $icno = $request->icno;
        $vehicleRegno = $request->vehicleRegno;
        $bltype = $request->bltype;
        $blcat = $request->blcat;

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

    public function semakan_dimerit(Request $request)
    {
        $icno = $request->icno;
        $category = $request->category;
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

    public function semakan_pertukaran_lesen_memandu_luar_negara(Request $request)
    {
        $icno = $request->icno;
        $category = $request->category;
        
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

    public function semakan_ujian_memandu(Request $request)
    {
        $icNo = $request->icNo;
        $category = $request->category;
        $soapUrl = "https://mobile.jpj.gov.my:443/jpj-revamp-svc-pvr-ws/lic_inquiry_testresult";

        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:lic="http://www.example.org/lic_inquiry_testresult/">
                                <soapenv:Header/>
                                <soapenv:Body>
                                <lic:inquiryTestResult>
                                    <!--Optional:-->
                                    <icNo>'.$icNo.'</icNo>
                                    <!--Optional:-->
                                    <category>'.$category.'</category>
                                </lic:inquiryTestResult>
                                </soapenv:Body>
                            </soapenv:Envelope>';

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: http://www.gov.jpj.org/lic_inquiry_testresult/",
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

        $bil_theory = $doc->getElementsByTagName('theoryTestResultInfo')->length;
        $i = 0;
        while ($i < $bil_theory) {
            $testDate = $doc->getElementsByTagName('testDate')->item($i)->nodeValue;
            $testType = $doc->getElementsByTagName('testType')->item($i)->nodeValue;
            $testVenue = $doc->getElementsByTagName('testVenue')->item($i)->nodeValue;
            $testResult = $doc->getElementsByTagName('testResult')->item($i)->nodeValue;
            $theoryTestMarks = $doc->getElementsByTagName('theoryTestMarks')->item($i)->nodeValue;
            $overAllResult = $doc->getElementsByTagName('overAllResult')->item($i)->nodeValue;
            $expiryDate = $doc->getElementsByTagName('expiryDate')->item($i)->nodeValue;
            $statusVerification = $doc->getElementsByTagName('statusVerification')->item($i)->nodeValue;
            $testCode = $doc->getElementsByTagName('testCode')->item($i)->nodeValue;
            $licenseType = $doc->getElementsByTagName('licenseType')->item($i)->nodeValue;
            $classType = $doc->getElementsByTagName('classType')->item($i)->nodeValue;

            $theory_test[] = array(
                "testDate" => $testDate, 
                "testType" => $testType,
                "testVenue" => $testVenue,
                "testResult" => $testResult,
                "theoryTestMarks" => $theoryTestMarks,
                "overAllResult" => $overAllResult,
                "expiryDate" => $expiryDate,
                "statusVerification" => $statusVerification, 
                "testCode" => $testCode,
                "licenseType" => $licenseType,
                "classType" => $classType,
            );

            $i++;
        }

        $bil_prac = $doc->getElementsByTagName('practicalTestResultInfo')->length;
        $j = 0;
        while ($j < $bil_prac) {
            $testDate = $doc->getElementsByTagName('testDate')->item($j)->nodeValue;
            $testType = $doc->getElementsByTagName('testType')->item($j)->nodeValue;
            $licenseType = $doc->getElementsByTagName('licenseType')->item($j)->nodeValue;
            $licenseClass = $doc->getElementsByTagName('licenseClass')->item($j)->nodeValue;
            $usageCode = $doc->getElementsByTagName('usageCode')->item($j)->nodeValue;
            $testVenue = $doc->getElementsByTagName('testVenue')->item($j)->nodeValue;
            $overAllResult = $doc->getElementsByTagName('overAllResult')->item($j)->nodeValue;
            $expiryDate = $doc->getElementsByTagName('expiryDate')->item($j)->nodeValue;
            $testCode = $doc->getElementsByTagName('testCode')->item($j)->nodeValue;

            $practical_test[] = array(
                "testDate" => $testDate, 
                "testType" => $testType,
                "licenseType" => $licenseType,
                "licenseClass" => $licenseClass,
                "usageCode" => $usageCode,
                "testVenue" => $testVenue,
                "overAllResult" => $overAllResult,
                "expiryDate" => $expiryDate,
                "testCode" => $testCode,
            );

            $j++;
        }

        return response()->json([
            'status' => 200,
            'icno' => $icno,
            'category' => $category,
            'name' => $name,
            'theory_test' => $theory_test,
            'practical_test' => $practical_test,
        ]);
    }
}
