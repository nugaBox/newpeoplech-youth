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
        case 'event':
            echo json_encode($db->getEventInfo());
            break;
        case 'slogan':
            echo json_encode($db->getSloganInfo());
            break;
        case 'account-info':
            echo json_encode($db->getAccountInfo());
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
    switch ($path) {
        case 'members':
            // action 파라미터 확인
            $action = $_GET['action'] ?? 'add';
            
            if ($action === 'update') {
                // 회원 수정 로직
                $id = $_POST['id'] ?? null;
                $year = $_POST['year'] ?? null;
                $name = $_POST['name'] ?? null;
                $position = $_POST['position'] ?? null;
                $dues = json_decode($_POST['dues'] ?? '{}', true);
                
                // 파일 업로드 처리
                $deleteExistingPhoto = $_POST['delete_existing_photo'] ?? '0';
                $photoPath = 'images/default-avatar.svg';
                
                // 기존 사진 삭제가 체크된 경우
                if ($deleteExistingPhoto === '1') {
                    $photoPath = 'images/default-avatar.svg';
                } else if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    // 새 사진이 업로드된 경우
                    $uploadDir = __DIR__ . '/../images/upload/';
                    $fileName = $_FILES['photo']['name'];
                    $uploadPath = $uploadDir . $fileName;
                    
                    // 업로드 디렉토리가 없으면 생성
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                        $photoPath = 'images/upload/' . $fileName;
                    } else {
                        error_log("파일 업로드 실패: " . $_FILES['photo']['tmp_name'] . " -> " . $uploadPath);
                    }
                } else if (isset($_POST['existing_photo']) && !empty($_POST['existing_photo']) && !isset($_FILES['photo'])) {
                    // 기존 사진 유지 (새 파일이 업로드되지 않은 경우에만)
                    $photoPath = $_POST['existing_photo'];
                }
                
                $result = $db->updateMember($id, $name, $position, $photoPath, $dues);
                echo json_encode(['success' => $result]);
            } else {
                // 회원 추가 로직
                $year = $_POST['year'] ?? null;
                $name = $_POST['name'] ?? null;
                $position = $_POST['position'] ?? null;
                $dues = json_decode($_POST['dues'] ?? '{}', true);
                
                // 파일 업로드 처리
                $photoPath = 'images/default-avatar.svg';
                if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . '/../images/upload/';
                    $fileName = $_FILES['photo']['name'];
                    $uploadPath = $uploadDir . $fileName;
                    
                    // 업로드 디렉토리가 없으면 생성
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                        $photoPath = 'images/upload/' . $fileName;
                    } else {
                        error_log("파일 업로드 실패: " . $_FILES['photo']['tmp_name'] . " -> " . $uploadPath);
                    }
                } else {
                    error_log("파일 업로드 오류: " . ($_FILES['photo']['error'] ?? '파일이 없음'));
                }
                
                $result = $db->addMember($year, $name, $position, $photoPath, $dues);
                echo json_encode(['success' => $result]);
            }
            break;
        case 'years':
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $db->addYear($input['year']);
            echo json_encode(['success' => $result]);
            break;
        case 'event':
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $db->saveEventInfo(
                $input['title'],
                $input['date']
            );
            echo json_encode(['success' => $result]);
            break;
        case 'group-info':
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $db->saveGroupInfo($input['name']);
            echo json_encode(['success' => $result]);
            break;
        case 'slogan':
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $db->saveSlogan(
                $input['text'],
                $input['reference']
            );
            echo json_encode(['success' => $result]);
            break;
        case 'account-info':
            $input = json_decode(file_get_contents('php://input'), true);
            $result = $db->saveAccountInfo(
                $input['bank'],
                $input['number']
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
