<?php
/**
 * SQLite 데이터베이스 연결 및 관리 클래스
 */

class Database {
    private $pdo;
    private $dbPath;
    
    public function __construct() {
        // db 폴더 내의 data.db만 사용하도록 설정
        $dbFolder = __DIR__ . '/../db/';
        $dbFolderDb = $dbFolder . 'data.db';
        
        // db 폴더가 없으면 생성
        if (!is_dir($dbFolder)) {
            if (!mkdir($dbFolder, 0755, true)) {
                throw new Exception('db 폴더를 생성할 수 없습니다: ' . $dbFolder);
            }
        }
        
        // db 폴더의 쓰기 권한 확인
        if (!is_writable($dbFolder)) {
            if (!chmod($dbFolder, 0755)) {
                throw new Exception('db 폴더에 쓰기 권한을 설정할 수 없습니다: ' . $dbFolder);
            }
        }
        
        // db 폴더의 data.db 사용
        $this->dbPath = $dbFolderDb;
        $this->connect();
        $this->createTables();
    }
    

    private function connect() {
        try {
            // 데이터베이스 파일이 없으면 생성
            if (!file_exists($this->dbPath)) {
                if (!touch($this->dbPath)) {
                    throw new Exception("데이터베이스 파일 생성 실패: " . $this->dbPath);
                }
                chmod($this->dbPath, 0666);
            }
            
            $this->pdo = new PDO('sqlite:' . $this->dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception('데이터베이스 연결 실패: ' . $e->getMessage() . ' (파일 경로: ' . $this->dbPath . ')');
        }
    }

    private function createTables() {
        // 그룹 정보 테이블
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS group_info (
                id INTEGER PRIMARY KEY,
                name TEXT NOT NULL,
                bank TEXT NOT NULL,
                account_number TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        // 회원 테이블
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS members (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                year INTEGER NOT NULL,
                name TEXT NOT NULL,
                position TEXT NOT NULL,
                photo TEXT NOT NULL,
                dues TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        // 이벤트 정보 테이블
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS event_info (
                id INTEGER PRIMARY KEY,
                title TEXT NOT NULL,
                date TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        // 성경 구절 테이블
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS slogan_info (
                id INTEGER PRIMARY KEY,
                text TEXT NOT NULL,
                reference TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        // 모임통장 정보 테이블
        $this->pdo->exec('
            CREATE TABLE IF NOT EXISTS account_info (
                id INTEGER PRIMARY KEY,
                bank TEXT NOT NULL,
                number TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');

        // 인덱스 생성
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS idx_members_year ON members(year)');
        $this->pdo->exec('CREATE INDEX IF NOT EXISTS idx_members_name ON members(name)');
    }

    // 그룹 정보 관련 메서드
    public function getGroupInfo() {
        $stmt = $this->pdo->query('SELECT * FROM group_info ORDER BY id DESC LIMIT 1');
        $result = $stmt->fetch();
        
        if ($result) {
            return [
                'name' => $result['name'],
                'accountInfo' => [
                    'bank' => $result['bank'],
                    'accountNumber' => $result['account_number']
                ]
            ];
        }
        
        // 기본값을 데이터베이스에 저장
        $this->updateGroupInfo('광주새백성교회 청장년회', '토스뱅크', '1001-7545-1977');
        return [
            'name' => '광주새백성교회 청장년회',
            'accountInfo' => [
                'bank' => '토스뱅크',
                'accountNumber' => '1001-7545-1977'
            ]
        ];
    }

    public function updateGroupInfo($name, $bank, $accountNumber) {
        $stmt = $this->pdo->prepare('
            INSERT OR REPLACE INTO group_info (id, name, bank, account_number, updated_at)
            VALUES (1, ?, ?, ?, CURRENT_TIMESTAMP)
        ');
        return $stmt->execute([$name, $bank, $accountNumber]);
    }

    public function saveGroupInfo($name) {
        $stmt = $this->pdo->prepare('
            INSERT OR REPLACE INTO group_info (id, name, bank, account_number, updated_at)
            VALUES (1, ?, "기본은행", "기본계좌번호", CURRENT_TIMESTAMP)
        ');
        return $stmt->execute([$name]);
    }

    // 회원 관련 메서드
    public function getMembersByYear($year) {
        $stmt = $this->pdo->prepare('SELECT * FROM members WHERE year = ? ORDER BY id');
        $stmt->execute([$year]);
        $members = $stmt->fetchAll();
        
        // dues JSON 디코딩
        foreach ($members as &$member) {
            $member['dues'] = json_decode($member['dues'], true);
        }
        
        return $members;
    }

    public function getAllYears() {
        $stmt = $this->pdo->query('SELECT DISTINCT year FROM members ORDER BY year DESC');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getMember($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM members WHERE id = ?');
        $stmt->execute([$id]);
        $member = $stmt->fetch();
        
        if ($member) {
            $member['dues'] = json_decode($member['dues'], true);
        }
        
        return $member;
    }

    public function addMember($year, $name, $position, $photo, $dues) {
        $stmt = $this->pdo->prepare('
            INSERT INTO members (year, name, position, photo, dues)
            VALUES (?, ?, ?, ?, ?)
        ');
        return $stmt->execute([$year, $name, $position, $photo, json_encode($dues)]);
    }

    public function updateMember($id, $name, $position, $photo, $dues) {
        $stmt = $this->pdo->prepare('
            UPDATE members 
            SET name = ?, position = ?, photo = ?, dues = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ');
        return $stmt->execute([$name, $position, $photo, json_encode($dues), $id]);
    }

    public function deleteMember($id) {
        $stmt = $this->pdo->prepare('DELETE FROM members WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public function updateDues($id, $month, $paid) {
        $member = $this->getMember($id);
        if ($member) {
            $dues = $member['dues'];
            $dues[$month] = $paid;
            return $this->updateMember($id, $member['name'], $member['position'], $member['photo'], $dues);
        }
        return false;
    }

    // 이벤트 정보 관련 메서드
    public function getEventInfo() {
        $stmt = $this->pdo->query('SELECT * FROM event_info ORDER BY id DESC LIMIT 1');
        $result = $stmt->fetch();
        
        if ($result) {
            return [
                'title' => $result['title'],
                'date' => $result['date']
            ];
        }
        
        // 기본값을 데이터베이스에 저장
        $this->saveEventInfo('청장년회 월례회', '2025-10-12');
        return ['title' => '청장년회 월례회', 'date' => '2025-10-12'];
    }

    public function saveEventInfo($title, $date) {
        $stmt = $this->pdo->prepare('
            INSERT OR REPLACE INTO event_info (id, title, date, updated_at)
            VALUES (1, ?, ?, CURRENT_TIMESTAMP)
        ');
        return $stmt->execute([$title, $date]);
    }

    // 성경 구절 관련 메서드
    public function getSloganInfo() {
        $stmt = $this->pdo->query('SELECT * FROM slogan_info ORDER BY id DESC LIMIT 1');
        $result = $stmt->fetch();
        
        if ($result) {
            return [
                'text' => $result['text'],
                'reference' => $result['reference']
            ];
        }
        
        // 기본값을 데이터베이스에 저장
        $this->saveSlogan('새벽 이슬 같은 주의 청년들', '시 110:3');
        return ['text' => '새벽 이슬 같은 주의 청년들', 'reference' => '시 110:3'];
    }

    public function saveSlogan($text, $reference) {
        $stmt = $this->pdo->prepare('
            INSERT OR REPLACE INTO slogan_info (id, text, reference, updated_at)
            VALUES (1, ?, ?, CURRENT_TIMESTAMP)
        ');
        return $stmt->execute([$text, $reference]);
    }

    // 모임통장 정보 관련 메서드
    public function getAccountInfo() {
        $stmt = $this->pdo->query('SELECT * FROM account_info ORDER BY id DESC LIMIT 1');
        $result = $stmt->fetch();
        
        if ($result) {
            return [
                'bank' => $result['bank'],
                'number' => $result['number']
            ];
        }
        
        // 기본값을 데이터베이스에 저장
        $this->saveAccountInfo('토스뱅크', '1001-7545-1977');
        return ['bank' => '토스뱅크', 'number' => '1001-7545-1977'];
    }

    public function saveAccountInfo($bank, $number) {
        $stmt = $this->pdo->prepare('
            INSERT OR REPLACE INTO account_info (id, bank, number, updated_at)
            VALUES (1, ?, ?, CURRENT_TIMESTAMP)
        ');
        return $stmt->execute([$bank, $number]);
    }

    // 데이터베이스 경로 반환
    public function getDbPath() {
        return $this->dbPath;
    }
    
    // 전체 데이터 구조 반환 (기존 YAML 구조와 호환)
    public function getAllData() {
        $groupInfo = $this->getGroupInfo();
        $years = [];
        
        foreach ($this->getAllYears() as $year) {
            $members = $this->getMembersByYear($year);
            $years[] = [
                'year' => $year,
                'members' => $members
            ];
        }
        
        return [
            'groupInfo' => $groupInfo,
            'years' => $years
        ];
    }

    // YAML에서 SQLite로 마이그레이션
    public function migrateFromYaml($yamlData) {
        try {
            $this->pdo->beginTransaction();
            
            // 그룹 정보 마이그레이션
            if (isset($yamlData['groupInfo'])) {
                $groupInfo = $yamlData['groupInfo'];
                $this->updateGroupInfo(
                    $groupInfo['name'],
                    $groupInfo['accountInfo']['bank'],
                    $groupInfo['accountInfo']['accountNumber']
                );
            }
            
            // 기존 데이터 삭제
            $this->pdo->exec('DELETE FROM members');
            
            // 회원 데이터 마이그레이션
            if (isset($yamlData['years'])) {
                foreach ($yamlData['years'] as $yearData) {
                    foreach ($yearData['members'] as $member) {
                        $this->addMember(
                            $yearData['year'],
                            $member['name'],
                            $member['position'],
                            $member['photo'],
                            $member['dues']
                        );
                    }
                }
            }
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function addYear($year) {
        // 연도가 이미 존재하는지 확인
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM members WHERE year = ?');
        $stmt->execute([$year]);
        $count = $stmt->fetchColumn();
        
        if ($count > 0) {
            return false; // 이미 존재하는 연도
        }
        
        // 새로운 연도는 빈 회원 목록으로 생성
        // 실제로는 연도가 존재하지 않으므로 성공으로 반환
        // 연도 선택 옵션에만 추가되고, 실제 회원이 추가될 때까지는 빈 상태
        return true;
    }
}
?>

