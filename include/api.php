<?php
/**
 * SQLite API 엔드포인트
 */

// 디버깅을 위한 로그 파일 생성
error_log("API 호출됨: " . date('Y-m-d H:i:s') . " - " . $_SERVER['REQUEST_URI']);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/database.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_GET['path'] ?? '';

try {
    $db = new Database();
    
    switch ($method) {
        case 'GET':
            handleGet($db, $path);
            break;
        case 'POST':
            handlePost($db, $path);
            break;
        case 'PUT':
            handlePut($db, $path);
            break;
        case 'DELETE':
            handleDelete($db, $path);
            break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function handleGet($db, $path) {
    switch ($path) {
        case 'group-info':
            echo json_encode($db->getGroupInfo());
            break;
        case 'years':
            echo json_encode($db->getAllYears());
            break;
        case 'members':
            $year = $_GET['year'] ?? null;
            if ($year) {
                echo json_encode($db->getMembersByYear($year));
            } else {
                echo json_encode(['error' => 'Year parameter required']);
            }
            break;
        case 'all-data':
            echo json_encode($db->getAllData());
            break;
        case 'test':
            // 간단한 연결 테스트
            $dbPath = $db->getDbPath();
            $fileExists = file_exists($dbPath);
            $fileSize = $fileExists ? filesize($dbPath) : 0;
            $projectRootDb = __DIR__ . '/../db/data.db';
            
            // 프로젝트 루트 여부를 더 정확하게 판단
            $realDbPath = realpath($dbPath) ?: $dbPath;
            $realProjectRootDb = realpath($projectRootDb) ?: $projectRootDb;
            $isInProjectRoot = ($realDbPath === $realProjectRootDb) || 
                              (strpos($realDbPath, dirname($realProjectRootDb)) !== false && 
                               basename($realDbPath) === 'data.db');
            
            echo json_encode([
                'success' => true, 
                'message' => 'API is working', 
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
        case 'ping':
            // 간단한 ping 테스트
            echo json_encode([
                'success' => true, 
                'message' => 'Pong!', 
                'timestamp' => date('Y-m-d H:i:s'),
                'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'php_version' => phpversion(),
                'current_dir' => __DIR__,
                'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown'
            ]);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

function handlePost($db, $path) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($path) {
        case 'members':
            $result = $db->addMember(
                $input['year'],
                $input['name'],
                $input['position'],
                $input['photo'],
                $input['dues']
            );
            echo json_encode(['success' => $result]);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

function handlePut($db, $path) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($path) {
        case 'group-info':
            $result = $db->updateGroupInfo(
                $input['name'],
                $input['bank'],
                $input['accountNumber']
            );
            echo json_encode(['success' => $result]);
            break;
        case 'members':
            $result = $db->updateMember(
                $input['id'],
                $input['name'],
                $input['position'],
                $input['photo'],
                $input['dues']
            );
            echo json_encode(['success' => $result]);
            break;
        case 'dues':
            $result = $db->updateDues(
                $input['id'],
                $input['month'],
                $input['paid']
            );
            echo json_encode(['success' => $result]);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

function handleDelete($db, $path) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    switch ($path) {
        case 'members':
            $result = $db->deleteMember($input['id']);
            echo json_encode(['success' => $result]);
            break;
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint not found']);
    }
}

?>
