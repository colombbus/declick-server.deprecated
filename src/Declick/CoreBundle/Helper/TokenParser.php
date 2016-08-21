<?php
namespace Declick\CoreBundle\Helper;

use Namshi\JOSE\JWS;
use DateTime;
use Exception;

class TokenParser
{
   /**
    * Public key
    */
   private $publicKey;
   
   function __construct($publicKey) {
      $this->publicKey = openssl_pkey_get_public("file://".$publicKey);
   }
   /**
    * Decode tokens
    */
   public function decodeToken($encryptedParams)
   {
      $jws  = JWS::load($encryptedParams);
      /*if ($jws->verify($this->publicKey, 'RS512')) {
          */
          $params = $jws->getPayload();
      /*} */
      $datetime = new DateTime();
      $datetime->modify('+1 day');
      $tomorrow = $datetime->format('d-m-Y');
      if (!isset($params['date'])) {
         if (!isset($params)) {
            throw new Exception('Token cannot be decrypted, please check your SSL keys');
         }
         else {
            throw new Exception('Invalid Task token, unable to decrypt: '.$params.'; current: '.date('d-m-Y'));
         }
      }
      else if ($params['date'] != date('d-m-Y') && $params['date'] != $tomorrow) {
         throw new Exception('API token expired');
      }
      
      return $params;
   }
}
