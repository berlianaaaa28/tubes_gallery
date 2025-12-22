<?php
use PHPUnit\Framework\TestCase;

class FileTypeTest extends TestCase
{
    private $projectFiles = [
        'index.php',
        'detail.php',
        'favorit.php',
        'cluster.php',
        'trending.php',
        'photo-action.php',
        'list.php',
        'move-to-cluster.php'
    ];

    /**
     * Test 1: Pastikan file utama project ada
     */
    public function test_files_exist()
    {
        foreach ($this->projectFiles as $file) {
            $this->assertFileExists(
                $file,
                "File $file tidak ditemukan!"
            );
        }
    }

    /**
     * Test 2: Pastikan file PHP mengandung kode PHP
     */
    public function test_php_files_contain_php_code()
    {
        foreach ($this->projectFiles as $file) {
            $content = file_get_contents($file);

            $this->assertStringContainsString(
                '<?php',
                $content,
                "File $file tidak mengandung kode PHP!"
            );
        }
    }

    /**
     * Test 3: Pastikan file PHP mengandung elemen HTML
     */
    public function test_php_files_contain_html_elements()
    {
        foreach ($this->projectFiles as $file) {
            $content = file_get_contents($file);

            $this->assertMatchesRegularExpression(
                '/<html|<head|<body|<div|<img|<form|<input|<h1|<h2/i',
                $content,
                "File $file tidak mengandung elemen HTML!"
            );
        }
    }

    /**
     * Test 4: Pastikan tidak ada error sintaks PHP
     */
    public function test_no_syntax_errors_in_php_files()
    {
        foreach ($this->projectFiles as $file) {
            exec("php -l $file", $output, $resultCode);

            $this->assertSame(
                0,
                $resultCode,
                "File $file memiliki error sintaks:\n" . implode("\n", $output)
            );
        }
    }

    /**
     * Test 5: Pastikan identitas aplikasi galeri foto Pexels ada
     */
    public function test_gallery_application_identity_exists()
    {
        $content = strtolower(file_get_contents('index.php'));

        $this->assertTrue(
            str_contains($content, 'pexels') ||
            str_contains($content, 'gallery') ||
            str_contains($content, 'photo'),
            "Identitas aplikasi galeri foto (Pexels) tidak ditemukan!"
        );
    }
}
