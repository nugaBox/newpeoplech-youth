<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>설정 | 광주새백성교회 청장년회</title>
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
        <?php // 상단 헤더 ?>
        <div class="header-section bg-white shadow-sm px-4 py-3">
            <div class="flex items-center justify-between">
                <button onclick="goBack()" class="back-btn text-gray-600 hover:text-gray-800">
                    <i data-feather="arrow-left" class="w-6 h-6"></i>
                </button>
                <h1 class="text-lg font-semibold text-gray-800">관리자 페이지</h1>
                <?php /*
                <button onclick="saveAllData()" class="px-3 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600">
                    저장
                </button>
                */ ?>
                <button class="text-white">
                    <i data-feather="save" class="w-6 h-6"></i>
                </button>
            </div>
        </div>

        <?php // 메인 콘텐츠 카드 ?>
        <div class="main-card admin-main-card">
            <?php // 관리 기능 탭 ?>
            <div class="p-6 pb-4">
                <div class="grid gap-2 mb-6" style="grid-template-columns: 1fr 1fr 1fr;">
                    <button id="tab-events" class="tab-btn active px-4 py-2 bg-blue-500 text-white rounded-lg text-sm">
                        이벤트 관리
                    </button>
                    <button id="tab-group-info" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                        모임 정보
                    </button>
                    <button id="tab-slogan" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                        성경 구절
                    </button>
                    <button id="tab-members" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                        회원 관리
                    </button>
                    <button id="tab-dues" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                        회비 관리
                    </button>
                    <button id="tab-account" class="tab-btn px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm">
                        모임통장
                    </button>
                </div>
            </div>

            <?php // 스크롤 가능한 콘텐츠 영역 ?>
            <div class="scrollable-content max-h-screen overflow-y-auto">
                
                <?php // 이벤트 관리 탭 ?>
                <div id="content-events" class="tab-content px-6 pb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">이벤트 관리</h2>
                    
                    <?php // 다음 이벤트 카드 관리 ?>
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h3 class="font-medium text-gray-700 mb-3">다음 이벤트 설정</h3>
                        <div class="space-y-3">
                            <input type="text" id="event-title" placeholder="이벤트 제목" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="date" id="event-date" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="saveEventInfo()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                이벤트 정보 저장
                            </button>
                        </div>
                    </div>
                </div>

                <?php // 모임 정보 관리 탭 ?>
                <div id="content-group-info" class="tab-content px-6 pb-6 hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">모임 정보 관리</h2>
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h3 class="font-medium text-gray-700 mb-3">모임 정보 수정</h3>
                        <div class="space-y-3">
                            <input type="text" id="group-name" placeholder="모임명" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="saveGroupInfo()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                모임 정보 저장
                            </button>
                        </div>
                    </div>
                </div>

                <?php // 성경 구절 관리 탭 ?>
                <div id="content-slogan" class="tab-content px-6 pb-6 hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">성경 구절 관리</h2>
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h3 class="font-medium text-gray-700 mb-3">성경 구절 수정</h3>
                        <div class="space-y-3">
                            <input type="text" id="slogan-text" placeholder="성경 구절 텍스트" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="text" id="slogan-reference" placeholder="성경 참조 (예: 시 110:3)" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="saveSlogan()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                성경 구절 저장
                            </button>
                        </div>
                    </div>
                </div>

                <?php // 회원 관리 탭 ?>
                <div id="content-members" class="tab-content px-6 pb-6 hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">회원 관리</h2>

                    <?php // 새로운 연도 만들기 ?>
                    <div class="bg-blue-50 rounded-xl mb-4">
                        <h4 class="font-medium text-gray-700 mb-2">새로운 연도 만들기</h4>
                        <div class="flex items-center gap-3">
                            <input type="number" id="new-year-input" placeholder="연도 (예: 2030)" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   min="0" max="9999">
                            <button onclick="createNewYear()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                추가
                            </button>
                        </div>
                    </div>

                    <?php // 연도 선택 ?>
                    <div class="mb-4">
                        <h4 class="block text-sm font-medium text-gray-700 mb-2">관리할 연도 선택</h4>
                        <select id="member-year-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">연도를 선택하세요</option>
                        </select>
                    </div>

                    

                    <?php // 회원 목록 ?>
                    <div id="members-list" class="mb-6">
                        <?php // 회원 목록이 여기에 동적으로 생성됩니다 ?>
                    </div>

                    <?php // 새 회원 추가 ?>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <h3 class="font-medium text-gray-700 mb-3">새 회원 추가</h3>
                        <div class="space-y-3">
                            <input type="text" id="new-member-name" placeholder="이름" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="text" id="new-member-position" placeholder="직급" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            
                            <?php // 파일 선택 버튼 ?>
                            <div class="flex items-center gap-3">
                                <input type="file" id="new-member-photo" accept="image/*" 
                                       class="hidden">
                                <button type="button" onclick="document.getElementById('new-member-photo').click()" 
                                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2">
                                    <i data-feather="upload" class="w-4 h-4"></i>
                                    <span>사진 선택</span>
                                </button>
                                <span id="new-member-photo-name" class="text-sm text-gray-500"></span>
                            </div>
                            
                            <?php // 추가 버튼 ?>
                            <button onclick="addMember()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                회원 추가
                            </button>
                        </div>
                    </div>
                </div>

                <?php // 회비 관리 탭 ?>
                <div id="content-dues" class="tab-content px-6 pb-6 hidden dues-content">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">회비 관리</h2>
                    
                    <?php // 연도 선택 ?>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">관리할 연도 선택</label>
                        <select id="dues-year-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">연도를 선택하세요</option>
                        </select>
                    </div>

                    <?php // 알림 메시지 ?>
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <i data-feather="info" class="w-4 h-4 text-blue-600"></i>
                            <p class="text-sm text-blue-700">✓/✗ 표시를 클릭하면 변경하세요</p>
                        </div>
                    </div>

                    <?php // 회비 현황 테이블 ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-2">회원/월</th>
                                    <th class="text-center py-3 px-2">1</th>
                                    <th class="text-center py-3 px-2">2</th>
                                    <th class="text-center py-3 px-2">3</th>
                                    <th class="text-center py-3 px-2">4</th>
                                    <th class="text-center py-3 px-2">5</th>
                                    <th class="text-center py-3 px-2">6</th>
                                    <th class="text-center py-3 px-2">7</th>
                                    <th class="text-center py-3 px-2">8</th>
                                    <th class="text-center py-3 px-2">9</th>
                                    <th class="text-center py-3 px-2">10</th>
                                    <th class="text-center py-3 px-2">11</th>
                                    <th class="text-center py-3 px-2">12</th>
                                </tr>
                            </thead>
                            <tbody id="admin-dues-table">
                                <?php // 회비 현황이 여기에 동적으로 생성됩니다 ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php // 모임통장 관리 탭 ?>
                <div id="content-account" class="tab-content px-6 pb-6 hidden">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">모임통장 관리</h2>
                    
                    <div class="bg-gray-50 rounded-xl p-4 mb-4">
                        <h3 class="font-medium text-gray-700 mb-3">모임통장 정보 수정</h3>
                        <div class="space-y-3">
                            <input type="text" id="account-bank" placeholder="은행명" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <input type="text" id="account-number" placeholder="계좌번호" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button onclick="saveAccountInfo()" class="w-full px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                모임통장 정보 저장
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php // 모달 (회원 수정용) ?>
    <div id="edit-member-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-lg font-semibold text-gray-800">회원 정보 수정</h3>
                <button onclick="closeEditModal()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i data-feather="x" class="w-6 h-6 text-gray-500"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="space-y-3">
                    <input type="text" id="edit-member-name" placeholder="이름" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <input type="text" id="edit-member-position" placeholder="직급" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    <?php // 파일 선택 버튼 ?>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <input type="file" id="edit-member-photo" accept="image/*" 
                                   class="hidden">
                            <button type="button" onclick="document.getElementById('edit-member-photo').click()" 
                                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors flex items-center gap-2">
                                <i data-feather="upload" class="w-4 h-4"></i>
                                <span>사진 변경</span>
                            </button>
                            <span id="edit-member-photo-name" class="text-sm text-gray-500"></span>
                        </div>
                        
                        <?php // 기존 사진 삭제 체크박스 ?>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="delete-existing-photo" class="w-4 h-4">
                            <label for="delete-existing-photo" class="text-sm text-gray-600">
                                기존 사진 삭제
                            </label>
                        </div>
                    </div>
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
    </div>

    <?php // JavaScript ?>
    <script src="js/admin.js"></script>
</body>
</html>

