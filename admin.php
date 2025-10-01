<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>관리자 페이지 - 광주새백성교회 청장년회</title>
    
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
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="mobile-container w-full max-w-sm mx-auto">
            
            <!-- 상단 헤더 -->
            <div class="header-section bg-blue-100 px-4 py-3 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <button onclick="goBack()" class="back-btn text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-gray-800">관리자 페이지</h1>
                    <button onclick="saveData()" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600">
                        저장
                    </button>
                </div>
            </div>

            <!-- 메인 콘텐츠 카드 -->
            <div class="main-card bg-white rounded-t-2xl -mt-4 relative z-10 shadow-lg">
                
                <!-- 관리 기능 탭 -->
                <div class="p-6 pb-4">
                    <div class="flex gap-2 mb-6">
                        <button id="tab-years" class="tab-btn active px-4 py-2 bg-blue-500 text-white rounded-lg text-sm">
                            연도 관리
                        </button>
                        <button id="tab-members" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                            회원 관리
                        </button>
                        <button id="tab-dues" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                            회비 관리
                        </button>
                    </div>
                </div>

                <!-- 스크롤 가능한 콘텐츠 영역 -->
                <div class="scrollable-content max-h-96 overflow-y-auto">
                    
                    <!-- 연도 관리 탭 -->
                    <div id="content-years" class="tab-content px-6 pb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">연도 관리</h2>
                        
                        <!-- 새 연도 추가 -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <h3 class="font-medium text-gray-700 mb-3">새 연도 추가</h3>
                            <div class="flex gap-2">
                                <input type="number" id="new-year" placeholder="연도 입력" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button onclick="addYear()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                    추가
                                </button>
                            </div>
                        </div>

                        <!-- 기존 연도 목록 -->
                        <div class="space-y-2">
                            <h3 class="font-medium text-gray-700 mb-3">기존 연도</h3>
                            <div id="years-list">
                                <!-- 연도 목록이 여기에 동적으로 생성됩니다 -->
                            </div>
                        </div>
                    </div>

                    <!-- 회원 관리 탭 -->
                    <div id="content-members" class="tab-content px-6 pb-6 hidden">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">회원 관리</h2>
                        
                        <!-- 연도 선택 -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">관리할 연도 선택</label>
                            <select id="member-year-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">연도를 선택하세요</option>
                            </select>
                        </div>

                        <!-- 새 회원 추가 -->
                        <div class="bg-gray-50 rounded-xl p-4 mb-4">
                            <h3 class="font-medium text-gray-700 mb-3">새 회원 추가</h3>
                            <div class="space-y-3">
                                <input type="text" id="new-member-name" placeholder="이름" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text" id="new-member-position" placeholder="직급" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <div class="flex gap-2">
                                    <input type="file" id="new-member-photo" accept="image/*" 
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button onclick="addMember()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                        추가
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- 회원 목록 -->
                        <div id="members-list">
                            <!-- 회원 목록이 여기에 동적으로 생성됩니다 -->
                        </div>
                    </div>

                    <!-- 회비 관리 탭 -->
                    <div id="content-dues" class="tab-content px-6 pb-6 hidden">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">회비 관리</h2>
                        
                        <!-- 연도 선택 -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">관리할 연도 선택</label>
                            <select id="dues-year-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">연도를 선택하세요</option>
                            </select>
                        </div>

                        <!-- 회비 현황 테이블 -->
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
                                <tbody id="admin-dues-table">
                                    <!-- 회비 현황이 여기에 동적으로 생성됩니다 -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- 모달 (회원 수정용) -->
    <div id="edit-member-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl p-6 m-4 w-full max-w-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">회원 정보 수정</h3>
            <div class="space-y-3">
                <input type="text" id="edit-member-name" placeholder="이름" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="text" id="edit-member-position" placeholder="직급" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="file" id="edit-member-photo" accept="image/*" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2 mt-4">
                <button onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    취소
                </button>
                <button onclick="saveMemberEdit()" class="flex-1 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    저장
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="js/admin-script.js"></script>
</body>
</html>

