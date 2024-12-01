<?php

namespace App\Http\Controllers;

use Google\Service\Sheets;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class SpreadsheetController extends Controller
{

    public function getSpreadSheetByDownload() {
        try {
            // URL of the Google Spreadsheet exported as .xlsx
            $spreadsheetUrl = 'https://docs.google.com/spreadsheets/d/1dNZSORvXz3fmSt7hdtQWZrB4ulTfOxaN6WBm-woTGY4/export?format=xlsx';

            // Temporary file path
            $tempFilePath = storage_path('app/temp-spreadsheet.xlsx');

            // Download the spreadsheet using Guzzle
            $client = new Client();
            $response = $client->get($spreadsheetUrl, [
                'sink' => $tempFilePath, // Save to a temporary location
            ]);

            // Check if the file was downloaded successfully
            if ($response->getStatusCode() === 200) {
                // Read the spreadsheet into an array
                $data = Excel::toArray(null, $tempFilePath);

                // Delete the temporary file
                unlink($tempFilePath);

                // Return the data as a JSON response
                return response()->json(['data' => $data]);
            }

            return response()->json(['error' => 'Failed to download the spreadsheet'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSpreadSheetByGoogle() {
        try {
            $spreadsheetId = '1dNZSORvXz3fmSt7hdtQWZrB4ulTfOxaN6WBm-woTGY4';
            $range = 'Sheet1!A1:Z100';
    
            $client = new Client();
            $client->setAuthConfig(env('GOOGLE_APPLICATION_CREDENTIALS'));
            $client->addScope(Sheets::SPREADSHEETS_READONLY);
    
            $service = new Sheets($client);
            $response = $service->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
    
            return response()->json(['data' => $values]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
