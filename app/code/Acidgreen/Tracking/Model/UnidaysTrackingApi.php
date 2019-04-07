<?php
namespace Acidgreen\Tracking\Model;

/**
 * UNiDAYS SDK Tracking Helper Class for generating tracking URLs.
 *
 * @category   SDK
 * @package    UNiDAYS
 * @subpackage None
 * @copyright  Copyright (c) 2017 MyUNiDAYS Ltd. (see above)
 * @license    The MIT License (MIT) (see above)
 * @version    Release: 1.1
 * @link       http://www.myunidays.com
 */
class UnidaysTrackingApi {

    protected $customer_ID;
    protected $key;
    protected $tracking_url;


    function setUp($CustomerID, $SigningKey, $TrackingUrl = null){

        $this->customer_ID=$CustomerID;
        $this->key=base64_decode($SigningKey);
        // Live URL
        $this->tracking_url=$TrackingUrl? $TrackingUrl : 'https://tracking.myunidays.com/perks/redemption/v1.1';
        //$this->tracking_url='https://tracking.myunidays.com/perks/redemption/v1.1-test'; // test url
    }



    public function createUrl($TransactionID, $MemberID, $currency,
                              $order_total, $items_unidays_discount, $code,
                              $items_tax, $shipping_gross, $shipping_discount,
                              $items_gross, $items_other_discount,
                              $unidays_discount_percentage, $new_customer, $url_type='server'){

        /* Creates a tracking URL for the given parameters.

        Keyword arguments:
            TransactionId                   --  Your Unique ID for the transaction ie Order123
        Currency                        --  ISO 4217 Currency Code
        OrderTotal                      --  Total monetary amount paid, formatted to 2 decimal places
        ItemsUNiDAYSDiscount            --  Total monetary amount of UNiDAYS discount applied on gross item value ItemsGross, formatted to 2 decimal places
        Code                            --  Discount code used, you will only have this if you are issuing codes instead of the codeless option
                                            if you don't have it leave it blank: ''
        ItemsTax                        --  Total monetary amount of tax applied to items, formatted to 2 decimal places.
        ShippingGross                   --  Total monetary amount of the items including tax, before any discounts are applied, formatted to 2 decimal places.
        ShippingDiscount                --  Total monetary amount of shipping discount (UNiDAYS or otherwise) applied to the order, formatted to 2 decimal places.
        ItemsGross                      --  Total monetary amount of the items, including tax, before any discounts are applied, formatted to 2 decimal places.
        ItemsOtherDiscount              --  Total monetary amount of all non UNiDAYS discounts applies to ItemsGross, formatted to 2 decimal places
        UNiDAYSDiscountPercentage       --  The UNiDAYS discount applied as a percentage formatted to 2 decimal places.
        NewCustomer                     --  Is the user a new (vs returning) customer to you? 1 if new, 0 if returning.
            url_type								        -- The type of URL to generate, one of 'pixel' or 'server'
                                                           (default: 'server')
    Examples::

          $tracking =  new Tracking('my customer id', 'my signing key');

          $pixel_url = $tracking->CreateUrl(
            'the transaction',
        'id of student',
        'GBP',
        209.00,
        13.00,
        'code used',
            34.50,
            5.00,
            3.00,
            230.00,
            10.00,
            10.00,
            1,
            'pixel') Returns https://tracking.myunidays.com/perks/redemption/v1.1.gif?CustomerId=my+customer+id&TransactionId=the+transaction&MemberId=id+of+student&Currency=GBP&OrderTotal=209.00&ItemsUNiDAYSDiscount=13.00&Code=code+used&ItemsTax=34.50&ShippingGross=5.00&ShippingDiscount=3.00&ItemsGross=230.00&ItemsOtherDiscount=10.00&UNiDAYSDiscountPercentage=10.00&NewCustomer=1&Signature=QlaXGYft1GOKJmQF%2bfRirPdNDHA3l9JnKnvAAaKRtb4qnswfBOdFwxfqfKiIlFG0lxC7LMh5Sn4Lx7X8es%2bvwg%3d%3d
    while
          $server_url = $tracking->CreateUrl(
            'the transaction',
        'id of student',
        'GBP',
        209.00,
        13.00,
        'code used',
            34.50,
            5.00,
            3.00,
            230.00,
            10.00,
            10.00,
            1,
        'server') Returns https://tracking.myunidays.com/perks/redemption/v1.1?CustomerId=my+customer+id&TransactionId=the+transaction&MemberId=id+of+student&Currency=GBP&OrderTotal=209.00&ItemsUNiDAYSDiscount=13.00&Code=code+used&ItemsTax=34.50&ShippingGross=5.00&ShippingDiscount=3.00&ItemsGross=230.00&ItemsOtherDiscount=10.00&UNiDAYSDiscountPercentage=10.00&NewCustomer=1&Signature=QlaXGYft1GOKJmQF%2bfRirPdNDHA3l9JnKnvAAaKRtb4qnswfBOdFwxfqfKiIlFG0lxC7LMh5Sn4Lx7X8es%2bvwg%3d%3d

        Returns a URL to make a server-to-server request to if url_type is specified
        as 'server'; otherwise returns a URL to be placed inside an <img /> element
        in your receipt page.

        For pixel URLs the server will respond with a 1x1px transparent gif.

        */

        if($url_type=='server'){
            $extension='';
        }else{
            $extension='.gif';
        }
        $querystring = '?CustomerId=' . $this->encode_urlvariables($this->customer_ID)
            . '&TransactionId=' . $this->encode_urlvariables($TransactionID)
            . '&MemberId=' . $this->encode_urlvariables($MemberID)
            . '&Currency=' . rawurlencode($currency)
            . '&OrderTotal=' . rawurlencode(number_format($order_total, 2, '.',''))
            . '&ItemsUNiDAYSDiscount=' . $this->url_encode_number($items_unidays_discount)
            . '&Code=' . rawurlencode($code)
            . '&ItemsTax=' . $this->url_encode_number($items_tax)
            . '&ShippingGross=' . $this->url_encode_number($shipping_gross)
            . '&ShippingDiscount=' . $this->url_encode_number($shipping_discount)
            . '&ItemsGross=' . $this->url_encode_number($items_gross)
            . '&ItemsOtherDiscount=' . $this->url_encode_number($items_other_discount)
            . '&UNiDAYSDiscountPercentage=' . $this->url_encode_number($unidays_discount_percentage)
            . '&NewCustomer=' . rawurlencode($new_customer);

        $signature=$this->encode_signature(hash_hmac("sha512", $querystring, $this->key, true));

        $url=$this->tracking_url.$extension.$querystring.'&Signature='.$signature;
        return $url;
    }

    private function url_encode_number($number) {
        return $number != '' ? rawurlencode(number_format($number, 2, '.', '')) : '';
    }

    private function encode_urlvariables($urlvariable){
        //Enforces lowercae url encoding for the base64 encoded variable passed into the url (i.e. CustomerID, TransactionId etc.)
        $first_encode= rawurlencode($urlvariable);
        $encoded_variable= preg_replace_callback('/%(\d[A-F]|[A-F]\d)/', function(array $matches){return strtolower($matches[0]);}, $first_encode);//If your server is not running PHP 5.3 or later, this line may generate an error due to the use of an anonymous function.
        return $encoded_variable;
    }

    public function encode_signature($hash){
        /* URL encoding characters are uppercase in PHP, and since we are looking to match the hash algorithms provided exactly, we have to take the additional precaution of forcing any url encoding characters in the hash to be displayed in lower case
        This function takes the signature generated by hash_hmac, applies both base64 encoding and then url encoding. It then replaces any upper case letters that are part of a url encoded character with the lower case version.
        */
        $first_encode=rawurlencode(base64_encode($hash));
        $encoded_signature = preg_replace_callback('/%(\d[A-F]|[A-F]\d)/',function(array $matches){return strtolower($matches[0]);},$first_encode);//If your server is not running PHP 5.3 or later, this line may generate an error due to the use of an anonymous function.
        return $encoded_signature;
    }
}
?>