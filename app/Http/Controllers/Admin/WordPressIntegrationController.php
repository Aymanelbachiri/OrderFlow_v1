<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class WordPressIntegrationController extends Controller
{
    /**
     * Display WordPress integration settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Unauthorized. Admin access required.');
        }
        
        // Get existing tokens (single-user version - no source filtering)
        $tokens = $user->tokens()->get()->map(function($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at,
                'created_at' => $token->created_at,
            ];
        });

        return view('admin.wordpress-integration.index', compact('tokens'));
    }

    /**
     * Generate new API token
     */
    public function generateToken(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('admin.wordpress-integration.index')
                ->with('error', 'Unauthorized. Admin access required.');
        }

        $validated = $request->validate([
            'token_name' => 'nullable|string|max:255',
        ]);

        $tokenName = $validated['token_name'] ?? 'wordpress-integration-' . now()->format('Y-m-d');
        
        // Create new token
        $token = $user->createToken($tokenName, ['wordpress:read']);
        $plainTextToken = $token->plainTextToken;

        return redirect()->route('admin.wordpress-integration.index')
            ->with('success', 'API token generated successfully!')
            ->with('new_token', $plainTextToken);
    }

    /**
     * Revoke API token
     */
    public function revokeToken(Request $request, $tokenId)
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('admin.wordpress-integration.index')
                ->with('error', 'Unauthorized. Admin access required.');
        }

        $token = $user->tokens()->find($tokenId);
        
        if (!$token) {
            return redirect()->route('admin.wordpress-integration.index')
                ->with('error', 'Token not found.');
        }

        $token->delete();

        return redirect()->route('admin.wordpress-integration.index')
            ->with('success', 'Token revoked successfully.');
    }

    /**
     * Download WordPress plugin as zip file
     */
    public function downloadPlugin()
    {
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->route('admin.wordpress-integration.index')
                ->with('error', 'Unauthorized. Admin access required.');
        }

        $pluginPath = base_path('wordpress-plugin');
        
        if (!is_dir($pluginPath)) {
            return redirect()->route('admin.wordpress-integration.index')
                ->with('error', 'Plugin directory not found.');
        }

        // Ensure temp directory exists
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFileName = 'iptv-integration-plugin.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);
        
        // Check if zip exists and if plugin files have been modified
        $needsRegeneration = true;
        if (file_exists($zipPath)) {
            $zipModifiedTime = filemtime($zipPath);
            $pluginModifiedTime = $this->getLatestPluginFileModificationTime($pluginPath);
            
            if ($pluginModifiedTime <= $zipModifiedTime) {
                $needsRegeneration = false;
            }
        }

        // Create or regenerate zip archive if needed
        if ($needsRegeneration) {
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return redirect()->route('admin.wordpress-integration.index')
                    ->with('error', 'Failed to create zip file.');
            }

            // Add files to zip
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($pluginPath),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($pluginPath) + 1);
                    
                    // Skip hidden files and directories
                    if (strpos($relativePath, '.') !== 0) {
                        $zip->addFile($filePath, $relativePath);
                    }
                }
            }

            $zip->close();
        }

        return response()->download($zipPath, $zipFileName);
    }

    /**
     * Get the latest modification time of all plugin files
     */
    private function getLatestPluginFileModificationTime($pluginPath)
    {
        $latestTime = 0;
        
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($pluginPath),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($pluginPath) + 1);
                
                if (strpos($relativePath, '.') !== 0) {
                    $fileTime = filemtime($filePath);
                    if ($fileTime > $latestTime) {
                        $latestTime = $fileTime;
                    }
                }
            }
        }

        return $latestTime;
    }
}

