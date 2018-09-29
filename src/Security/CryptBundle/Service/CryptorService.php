<?php
namespace Security\CryptBundle\Service;
 
use Security\CryptBundle\Exception\InvalidValueException;
 
class CryptorService implements CryptorServiceInterface
{
    private $hash;
    private $algorithm;
    private $mode;
    private $cipher;
 
    public function __construct(
        $secret = 'hgvER5445ds5sd@£$%',
        $algorithm = 'rijndael-128',
        $mode = 'ecb'
    ) {
        $this->hash = sha1($secret);
        $this->algorithm = $algorithm;
        $this->mode = $mode;
    }
 
    public function encrypt($plainString)
    {
        $this->initiate($plainString);
        $encryptedValue = mcrypt_generic($this->cipher, $plainString);
        $this->finalise();
 
        return base64_encode($encryptedValue);
    }
 
    public function decrypt($encryptedValue)
    {
        $decodedValue = base64_decode($encryptedValue, true);
        if ($decodedValue === false) {
            throw new InvalidValueException(sprintf('Given value [%s] is not a valid base64 string.', $encryptedValue));
        }
 
        $this->initiate($decodedValue);
        $decryptedValue = mdecrypt_generic($this->cipher, $decodedValue);
        $this->finalise();
 
        return $decryptedValue;
    }
 
    private function initiate($value)
    {
        if (!is_string($value)) {
            throw new InvalidValueException(sprintf('Given value [%s] is not a string.', $value));
        }
 
        if (mb_strlen($value) == 0) {
            throw new InvalidValueException(sprintf('Given value [%s] is empty.', $value));
        }
 
        // Open the cipher
        $this->cipher = mcrypt_module_open($this->algorithm, '', $this->mode, '');
 
        // Get key size
        $keySize = mcrypt_enc_get_key_size($this->cipher);
        // Get key
        $key = substr($this->hash, 0, $keySize);
 
        // Get iv size
        $ivSize = mcrypt_enc_get_iv_size($this->cipher);
        // Get iv
        $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_RANDOM);
 
        // Initialise encryption
        mcrypt_generic_init($this->cipher, $key, $iv);
    }
 
    private function finalise()
    {
        // Terminate encryption handler
        mcrypt_generic_deinit($this->cipher);
        // Close module
        mcrypt_module_close($this->cipher);
    }
}
