<?php

namespace App\Helpers\PaymentServices;

use App\Models\Transaction;

class Robokassa
{

    public $invoiceNumber = 0;

    public $invoiceDesc;

    public $invoiceSum;

    public $isTest;

    public $signature;

    protected $login;

    protected $passw;

    protected $endpoint = 'https://auth.robokassa.ru/Merchant/Index.aspx';

    protected $hash;

    protected $encoding = 'utf-8';

    protected $culture = 'ru';

    protected $packages = [
        '15' => ['sum' => '300', 'desc' => '15 ставок за 300 руб.'],
        '25' => ['sum' => '550', 'desc' => '25 ставок за 550 руб.'],
        '50' => ['sum' => '1000', 'desc' => '15 ставок за 1000 руб.'],
        '100' => ['sum' => '1300', 'desc' => '15 ставок за 1300 руб.'],
        '250' => ['sum' => '1300', 'desc' => '15 ставок за 1300 руб.'],
        '500' => ['sum' => '2000', 'desc' => '15 ставок за 2000 руб.'],
        '1000' => ['sum' => '2900', 'desc' => '1000 ставок за 2900 руб.'],
        '2500' => ['sum' => '4000', 'desc' => '2500 ставок за 4000 руб.'],
    ];

    public function __construct($args = [])
    {
        $this->login = env('ROBOKASSA_LOGIN');
        $this->passw = env('ROBOKASSA_PASSW');
        $this->hash = env('ROBOKASSA_HASH');
        $this->isTest = env('ROBOKASSA_TEST');

        if (isset($args['package'])) {
            $this->setfromPackage($args['package']);
        }

        $this->invoiceNumber = isset($args['invoice_number']) ? $args['invoice_number'] : null;
    }

    /**
     * Check out Robokassa
     */
    public function checkout()
    {
        return redirect($this->makeLink());
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->makeLink();
    }

    /**
     * Set invoice number
     * 
     * @param $number
     * @return mixed
     */
    public function setInvoiceNumber($number)
    {
        return $this->invoiceNumber = $number;
    }

    /**
     * Make link
     *
     * @return string Return link to robbokassa
     */
    protected function makeLink()
    {
        $this->generateSignature();

        $stringParams = http_build_query([
            'isTest' => $this->isTest,
            'MrchLogin' => $this->login,
            'InvId' => $this->invoiceNumber,
            'OutSum' => $this->invoiceSum,
            'Desc' => $this->invoiceDesc,
            'SignatureValue' => $this->signature,
            'Encoding' => $this->encoding,
            'Culture' => $this->culture,
        ]);

        return $this->endpoint . '?' . $stringParams;
    }

    /**
     * Generate signature
     *
     * @return string
     */
    protected function generateSignature()
    {
        $string = "$this->login:$this->invoiceSum:$this->invoiceNumber:$this->passw";

        switch ($this->hash) {
            case 'MD5':
                $this->signature = md5($string);
                break;
            case 'SHA1':
                $this->signature = sha1($string);
        }
        return $this->signature;
    }

    /**
     * Set properties from default data
     *
     * @param $package
     */
    protected function setfromPackage($package)
    {
        $package = $this->packages[$package];
        $this->invoiceSum = $package['sum'];
        $this->invoiceDesc = $package['desc'];
    }

}