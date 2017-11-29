<?php
declare(strict_types=1);

namespace EasyDB;

use \EasyDB\Exception as Issues;

/**
 * Class Factory
 *
 * @package EasyDB
 */
abstract class Factory
{
    /**
     * Create a new EasyDB object based on PDO constructors
     *
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array $options
     * @return \EasyDB\EasyDB
     * @throws Issues\ConstructorFailed
     */
    public static function create(
        string $dsn,
        string $username = null,
        string $password = null,
        array $options = []
    ): EasyDB {
        $dbEngine = '';
        $post_query = null;
        
        if (!\is_string($username)) {
            $username = '';
        }
        if (!\is_string($password)) {
            $password = '';
        }

        // Let's grab the DB engine
        if (strpos($dsn, ':') !== false) {
            $dbEngine = explode(':', $dsn)[0];
        }

        /** @var string $post_query */
        $post_query = '';

        // If no charset is specified, default to UTF-8
        switch ($dbEngine) {
            case 'mysql':
                if (\strpos($dsn, ';charset=') === false) {
                    $dsn .= ';charset=utf8mb4';
                }
                break;
            case 'pgsql':
                $post_query = 'SET NAMES UNICODE';
                break;
            case 'sqlite':
                $post_query = 'SET NAMES UTF8';
                break;
        }

        try {
            $pdo = new \PDO($dsn, $username, $password, $options);
        } catch (\PDOException $e) {
            // Don't leak credentials directly if we can.
            throw new Issues\ConstructorFailed(
                'Could not create a PDO connection. Please check your username and password.'
            );
        }

        if (!empty($post_query)) {
            $pdo->query($post_query);
        }

        return new EasyDB($pdo, $dbEngine, $options);
    }
}
