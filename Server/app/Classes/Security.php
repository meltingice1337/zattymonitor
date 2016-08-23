<?php

namespace App\Classes;

class Security {

	static function generateKey()
	{
		return bin2hex(openssl_random_pseudo_bytes(16));
	}

	static function EncryptAndSign($plaintext, $eKey, $aKey)
	{

		$iv =  self::generateKey();
		$hash = hash_pbkdf2('sha1', $eKey, $iv, 1000, 32, true);
		$block = mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
		$padding = $block - (strlen($plaintext) % $block);
		$plaintext .= str_repeat(chr($padding), $padding);
		$ciphertext = mcrypt_encrypt(
			MCRYPT_RIJNDAEL_256,
			$hash,
			$plaintext,
			MCRYPT_MODE_CBC,
			$iv
			);
		$hmac = hash_hmac('sha256', $iv.$ciphertext, $aKey, true);
		return  base64_encode(
			$hmac.$iv.$ciphertext
			);
	}

	static function Decrypt($encryptedText, $eKey)
	{
		$decoded = base64_decode($encryptedText);
		$hmac = mb_substr($decoded, 0, 32, '8bit');
		$iv = mb_substr($decoded, 32, 32, '8bit');
		$hash = hash_pbkdf2('sha1', $eKey, $iv, 1000, 32, true);
		$ciphertext = mb_substr($decoded, 64, null, '8bit');
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256,$hash,$ciphertext,MCRYPT_MODE_CBC,$iv);
		$decrypted = self::pkcs7unpad($decrypted, mcrypt_get_block_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));
		return $decrypted;

	}
	static function pkcs7unpad($padded, $blocksize)
	{
		$l = strlen($padded);
		$padsize = ord($padded[$l - 1]);
		$padding = substr($padded, -1 * $padsize);
		return substr($padded, 0, $l - $padsize);
	}
	static function VerifyMessage($encryptedText, $aKey)
	{
		$decoded = base64_decode($encryptedText);
		$hmac = mb_substr($decoded, 0, 32, '8bit');
		$ciphertext = mb_substr($decoded, 32, null, '8bit');

		$calculated = hash_hmac('sha256', $ciphertext, $aKey, true);

		return hash_equals($hmac , $calculated);
	}
}
