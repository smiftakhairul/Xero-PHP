<?php

/*function post_request() {
    $xero = array(
        'consumer_key' => 'OXG4JEGQH9C9VBEYDUMPRE8GWMOWM6',
        'shared_secret' => 'QSPA86QWBKZ1KKIRXNTUT1WINWFX7E',
        'app_name'=>'Summit',
        'app_type'=>'Private',
        'account_code'=>'123',
        'salse_account_code'=>'200',
        'CurrencyCode'=>'AUD'
    );
    // $xero_oauth = Configure::read('XeroOAuth');
        // require_once APP . 'vendors' . DS . 'XeroOAuth' . DS . 'lib' . DS . 'XeroOAuth.php';
        require HOC_PATH . 'libs/xero/XeroOAuth/lib/XeroOAuth.php';
        // define('XRO_BASE_PATH', str_replace("\\", "/",APP . 'vendors' . DS . 'XeroOAuth'));
        define('XRO_BASE_PATH', HOC_PATH .'libs/xero/XeroOAuth');
        define("XRO_APP_TYPE", trim($xero['app_type']));
        define("OAUTH_CALLBACK", "oob");
}*/

require_once 'vendor/autoload.php';
use XeroPHP\Application\PrivateApplication;

function generate_xero_invoice_request($data = array())
{   
    $key = "-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQDkEWke6CFC17b+O+kWc2SZsObJqx9pGavRgyXyjEJnv+4aMYnE
hBsFlIyD3G4zqyJJQP3Aq9VSasqhjzOhu+dw/wbMx+aqky/wLW2Fv/nS8vVtkLyh
GDiO8QiFs2XtuH5U97piCQhpsUxHODtXU7TOZFnFyzeF0id6tLZkWICHKQIDAQAB
AoGAPmHcxQ1te6ERdrzgZrmtfLR8jBD4iIDzFF2xFYTz7Pj8ocGHE2+nDIGzZaX3
pr5apHrYbckSknaPcl1/G/APv75duXxjIzHBcgzAiAL1Sr6PrfTzie6oivUkxuTN
zREk7zLgbfigY0X/dZ2ArzdZuKBY4Y6JBqgPsC3F7uqM0+ECQQD6PWIwpN41f9CX
Q37QMAmoJQOzaqTG9UWJeSjwboQo2ca1lbo/ea4/vsDfv29yD9S/p6RHOKgi9+TH
ClaxKtGVAkEA6VFfeosfYe8/dXPZPrbwkSCbnDwm1949hvtarrwXWLfjHk2ofH4V
AhC+V3rlVsNk1cWrKd/jS6SSVlmHPA9iRQJAQ+eUVY7nnazpdaKjLutaM36X2n/9
5t931y5BpbzXsB4ohe3zdHYYisPAovbXzyPsn3VmZs1BEvwh4ME1dS4hYQJAFFTp
/BTJjFA7+HE6+jMY7Zyo0smPHPqZ4/xdX6K2ah3EJezghNC75e0tmRP1jkUCsXpD
Oyfud7YEpo+wsDQq/QJACHFMPON65dJ/k377MhPEzt7CWvChoI5z0548qZmgFX2i
mC1LwnRYxl352zRZxf8f03ktPKNuTw6wnKNFhr3T9Q==
-----END RSA PRIVATE KEY-----";
    $account_code = '200';
    $bank_account_code = '090';
    $tax_type = '';
    $config = [
        'oauth' => [
            'callback' => 'http://localhost/',
            'consumer_key' => 'DLSOT9WOXWGTDFRWFP7KGXEBS0PBDB',
            'consumer_secret' => 'AQKALQC5UPAWOCVGTOEOYQQS8EHLAT',
            'rsa_private_key' => $key,
        ],
    ];
    

    $xero = new PrivateApplication($config);


     //sample data
    /*/$data = array(
        'contact' => array(
            'Name' => 'Test Usefdr24541',
            'EmailAddress' => 'test1233563@test.com'
        ),
        'items' => array(
            array(
                'Description' => 'abc3541',
                'Quantity' => '10',
                'UnitAmount' => '12',
            ),
            array(
                'Description' => 'def355',
                'Quantity' => '20',
                'UnitAmount' => '23',
            ),

        )
    );*/

    if(sizeof($data) > 0){
        //Set and get contact data
        $contactInfo = $xero->load(\XeroPHP\Models\Accounting\Contact::class)
            ->where('Name', $data['contact']['Name'])
            //->andWhere('EmailAddress', $data['contact']['EmailAddress'])
            ->first();

        if (!$contactInfo) {
            //add new contact and get contactID
            $contact = new \XeroPHP\Models\Accounting\Contact($xero);
            try {
                $contact->setName($data['contact']['Name']);
                    //->setEmailAddress($data['contact']['EmailAddress']);
                $contact->save();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }


        $contactInfo = $xero->load(\XeroPHP\Models\Accounting\Contact::class)
            ->where('Name', $data['contact']['Name'])
           //->andWhere('EmailAddress', $data['contact']['EmailAddress'])
            ->first();
        if (isset($contactInfo['ContactID'])) {
            //make invoice
            $contact = $xero->loadByGUID('Accounting\\Contact', $contactInfo['ContactID']);
            $xeroInvoice = new \XeroPHP\Models\Accounting\Invoice($xero);
            $now = new DateTime("now");
            $due = new DateTime($now->format('Y-m-t'));
            $xeroInvoice->setType('ACCREC')
                ->setContact($contact)
                ->setReference('WEB-' . date('Ymdhis'))
                ->setDueDate($due)
                ->setStatus('AUTHORISED');

            if (sizeof($data['items']) > 0) {
                foreach ($data['items'] as $item) {
                    $xeroLineItem = new \XeroPHP\Models\Accounting\Invoice\LineItem($xero);
                    $xeroLineItem->setQuantity($item['Quantity']);
                    $xeroLineItem->setDescription($item['Description']);
                    $xeroLineItem->setUnitAmount($item['UnitAmount']);
                    $xeroLineItem->setAccountCode($account_code); // sale
                    $xeroLineItem->setTaxType($tax_type); // sale
                    $xeroInvoice->addLineItem($xeroLineItem);
                }
                try {
                    $invoice = $xeroInvoice->save();
                    $response = $invoice->getElements();
                } catch (Exception $e) {
                    echo $e->getMessage();
                    $response = false;
                }

                // Modified::Payment Addition
                if($response) {
                    try {
                        $payment = new \XeroPHP\Models\Accounting\Payment($xero);
                        $payment
                            ->setInvoice((new \XeroPHP\Models\Accounting\Invoice($xero))
                                ->setInvoiceID($response[0]['InvoiceID']))
                            ->setAccount((new \XeroPHP\Models\Accounting\Account($xero))
//            ->setAccountID('057d00dc-7674-4301-8919-7a6aea9433f4')
                                ->setCode($bank_account_code))
                            ->setDate($due)
                            ->setAmount($response[0]['Total']);

                        $paymentResponse = $payment->save();
                        $getPaymentResponse = $paymentResponse->getElements();
                    } catch (Exception $e) {
                        echo $e->getMessage();
                        $getPaymentResponse = false;
                    }
                }
            } else {
                $response = false;
            }
        } else {
            $response = false;
        }

    }else{
        $response = false;
    }
//    return array('invoice' => $response, 'payment' => $getPaymentResponse);
    return $getPaymentResponse;
}

?>

<?php
    // Call function
    echo '<a href="xero.php?call=true">Generate Xero Invoice Request</a>';

    if(isset($_GET['call']) && $_GET['call'] == true) {
        $data = array(
            'contact' => array(
                'Name' => 'Test User',
                'EmailAddress' => 'bm@test.com'
            ),
            'items' => array(
                array(
                    'Description' => 'Item 1',
                    'Quantity' => '15',
                    'UnitAmount' => '12',
                ),
                array(
                    'Description' => 'Item 2',
                    'Quantity' => '30',
                    'UnitAmount' => '10',
                ),

            )
        );

        $result = generate_xero_invoice_request($data);

        echo '<pre>';
        print_r(json_encode($result, JSON_PRETTY_PRINT));
        echo '</pre>';
    }
?>
