<?php

namespace Akceli;

use Illuminate\Console\Command;

/**
 * Class Log
 * @package Akceli
 *
 * @mixin Command
 * @method static comment(string $message, $verbosity = null)
 * @method static info(string $message, $verbosity = null)
 * @method static error(string $message, $verbosity = null)
 * @method static warn(string $message, $verbosity = null)
 * @method static alert(string $message, $verbosity = null)
 * @method static ask($question, $default = null)
 * @method static choice($question, array $choices, $default = null, $attempts = null, $multiple = null)
 */
class Console
{
    /**
     * @var Command
     */
    private static $logger;

    /**
     * @param Command $logger
     */
    public static function setLogger(Command $logger)
    {
        self::$logger = $logger;
    }

    public static function __callStatic($name, $arguments)
    {
        return call_user_func([self::$logger, $name], ...$arguments);
    }


    /**
     * Interactive directory navigator for selecting or creating nested paths
     *
     * Allows users to:
     * - Browse existing directories
     * - Navigate into subdirectories
     * - Go back to parent directories
     * - Create new directories at any level
     * - Specify a final name (like a component/file name)
     *
     * @param string $basePath Base directory to navigate (e.g., 'app/Dto', 'resources/js/pages')
     * @param string|null $defaultPath Default path to suggest (e.g., 'Default/Example')
     * @param string|null $finalNamePrompt Prompt for final name (e.g., 'Page component name?')
     * @param string|null $finalNameDefault Default value for final name (e.g., 'Index')
     * @param bool $requireFinalName Whether to ask for a final name at the end
     * @return string The selected/created path (e.g., 'Dto/Default/ExamplePage')
     *
     * @example
     * // Navigate DTO directory with final name
     * $path = Console::dirNavigator(
     *     basePath: 'app/Dto',
     *     defaultPath: 'Default/Example',
     *     finalNamePrompt: 'DTO name?',
     *     finalNameDefault: 'UserDto'
     * );
     * // Returns: 'Default/UserDto'
     *
     * @example
     * // Navigate pages directory
     * $path = Console::dirNavigator(
     *     basePath: resource_path('js/pages'),
     *     defaultPath: 'Dashboard',
     *     finalNamePrompt: 'Page component name?',
     *     finalNameDefault: 'Index'
     * );
     * // Returns: 'Users/Index'
     *
     * @example
     * // Just directory selection, no final name
     * $path = Console::dirNavigator(
     *     basePath: 'app/Services',
     *     requireFinalName: false
     * );
     * // Returns: 'Payment/Stripe'
     */
    public static function dirNavigator(
        string $basePath,
        ?string $defaultPath = null,
        ?string $finalNamePrompt = null,
        ?string $finalNameDefault = null,
        bool $requireFinalName = true
    ): string {
        // Normalize base path
        $basePath = rtrim($basePath, DIRECTORY_SEPARATOR);

        // Convert base path to absolute if it's relative
        if (!self::isAbsolutePath($basePath)) {
            $basePath = base_path($basePath);
        }

        // Extract base name for display
        $baseDisplayName = basename($basePath);

        // Start with empty path
        $selectedPath = [];

        // Show header
        self::info('📁 Directory Navigator');
        if ($defaultPath) {
            self::comment("Default: {$baseDisplayName}/{$defaultPath}");
        }
        self::info('');

        // Directory navigation loop
        $navigating = true;
        while ($navigating) {
            $currentPath = $basePath . (empty($selectedPath) ? '' : DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $selectedPath));

            // Get existing directories at current level
            $existingDirs = [];
            if (is_dir($currentPath)) {
                $existingDirs = self::getDirectoriesInPath($currentPath);
                sort($existingDirs);
            }

            // Show current location
            $contextPath = empty($selectedPath)
                ? "{$baseDisplayName}/"
                : "{$baseDisplayName}/" . implode('/', $selectedPath) . '/';
            self::comment("📍 Current: {$contextPath}");

            // Build choices
            $choices = [];

            // Add "go back" option if we're nested
            if (!empty($selectedPath)) {
                $choices[] = '⬆️  .. (go back)';
            }

            // Add existing directories
            foreach ($existingDirs as $dir) {
                $choices[] = "📁 {$dir}";
            }

            // Add action options
            $choices[] = '➕ Create new directory';
            $choices[] = '✅ Finish here';

            // If we want to use default and we're at root level, add that option
            if ($defaultPath && empty($selectedPath)) {
                $choices[] = "⚡ Use default ({$defaultPath})";
            }

            // Get selection
            $selection = self::choice(
                'Select directory or action:',
                $choices,
                '✅ Finish here',
                1
            );

            // Handle selection
            if ($selection === '✅ Finish here') {
                $navigating = false;
            } elseif ($selection === '⬆️  .. (go back)') {
                array_pop($selectedPath);
            } elseif ($selection === '➕ Create new directory') {
                $newDir = self::ask('New directory name:', 'NewDirectory');
                $newDir = Str::studly($newDir); // Convert to StudlyCase
                $selectedPath[] = $newDir;
                self::info("✓ Created path: {$newDir}");
                self::info('');
            } elseif (isset($defaultPath) && $selection === "⚡ Use default ({$defaultPath})") {
                // Use the default path
                $selectedPath = explode('/', $defaultPath);
                $navigating = false;
            } else {
                // Navigate into selected directory (remove emoji and trim)
                $dirName = trim(str_replace('📁', '', $selection));
                $selectedPath[] = $dirName;
            }
        }

        self::info('');

        // Get final name if required
        if ($requireFinalName) {
            $currentLocation = empty($selectedPath)
                ? "{$baseDisplayName}/"
                : "{$baseDisplayName}/" . implode('/', $selectedPath) . '/';

            self::comment("📝 Creating in: {$currentLocation}");

            $prompt = $finalNamePrompt ?? 'Name:';
            $default = $finalNameDefault ?? 'NewItem';

            $finalName = self::ask($prompt, $default);
            $selectedPath[] = $finalName;
        }

        $fullPath = implode('/', $selectedPath);

        self::info('');
        self::info("✓ Selected path: {$fullPath}");
        self::info('');

        return $fullPath;
    }

    /**
     * Get directories in a given path
     *
     * @param string $path
     * @return array
     */
    protected static function getDirectoriesInPath(string $path): array
    {
        $directories = [];

        if (!is_dir($path)) {
            return [];
        }

        $items = @scandir($path);

        if ($items === false) {
            return [];
        }

        foreach ($items as $item) {
            // Skip current and parent directory references
            if ($item === '.' || $item === '..') {
                continue;
            }

            $fullPath = $path . DIRECTORY_SEPARATOR . $item;

            // Only include directories
            if (is_dir($fullPath)) {
                $directories[] = $item;
            }
        }

        return $directories;
    }

    /**
     * Check if a path is absolute
     *
     * @param string $path
     * @return bool
     */
    protected static function isAbsolutePath(string $path): bool
    {
        // Unix-like systems: starts with /
        if (substr($path, 0, 1) === '/') {
            return true;
        }

        // Windows: starts with drive letter (e.g., C:\)
        if (strlen($path) > 1 && substr($path, 1, 1) === ':') {
            return true;
        }

        return false;
    }
}
