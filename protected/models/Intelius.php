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
                    $data['addresses'][] = array(
                        'addressUrl' => $matches[1][$i],
                        'lineOne' => strip_tags($matches[2][$i]),
                        'lineTwo' => $matches[3][$i],
                        'phone' => $this->processPhone($matches[4][$i]),
                    );
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
            array(
                'areaCode' => $match[1],
                'prefix' => $match[2],
                'exchange' => $match[3],
            )
        );
    }
}