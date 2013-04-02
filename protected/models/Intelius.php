<?php
class Intelius
{
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $password;
    /**
     * @var WebRequest
     */
    private $webRequest;
    /**
     * @var bool
     */
    private $loggedIn = false;

    /**
     * @param string $username
     * @param string $password
     */
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->webRequest = new WebRequest();
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $cityState
     * @return array
     */
    public function searchByName($firstName, $lastName, $cityState)
    {
        $this->login();
        $response = $this->webRequest->get('https://iservices.intelius.com/premier/search.php?' . http_build_query(array(
            'componentId' => '1',
            'qf' => $firstName,
            'qn' => $lastName,
            'qcs' => $cityState,
        )));
        return $this->processSearchResults($response);
    }

    /**
     * @param int $areaCode
     * @param int $prefix
     * @param int $exchange
     * @return array
     */
    public function searchByPhone($areaCode, $prefix, $exchange)
    {
        $this->login();
        $response = $this->webRequest->get('https://iservices.intelius.com/premier/search.php?' . http_build_query(array(
            'componentId' => '1',
            'qnpa' => $areaCode,
            'qnxx' => $prefix,
            'qstation' => $exchange,
        )));
        return $this->processSearchResults($response);

    }

    /**
     * @param string $email
     * @return array
     */
    public function searchByEmail($email)
    {
        $this->login();
        $response = $this->webRequest->get('https://iservices.intelius.com/premier/search.php?' . http_build_query(array(
            'componentId' => '1',
            'qee' => $email,
        )));
        return $this->processSearchResults($response);
    }

    /**
     * @throws Exception
     */
    private function login()
    {
        if ($this->loggedIn) {
            return;
        }
        $response = $this->webRequest->get('https://iservices.intelius.com/premier/dashboard.php');
        if (!preg_match("/<input name='(.*?)' id='(.*?)' value='(.*?)' type='hidden' \\/>/", $response, $match)) {
            throw new Exception();
        }
        $this->webRequest->post('https://iservices.intelius.com/premier/dashboard.php', array(
            'email' => $this->username,
            'password' => $this->password,
            $match[1] => $match[3],
        ));
        $this->loggedIn = true;
    }

    /**
     * @param string $response
     * @return array
     */
    private function processSearchResults($response)
    {
        if (!preg_match_all('/<div class="identity">.*?<a.*?href="(.*?)".*?>(.*?)<\/a>.*?<p>.*?Age: <strong>(.*?)<\/strong>/s', $response, $matches)) {
            return array();
        }
        $data = array();
        for ($i = 0; $i < count($matches[0]); $i++) {
            $name = $this->processName($matches[2][$i]);
            $data[] = array(
                'profileUrl' => $matches[1][$i],
                'firstName' => $name['firstName'],
                'lastName' => $name['lastName'],
                'age' => $matches[3][$i],
            );
        }
        return $data;
    }

    public function processProfile($profileUrl)
    {
        $this->login();
        $data = array(
            'profileUrl' => $profileUrl,
        );
        $response = $this->webRequest->get($profileUrl);
        if (preg_match('/<span class="name">(.*?)<\/span>/', $response, $match)) {
            $name = $this->processName(preg_replace('/\s+/', ' ', $match[1]));
            $data['firstName'] = $name['firstName'];
            $data['lastName'] = $name['lastName'];
        }
        if (preg_match('/<p>Age: <strong>(.*?)<\/strong><\/p>/', $response, $match)) {
            $data['age'] = $match[1];
        }
//        if (preg_match('/<strong>Phone<\/strong>.*?<ul>(.*?)<\/ul>/s', $response, $match)) {
//            $phonesResponse = $match[1];
//            if (preg_match_all('/<li>(.*?)<\/li>/s', $phonesResponse, $matches)) {
//                for ($i = 0; $i < count($matches[0]); $i++) {
//                    if (!is_null($phone = $this->processPhone($matches[1][$i]))) {
//                        $data['phones'][] = $phone;
//                    }
//                }
//            }
//        }
        if (preg_match('/<strong>Email<\/strong>.*?<ul>(.*?)<\/ul>/s', $response, $match)) {
            $emailResponse = $match[1];
            if (preg_match_all('/<li>(.*?)<\/li>/s', $emailResponse, $matches)) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $data['emails'][] = $matches[1][$i];
                }
            }
        }
        if (preg_match("/<ul class='addresses'>(.*?)<\\/ul>/s", $response, $match)) {
            $addressResponse = $match[1];
            if (preg_match_all('/<li.*?><a.*?href="(.*?)".*?>(.*?)<br\/>(.*?)<br\/>(.*?)<img/s', $addressResponse, $matches)) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $data['addresses'][] = CMap::mergeArray(array(
                        'addressUrl' => $matches[1][$i],
                        'line1' => strip_tags($matches[2][$i]),
                        'line2' => $matches[3][$i],
                        'phone' => $this->processPhone($matches[4][$i]),
                    ), $this->processAddressInfo($matches[1][$i]));
                }
            }
        }
        if (preg_match_all('/<a data-clicktracking=\'{"category":"Profile","action":"click","label":"Profile-Profile_Relatives"}\' href="(.*?)">(.*?)<\/a>.*?\(Age: (.*?)\)/s', $response, $matches)) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $name = $this->processName($matches[2][$i]);
                $data['relatives'][] = array(
                    'firstName' => $name['firstName'],
                    'lastName' => $name['lastName'],
                    'age' => $matches[3][$i],
                    'profileUrl' => $matches[1][$i],
                );
            }
        }
        return $data;
    }

    private function processAddressInfo($addressUrl)
    {
        $data = array();
        $response = $this->webRequest->get($addressUrl, true);
        if (preg_match('/<strong>Owner Information<\/strong>.*?<table.*?>.*?' . str_repeat('<td>(.*?)<\/td>.*?<\/tr>.*?', 4) . '<\/table>/s',
            $response, $match)
        ) {
            $fullName = trim($match[1]);
            if (strpos($fullName, '<br/>') !== false) {
                $fullName = preg_replace('/\s+/', ' ', $fullName);
                $fullName = implode(';', explode(' <br/> ', $fullName));
            }
            $data['owner'] = array(
                'fullName' => $fullName,
                'estMarketValue' => $this->processNumber($match[2]),
                'taxAmount' => $this->processNumber($match[3]),
                'taxYear' => $match[4],
            );
        }
        if (preg_match('/<strong>Property Details<\/strong>.*?<table.*?>.*?' . str_repeat('<td>(.*?)<\/td>.*?<\/tr>.*?', 6) . '<\/table>/s',
            $response, $match)
        ) {
            $data['details'] = array(
                'acres' => $match[1],
                'bedrooms' => $match[2],
                'bathrooms' => $match[3],
                'builtYear' => $match[4],
                'landArea' => $this->processNumber($match[5]),
                'livingArea' => $this->processNumber($match[6]),
            );
        }
        if (preg_match('/<strong>Neighbors<\/strong>.*?<table.*?>(.*?)<\/table>/s', $response, $match)) {
            $neighborResponse = $match[1];
            if (preg_match_all('/<td class="c1">(.*?)<\/td>/s', $neighborResponse, $matches)) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $data['neighbors'][] = $matches[1][$i];
                }
            }
        }
        if (preg_match('/<strong>Local Census Data<\/strong>.*?' . str_repeat('<td>(.*?)<\/td>.*?<\/tr>.*?', 3) . '/s',
            $response, $match)
        ) {
            if (preg_match('/^([0-9]{1,2}%) \/ ([0-9]{1,2}%)$/', $match[3], $sexMatch)) {
                $data['censusData'] = array(
                    'households' => $this->processNumber($match[1]),
                    'families' => $this->processPercent($match[2]),
                    'male' => $this->processPercent($sexMatch[1]),
                    'female' => $this->processPercent($sexMatch[2]),
                );
            }
        }
        if (preg_match('/<strong>Education<\/strong>.*?' . str_repeat('<td>(.*?)<\/td>.*?<\/tr>.*?', 3) . '/s',
            $response, $match)
        ) {
            $data['education'] = array(
                'highSchool' => $this->processPercent($match[1]),
                'college' => $this->processPercent($match[2]),
                'graduate' => $this->processPercent($match[3]),
            );
        }
        if (preg_match('/<strong>Income<\/strong>.*?' . str_repeat('<td>(.*?)<\/td>.*?<\/tr>.*?', 11) . '/s',
            $response, $match)
        ) {
            $data['income'] = array(
                'average' => $this->processNumber($match[1]),
                'less_10' => $this->processPercent($match[2]),
                'slice_10_15' => $this->processPercent($match[3]),
                'slice_15_25' => $this->processPercent($match[4]),
                'slice_25_35' => $this->processPercent($match[5]),
                'slice_35_50' => $this->processPercent($match[6]),
                'slice_50_75' => $this->processPercent($match[7]),
                'slice_75_100' => $this->processPercent($match[8]),
                'slice_100_150' => $this->processPercent($match[9]),
                'slice_150_200' => $this->processPercent($match[10]),
                'more_200' => $this->processPercent($match[11]),
            );
        }
        return $data;
    }

    /**
     * @param string $name
     * @return array
     */
    private function processName($name)
    {
        if (substr_count($name, ' ') == 1) {
            $pattern = '/(\S+) (\S+)/';
        } else {
            $pattern = '/(\S+) \S+ (\S+)/';
        }
        if (!preg_match($pattern, $name, $match)) {
            return null;
        }
        return array(
            'firstName' => $match[1],
            'lastName' => $match[2],
        );
    }

    /**
     * @param string $phone
     * @return array
     */
    private function processPhone($phone)
    {
        if (!preg_match('/\(([0-9]{3})\) ([0-9]{3})-([0-9]{4})/', $phone, $match)) {
            return null;
        }
        return array(
            'areaCode' => $match[1],
            'prefix' => $match[2],
            'exchange' => $match[3],
        );
    }

    /**
     * @param string $number
     * @return string
     */
    private function processNumber($number)
    {
        return preg_replace('/\D+/', '', $number);
    }

    private function processPercent($number)
    {
        return $this->processNumber($number) / 100;
    }
}