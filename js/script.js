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
        initializeTheme();
        await loadData();
        renderCurrentMembers();
        renderYearlyMembers();
        renderDuesTable();
        renderAccountInfo();
        initializeBackgroundImage();
        setupEventListeners();
        setupMobileScrollBehavior();
    } catch (error) {
        console.error("앱 초기화 중 오류 발생:", error);
        showToast("데이터를 불러오는 중 오류가 발생했습니다.", "error");
    }
}

// 데이터 로드 (SQLite API 사용)
async function loadData() {
    try {
        const response = await fetch("include/api.php?path=all-data");
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
    const yearElement = document.getElementById("current-year");

    if (!container || !groupData) return;

    // 연도 표시
    if (yearElement) {
        yearElement.textContent = `${currentYear}년 회원`;
    }

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
    toggle.className = "year-toggle";
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
                
                <span class="member-info-name">${member.name}</span>
            </div>
        </td>
        ${Array.from({ length: 12 }, (_, i) => {
            const month = i + 1;
            const isPaid = member.dues[month.toString()];
            return `
                <td>
                    <div class="dues-status ${isPaid ? "paid" : "unpaid"}">
                        ${isPaid ? "✓" : "X"}
                    </div>
                </td>
            `;
        }).join("")}
    `;
    return row;
}

// 계좌 정보 렌더링
function renderAccountInfo() {
    if (!groupData || !groupData.groupInfo || !groupData.groupInfo.accountInfo)
        return;

    const accountBank = document.getElementById("account-bank");
    const accountNumber = document.getElementById("account-number");

    if (accountBank) {
        accountBank.textContent = groupData.groupInfo.accountInfo.bank;
    }

    if (accountNumber) {
        accountNumber.textContent =
            groupData.groupInfo.accountInfo.accountNumber;
    }
}

// 스크롤 동작 설정 (모바일/데스크톱 공통)
function setupMobileScrollBehavior() {
    setupScrollBehavior();
}

// 공통 스크롤 동작 설정 (모바일/데스크톱 공통)
function setupScrollBehavior() {
    const appContainer = document.querySelector(".app-container");
    const mainCard = document.querySelector(".main-card");
    const cardTitle = document.querySelector(".card-title");

    if (!appContainer || !mainCard || !cardTitle) return;

    let isScrolling = false;
    let cardTitleSticky = false;

    // 스크롤 이벤트 리스너
    appContainer.addEventListener(
        "scroll",
        throttle(function () {
            if (isScrolling) return;

            const scrollTop = appContainer.scrollTop;
            const heroSectionHeight =
                document.querySelector(".hero-section").offsetHeight;

            // main-card 스타일 변경 (기존 로직)
            if (scrollTop > heroSectionHeight - 20) {
                if (!mainCard.classList.contains("sticky")) {
                    mainCard.classList.add("sticky");
                    console.log("main-card sticky 추가됨");
                }
            } else {
                if (mainCard.classList.contains("sticky")) {
                    mainCard.classList.remove("sticky");
                    console.log("main-card sticky 제거됨");
                }
            }

            // card-title 고정 로직
            const cardTitleRect = cardTitle.getBoundingClientRect();
            const appContainerRect = appContainer.getBoundingClientRect();

            // card-title이 상단에 닿았을 때 고정
            if (cardTitleRect.top <= appContainerRect.top + 10) {
                if (!cardTitleSticky) {
                    cardTitleSticky = true;
                    console.log("card-title sticky 상태:", cardTitleSticky);
                }
            } else {
                if (cardTitleSticky) {
                    cardTitleSticky = false;
                    console.log("card-title sticky 해제됨");
                }
            }
        }, 16)
    ); // 60fps로 제한

    // 부드러운 스크롤을 위한 CSS 스크롤 동작 설정
    appContainer.style.scrollBehavior = "smooth";
}

// 이벤트 리스너 설정
function setupEventListeners() {
    // 참여하기 버튼은 HTML에서 직접 링크로 처리됨

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

    // 관리 버튼 (헤더 섹션에 있는 버튼)
    const manageBtn = document.querySelector(".header-section button");
    if (manageBtn) {
        manageBtn.addEventListener("click", function () {
            window.location.href = "admin.php";
        });
    }

    // 테마 토글 버튼
    const themeToggle = document.getElementById("theme-toggle");
    if (themeToggle) {
        themeToggle.addEventListener("click", function () {
            toggleTheme();
        });
    }

    // 연도별 회원 명단 모달 버튼
    const yearlyMembersBtn = document.getElementById("yearly-members-btn");
    if (yearlyMembersBtn) {
        yearlyMembersBtn.addEventListener("click", function () {
            openYearlyMembersModal();
        });
    }

    // 모달 닫기 버튼
    const closeModalBtn = document.getElementById("close-modal");
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", function () {
            closeYearlyMembersModal();
        });
    }

    // 모달 배경 클릭 시 닫기
    const modalOverlay = document.getElementById("yearly-members-modal");
    if (modalOverlay) {
        modalOverlay.addEventListener("click", function (e) {
            if (e.target === modalOverlay) {
                closeYearlyMembersModal();
            }
        });
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
        btn.textContent = "계좌 복사하기";
    }, 2000);

    showToast("모임통장 계좌번호가 복사되었습니다!", "success");
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

// 키보드 접근성 지원
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        // 모달이 열려있으면 모달 닫기
        const modal = document.getElementById("yearly-members-modal");
        if (modal && modal.classList.contains("show")) {
            closeYearlyMembersModal();
        }
    }
});

// 테마 초기화
function initializeTheme() {
    const savedTheme = localStorage.getItem("theme");
    const prefersDark = window.matchMedia(
        "(prefers-color-scheme: dark)"
    ).matches;

    if (savedTheme) {
        document.documentElement.className = savedTheme;
    } else if (prefersDark) {
        document.documentElement.className = "dark";
    } else {
        document.documentElement.className = "light";
    }

    updateThemeIcon();
    updateBackgroundImage(document.documentElement.className);
}

// 테마 토글
function toggleTheme() {
    const currentTheme = document.documentElement.className;
    const newTheme = currentTheme === "dark" ? "light" : "dark";

    document.documentElement.className = newTheme;
    localStorage.setItem("theme", newTheme);
    updateThemeIcon();
    updateBackgroundImage(newTheme);

    // showToast(`${newTheme === 'dark' ? '다크' : '라이트'} 모드로 변경되었습니다.`, 'info');
}

// 배경 이미지 업데이트
function updateBackgroundImage(theme) {
    const heroLight = document.getElementById("hero-light");
    const heroDark = document.getElementById("hero-dark");

    if (!heroLight || !heroDark) return;

    if (theme === "dark") {
        // 다크 모드: 다크 이미지를 보이게 함
        heroLight.style.opacity = "0";
        heroDark.style.opacity = "1";
        heroLight.style.zIndex = "0";
        heroDark.style.zIndex = "1";
    } else {
        // 라이트 모드: 라이트 이미지를 보이게 함
        heroLight.style.opacity = "1";
        heroDark.style.opacity = "0";
        heroLight.style.zIndex = "1";
        heroDark.style.zIndex = "0";
    }
}

// 초기 배경 이미지 설정
function initializeBackgroundImage() {
    const heroLight = document.getElementById("hero-light");
    const heroDark = document.getElementById("hero-dark");

    if (!heroLight || !heroDark) return;

    // 초기 상태: 라이트 이미지가 보이도록 설정
    heroLight.style.opacity = "1";
    heroDark.style.opacity = "0";
    heroLight.style.zIndex = "1";
    heroDark.style.zIndex = "0";
}

// 테마 아이콘 업데이트
function updateThemeIcon() {
    const themeToggle = document.getElementById("theme-toggle");
    if (!themeToggle) return;

    const isDark = document.documentElement.className === "dark";
    const icon = themeToggle.querySelector("svg path");

    if (isDark) {
        // 해 아이콘 (다크모드일 때)
        icon.setAttribute(
            "d",
            "M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
        );
    } else {
        // 달 아이콘 (라이트모드일 때)
        icon.setAttribute(
            "d",
            "M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
        );
    }
}

// 연도별 회원 명단 모달 열기
function openYearlyMembersModal() {
    const modal = document.getElementById("yearly-members-modal");
    if (modal) {
        modal.classList.add("show");
        document.body.style.overflow = "hidden"; // 배경 스크롤 방지
    }
}

// 연도별 회원 명단 모달 닫기
function closeYearlyMembersModal() {
    const modal = document.getElementById("yearly-members-modal");
    if (modal) {
        modal.classList.remove("show");
        document.body.style.overflow = ""; // 스크롤 복원
    }
}

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
