<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>광주새백성교회 청장년회</title>
    <?php // 메타 정보 ?>
    <meta name="description" content="새벽 이슬 같은 주의 청년들">
    <meta name="keywords" content="광주새백성교회, 청장년회, 교회, 청년, 월례회">
    <meta name="author" content="광주새백성교회">
    <?php // Open Graph 메타 태그 ?>
    <meta property="og:title" content="광주새백성교회 청장년회">
    <meta property="og:description" content="새벽 이슬 같은 주의 청년들">
    <meta property="og:image" content="/images/meta_image.jpg">
    <meta property="og:url" content="https://youth.newpeoplech.com">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="광주새백성교회 청장년회">
    <?php // Favicon ?>
    <link rel="icon" type="image/png" href="/images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png" />
    <link rel="manifest" href="/images/favicon/site.webmanifest" />
    <?php // CSS ?>
    <link rel="stylesheet" href="css/tailwind.css">
    <link rel="stylesheet" href="css/pretendardvariable.css">
    <link rel="stylesheet" href="css/style.css">
    <?php // Feather Icons ?>
    <script src="js/feather-icons-4.29.2.min.js"></script>
</head>
<body class="bg-gray-50">
    <?php // 메인 컨테이너 ?>
    <div class="app-container">
        <?php // 상단 배경 이미지 ?>
        <div class="hero-section">
            <img src="images/youth_light.jpg" class="hero-image hero-image-light" id="hero-light">
            <img src="images/youth_dark.jpg" class="hero-image hero-image-dark" id="hero-dark">
            <div class="hero-overlay"></div>
            <?php /* // 교회 홈페이지 버튼
            <a href="https://www.newpeoplech.com" target="_blank" class="theme-toggle-btn" style="left: 20px;">
                <i data-feather="home" class="w-5 h-5"></i>
            </a>
            */ ?>
            <a href="/admin.php" class="theme-toggle-btn" style="left: 20px;">
                <i data-feather="settings" class="w-5 h-5"></i>
            </a>
            <?php // 테마 토글 버튼 ?>
            <button id="theme-toggle" class="theme-toggle-btn">
                <i data-feather="sun" class="w-5 h-5"></i>
            </button>
            
            <?php // 다음 이벤트 카드 (hero-section 오버레이) ?>
            <div class="event-card-glass next-event-card">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <i data-feather="calendar" class="w-6 h-6 text-gray-700"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-gray-700 text-sm" id="next-event-date">예정된 행사</p>
                        <p class="text-gray-700 font-semibold text-base" id="next-event-title">​</p>
                    </div>
                </div>
            </div>
        </div>
        <?php // 메인 콘텐츠 카드 ?>
        <div class="main-card">
                <?php // 모임 정보 헤더 ?>
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
                    <?php // 성경 구절 섹션 ?>
                    <div class="p-4 border border-gray-200 rounded-xl text-center" id="slogan-title">
                        <p class="text-gray-800 font-semibold text-lg">새벽 이슬 같은 주의 청년들<small class="text-gray-500 text-xs">시 110:3</small></p>
                    </div>
                    
                    <?php // 연도별 회원 명단 버튼 ?>
                    <button id="yearly-members-btn" class="w-full mt-4 p-3 bg-gray-50 rounded-xl flex items-center justify-between hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-3">
                            <i data-feather="users" class="w-5 h-5 text-gray-500"></i>
                            <span class="text-sm text-gray-600">연도별 회원 명단</span>
                        </div>
                        <i data-feather="chevron-right" class="w-5 h-5 text-gray-400"></i>
                    </button>
                </div>
                <?php // 회원 현황 섹션 ?>
                <div class="px-6 pb-6">
                    <h2 id="current-year" class="text-lg font-semibold text-gray-800 mb-4"></h2>
                    <div id="current-members" class="grid grid-cols-2 gap-3">
                        <?php // 회원 카드들이 여기에 동적으로 생성됩니다 ?>
                    </div>
                </div>

                <?php // 회비 납부 현황 섹션 ?>
                <div class="px-6 pb-6 mt-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">회비 납부 현황</h2>
                        <select id="dues-year-select" class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm border-0 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="">연도 선택</option>
                        </select>
                    </div>
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
                                <?php // 회비 현황이 여기에 동적으로 생성됩니다 ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php // 모임통장 정보 섹션 ?>
                <div class="px-6 pb-6">
                    <h3 class="text-md font-medium text-gray-700 mb-3 account-info">모임통장 정보 <img src="images/toss.png"><span id="account-bank"></span> <span id="account-number"></span></h3>
                    <div class="flex gap-3">
                        <a href="https://docs.google.com/spreadsheets/d/1SVkDdbGm4EHqbzSRjQ-xTb5IVDAY4fSZ/edit?usp=share_link&ouid=100222666619716175380&rtpof=true&sd=true" target="_blank" class="flex-1 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-medium transition-colors text-center block">
                            회계보고
                        </a>
                        <button id="account-copy-btn" class="flex-1 py-3 bg-blue-500 hover:bg-blue-600 rounded-xl text-white font-medium transition-colors">
                            계좌 복사하기
                        </button>
                    </div>
                </div>
                <?php // 카피라이트 ?>
                <div class="px-6 mt-4 pb-20" id="copyright">
                    <p class="text-center text-gray-400">
                        © <script>document.write(new Date().getFullYear());</script> New People Church. All rights reserved.
                    </p>
        </div>
    </div>
    <?php // 연도별 회원 명단 모달 ?>
    <div id="yearly-members-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="text-xl font-bold text-gray-800">연도별 회원 명단</h2>
                <button id="close-modal" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-feather="x" class="w-6 h-6 text-gray-500"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="yearly-members">
                    <?php // 연도별 토글이 여기에 동적으로 생성됩니다 ?>
                </div>
            </div>
        </div>
    </div>
    <?php // JavaScript ?>
    <script src="js/script.js"></script>
</body>
</html>

