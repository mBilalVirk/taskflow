// kanban.js
let draggedTask = null;

document.addEventListener("DOMContentLoaded", function () {
    initDragAndDrop();
    initCreateTaskForm();
    initModals();
});

// ====================== DRAG & DROP ======================
function initDragAndDrop() {
    document.querySelectorAll(".task-card").forEach((card) => {
        attachDragEvents(card);
    });

    document.querySelectorAll('[id$="-column"]').forEach((column) => {
        column.addEventListener("dragover", (e) => {
            e.preventDefault();
            column.parentElement.style.backgroundColor = "#e0e7ff";
        });

        column.addEventListener("dragleave", () => {
            column.parentElement.style.backgroundColor = "#f3f4f6";
        });

        column.addEventListener("drop", (e) => {
            e.preventDefault();
            column.parentElement.style.backgroundColor = "#f3f4f6";

            if (draggedTask) {
                const taskId = draggedTask.dataset.id;
                const newStatus = column.dataset.status;
                updateTaskStatus(taskId, newStatus, column);
            }
        });
    });
}

function attachDragEvents(card) {
    card.addEventListener("dragstart", function () {
        draggedTask = this;
        this.style.opacity = "0.5";
    });

    card.addEventListener("dragend", function () {
        this.style.opacity = "1";
    });
}

// ====================== UPDATE TASK STATUS ======================
function updateTaskStatus(taskId, newStatus, targetColumn) {
    const taskElement = draggedTask;

    fetch(`${window.kanbanConfig.routes.updateStatusBase}/${taskId}/status`, {
        method: "PUT",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": window.kanbanConfig.csrfToken,
            Accept: "application/json",
        },
        body: JSON.stringify({ status: newStatus }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                targetColumn.appendChild(taskElement);
                // Find the title inside the moved task
                const titleElement = taskElement.querySelector("h3");

                if (newStatus === "done") {
                    // Add line-through and dim the text
                    titleElement.classList.add("line-through", "opacity-50");
                } else {
                    // Remove them if moving back to 'todo' or 'in_progress'
                    titleElement.classList.remove("line-through", "opacity-50");
                }
                updateColumnCounts();
            } else if (data.success === false) {
                window.showToast(
                    data.message || "Failed to move task",
                    "warning",
                );
            }
        })
        .catch((err) => {
            //console.error(err);
            //alert("Failed to move task");
        })
        .finally(() => {
            draggedTask = null;
        });
}

function initCreateTaskForm() {
    const form = document.getElementById("createTaskForm");
    if (!form) return;

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        //console.log("Submitting to:", window.kanbanConfig.routes.createTask); // Debug

        const formData = new FormData(this);

        fetch(window.kanbanConfig.routes.createTask, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": window.kanbanConfig.csrfToken,
                Accept: "application/json",
            },
        })
            .then((res) => {
                // console.log("Response Status:", res.status);
                return res.text(); // Changed to .text() first for debugging
            })
            .then((text) => {
                // console.log("Raw Response:", text.substring(0, 300)); // Show first 300 chars

                try {
                    const data = JSON.parse(text);
                    if (data.success && data.task) {
                        closeCreateTaskModal();
                        addTaskToColumn(
                            data.task,
                            document.getElementById("taskStatus").value,
                        );
                        window.showToast(
                            data.message || "Task created successfully!",
                            "success",
                        );
                    } else {
                        //alert(data.message || "Failed to create task");
                        window.showToast(
                            data.message || "Failed to create task",
                            "error",
                        );
                    }
                } catch (e) {
                    alert("Server returned invalid response. Check console.");
                }
            })
            .catch((err) => {
                console.error(err);
                alert("Network error while creating task");
            });
    });
}

function addTaskToColumn(task, status) {
    const column = document.getElementById(status + "-column");
    if (!column) return;

    const cardHTML = `
        <div class="bg-white rounded-lg shadow p-4 cursor-move hover:shadow-lg transition task-card"
             draggable="true" data-id="${task.id}" onclick="openTaskModal('${task.id}')">
            <h3 class="font-bold text-gray-900 mb-2 text-sm">${escapeHtml(task.title)}</h3>
            ${task.description ? `<p class="text-xs text-gray-600 mb-3 line-clamp-2">${escapeHtml(task.description)}</p>` : ""}
            
            <div class="flex items-center justify-between">
                <span class="text-xs px-2 py-1 rounded font-semibold ${getPriorityClass(task.priority)}">
                    ${ucfirst(task.priority)}
                </span>
                ${
                    task.assignee
                        ? `
                <img src="${task.assignee.avatar || "https://ui-avatars.com/api/?name=" + encodeURIComponent(task.assignee.name)}" 
                     alt="${escapeHtml(task.assignee.name)}" class="w-6 h-6 rounded-full" title="${escapeHtml(task.assignee.name)}">
                `
                        : ""
                }
            </div>
            
            ${
                task.due_date
                    ? `
            <p class="text-xs text-gray-500 mt-2">
                <i class="fas fa-calendar mr-1"></i> ${formatDueDate(task.due_date)}
            </p>`
                    : ""
            }
        </div>
    `;

    // Remove "No tasks yet"
    const emptyMsg = column.querySelector(".text-center");
    if (emptyMsg) emptyMsg.remove();

    column.insertAdjacentHTML("beforeend", cardHTML);

    const newCard = column.lastElementChild;
    attachDragEvents(newCard);
    updateColumnCounts();
}

// ====================== HELPERS ======================
function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text || "";
    return div.innerHTML;
}

function ucfirst(str) {
    return str ? str.charAt(0).toUpperCase() + str.slice(1) : "";
}

function getPriorityClass(priority) {
    if (priority === "urgent") return "bg-red-100 text-red-700";
    if (priority === "high") return "bg-orange-100 text-orange-700";
    if (priority === "medium") return "bg-yellow-100 text-yellow-700";
    return "bg-blue-100 text-blue-700";
}

function formatDueDate(dateStr) {
    if (!dateStr) return "";
    const date = new Date(dateStr);
    return (
        date.toLocaleString("default", { month: "short" }) +
        " " +
        date.getDate()
    );
}

function updateColumnCounts() {
    ["todo", "in_progress", "done"].forEach((status) => {
        const column = document.getElementById(status + "-column");
        if (!column) return;

        const count = column.querySelectorAll(".task-card").length;
        const header = document.querySelector(`[data-status="${status}"]`);
        const badge = header ? header.querySelector("span") : null;

        if (badge) badge.textContent = count;

        // Remove empty message
        const empty = column.querySelector(".text-center");
        if (count > 0 && empty) empty.remove();
    });
}

// ====================== MODALS ======================
function openTaskModal(taskId) {
    const modal = document.getElementById("taskModal");
    const content = document.getElementById("modalContent");

    fetch(`${window.kanbanConfig.routes.taskModalBase}/${taskId}/modal`)
        .then((res) => res.text())
        .then((html) => {
            content.innerHTML = html;
            modal.classList.remove("hidden");
        })
        .catch((err) => console.error(err));
}

function closeTaskModal() {
    document.getElementById("taskModal").classList.add("hidden");
}

function openCreateTaskModal(status) {
    document.getElementById("taskStatus").value = status;
    document.getElementById("createTaskModal").classList.remove("hidden");
}

function closeCreateTaskModal() {
    document.getElementById("createTaskModal").classList.add("hidden");
}

function initModals() {
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            closeTaskModal();
            closeCreateTaskModal();
        }
    });
}

// ====================== EDIT TASK ======================

function openEditModal(taskId) {
    event.stopPropagation();

    if (!taskId) return;

    fetch(`/team/${currentTeamId}/projects/${currentProjectId}/tasks/${taskId}`)
        .then((response) => {
            if (!response.ok) throw new Error("Failed to load task");
            return response.text();
        })
        .then((html) => {
            // For now, just show alert (we'll improve this)
            alert("Edit modal will open here soon. Task ID: " + taskId);
            console.log("Edit requested for task:", taskId);
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to load task details");
        });
}

function closeEditTaskModal() {
    const modal = document.getElementById("editTaskModal");
    if (modal) modal.classList.add("hidden");
}

window.openCreateTaskModal = openCreateTaskModal;
window.closeCreateTaskModal = closeCreateTaskModal;
window.openTaskModal = openTaskModal;
window.closeTaskModal = closeTaskModal;
// window.openEditModal = openEditModal;
// window.closeEditTaskModal = closeEditTaskModal;
