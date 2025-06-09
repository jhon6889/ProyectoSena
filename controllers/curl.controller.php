    <?php

    class CurlController {

    static public function request($url, $method, $fields) {  
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'api.lte.com/'.$url,
            CURLOPT_RETURNTRANSFER => true, 
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Jj@mes6889'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response); 
        return $response;
    }
    }


