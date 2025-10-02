<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>광주새백성교회 청장년회</title>
    <link rel="icon" type="image/png" href="/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/images/favicon/site.webmanifest" />
    <link rel="stylesheet" href="css/tailwind.css">
    <link rel="stylesheet" href="css/pretendardvariable.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-gray-50">
    <!-- 메인 컨테이너 -->
    <div class="app-container">
        <!-- 상단 배경 이미지 -->
        <div class="hero-section">
            <img src="images/youth_light.jpg" class="hero-image hero-image-light" id="hero-light">
            <img src="images/youth_dark.jpg" class="hero-image hero-image-dark" id="hero-dark">
            <div class="hero-overlay"></div>
            <!-- 테마 토글 버튼 -->
            <button id="theme-toggle" class="theme-toggle-btn">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </button>
        </div>
        <!-- 메인 콘텐츠 카드 -->
        <div class="main-card">
                <!-- 모임 정보 헤더 -->
                <div class="card-title p-6 pb-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            <img src="images/site-logo.png" alt="광주새백성교회 로고" class="site-logo">
                            <h1 class="text-xl font-bold text-gray-800">광주새백성교회 청장년회</h1>
                        </div>
                        <?php /*
                        <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            2025년도
                        </button>
                        */ ?>
                    </div>
                </div>
                <div class="p-6 pt-0 pb-4">
                    <!-- 연도별 회원 명단 버튼 -->
                    <button id="yearly-members-btn" class="w-full mt-4 p-3 bg-gray-50 rounded-xl flex items-center justify-between hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-sm text-gray-600">연도별 회원 명단</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
                <!-- 회원 현황 섹션 -->
                <div class="px-6 pb-6">
                    <h2 id="current-year" class="text-lg font-semibold text-gray-800 mb-4"></h2>
                    <div id="current-members" class="grid grid-cols-2 gap-3">
                        <!-- 회원 카드들이 여기에 동적으로 생성됩니다 -->
                    </div>
                </div>

                <!-- 회비 현황 섹션 -->
                <div class="px-6 pb-6 mt-4 mb-4">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">회비 현황</h2>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-center py-2 px-1">회원/월</th>
                                    <th class="text-center py-2 px-1">1</th>
                                    <th class="text-center py-2 px-1">2</th>
                                    <th class="text-center py-2 px-1">3</th>
                                    <th class="text-center py-2 px-1">4</th>
                                    <th class="text-center py-2 px-1">5</th>
                                    <th class="text-center py-2 px-1">6</th>
                                    <th class="text-center py-2 px-1">7</th>
                                    <th class="text-center py-2 px-1">8</th>
                                    <th class="text-center py-2 px-1">9</th>
                                    <th class="text-center py-2 px-1">10</th>
                                    <th class="text-center py-2 px-1">11</th>
                                    <th class="text-center py-2 px-1">12</th>
                                </tr>
                            </thead>
                            <tbody id="dues-table">
                                <!-- 회비 현황이 여기에 동적으로 생성됩니다 -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 모임통장 정보 섹션 -->
                <div class="px-6 pb-20">
                    <h3 class="text-md font-medium text-gray-700 mb-3 account-info">모임통장 <span id="account-bank">로딩중...</span> <span id="account-number">로딩중...</span></h3>
                    <div class="flex gap-3">
                        <a href="https://docs.google.com/spreadsheets/d/1SVkDdbGm4EHqbzSRjQ-xTb5IVDAY4fSZ/edit?usp=share_link&ouid=100222666619716175380&rtpof=true&sd=true" target="_blank" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-medium transition-colors text-center block">
                            회계보고
                        </a>
                        <button id="account-copy-btn" class="flex-1 py-3 bg-blue-500 hover:bg-blue-600 rounded-xl text-white font-medium transition-colors">
                            계좌 복사하기
                        </button>
                    </div>
                </div>
        </div>
    </div>

    <!-- 연도별 회원 명단 모달 -->
    <div id="yearly-members-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-xl font-bold text-gray-800">연도별 회원 명단</h2>
                <button id="close-modal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div id="yearly-members">
                    <!-- 연도별 토글이 여기에 동적으로 생성됩니다 -->
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>

