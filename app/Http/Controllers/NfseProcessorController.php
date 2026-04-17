<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DOMDocument;
use DOMXPath;
use ZipArchive;

class NfseProcessorController extends Controller
{
    public function index()
    {
        return view('nfse.upload');
    }

    public function process(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml'
        ]);

        $file = $request->file('xml_file');
        
        // Salva arquivo no disco local storage temporario
        $filename = uniqid('nfse_') . '.xml';
        Storage::disk('local')->putFileAs('xml_uploads', $file, $filename);
        
        $path = Storage::disk('local')->path('xml_uploads/' . $filename);

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->load($path);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);
        
        $listaNfseList = $xpath->query("//*[local-name()='ListaNfse']");

        if ($listaNfseList->length === 0) {
            return back()->with('error', 'A tag <ListaNfse> não foi encontrada no XML.');
        }

        $listaNfse = $listaNfseList->item(0);

        $compNfseList = $xpath->query(".//*[local-name()='CompNfse']", $listaNfse);
        if ($compNfseList->length === 0) {
            $compNfseList = $xpath->query(".//*[local-name()='Nfse']", $listaNfse);
        }

        if ($compNfseList->length === 0) {
            return back()->with('error', 'Nenhuma nota encontrada dentro de <ListaNfse>.');
        }

        $notasEncontradas = [];

        foreach ($compNfseList as $i => $comp) {
            $tagName = str_contains($comp->nodeName, ':') ? explode(':', $comp->nodeName)[1] : $comp->nodeName;
            
            $nfseEl = null;

            if ($tagName === 'CompNfse') {
                $checkNfse = $xpath->query(".//*[local-name()='Nfse']", $comp);
                if ($checkNfse->length > 0) {
                    $nfseEl = $checkNfse->item(0);
                }
            } else {
                $nfseEl = $comp;
            }

            if (!$nfseEl) {
                $nfseEl = $comp;
            }

            $numeroEl = $xpath->query(".//*[local-name()='Numero']", $nfseEl);
            $codigoEl = $xpath->query(".//*[local-name()='CodigoVerificacao']", $nfseEl);

            $numero = ($numeroEl->length > 0) ? trim($numeroEl->item(0)->nodeValue) : 'N/A';
            $codigo = ($codigoEl->length > 0) ? trim($codigoEl->item(0)->nodeValue) : 'N/A';

            $notasEncontradas[] = [
                'index' => $i + 1,
                'numero' => $numero,
                'codigo' => $codigo,
            ];
        }

        return view('nfse.select', compact('notasEncontradas', 'filename'));
    }

    public function download(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'selected_indices' => 'required|array'
        ]);

        $filename = $request->input('filename');
        $selectedIndices = $request->input('selected_indices'); // Array com indices [ "1", "2" ]

        $path = Storage::disk('local')->path('xml_uploads/' . $filename);

        if (!file_exists($path)) {
            return redirect()->route('nfse.index')->with('error', 'Arquivo XML original expirou ou não foi encontrado. Por favor, faça o upload novamente.');
        }

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->load($path);
        libxml_clear_errors();

        $xpath = new DOMXPath($doc);
        $listaNfseList = $xpath->query("//*[local-name()='ListaNfse']");

        if ($listaNfseList->length === 0) {
            return back()->with('error', 'Tag <ListaNfse> não encontrada no processamento de extração.');
        }

        $listaNfse = $listaNfseList->item(0);

        $compNfseList = $xpath->query(".//*[local-name()='CompNfse']", $listaNfse);
        if ($compNfseList->length === 0) {
            $compNfseList = $xpath->query(".//*[local-name()='Nfse']", $listaNfse);
        }

        // Criando ZIP temporario
        $zipFile = Storage::disk('local')->path('xml_uploads/download_' . uniqid() . '.zip');
        $zip = new ZipArchive();

        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'Não foi possível criar o arquivo ZIP.');
        }

        foreach ($compNfseList as $i => $comp) {
            $currentIndex = (string)($i + 1);

            if (in_array($currentIndex, $selectedIndices)) {
                $tagName = str_contains($comp->nodeName, ':') ? explode(':', $comp->nodeName)[1] : $comp->nodeName;
                
                if ($tagName === 'CompNfse') {
                    $checkNfse = $xpath->query(".//*[local-name()='Nfse']", $comp);
                    if ($checkNfse->length > 0) {
                        $nfseEl = $checkNfse->item(0);
                    } else {
                        $nfseEl = $comp;
                    }
                } else {
                    $nfseEl = $comp;
                }

                $numeroEl = $xpath->query(".//*[local-name()='Numero']", $nfseEl);
                $codigoEl = $xpath->query(".//*[local-name()='CodigoVerificacao']", $nfseEl);

                $numero = ($numeroEl->length > 0 && !empty(trim($numeroEl->item(0)->nodeValue))) ? trim($numeroEl->item(0)->nodeValue) : null;
                $codigo = ($codigoEl->length > 0 && !empty(trim($codigoEl->item(0)->nodeValue))) ? trim($codigoEl->item(0)->nodeValue) : null;

                $filenameOut = 'NFSe_';
                if ($numero && $numero !== 'N/A') {
                    $filenameOut .= $numero;
                } elseif ($codigo && $codigo !== 'N/A') {
                    $filenameOut .= $codigo;
                } else {
                    $filenameOut .= $currentIndex;
                }
                $filenameOut .= '.xml';

                $tempDoc = new DOMDocument('1.0', 'UTF-8');
                $tempDoc->formatOutput = true;
                $importedNode = $tempDoc->importNode($nfseEl, true);
                $tempDoc->appendChild($importedNode);

                $xmlContent = $tempDoc->saveXML();
                
                // Remove the xml declaration if abrasive logic dictates so, but standard is fine.

                $zip->addFromString($filenameOut, $xmlContent);
            }
        }

        $zip->close();
        
        return response()->download($zipFile)->deleteFileAfterSend(true);
    }
}
