// 전역 변수
let groupData = null;
let currentEditMember = null;
let currentEditYear = null;

// DOM이 로드되면 실행
document.addEventListener("DOMContentLoaded", function () {
    initializeAdminApp();
});

// 관리자 앱 초기화
async function initializeAdminApp() {
    try {
        await loadData();
        setupTabs();
        renderYearsList();
        setupEventListeners();
    } catch (error) {
        console.error("관리자 앱 초기화 중 오류 발생:", error);
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

// 탭 설정
function setupTabs() {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            const targetContent = this.id.replace("tab-", "content-");

            // 모든 탭 비활성화
            tabs.forEach((t) => {
                t.classList.remove("active", "bg-blue-500", "text-white");
                t.classList.add("bg-gray-200", "text-gray-700");
            });

            // 모든 콘텐츠 숨기기
            contents.forEach((c) => c.classList.add("hidden"));

            // 선택된 탭 활성화
            this.classList.add("active", "bg-blue-500", "text-white");
            this.classList.remove("bg-gray-200", "text-gray-700");

            // 선택된 콘텐츠 표시
            document.getElementById(targetContent).classList.remove("hidden");

            // 탭별 데이터 로드
            switch (targetContent) {
                case "content-members":
                    loadMembersTab();
                    break;
                case "content-dues":
                    loadDuesTab();
                    break;
            }
        });
    });
}

// 연도 목록 렌더링
function renderYearsList() {
    const container = document.getElementById("years-list");
    if (!container || !groupData) return;

    container.innerHTML = "";
    groupData.years.forEach((yearData) => {
        const yearItem = createYearItem(yearData);
        container.appendChild(yearItem);
    });
}

// 연도 아이템 생성
function createYearItem(yearData) {
    const item = document.createElement("div");
    item.className =
        "flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200";
    item.innerHTML = `
        <div>
            <div class="font-medium text-gray-800">${yearData.year}년</div>
            <div class="text-sm text-gray-500">회원 ${yearData.members.length}명</div>
        </div>
        <div class="flex gap-2">
            <button onclick="editYear(${yearData.year})" class="px-3 py-1 bg-blue-100 text-blue-600 rounded text-sm hover:bg-blue-200">
                수정
            </button>
            <button onclick="deleteYear(${yearData.year})" class="px-3 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">
                삭제
            </button>
        </div>
    `;
    return item;
}

// 새 연도 추가
function addYear() {
    const yearInput = document.getElementById("new-year");
    const year = parseInt(yearInput.value);

    if (!year || year < 2000 || year > 2100) {
        showToast("올바른 연도를 입력해주세요.", "error");
        return;
    }

    if (groupData.years.find((y) => y.year === year)) {
        showToast("이미 존재하는 연도입니다.", "error");
        return;
    }

    groupData.years.push({
        year: year,
        members: [],
    });

    // 연도순 정렬
    groupData.years.sort((a, b) => b.year - a.year);

    renderYearsList();
    updateYearSelects();
    yearInput.value = "";
    showToast(`${year}년이 추가되었습니다.`, "success");
}

// 연도 수정
function editYear(year) {
    const newYear = prompt("새로운 연도를 입력하세요:", year);
    if (!newYear || newYear === year.toString()) return;

    const newYearNum = parseInt(newYear);
    if (!newYearNum || newYearNum < 2000 || newYearNum > 2100) {
        showToast("올바른 연도를 입력해주세요.", "error");
        return;
    }

    if (groupData.years.find((y) => y.year === newYearNum && y.year !== year)) {
        showToast("이미 존재하는 연도입니다.", "error");
        return;
    }

    const yearData = groupData.years.find((y) => y.year === year);
    if (yearData) {
        yearData.year = newYearNum;
        groupData.years.sort((a, b) => b.year - a.year);
        renderYearsList();
        updateYearSelects();
        showToast("연도가 수정되었습니다.", "success");
    }
}

// 연도 삭제
function deleteYear(year) {
    if (
        !confirm(
            `${year}년 데이터를 삭제하시겠습니까? 이 작업은 되돌릴 수 없습니다.`
        )
    ) {
        return;
    }

    groupData.years = groupData.years.filter((y) => y.year !== year);
    renderYearsList();
    updateYearSelects();
    showToast(`${year}년이 삭제되었습니다.`, "success");
}

// 회원 관리 탭 로드
function loadMembersTab() {
    updateYearSelects();
    const yearSelect = document.getElementById("member-year-select");
    if (yearSelect.value) {
        renderMembersList(parseInt(yearSelect.value));
    }
}

// 회원 목록 렌더링
function renderMembersList(year) {
    const container = document.getElementById("members-list");
    if (!container || !groupData) return;

    const yearData = groupData.years.find((y) => y.year === year);
    if (!yearData) {
        container.innerHTML =
            '<p class="text-gray-500 text-center py-4">해당 연도의 데이터가 없습니다.</p>';
        return;
    }

    container.innerHTML = "";
    yearData.members.forEach((member) => {
        const memberItem = createMemberItem(member, year);
        container.appendChild(memberItem);
    });
}

// 회원 아이템 생성
function createMemberItem(member, year) {
    const item = document.createElement("div");
    item.className =
        "flex items-center justify-between p-3 bg-white rounded-lg border border-gray-200 mb-2";
    item.innerHTML = `
        <div class="flex items-center gap-3">
            <img src="${member.photo}" alt="${member.name}" class="w-10 h-10 rounded-full object-cover border border-gray-200"
                 onerror="this.src='images/default-avatar.svg'">
            <div>
                <div class="font-medium text-gray-800">${member.name}</div>
                <div class="text-sm text-gray-500">${member.position}</div>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="editMember(${member.id}, ${year})" class="px-3 py-1 bg-blue-100 text-blue-600 rounded text-sm hover:bg-blue-200">
                수정
            </button>
            <button onclick="deleteMember(${member.id}, ${year})" class="px-3 py-1 bg-red-100 text-red-600 rounded text-sm hover:bg-red-200">
                삭제
            </button>
        </div>
    `;
    return item;
}

// 새 회원 추가
function addMember() {
    const yearSelect = document.getElementById("member-year-select");
    const nameInput = document.getElementById("new-member-name");
    const positionInput = document.getElementById("new-member-position");
    const photoInput = document.getElementById("new-member-photo");

    const year = parseInt(yearSelect.value);
    const name = nameInput.value.trim();
    const position = positionInput.value.trim();

    if (!year || !name || !position) {
        showToast("모든 필드를 입력해주세요.", "error");
        return;
    }

    const yearData = groupData.years.find((y) => y.year === year);
    if (!yearData) {
        showToast("해당 연도를 찾을 수 없습니다.", "error");
        return;
    }

    // 새 회원 ID 생성
    const newId =
        Math.max(
            ...groupData.years.flatMap((y) => y.members.map((m) => m.id)),
            0
        ) + 1;

    // 기본 회비 상태 (모두 미납)
    const dues = {};
    for (let i = 1; i <= 12; i++) {
        dues[i.toString()] = false;
    }

    const newMember = {
        id: newId,
        name: name,
        position: position,
        photo: photoInput.files[0]
            ? `images/members/${name}_${newId}.jpg`
            : "images/default-avatar.svg",
        dues: dues,
    };

    yearData.members.push(newMember);
    renderMembersList(year);

    // 입력 필드 초기화
    nameInput.value = "";
    positionInput.value = "";
    photoInput.value = "";

    showToast("새 회원이 추가되었습니다.", "success");
}

// 회원 수정 모달 열기
function editMember(memberId, year) {
    const yearData = groupData.years.find((y) => y.year === year);
    const member = yearData.members.find((m) => m.id === memberId);

    if (!member) return;

    currentEditMember = memberId;
    currentEditYear = year;

    document.getElementById("edit-member-name").value = member.name;
    document.getElementById("edit-member-position").value = member.position;

    const modal = document.getElementById("edit-member-modal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

// 회원 수정 모달 닫기
function closeEditModal() {
    const modal = document.getElementById("edit-member-modal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");

    currentEditMember = null;
    currentEditYear = null;
}

// 회원 수정 저장
function saveMemberEdit() {
    if (!currentEditMember || !currentEditYear) return;

    const nameInput = document.getElementById("edit-member-name");
    const positionInput = document.getElementById("edit-member-position");
    const photoInput = document.getElementById("edit-member-photo");

    const name = nameInput.value.trim();
    const position = positionInput.value.trim();

    if (!name || !position) {
        showToast("모든 필드를 입력해주세요.", "error");
        return;
    }

    const yearData = groupData.years.find((y) => y.year === currentEditYear);
    const member = yearData.members.find((m) => m.id === currentEditMember);

    if (member) {
        member.name = name;
        member.position = position;
        if (photoInput.files[0]) {
            member.photo = `images/members/${name}_${currentEditMember}.jpg`;
        }

        renderMembersList(currentEditYear);
        closeEditModal();
        showToast("회원 정보가 수정되었습니다.", "success");
    }
}

// 회원 삭제
function deleteMember(memberId, year) {
    if (!confirm("이 회원을 삭제하시겠습니까?")) {
        return;
    }

    const yearData = groupData.years.find((y) => y.year === year);
    yearData.members = yearData.members.filter((m) => m.id !== memberId);

    renderMembersList(year);
    showToast("회원이 삭제되었습니다.", "success");
}

// 회비 관리 탭 로드
function loadDuesTab() {
    updateYearSelects();
    const yearSelect = document.getElementById("dues-year-select");
    if (yearSelect.value) {
        renderDuesTable(parseInt(yearSelect.value));
    }
}

// 회비 현황 테이블 렌더링
function renderDuesTable(year) {
    const tbody = document.getElementById("admin-dues-table");
    if (!tbody || !groupData) return;

    const yearData = groupData.years.find((y) => y.year === year);
    if (!yearData) {
        tbody.innerHTML =
            '<tr><td colspan="13" class="text-center text-gray-500 py-4">해당 연도의 데이터가 없습니다.</td></tr>';
        return;
    }

    tbody.innerHTML = "";
    yearData.members.forEach((member) => {
        const row = createDuesRow(member, year);
        tbody.appendChild(row);
    });
}

// 회비 현황 행 생성 (관리자용)
function createDuesRow(member, year) {
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
                    <button onclick="toggleDues(${
                        member.id
                    }, ${year}, ${month})" 
                            class="dues-status ${
                                isPaid ? "paid" : "unpaid"
                            } cursor-pointer hover:opacity-80">
                        ${isPaid ? "✓" : "✗"}
                    </button>
                </td>
            `;
        }).join("")}
    `;
    return row;
}

// 회비 상태 토글
function toggleDues(memberId, year, month) {
    const yearData = groupData.years.find((y) => y.year === year);
    const member = yearData.members.find((m) => m.id === memberId);

    if (member) {
        member.dues[month.toString()] = !member.dues[month.toString()];
        renderDuesTable(year);
        showToast("회비 상태가 변경되었습니다.", "success");
    }
}

// 연도 선택 옵션 업데이트
function updateYearSelects() {
    const selects = ["member-year-select", "dues-year-select"];

    selects.forEach((selectId) => {
        const select = document.getElementById(selectId);
        if (!select) return;

        const currentValue = select.value;
        select.innerHTML = '<option value="">연도를 선택하세요</option>';

        groupData.years.forEach((yearData) => {
            const option = document.createElement("option");
            option.value = yearData.year;
            option.textContent = `${yearData.year}년 (${yearData.members.length}명)`;
            select.appendChild(option);
        });

        if (currentValue) {
            select.value = currentValue;
        }
    });
}

// 이벤트 리스너 설정
function setupEventListeners() {
    // 연도 선택 변경 이벤트
    const memberYearSelect = document.getElementById("member-year-select");
    if (memberYearSelect) {
        memberYearSelect.addEventListener("change", function () {
            if (this.value) {
                renderMembersList(parseInt(this.value));
            }
        });
    }

    const duesYearSelect = document.getElementById("dues-year-select");
    if (duesYearSelect) {
        duesYearSelect.addEventListener("change", function () {
            if (this.value) {
                renderDuesTable(parseInt(this.value));
            }
        });
    }
}

// 데이터 저장 (SQLite API 사용)
async function saveData() {
    try {
        // 그룹 정보 업데이트
        if (groupData && groupData.groupInfo) {
            const response = await fetch("include/api.php?path=group-info", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    name: groupData.groupInfo.name,
                    bank: groupData.groupInfo.accountInfo.bank,
                    accountNumber:
                        groupData.groupInfo.accountInfo.accountNumber,
                }),
            });

            if (!response.ok) {
                throw new Error("그룹 정보 저장 실패");
            }
        }

        showToast("데이터가 저장되었습니다.", "success");
    } catch (error) {
        console.error("데이터 저장 오류:", error);
        showToast("데이터 저장 중 오류가 발생했습니다.", "error");
    }
}

// 뒤로가기
function goBack() {
    window.location.href = "index.php";
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
