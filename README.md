# 광주새백성교회 청장년회 홈페이지

광주새백성교회 청장년회를 위한 모바일 우선 반응형 웹사이트입니다.

## 주요 기능

### 사용자 페이지 (index.php)

-   **회원 현황**: 현재 연도 회원들의 사진, 이름, 직급 표시
-   **연도별 회원 명단**: 토글 방식으로 연도별 회원 목록 확인
-   **회비 현황**: 월별 회비 납부 상태를 시각적으로 표시 (✓/✗)
-   **모임통장 정보**: 계좌번호 복사 기능
-   **반응형 디자인**: 데스크탑, 태블릿, 모바일 모든 기기 지원
-   **터치 인터랙션**: 메인 카드 터치로 확장/축소 가능

### 관리자 페이지 (admin.php)

-   **연도 관리**: 새 연도 추가, 기존 연도 수정/삭제
-   **회원 관리**: 회원 추가/수정/삭제, 사진 업로드, 직급 설정
-   **회비 관리**: 월별 회비 납부 상태 토글 관리

## 기술 스택

-   **Frontend**: HTML5, CSS3, JavaScript (ES6+)
-   **CSS Framework**: Tailwind CSS (CDN)
-   **Font**: Noto Sans KR
-   **Data Storage**: JSON 파일 기반
-   **PHP**: 8.0+ (기본 구조만 사용)

## 파일 구조

```
newpeoplech-youth/
├── index.php              # 사용자 메인 페이지
├── admin.php              # 관리자 페이지
├── data.json              # 데이터 저장소
├── README.md              # 프로젝트 설명서
├── css/
│   └── style.css          # 커스텀 스타일
├── js/
│   ├── script.js          # 사용자 페이지 JavaScript
│   └── admin-script.js    # 관리자 페이지 JavaScript
├── images/
│   ├── default-avatar.svg # 기본 아바타 이미지
│   ├── hero-bg.jpg        # 히어로 배경 이미지
│   └── members/           # 회원 사진 폴더
└── fonts/                 # 폰트 파일 폴더
```

## 설치 및 실행

1. **웹 서버 설정**

    ```bash
    # PHP 내장 서버 사용 (개발용)
    php -S localhost:8000

    # 또는 Apache/Nginx 설정
    ```

2. **브라우저 접속**
    - 사용자 페이지: `http://localhost:8000/index.php`
    - 관리자 페이지: `http://localhost:8000/admin.php`

## 사용법

### 사용자 페이지

1. 메인 카드를 터치하여 확장/축소
2. 연도별 회원 명단 토글 클릭
3. 모임통장 정보 버튼 클릭하여 계좌번호 복사
4. 회비 현황에서 월별 납부 상태 확인

### 관리자 페이지

1. **연도 관리 탭**

    - 새 연도 추가
    - 기존 연도 수정/삭제

2. **회원 관리 탭**

    - 연도 선택 후 회원 추가/수정/삭제
    - 회원 사진 업로드
    - 직급 설정

3. **회비 관리 탭**
    - 연도 선택 후 월별 회비 상태 토글
    - ✓ (납부) / ✗ (미납) 상태 변경

## 데이터 구조

### data.json 구조

```json
{
    "groupInfo": {
        "name": "광주새백성교회 청장년회",
        "accountInfo": {
            "bank": "토스뱅크",
            "accountNumber": "111-11-1111"
        }
    },
    "years": [
        {
            "year": 2024,
            "members": [
                {
                    "id": 1,
                    "name": "김철수",
                    "position": "회장",
                    "photo": "images/default-avatar.svg",
                    "dues": {
                        "1": true,
                        "2": true
                        // ... 12개월
                    }
                }
            ]
        }
    ]
}
```

## 커스터마이징

### 스타일 수정

-   `css/style.css` 파일에서 색상, 레이아웃 등 수정 가능
-   Tailwind CSS 클래스 활용

### 데이터 수정

-   `data.json` 파일 직접 편집
-   관리자 페이지를 통한 웹 인터페이스 편집

### 이미지 추가

-   `images/members/` 폴더에 회원 사진 추가
-   기본 아바타는 `images/default-avatar.svg` 수정

## 브라우저 지원

-   Chrome 60+
-   Firefox 55+
-   Safari 12+
-   Edge 79+

## 라이선스

이 프로젝트는 MIT 라이선스 하에 배포됩니다.

## 문의

프로젝트 관련 문의사항이 있으시면 개발자에게 연락해주세요.

