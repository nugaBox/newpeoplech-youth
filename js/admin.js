// 전역 변수
let groupData = null;
let currentEditMember = null;
let currentEditYear = null;
let currentTab = "events";

// DOM이 로드되면 실행
document.addEventListener("DOMContentLoaded", function () {
    initializeAdminApp();
});

// 관리자 앱 초기화
async function initializeAdminApp() {
    try {
        await loadData();
        setupTabs();
        loadInitialData();
        setupEventListeners();
        initializeFeatherIcons();
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
        console.log("데이터 로드 완료:", groupData);
    } catch (error) {
        console.error("데이터 로드 오류:", error);
        throw error;
    }
}

// 초기 데이터 로드
async function loadInitialData() {
    await loadEventData();
    await loadGroupInfoData();
    await loadSloganData();
    await loadAccountData();
    updateYearSelects();
    setupFileUploadHandlers();
}

// 파일 업로드 핸들러 설정
function setupFileUploadHandlers() {
    // 새 회원 추가 파일 선택
    const newMemberPhoto = document.getElementById("new-member-photo");
    const newMemberPhotoName = document.getElementById("new-member-photo-name");

    if (newMemberPhoto && newMemberPhotoName) {
        newMemberPhoto.addEventListener("change", function () {
            if (this.files[0]) {
                newMemberPhotoName.textContent = this.files[0].name;
            } else {
                newMemberPhotoName.textContent = "";
            }
        });
    }

    // 회원 수정 파일 선택
    const editMemberPhoto = document.getElementById("edit-member-photo");
    const editMemberPhotoName = document.getElementById(
        "edit-member-photo-name"
    );

    if (editMemberPhoto && editMemberPhotoName) {
        editMemberPhoto.addEventListener("change", function () {
            if (this.files[0]) {
                editMemberPhotoName.textContent = this.files[0].name;
            } else {
                editMemberPhotoName.textContent = "";
            }
        });
    }
}

// 탭 설정
function setupTabs() {
    const tabs = document.querySelectorAll(".tab-btn");
    const contents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            const targetContent = this.id.replace("tab-", "content-");
            currentTab = this.id.replace("tab-", "");

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

            // 회비 관리 탭일 때 스크롤 컨테이너 높이 제한 제거
            const scrollContainer = document.querySelector(
                ".scrollable-content"
            );
            if (targetContent === "content-dues") {
                scrollContainer.style.maxHeight = "none";
                scrollContainer.style.overflowY = "visible";
            } else {
                scrollContainer.style.maxHeight = "100vh";
                scrollContainer.style.overflowY = "auto";
            }

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

// 이벤트 데이터 로드
async function loadEventData() {
    try {
        const response = await fetch("include/api.php?path=event");
        if (response.ok) {
            const eventData = await response.json();
            const eventTitle = document.getElementById("event-title");
            const eventDate = document.getElementById("event-date");

            if (eventTitle && eventData && eventData.title) {
                eventTitle.value = eventData.title;
            }
            if (eventDate && eventData && eventData.date) {
                eventDate.value = eventData.date;
            }
        }
    } catch (error) {
        console.error("이벤트 데이터 로드 오류:", error);
    }
}

// 모임 정보 데이터 로드
async function loadGroupInfoData() {
    try {
        const response = await fetch("include/api.php?path=group-info");
        if (response.ok) {
            const groupInfoData = await response.json();
            const groupName = document.getElementById("group-name");
            if (groupName && groupInfoData && groupInfoData.name) {
                groupName.value = groupInfoData.name;
            }
        }
    } catch (error) {
        console.error("모임 정보 데이터 로드 오류:", error);
    }
}

// 성경 구절 데이터 로드
async function loadSloganData() {
    try {
        const response = await fetch("include/api.php?path=slogan");
        if (response.ok) {
            const sloganData = await response.json();
            const sloganText = document.getElementById("slogan-text");
            const sloganReference = document.getElementById("slogan-reference");

            if (sloganText && sloganData && sloganData.text) {
                sloganText.value = sloganData.text;
            }
            if (sloganReference && sloganData && sloganData.reference) {
                sloganReference.value = sloganData.reference;
            }
        }
    } catch (error) {
        console.error("성경 구절 데이터 로드 오류:", error);
    }
}

// 계좌 정보 데이터 로드
async function loadAccountData() {
    try {
        const response = await fetch("include/api.php?path=account-info");
        if (response.ok) {
            const accountData = await response.json();
            const accountBank = document.getElementById("account-bank");
            const accountNumber = document.getElementById("account-number");

            if (accountBank && accountData && accountData.bank) {
                accountBank.value = accountData.bank;
            }
            if (accountNumber && accountData && accountData.number) {
                accountNumber.value = accountData.number;
            }
        }
    } catch (error) {
        console.error("계좌 정보 데이터 로드 오류:", error);
    }
}

// 이벤트 정보 저장
async function saveEventInfo() {
    const eventTitle = document.getElementById("event-title").value.trim();
    const eventDate = document.getElementById("event-date").value;

    if (!eventTitle) {
        showToast("이벤트 제목을 입력해주세요.", "error");
        return;
    }

    if (!eventDate) {
        showToast("이벤트 날짜를 선택해주세요.", "error");
        return;
    }

    try {
        const response = await fetch("include/api.php?path=event", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                title: eventTitle,
                date: eventDate,
            }),
        });

        if (!response.ok) {
            throw new Error("이벤트 정보 저장 실패");
        }

        showToast("이벤트 정보가 저장되었습니다.", "success");
    } catch (error) {
        console.error("이벤트 정보 저장 오류:", error);
        showToast("이벤트 정보 저장 중 오류가 발생했습니다.", "error");
    }
}

// 날짜 포맷팅 함수 (YYYY-MM-DD → YYYY년 M월 D일)
function formatEventDate(dateString) {
    const date = new Date(dateString);
    const year = date.getFullYear();
    const month = date.getMonth() + 1;
    const day = date.getDate();

    return `${year}년 ${month}월 ${day}일`;
}

// 모임 정보 저장
async function saveGroupInfo() {
    const groupName = document.getElementById("group-name").value.trim();

    if (!groupName) {
        showToast("모임명을 입력해주세요.", "error");
        return;
    }

    try {
        if (groupData && groupData.groupInfo) {
            groupData.groupInfo.name = groupName;
        }

        const response = await fetch("include/api.php?path=group-info", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                name: groupName,
            }),
        });

        if (!response.ok) {
            throw new Error("모임 정보 저장 실패");
        }

        showToast("모임 정보가 저장되었습니다.", "success");
    } catch (error) {
        console.error("모임 정보 저장 오류:", error);
        showToast("모임 정보 저장 중 오류가 발생했습니다.", "error");
    }
}

// 성경 구절 저장
async function saveSlogan() {
    const sloganText = document.getElementById("slogan-text").value.trim();
    const sloganReference = document
        .getElementById("slogan-reference")
        .value.trim();

    if (!sloganText) {
        showToast("성경 구절을 입력해주세요.", "error");
        return;
    }

    try {
        const response = await fetch("include/api.php?path=slogan", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                text: sloganText,
                reference: sloganReference,
            }),
        });

        if (!response.ok) {
            throw new Error("성경 구절 저장 실패");
        }

        showToast("성경 구절이 저장되었습니다.", "success");
    } catch (error) {
        console.error("성경 구절 저장 오류:", error);
        showToast("성경 구절 저장 중 오류가 발생했습니다.", "error");
    }
}

// 모임통장 정보 저장
async function saveAccountInfo() {
    const accountBank = document.getElementById("account-bank").value.trim();
    const accountNumber = document
        .getElementById("account-number")
        .value.trim();

    if (!accountBank || !accountNumber) {
        showToast("은행명과 계좌번호를 모두 입력해주세요.", "error");
        return;
    }

    try {
        const response = await fetch("include/api.php?path=account-info", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                bank: accountBank,
                number: accountNumber,
            }),
        });

        if (!response.ok) {
            throw new Error("모임통장 정보 저장 실패");
        }

        // 로컬 데이터 업데이트
        if (groupData && groupData.groupInfo) {
            if (!groupData.groupInfo.accountInfo) {
                groupData.groupInfo.accountInfo = {};
            }
            groupData.groupInfo.accountInfo.bank = accountBank;
            groupData.groupInfo.accountInfo.accountNumber = accountNumber;
        }

        showToast("모임통장 정보가 저장되었습니다.", "success");
    } catch (error) {
        console.error("모임통장 정보 저장 오류:", error);
        showToast("모임통장 정보 저장 중 오류가 발생했습니다.", "error");
    }
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
async function addMember() {
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

    try {
        // 기본 회비 상태 (모두 미납)
        const dues = {};
        for (let i = 1; i <= 12; i++) {
            dues[i.toString()] = false;
        }

        // FormData 생성
        const formData = new FormData();
        formData.append("year", year);
        formData.append("name", name);
        formData.append("position", position);
        formData.append("dues", JSON.stringify(dues));

        // 파일이 있으면 업로드
        if (photoInput.files[0]) {
            const file = photoInput.files[0];
            const fileName = `${name}_${Date.now()}.${file.name
                .split(".")
                .pop()}`;
            formData.append("photo", file, fileName);
        } else {
            formData.append("photo", "images/default-avatar.svg");
        }

        const response = await fetch("include/api.php?path=members", {
            method: "POST",
            body: formData,
        });

        if (!response.ok) {
            throw new Error("회원 추가 실패");
        }

        // 로컬 데이터 업데이트
        await loadData();
        renderMembersList(year);

        // 입력 필드 초기화
        nameInput.value = "";
        positionInput.value = "";
        photoInput.value = "";
        document.getElementById("new-member-photo-name").textContent = "";

        showToast("새 회원이 추가되었습니다.", "success");
    } catch (error) {
        console.error("회원 추가 오류:", error);
        showToast("회원 추가 중 오류가 발생했습니다.", "error");
    }
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

    // 파일 선택 초기화
    document.getElementById("edit-member-photo").value = "";
    document.getElementById("edit-member-photo-name").textContent = "";
    document.getElementById("delete-existing-photo").checked = false;

    const modal = document.getElementById("edit-member-modal");
    modal.classList.add("show");
    document.body.style.overflow = "hidden";
}

// 회원 수정 모달 닫기
function closeEditModal() {
    const modal = document.getElementById("edit-member-modal");
    modal.classList.remove("show");
    document.body.style.overflow = "";

    currentEditMember = null;
    currentEditYear = null;
}

// 회원 수정 저장
async function saveMemberEdit() {
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

    try {
        const yearData = groupData.years.find(
            (y) => y.year === currentEditYear
        );
        const member = yearData.members.find((m) => m.id === currentEditMember);

        if (member) {
            // FormData 생성
            const formData = new FormData();
            formData.append("id", currentEditMember);
            formData.append("year", currentEditYear);
            formData.append("name", name);
            formData.append("position", position);
            formData.append("dues", JSON.stringify(member.dues));

            // 기존 사진 삭제 체크박스 확인
            const deleteExistingPhoto = document.getElementById(
                "delete-existing-photo"
            ).checked;
            formData.append(
                "delete_existing_photo",
                deleteExistingPhoto ? "1" : "0"
            );

            // 파일이 있으면 업로드
            if (photoInput.files[0]) {
                const file = photoInput.files[0];
                const fileName = `${name}_${Date.now()}.${file.name
                    .split(".")
                    .pop()}`;
                formData.append("photo", file, fileName);
            } else if (!deleteExistingPhoto) {
                // 기존 사진 삭제가 체크되지 않았으면 기존 사진 유지
                formData.append("existing_photo", member.photo);
            }
            // deleteExistingPhoto가 체크되었고 새 파일이 없으면 아무것도 전달하지 않음 (기본 아바타로 변경)

            const response = await fetch(
                "include/api.php?path=members&action=update",
                {
                    method: "POST",
                    body: formData,
                }
            );

            if (!response.ok) {
                throw new Error("회원 정보 수정 실패");
            }

            // 로컬 데이터 업데이트
            await loadData();
            renderMembersList(currentEditYear);
            closeEditModal();
            showToast("회원 정보가 수정되었습니다.", "success");
        }
    } catch (error) {
        console.error("회원 수정 오류:", error);
        showToast("회원 수정 중 오류가 발생했습니다.", "error");
    }
}

// 회원 삭제
async function deleteMember(memberId, year) {
    if (!confirm("이 회원을 삭제하시겠습니까?")) {
        return;
    }

    try {
        const response = await fetch("include/api.php?path=members", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                id: memberId,
            }),
        });

        if (!response.ok) {
            throw new Error("회원 삭제 실패");
        }

        // 로컬 데이터 업데이트
        await loadData();
        renderMembersList(year);
        showToast("회원이 삭제되었습니다.", "success");
    } catch (error) {
        console.error("회원 삭제 오류:", error);
        showToast("회원 삭제 중 오류가 발생했습니다.", "error");
    }
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

    // Feather 아이콘 다시 렌더링
    feather.replace();
}

// 회비 현황 행 생성 (관리자용)
function createDuesRow(member, year) {
    const row = document.createElement("tr");
    row.innerHTML = `
        <td class="py-3 px-2">
            <div class="member-info">
                <span class="member-info-name">${member.name}</span>
            </div>
        </td>
        ${Array.from({ length: 12 }, (_, i) => {
            const month = i + 1;
            const isPaid = member.dues[month.toString()];
            return `
                <td class="py-3 px-2">
                    <button onclick="toggleDues(${
                        member.id
                    }, ${year}, ${month})" 
                            class="dues-status ${
                                isPaid ? "paid" : "unpaid"
                            } cursor-pointer hover:opacity-80 w-8 h-8 flex items-center justify-center rounded">
                        ${
                            isPaid
                                ? '<i data-feather="check" class="w-4 h-4"></i>'
                                : '<i data-feather="x" class="w-4 h-4"></i>'
                        }
                    </button>
                </td>
            `;
        }).join("")}
    `;
    return row;
}

// 회비 상태 토글
async function toggleDues(memberId, year, month) {
    try {
        const yearData = groupData.years.find((y) => y.year === year);
        const member = yearData.members.find((m) => m.id === memberId);

        if (member) {
            const newStatus = !member.dues[month.toString()];

            const response = await fetch("include/api.php?path=dues", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: memberId,
                    month: month,
                    paid: newStatus,
                }),
            });

            if (!response.ok) {
                throw new Error("회비 상태 변경 실패");
            }

            // 로컬 데이터 업데이트
            member.dues[month.toString()] = newStatus;
            renderDuesTable(year);
            // Feather 아이콘 다시 렌더링
            feather.replace();
            showToast("회비 상태가 변경되었습니다.", "success");
        }
    } catch (error) {
        console.error("회비 상태 변경 오류:", error);
        showToast("회비 상태 변경 중 오류가 발생했습니다.", "error");
    }
}

// 연도 선택 옵션 업데이트
function updateYearSelects() {
    const selects = ["member-year-select", "dues-year-select"];

    selects.forEach((selectId) => {
        const select = document.getElementById(selectId);
        if (!select || !groupData) return;

        const currentValue = select.value;
        select.innerHTML = '<option value="">연도를 선택하세요</option>';

        groupData.years.forEach((yearData) => {
            const option = document.createElement("option");
            option.value = yearData.year;
            option.textContent = `${yearData.year}년 (${yearData.members.length}명)`;
            select.appendChild(option);
        });

        // 회비 관리 탭과 회원 관리 탭의 경우 기본값을 현재 연도로 설정
        if (
            selectId === "dues-year-select" ||
            selectId === "member-year-select"
        ) {
            const currentYear = new Date().getFullYear();
            select.value = currentYear;
        } else if (currentValue) {
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

    // 회비 관리 탭 클릭 시 기본 연도 설정
    const duesTab = document.getElementById("tab-dues");
    if (duesTab) {
        duesTab.addEventListener("click", function () {
            if (duesYearSelect && !duesYearSelect.value) {
                const currentYear = new Date().getFullYear();
                duesYearSelect.value = currentYear;
                renderDuesTable(currentYear);
            }
        });
    }

    // 모달 배경 클릭 시 닫기
    const modalOverlay = document.getElementById("edit-member-modal");
    if (modalOverlay) {
        modalOverlay.addEventListener("click", function (e) {
            if (e.target === modalOverlay) {
                closeEditModal();
            }
        });
    }

    // ESC 키로 모달 닫기
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            const modal = document.getElementById("edit-member-modal");
            if (modal && modal.classList.contains("show")) {
                closeEditModal();
            }
        }
    });
}

// 전체 데이터 저장
async function saveAllData() {
    try {
        showToast("모든 데이터를 저장하고 있습니다...", "info");

        // 각 탭별로 데이터 저장
        await saveGroupInfo();
        await saveAccountInfo();

        showToast("모든 데이터가 저장되었습니다.", "success");
    } catch (error) {
        console.error("전체 데이터 저장 오류:", error);
        showToast("데이터 저장 중 오류가 발생했습니다.", "error");
    }
}

// 뒤로가기
function goBack() {
    window.location.href = "index.php";
}

// Feather 아이콘 초기화
function initializeFeatherIcons() {
    if (typeof feather !== "undefined") {
        feather.replace();
    }
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

// 새로운 연도 만들기
async function createNewYear() {
    const yearInput = document.getElementById("new-year-input");
    const year = parseInt(yearInput.value);

    if (!year || year < 2000 || year > 2100) {
        showToast("올바른 연도를 입력해주세요 (2000-2100)", "error");
        return;
    }

    // 이미 존재하는 연도인지 확인
    const existingYear = groupData.years.find((y) => y.year === year);
    if (existingYear) {
        showToast("이미 존재하는 연도입니다.", "error");
        return;
    }

    try {
        const response = await fetch("include/api.php?path=years", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ year: year }),
        });

        if (!response.ok) {
            throw new Error("연도 생성 실패");
        }

        const result = await response.json();
        if (result.success) {
            // 새 연도를 groupData에 추가
            if (!groupData.years.find((y) => y.year === year)) {
                groupData.years.push({
                    year: year,
                    members: [],
                });
            }

            // 연도 선택 업데이트
            updateYearSelects();

            // 새로 생성된 연도 선택
            const yearSelect = document.getElementById("member-year-select");
            yearSelect.value = year;

            // 회원 목록 렌더링 (빈 목록)
            renderMembersList(year);

            // 입력 필드 초기화
            yearInput.value = "";

            showToast(`${year}년이 생성되었습니다.`, "success");
        } else {
            throw new Error(result.error || "연도 생성 실패");
        }
    } catch (error) {
        console.error("연도 생성 오류:", error);
        showToast("연도 생성 중 오류가 발생했습니다.", "error");
    }
}
