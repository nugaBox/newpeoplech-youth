<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>광주새백성교회 청장년회</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Noto Sans KR 폰트 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- 커스텀 CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="font-noto-sans bg-gray-50">
    <!-- 메인 컨테이너 -->
    <div class="min-h-screen flex items-center justify-center p-0 md:p-4">
        <div class="mobile-container w-full max-w-sm mx-auto">
            
            <!-- 상단 헤더 (데스크탑/태블릿에서만 표시) -->
            <div class="header-section bg-blue-100 px-4 py-3 rounded-t-2xl hidden md:block">
                <div class="flex items-center justify-between">
                    <div class="flex gap-2">
                        <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded-lg text-sm">관리</button>
                    </div>
                </div>
            </div>

            <!-- 상단 배경 이미지 -->
            <div class="hero-section relative h-48 bg-gradient-to-br from-blue-400 to-purple-500 rounded-t-2xl overflow-hidden">
                <img src="images/hero-bg.jpg" alt="청장년회 모임 사진" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                
                <?php /*
                <!-- 회비 안내 카드 -->
                <div class="absolute bottom-4 left-4 right-4 bg-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600">이번달 회비를 내주세요</p>
                            <p class="text-lg font-semibold text-gray-800">₩ 10,000원 내기</p>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                */ ?>
            </div>

            <!-- 메인 콘텐츠 카드 -->
            <div class="main-card bg-white rounded-t-2xl -mt-4 relative z-10 shadow-lg">
                
                <!-- 모임 정보 헤더 -->
                <div class="p-6 pb-4">
                    <h1 class="text-xl font-bold text-gray-800 mb-2">광주새백성교회 청장년회</h1>
                    <div class="flex items-center justify-between">
                        <div class="text-3xl font-bold text-gray-800">469,144원</div>
                        <button class="px-3 py-1 bg-gray-100 text-gray-600 rounded-lg text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            카드
                        </button>
                    </div>
                    
                    <!-- 액션 버튼들 -->
                    <div class="flex gap-3 mt-4">
                        <button class="flex-1 py-3 bg-blue-50 text-blue-600 rounded-xl font-medium">채우기</button>
                        <button class="flex-1 py-3 bg-blue-500 text-white rounded-xl font-medium">보내기</button>
                    </div>
                    
                    <!-- 이자 정보 -->
                    <div class="mt-4 p-3 bg-gray-50 rounded-xl flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">연 1.6% (세전) 이자 받는 모임금고 만들기</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>

                <!-- 스크롤 가능한 콘텐츠 영역 -->
                <div class="scrollable-content max-h-96 overflow-y-auto">
                    
                    <!-- 회원 현황 섹션 -->
                    <div class="px-6 pb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">회원 현황</h2>
                        <div id="current-members" class="grid grid-cols-2 gap-3">
                            <!-- 회원 카드들이 여기에 동적으로 생성됩니다 -->
                        </div>
                    </div>

                    <!-- 연도별 회원 명단 섹션 -->
                    <div class="px-6 pb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">연도별 회원 명단</h2>
                        <div id="yearly-members">
                            <!-- 연도별 토글이 여기에 동적으로 생성됩니다 -->
                        </div>
                    </div>

                    <!-- 회비 현황 섹션 -->
                    <div class="px-6 pb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">회비 현황</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-2 px-1">회원</th>
                                        <th class="text-center py-2 px-1">1월</th>
                                        <th class="text-center py-2 px-1">2월</th>
                                        <th class="text-center py-2 px-1">3월</th>
                                        <th class="text-center py-2 px-1">4월</th>
                                        <th class="text-center py-2 px-1">5월</th>
                                        <th class="text-center py-2 px-1">6월</th>
                                        <th class="text-center py-2 px-1">7월</th>
                                        <th class="text-center py-2 px-1">8월</th>
                                        <th class="text-center py-2 px-1">9월</th>
                                        <th class="text-center py-2 px-1">10월</th>
                                        <th class="text-center py-2 px-1">11월</th>
                                        <th class="text-center py-2 px-1">12월</th>
                                    </tr>
                                </thead>
                                <tbody id="dues-table">
                                    <!-- 회비 현황이 여기에 동적으로 생성됩니다 -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- 모임통장 정보 섹션 -->
                    <div class="px-6 pb-6">
                        <h3 class="text-md font-medium text-gray-700 mb-3">모임통장 정보</h3>
                        <button id="account-copy-btn" class="w-full py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 font-medium transition-colors">
                            토스뱅크 111-11-1111
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="js/script.js"></script>
</body>
</html>

