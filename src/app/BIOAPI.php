<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BIOAPI extends Model
{
    static protected $URL = 'http://race-and-sex-prediction-dev.us-east-2.elasticbeanstalk.com';
    
    static public function getData( $image ) {
    
        $imagefile =  public_path().'/images/'.preg_replace("/ |'|\(|\)/", '\\\${0}', $image );

        if ( ! file_exists( $imagefile ) ) throw new \Exception( 'image does not exist: '.$imagefile );
        
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, 1);

        //Set the Url
        curl_setopt($ch, CURLOPT_URL, self::$URL.'/predict');

        //Create a POST array with the file in it
        $postData = array(
  //          'image' => '@'.$imagefile,
            'image' => new \CurlFile( $imagefile, mime_content_type( $imagefile ) , basename( $imagefile ) )
        );
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData );

        // Execute the request
        $response = curl_exec($ch);
        
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        if ( $httpcode != 200 ) throw new \Exception( $response );
        
        $decoded = json_decode( $response );
        
        if ( $decoded === NULL ) throw new \Exception( 'fail to parse API response' );
        //ech o public_path();
        return $decoded;
    } 
}
