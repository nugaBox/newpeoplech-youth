# 광주새백성교회 청장년회 홈페이지

광주새백성교회 청장년회 홈페이지 모바일 우선 반응형 웹사이트입니다.

> 사용자 URL : https://youth.newpeoplech.com
>
> 관리자 URL : https://youth.newpeoplech.com/admin.php
>
> Original URL : https://youth.nugabox.com (Proxy)

## Features

### 사용자 페이지

#### 기본 기능

-   **회원 현황**: 현재 연도 회원들의 사진, 이름, 직급 표시
-   **연도별 회원 명단**: 토글 방식으로 연도별 회원 목록 확인 (모달 팝업)
-   **회비 현황**: 월별 회비 납부 상태를 시각적으로 표시 (✓/✗), 연도별 조회 가능
-   **모임통장 정보**: 계좌번호 복사 기능 (클립보드 API 지원)
-   **다음 이벤트**: 예정된 행사 정보 표시 (제목, 날짜)

### 관리자 페이지

1. **이벤트 관리**: 다음 이벤트 제목 및 날짜 설정
2. **모임 정보**: 모임명 수정
3. **성경 구절**: 성경 구절 텍스트 및 참조 설정
4. **회원 관리**: 연도별 회원 추가/수정/삭제, 사진 업로드
5. **회비 관리**: 월별 회비 납부 상태 토글 관리
6. **모임통장**: 은행명 및 계좌번호 설정

## Stacks

-   **Frontend**: HTML5, CSS3, JavaScript (ES6+)
-   **CSS Framework**: Tailwind CSS v4.1.14 (npm 설치)
-   **Font**: Pretendard Variable
-   **Data Storage**: SQLite 데이터베이스
-   **PHP**: 8.0+ (기본 구조만 사용)
-   **Build Tool**: npm scripts

## Manual

### 1. 프로젝트 클론 및 의존성 설치

```bash
# 프로젝트 클론
git clone [repository-url]
cd newpeoplech-youth

# 의존성 설치
npm install
```

### 2. Tailwind CSS 빌드

```bash
# 프로덕션 빌드 (압축된 CSS 생성)
npm run build

# 개발용 빌드 (파일 변경 감지하여 자동 빌드)
npm run dev
```

### 3. 웹 서버 실행

```bash
# PHP 내장 서버 사용 (개발용)
php -S localhost:8000

# 또는 Apache/Nginx 설정
```

### 4. 데이터베이스 마이그레이션

```bash
# 웹 브라우저에서 마이그레이션 실행
http://localhost:8000/include/migrate.html
```

### 5. 브라우저 접속

-   **사용자 페이지**: `http://localhost:8000/index.php`
-   **관리자 페이지**: `http://localhost:8000/admin.php`
-   **마이그레이션**: `http://localhost:8000/include/migrate.html`

## 개발 워크플로우

### Tailwind CSS 커스터마이징

1. **설정 파일 수정**

    ```bash
    # tailwind.config.js에서 테마 커스터마이징
    # src/input.css에서 추가 스타일 정의
    ```

2. **개발 모드 실행**

    ```bash
    npm run dev
    # 파일 변경을 감지하여 자동으로 CSS 재빌드
    ```

3. **프로덕션 빌드**
    ```bash
    npm run build
    # 압축된 CSS 파일 생성
    ```

### 빌드된 파일 위치

-   **CSS**: `css/tailwind.css` (Tailwind CSS 유틸리티 클래스)
-   **기존 CSS**: `css/style.css` (커스텀 스타일)
-   **폰트**: `css/pretendardvariable.css` (Pretendard 폰트)

### npm 스크립트

```bash
npm run build        # 프로덕션 빌드 (압축)
npm run build-css    # 개발용 빌드 (감시 모드)
npm run dev          # 개발용 빌드 (감시 모드)
```
