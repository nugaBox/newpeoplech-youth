// 전역 변수
let groupData = null;
let currentYear = new Date().getFullYear();

// DOM이 로드되면 실행
document.addEventListener("DOMContentLoaded", function () {
    initializeApp();
});

// 앱 초기화
async function initializeApp() {
    try {
        await loadData();
        renderCurrentMembers();
        renderYearlyMembers();
        renderDuesTable();
        setupEventListeners();
        setupMobileScrollBehavior();
    } catch (error) {
        console.error("앱 초기화 중 오류 발생:", error);
        showToast("데이터를 불러오는 중 오류가 발생했습니다.", "error");
    }
}

// 데이터 로드
async function loadData() {
    try {
        const response = await fetch("data.json");
        if (!response.ok) {
            throw new Error("데이터를 불러올 수 없습니다.");
        }
        groupData = await response.json();
    } catch (error) {
        console.error("데이터 로드 오류:", error);
        throw error;
    }
}

// 현재 연도 회원 현황 렌더링
function renderCurrentMembers() {
    const container = document.getElementById("current-members");
    if (!container || !groupData) return;

    const currentYearData = groupData.years.find(
        (year) => year.year === currentYear
    );
    if (!currentYearData) {
        container.innerHTML =
            '<p class="text-gray-500 text-center col-span-2">현재 연도 데이터가 없습니다.</p>';
        return;
    }

    container.innerHTML = "";
    currentYearData.members.forEach((member) => {
        const memberCard = createMemberCard(member);
        container.appendChild(memberCard);
    });
}

// 회원 카드 생성
function createMemberCard(member) {
    const card = document.createElement("div");
    card.className = "member-card fade-in-up";
    card.innerHTML = `
        <div class="flex items-center gap-3">
            <img src="${member.photo}" alt="${member.name}" class="member-photo" 
                 onerror="this.src='images/default-avatar.svg'">
            <div>
                <div class="member-name">${member.name}</div>
                <div class="member-position">${member.position}</div>
            </div>
        </div>
    `;
    return card;
}

// 연도별 회원 명단 렌더링
function renderYearlyMembers() {
    const container = document.getElementById("yearly-members");
    if (!container || !groupData) return;

    container.innerHTML = "";
    groupData.years.forEach((yearData) => {
        const yearToggle = createYearToggle(yearData);
        container.appendChild(yearToggle);
    });
}

// 연도별 토글 생성
function createYearToggle(yearData) {
    const toggle = document.createElement("div");
    toggle.className = "year-toggle fade-in-up";
    toggle.innerHTML = `
        <div class="year-header" onclick="toggleYear(${yearData.year})">
            <div class="flex items-center justify-between">
                <div>
                    <div class="year-title">${yearData.year}년</div>
                    <div class="year-count">회원 ${
                        yearData.members.length
                    }명</div>
                </div>
                <svg class="toggle-icon w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </div>
        </div>
        <div class="year-content" id="year-${yearData.year}">
            <div class="year-members">
                ${yearData.members
                    .map(
                        (member) => `
                    <div class="year-member-item">
                        <img src="${member.photo}" alt="${member.name}" class="year-member-photo"
                             onerror="this.src='images/default-avatar.svg'">
                        <div class="year-member-info">
                            <div class="year-member-name">${member.name}</div>
                            <div class="year-member-position">${member.position}</div>
                        </div>
                    </div>
                `
                    )
                    .join("")}
            </div>
        </div>
    `;
    return toggle;
}

// 연도 토글 기능
function toggleYear(year) {
    const content = document.getElementById(`year-${year}`);
    const icon = content.previousElementSibling.querySelector(".toggle-icon");

    if (content.classList.contains("expanded")) {
        content.classList.remove("expanded");
        icon.classList.remove("rotated");
    } else {
        // 다른 연도들 닫기
        document.querySelectorAll(".year-content.expanded").forEach((el) => {
            el.classList.remove("expanded");
            el.previousElementSibling
                .querySelector(".toggle-icon")
                .classList.remove("rotated");
        });

        content.classList.add("expanded");
        icon.classList.add("rotated");
    }
}

// 회비 현황 테이블 렌더링
function renderDuesTable() {
    const tbody = document.getElementById("dues-table");
    if (!tbody || !groupData) return;

    const currentYearData = groupData.years.find(
        (year) => year.year === currentYear
    );
    if (!currentYearData) {
        tbody.innerHTML =
            '<tr><td colspan="13" class="text-center text-gray-500 py-4">현재 연도 데이터가 없습니다.</td></tr>';
        return;
    }

    tbody.innerHTML = "";
    currentYearData.members.forEach((member) => {
        const row = createDuesRow(member);
        tbody.appendChild(row);
    });
}

// 회비 현황 행 생성
function createDuesRow(member) {
    const row = document.createElement("tr");
    row.innerHTML = `
        <td>
            <div class="member-info">
                <img src="${member.photo}" alt="${
        member.name
    }" class="member-info-photo"
                     onerror="this.src='images/default-avatar.svg'">
                <span class="member-info-name">${member.name}</span>
            </div>
        </td>
        ${Array.from({ length: 12 }, (_, i) => {
            const month = i + 1;
            const isPaid = member.dues[month.toString()];
            return `
                <td>
                    <div class="dues-status ${isPaid ? "paid" : "unpaid"}">
                        ${isPaid ? "✓" : "✗"}
                    </div>
                </td>
            `;
        }).join("")}
    `;
    return row;
}

// 모바일 스크롤 동작 설정
function setupMobileScrollBehavior() {
    const isMobile = window.innerWidth <= 767;

    if (isMobile) {
        // 모바일에서 전체 화면 스크롤 비활성화
        document.body.style.overflow = "hidden";

        // 메인 카드의 스크롤 가능한 영역을 전체 화면으로 확장
        const scrollableContent = document.querySelector(".scrollable-content");
        if (scrollableContent) {
            scrollableContent.style.height = "calc(100vh - 200px)";
            scrollableContent.style.maxHeight = "none";
        }
    }
}

// 이벤트 리스너 설정
function setupEventListeners() {
    // 메인 카드 클릭 이벤트 (스크롤 확장)
    const mainCard = document.querySelector(".main-card");
    if (mainCard) {
        mainCard.addEventListener("click", function () {
            toggleMainCardExpansion();
        });
    }

    // 스크롤 이벤트 리스너 추가
    const scrollableContent = document.querySelector(".scrollable-content");
    if (scrollableContent) {
        scrollableContent.addEventListener("scroll", optimizedScrollHandler);
    }

    // 계좌번호 복사 버튼
    const accountCopyBtn = document.getElementById("account-copy-btn");
    if (accountCopyBtn) {
        accountCopyBtn.addEventListener("click", function () {
            copyAccountNumber();
        });
    }

    // 뒤로가기 버튼
    const backBtn = document.querySelector(".back-btn");
    if (backBtn) {
        backBtn.addEventListener("click", function () {
            window.history.back();
        });
    }

    // 게시판 버튼
    const boardBtn = document.querySelector('button:contains("게시판")');
    if (boardBtn) {
        boardBtn.addEventListener("click", function () {
            showToast("게시판 기능은 준비 중입니다.", "info");
        });
    }

    // 관리 버튼
    const manageBtn = document.querySelector('button:contains("관리")');
    if (manageBtn) {
        manageBtn.addEventListener("click", function () {
            window.location.href = "admin.php";
        });
    }
}

// 메인 카드 확장/축소 토글
function toggleMainCardExpansion() {
    const mainCard = document.querySelector(".main-card");
    const scrollableContent = document.querySelector(".scrollable-content");

    if (mainCard.classList.contains("expanded")) {
        mainCard.classList.remove("expanded");
        scrollableContent.style.maxHeight = "24rem"; // 96 (384px)
    } else {
        mainCard.classList.add("expanded");
        scrollableContent.style.maxHeight = "calc(100vh - 200px)";
    }
}

// 계좌번호 복사 기능
function copyAccountNumber() {
    if (
        !groupData ||
        !groupData.groupInfo ||
        !groupData.groupInfo.accountInfo
    ) {
        showToast("계좌 정보를 찾을 수 없습니다.", "error");
        return;
    }

    const accountNumber = groupData.groupInfo.accountInfo.accountNumber;
    const bankName = groupData.groupInfo.accountInfo.bank;
    const fullAccountInfo = `${bankName} ${accountNumber}`;

    if (navigator.clipboard && window.isSecureContext) {
        // 모던 브라우저의 Clipboard API 사용
        navigator.clipboard
            .writeText(fullAccountInfo)
            .then(() => {
                showCopySuccess();
            })
            .catch(() => {
                fallbackCopyText(fullAccountInfo);
            });
    } else {
        // 폴백 방법
        fallbackCopyText(fullAccountInfo);
    }
}

// 폴백 복사 방법
function fallbackCopyText(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    textArea.style.top = "-999999px";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
        document.execCommand("copy");
        showCopySuccess();
    } catch (err) {
        showToast("복사에 실패했습니다.", "error");
    }

    document.body.removeChild(textArea);
}

// 복사 성공 시각적 피드백
function showCopySuccess() {
    const btn = document.getElementById("account-copy-btn");
    btn.classList.add("copied");
    btn.textContent = "복사 완료!";

    setTimeout(() => {
        btn.classList.remove("copied");
        btn.textContent = "토스뱅크 111-11-1111";
    }, 2000);

    showToast("계좌번호가 복사되었습니다!", "success");
}

// 토스트 메시지 표시
function showToast(message, type = "info") {
    // 기존 토스트 제거
    const existingToast = document.querySelector(".toast");
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement("div");
    toast.className = `toast toast-${type}`;
    toast.textContent = message;

    // 타입별 색상 설정
    switch (type) {
        case "success":
            toast.style.background = "#48bb78";
            break;
        case "error":
            toast.style.background = "#f56565";
            break;
        case "warning":
            toast.style.background = "#ed8936";
            break;
        default:
            toast.style.background = "#4299e1";
    }

    document.body.appendChild(toast);

    // 애니메이션으로 표시
    setTimeout(() => {
        toast.classList.add("show");
    }, 100);

    // 3초 후 자동 제거
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    }, 3000);
}

// 유틸리티 함수들
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function () {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => (inThrottle = false), limit);
        }
    };
}

// 스크롤 이벤트 최적화
const optimizedScrollHandler = throttle(function () {
    const mainCard = document.querySelector(".main-card");
    const scrollableContent = document.querySelector(".scrollable-content");
    const isMobile = window.innerWidth <= 767;

    if (isMobile) {
        // 모바일에서 스크롤 위치에 따른 스타일 변경
        const scrollTop = scrollableContent.scrollTop;
        const threshold = 50; // 스크롤 임계값

        if (scrollTop > threshold) {
            mainCard.classList.add("sticky");
        } else {
            mainCard.classList.remove("sticky");
        }
    } else {
        // 데스크탑/태블릿에서 기존 로직 유지
        if (scrollableContent.scrollTop > 0) {
            mainCard.classList.add("sticky");
        } else {
            mainCard.classList.remove("sticky");
        }

        if (
            scrollableContent.scrollTop > 50 &&
            !mainCard.classList.contains("expanded")
        ) {
            mainCard.classList.add("expanded");
        } else if (
            scrollableContent.scrollTop <= 50 &&
            mainCard.classList.contains("expanded")
        ) {
            mainCard.classList.remove("expanded");
        }
    }
}, 100);

// 터치 이벤트 지원 (모바일)
let touchStartY = 0;
let touchEndY = 0;

document.addEventListener("touchstart", function (e) {
    touchStartY = e.changedTouches[0].screenY;
});

document.addEventListener("touchend", function (e) {
    touchEndY = e.changedTouches[0].screenY;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartY - touchEndY;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            // 위로 스와이프 - 카드 확장
            const mainCard = document.querySelector(".main-card");
            if (!mainCard.classList.contains("expanded")) {
                toggleMainCardExpansion();
            }
        } else {
            // 아래로 스와이프 - 카드 축소
            const mainCard = document.querySelector(".main-card");
            if (mainCard.classList.contains("expanded")) {
                toggleMainCardExpansion();
            }
        }
    }
}

// 키보드 접근성 지원
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        const mainCard = document.querySelector(".main-card");
        if (mainCard.classList.contains("expanded")) {
            toggleMainCardExpansion();
        }
    }
});

// 페이지 가시성 변경 시 데이터 새로고침
document.addEventListener("visibilitychange", function () {
    if (!document.hidden) {
        // 페이지가 다시 보이면 데이터 새로고침
        loadData()
            .then(() => {
                renderCurrentMembers();
                renderYearlyMembers();
                renderDuesTable();
            })
            .catch((error) => {
                console.error("데이터 새로고침 오류:", error);
            });
    }
});
