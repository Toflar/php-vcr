<?php

namespace VCR\Util;

class HttpUtilTest extends \PHPUnit_Framework_TestCase
{
    public function testParseResponseBasic()
    {
        $raw = "HTTP/1.1 201 Created\r\nContent-Type: text/html\r\nDate: Fri, 19 Jun 2015 16:05:18 GMT\r\nVary: Accept-Encoding\r\nContent-Length: 0";
        list($status, $headers, $body) = HttpUtil::parseResponse($raw);
        
        $expectedHeaders = array(
            'Content-Type: text/html',
            'Date: Fri, 19 Jun 2015 16:05:18 GMT',
            'Vary: Accept-Encoding',
            'Content-Length: 0'
        );
        
        $this->assertEquals('HTTP/1.1 201 Created', $status);
        $this->assertEquals(null, $body);
        $this->assertEquals($expectedHeaders, $headers);
    }
    
    public function testParseResponseMultipleHeaders()
    {
        $raw = "HTTP/1.1 201 Created\r\nContent-Type: text/html\r\nDate: Fri, 19 Jun 2015 16:05:18 GMT\r\nVary: Accept, Accept-Language, Expect\r\nVary: Accept-Encoding\r\nContent-Length: 0";
        list($status, $headers, $body) = HttpUtil::parseResponse($raw);
        
        $expectedHeaders = array(
            'Content-Type: text/html',
            'Date: Fri, 19 Jun 2015 16:05:18 GMT',
            'Vary: Accept, Accept-Language, Expect',
            'Vary: Accept-Encoding',
            'Content-Length: 0'
        );
        
        $this->assertEquals('HTTP/1.1 201 Created', $status);
        $this->assertEquals(null, $body);
        $this->assertEquals($expectedHeaders, $headers);
    }
    
    public function testParseHeadersBasic()
    {
        $inputArray = array(
            'Content-Type: text/html',
            'Date: Fri, 19 Jun 2015 16:05:18 GMT',
            'Vary: Accept-Encoding',
            'Content-Length: 0'
        );
        $excpetedHeaders = array(
            'Content-Type' => 'text/html',
            'Date' => 'Fri, 19 Jun 2015 16:05:18 GMT',
            'Vary' => 'Accept-Encoding',
            'Content-Length' => '0'
        );
        $outputArray = HttpUtil::parseHeaders($inputArray);
        $this->assertEquals($excpetedHeaders, $outputArray);
    }
    
    public function testParseHeadersMultiple()
    {
        $inputArray = array(
            'Content-Type: text/html',
            'Date: Fri, 19 Jun 2015 16:05:18 GMT',
            'Vary: Accept, Accept-Language, Expect',
            'Vary: Accept-Encoding',
            'Content-Length: 0'
        );
        $excpetedHeaders = array(
            'Content-Type' => 'text/html',
            'Date' => 'Fri, 19 Jun 2015 16:05:18 GMT',
            'Vary' => 'Accept, Accept-Language, Expect, Accept-Encoding',
            'Content-Length' => '0'
        );
        $outputArray = HttpUtil::parseHeaders($inputArray);
        $this->assertEquals($excpetedHeaders, $outputArray);
    }
}
