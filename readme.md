How to make xero invoice with private app

#####Steps of process
* Create a private application in xero by the [link](https://developer.xero.com/myapps/) https://developer.xero.com/myapps/

* To generate private-public key pair go [here] (https://developer.xero.com/documentation/api-guides/create-publicprivate-key)

- Public key is used to make the private key in private application

```code
-----BEGIN CERTIFICATE-----
MIICyjCCAjOgAwIBAgIJAPljB1SbSyd9MA0GCSqGSIb3DQEBCwUAMH4xCzAJBgNV
BAYTAkJEMQ4wDAYDVQQIDAVEaGFrYTEOMAwGA1UEBwwFRGhha2ExFDASBgNVBAoM
C2hvbHlvcmdhbmljMQ8wDQYDVQQDDAZtYWhlZGkxKDAmBgkqhkiG9w0BCQEWGWJp
dG1hc2NvdHdlYmRldkBnbWFpbC5jb20wHhcNMTkwMTA4MDM1MzUyWhcNMjQwMTA3
MDM1MzUyWjB+MQswCQYDVQQGEwJCRDEOMAwGA1UECAwFRGhha2ExDjAMBgNVBAcM
BURoYWthMRQwEgYDVQQKDAtob2x5b3JnYW5pYzEPMA0GA1UEAwwGbWFoZWRpMSgw
JgYJKoZIhvcNAQkBFhliaXRtYXNjb3R3ZWJkZXZAZ21haWwuY29tMIGfMA0GCSqG
SIb3DQEBAQUAA4GNADCBiQKBgQDkEWke6CFC17b+O+kWc2SZsObJqx9pGavRgyXy
jEJnv+4aMYnEhBsFlIyD3G4zqyJJQP3Aq9VSasqhjzOhu+dw/wbMx+aqky/wLW2F
v/nS8vVtkLyhGDiO8QiFs2XtuH5U97piCQhpsUxHODtXU7TOZFnFyzeF0id6tLZk
WICHKQIDAQABo1AwTjAdBgNVHQ4EFgQU48w7uLQE/nWXh34tIk7s0AwQRwEwHwYD
VR0jBBgwFoAU48w7uLQE/nWXh34tIk7s0AwQRwEwDAYDVR0TBAUwAwEB/zANBgkq
hkiG9w0BAQsFAAOBgQDV5XsfST5q/TEctIPJtCE7Qbe1HXUHd1Lwg6O/hLoY+Yrh
1qXFAU7cCQ2R6rG36jZYDhTXSloPm5c3H4j97xs5EoYxM66OvzOuTS0DdcH99LqX
91NVyNf6/lIY6PW57MqFABnMyK1dSKJnt0RSW4GMI/jjanRzcRNqjlUN3TCqcw==
-----END CERTIFICATE-----

```

* Create a composer.json

```json
    {
        "require": {
            "calcinai/xero-php": "^1.8"
        }
    }
```


* PHP code
```php
<?php 

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
   
   $config = [
               'oauth' => [
                   'callback' => 'http://localhost/',
                   'consumer_key' => 'NDMZ85A2MR0AZPJVCMFI7L12LGCI45',
                   'consumer_secret' => 'LVDVLYIRUOLOJHDKDRDIFP7OI0YKAZ',
                   'rsa_private_key' => $key,
               ],
           ];

    $xero = new PrivateApplication($config);

    /*
     //sample data
    $data = array(
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
            $xeroInvoice->setType('ACCREC') //for account receivable only
                ->setContact($contact)
                ->setReference('WEB-' . date('Ymdhis'))
                ->setDueDate($due)
                ->setStatus('AUTHORISED'); //to see in awating to payment

            if (sizeof($data['items']) > 0) {
                foreach ($data['items'] as $item) {
                    $xeroLineItem = new \XeroPHP\Models\Accounting\Invoice\LineItem($xero);
                    $xeroLineItem->setQuantity($item['Quantity']);
                    $xeroLineItem->setDescription($item['Description']);
                    $xeroLineItem->setUnitAmount($item['UnitAmount']);
                    $xeroLineItem->setAccountCode('270'); // chart of account code
                    $xeroLineItem->setTaxType('EXEMPTOUTPUT'); // sale tax type
                    $xeroInvoice->addLineItem($xeroLineItem);
                }
                try {
                    $invoice = $xeroInvoice->save();
                    $response = $invoice->getElements();

                } catch (Exception $e) {
                    echo $e->getMessage();
                    $response = false;
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
    return $response;
}


?>

```

* Just call the php function in your php code with contact information and item list by generate_xero_invoice_request($items_data_array);

* Here is the certificate code

