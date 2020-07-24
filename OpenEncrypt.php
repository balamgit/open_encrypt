<?php
class OpenEncrypt{

    // Store the cipher method
    protected $ciphering = "AES-128-CTR";
    //default IV
    protected $encryption_iv = "1234567891011121";
    //default encryption key
    protected $encryption_key = 'DefaultKey';
    //default minutes for validity
    protected $minutes_to_add = 3;

    public function __construct($minutes_to_add = 0, $encryption_key = null, $ciphering = null, $encryption_iv = null)
    {
        //set validity minutes
        if(!empty($minutes_to_add)){
            $this->minutes_to_add = $minutes_to_add;
        }

        //set encryption_key
        if(!empty($encryption_key)){
            $this->encryption_key = $encryption_key;
        }

        //set ciphering
        if(!empty($ciphering)){
            $this->ciphering = $ciphering;
        }

        //set encryption_iv
        if(!empty($encryption_iv)){
            $this->encryption_iv = $encryption_iv;
        }

    }

    //encrypt
    public function encrypt_open($encrypt_string, $options = 0, $with_date = 'YES'){

        if($with_date == 'YES'){
            /**custom encryption option
             * its useful while decrypt for to check Encrypted date-time
             * And also set validity time for encryption
             **/
            $final = $encrypt_string.'^^'.$this->add_validity_min();
        }
        else{
            $final = $encrypt_string;
        }

        // Use openssl_encrypt() function to encrypt the data
        return base64_encode(openssl_encrypt(
            $final,
            $this->ciphering,
            $this->encryption_key,
            $options,
            $this->encryption_iv));
    }

    //decrypt data
    public function decrypt_close($decryption_string, $options = 0){

        // Use openssl_decrypt() function to decrypt the data
        $decryption_string = base64_decode($decryption_string);
        return openssl_decrypt (
            $decryption_string,
            $this->ciphering,
            $this->encryption_key,
            $options,
            $this->encryption_iv);
    }

    //add encrypt validity
    private function add_validity_min(){

        $time = new DateTime();
        try {
            $time->add(new DateInterval('PT' . $this->minutes_to_add . 'M'));
        } catch (Exception $e) {

        }
        return $time->format('Y-m-d H:i');
    }

}