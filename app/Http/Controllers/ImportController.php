<?php

namespace App\Http\Controllers;

use App\Services\ImportService;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    protected $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    // show import page
    public function index()
    {
        return view('admin.import.index');
    }

    // import from jsonplaceholder
    public function importJsonPlaceholder()
    {
        $result = $this->importService->importFromJsonPlaceholder();

        if ($result['success']) {
            return redirect()->route('admin.import.index')
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.import.index')
            ->with('error', $result['message']);
    }

    // import from fakestore
    public function importFakeStore()
    {
        $result = $this->importService->importFromFakeStore();

        if ($result['success']) {
            return redirect()->route('admin.import.index')
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.import.index')
            ->with('error', $result['message']);
    }

    // import from custom url
    public function importManual(Request $request)
    {
        $validated = $request->validate([
            'api_url' => 'required|url',
        ]);

        // auto-generate source name from domain
        $parsedUrl = parse_url($validated['api_url']);
        $domain = $parsedUrl['host'] ?? 'unknown';
        $source = str_replace('www.', '', $domain);
        $source = strtolower($source);
        $source = str_replace('.', '-', $source);

        $result = $this->importService->importFromUrl(
            $validated['api_url'],
            $source
        );

        if ($result['success']) {
            return redirect()->route('admin.import.index')
                ->with('success', $result['message']);
        }

        return redirect()->route('admin.import.index')
            ->with('error', $result['message']);
    }
}
