<?php

namespace App\Http\Controllers;

use App\Models\ShieldDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShieldDomainController extends Controller
{
    /**
     * Serve template content for shield domains
     * This catches all requests to shield domains and serves the appropriate template
     */
    public function serve(Request $request, $path = '')
    {
        // Get the domain from the request
        $host = $request->getHost();
        
        // Remove port if present
        $host = preg_replace('/:\d+$/', '', $host);
        
        // Find the shield domain
        $shieldDomain = ShieldDomain::where('domain', $host)
            ->where('status', 'active')
            ->where('dns_configured', true)
            ->first();
        
        if (!$shieldDomain) {
            // If not a shield domain, let Laravel handle it normally
            abort(404);
        }
        
        $templateName = $shieldDomain->template_name;
        $templatePath = public_path("templates/{$templateName}");
        
        // Determine which template file to serve based on the path
        $filePath = $this->getTemplateFilePath($path, $templatePath);
        
        if (!$filePath || !file_exists($filePath)) {
            // If file doesn't exist, try index.html
            $filePath = $templatePath . '/index.html';
            
            if (!file_exists($filePath)) {
                Log::warning('Template file not found', [
                    'domain' => $host,
                    'template' => $templateName,
                    'path' => $path,
                    'template_path' => $templatePath,
                ]);
                abort(404);
            }
        }
        
        // Read and return the template file
        $content = file_get_contents($filePath);
        
        // Replace template paths in the content to work with the current domain
        $content = $this->fixTemplatePaths($content, $templateName);
        
        return response($content, 200)
            ->header('Content-Type', $this->getContentType($filePath));
    }
    
    /**
     * Determine which template file to serve based on the request path
     */
    private function getTemplateFilePath(string $path, string $templatePath): ?string
    {
        // Remove leading slash
        $path = ltrim($path, '/');
        
        // If empty path or root, serve index.html
        if (empty($path) || $path === '/') {
            return $templatePath . '/index.html';
        }
        
        // Map common paths to template files
        $pathMap = [
            'checkout' => 'checkout.html',
            'renew' => 'renew.html',
            'renewal' => 'renew.html',
            'success' => 'success.html',
            'cancel' => 'cancel.html',
        ];
        
        // Check if path matches a mapped route
        foreach ($pathMap as $route => $file) {
            if ($path === $route || str_starts_with($path, $route . '/')) {
                return $templatePath . '/' . $file;
            }
        }
        
        // Try to find the file directly
        $filePath = $templatePath . '/' . $path;
        
        // If it's a directory, try index.html inside it
        if (is_dir($filePath)) {
            $filePath .= '/index.html';
        }
        
        // If file doesn't exist, try adding .html extension
        if (!file_exists($filePath) && !str_ends_with($path, '.html')) {
            $filePath = $templatePath . '/' . $path . '.html';
        }
        
        return file_exists($filePath) ? $filePath : null;
    }
    
    /**
     * Fix template paths in HTML content to work with current domain
     */
    private function fixTemplatePaths(string $content, string $templateName): string
    {
        // Replace absolute template paths with relative paths
        $content = str_replace(
            "/templates/{$templateName}/",
            "/",
            $content
        );
        
        // Fix API endpoints to use the current domain
        $apiBaseUrl = url('/api/shield-domain');
        $content = str_replace(
            'https://api.yoursaas.com',
            $apiBaseUrl,
            $content
        );
        
        return $content;
    }
    
    /**
     * Get content type based on file extension
     */
    private function getContentType(string $filePath): string
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $contentTypes = [
            'html' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',
        ];
        
        return $contentTypes[$extension] ?? 'text/plain';
    }
}

