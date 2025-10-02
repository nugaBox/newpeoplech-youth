<?php
/**
 * 마이그레이션 전용 API
 */

// 디버깅을 위한 로그
error_log("Migration API 호출됨: " . date('Y-m-d H:i:s') . " - " . $_SERVER['REQUEST_URI']);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../include/database.php';
require_once __DIR__ . '/migrate_data.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

try {
    $db = new Database();
    
    switch ($method) {
        case 'GET':
            handleMigrateGet($db, $path);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function handleMigrateGet($db, $path) {
    switch ($path) {
        case 'migrate':
            // 마이그레이션 실행
            $result = migrateFromYaml($db);
            echo json_encode($result);
            break;
        case 'test':
            // 간단한 연결 테스트
            $dbPath = $db->getDbPath();
            $fileExists = file_exists($dbPath);
            $fileSize = $fileExists ? filesize($dbPath) : 0;
            $projectRootDb = __DIR__ . '/data.db';
            
            // 프로젝트 루트 여부를 더 정확하게 판단
            $realDbPath = realpath($dbPath) ?: $dbPath;
            $realProjectRootDb = realpath($projectRootDb) ?: $projectRootDb;
            $isInProjectRoot = ($realDbPath === $realProjectRootDb) || 
                              (strpos($realDbPath, dirname($realProjectRootDb)) !== false && 
                               basename($realDbPath) === 'data.db');
            
            echo json_encode([
                'success' => true, 
                'message' => 'Migration API is working', 
                'timestamp' => date('Y-m-d H:i:s'), 
                'dbPath' => $dbPath,
                'fileExists' => $fileExists,
                'fileSize' => $fileSize,
                'absolutePath' => $realDbPath,
                'projectRootDb' => $projectRootDb,
                'realProjectRootDb' => $realProjectRootDb,
                'isInProjectRoot' => $isInProjectRoot
            ]);
            break;
        case 'all-data':
            echo json_encode($db->getAllData());
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

function migrateFromYaml($db) {
    try {
        error_log("마이그레이션 시작: " . date('Y-m-d H:i:s'));
        
        // 새로운 마이그레이션 데이터 사용
        $yamlData = getNewMigrationData();
        error_log("마이그레이션 데이터 로드 완료: " . count($yamlData['years']) . "개 연도");
        
        $db->migrateFromYaml($yamlData);
        error_log("마이그레이션 완료: " . date('Y-m-d H:i:s'));
        
        return ['success' => true, 'message' => 'Migration completed successfully'];
    } catch (Exception $e) {
        error_log("마이그레이션 오류: " . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
?>
