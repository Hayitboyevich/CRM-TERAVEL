<?php

namespace App\Http\Controllers;

use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;

class ParserController extends Controller
{
    public function parser(Request $request)
    {
        $html = file_get_contents(storage_path('app/public/telegram.html'));
        $dom = new DOMDocument();

        // Load the HTML content
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        // Create a new DOMXPath instance
        $xpath = new DOMXPath($dom);

        // Find all elements with the class name "message"
        $messageElements = $xpath->query("//div[contains(@class, 'message')]");

        // Create an array to store the parsed data
        $parsedData = [];

        foreach ($messageElements as $messageElement) {
            // Get the from_name element
            $fromNameElement = $messageElement->getElementsByTagName('div')->item(2);

            // Get the text of the from_name element
            $fromName = $fromNameElement->textContent;

            // Get the text element
            $textElement = $messageElement->getElementsByTagName('div')->item(3);

            // Get the inner HTML of the text element
            $innerHTML = $dom->saveHTML($textElement);

            // Parse the inner HTML to extract specific information
            preg_match('/Ismingiz: (.*?)<br><br>Telefon raqamingiz: <a href="tel:(.*?)">(.*?)<\/a><br><br>Odam soni: (.*?)<br><br>Tur: (.*?)\n/s', $innerHTML, $matches);

            if (count($matches) === 6) {
                $parsedData[] = [
                    'from_name' => trim($fromName),
                    'ismingiz' => trim($matches[1]),
                    'telefon_raqamingiz' => trim($matches[2]),
                    'telefon_raqam' => trim($matches[3]),
                    'odam_soni' => trim($matches[4]),
                    'tur' => trim($matches[5]),
                ];
            }
        }

        // Return the parsed data as JSON response
        return response()->json($parsedData);
    }
}
