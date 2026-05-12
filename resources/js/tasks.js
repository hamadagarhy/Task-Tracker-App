import http from './http.js'
import {addGlobalEventListener} from "./utils.js";
export async function toggleTaskCompletion(button) {
    const taskId = button.dataset.taskId
    const url = button.dataset.url || `/tasks/${taskId}/complete`;

    button.disabled = true;

    try {
       const response = await http.patch(url, {
           redirect_to: window.location.href,
       })

        return response.data
    }finally {
        button.disabled = false
    }

}

function updateNumber(el, delta) {
    if (!el) return;
    const current = parseInt(el.textContent ?? '0', 10);
    if (Number.isNaN(current)) return;
    el.textContent = String(Math.max(0, current + delta));
}

function updateTodayRatio(deltaCompleted, deltaTotal = 0) {
    const ratio = document.querySelector('[data-stat-today-ratio]');
    if (!ratio) return;

    const [completedRaw = '0', totalRaw = '0'] = (ratio.textContent || '').split('/');
    const completed = Math.max(0, parseInt(completedRaw, 10) + deltaCompleted);
    const total = Math.max(0, parseInt(totalRaw, 10) + deltaTotal);
    ratio.textContent = `${completed}/${total}`;
}

function onDashboardTaskCompleted(button) {
    const item = button.closest('[data-task-item]');
    const section = button.closest('[data-task-section]');

    if (item) {
        item.remove();
    }

    // Update stats cards.
    updateNumber(document.querySelector('[data-stat-overdue]'), -1);
    updateNumber(document.querySelector('[data-stat-pending]'), -1);
    updateNumber(document.querySelector('[data-stat-completed-7d]'), 1);
    updateTodayRatio(1, 0);

    // Update list count badges.
    if (section) {
        const badge = section.querySelector('[data-section-count]');
        updateNumber(badge, -1);
    }
}

function onTasksIndexToggle(button, completed) {
    const row = button.closest('[data-task-row]');
    if (!row) return;

    const status = row.querySelector('[data-task-status]');
    if (status) {
        if (completed) {
            status.className = 'inline-flex rounded-full bg-green-100 dark:bg-green-900/40 px-2 py-0.5 text-xs font-medium text-green-800 dark:text-green-300';
            status.textContent = 'Completed';
        } else {
            status.className = 'inline-flex rounded-full bg-amber-100 dark:bg-amber-900/40 px-2 py-0.5 text-xs font-medium text-amber-800 dark:text-amber-200';
            status.textContent = 'Incomplete';
        }
    }

    button.textContent = completed ? 'Mark incomplete' : 'Mark complete';
}

async function deleteTask(button) {
    const url = button.dataset.url;
    if (!url) return;

    const confirmation = button.dataset.confirm || 'Delete this task?';
    if (!window.confirm(confirmation)) {
        return;
    }

    button.disabled = true;

    try {
        await http.delete(url);
        const row = button.closest('[data-task-row]');
        if (row) {
            row.remove();
        }
    } finally {
        button.disabled = false;
    }
}

export function initTaskCompletionHandlers(){
    addGlobalEventListener('click', '[data-task-toggle]', async (e, button) => {
        e.preventDefault()
        const onDashboard = button.dataset.context === 'dashboard'
        const onTasksIndex = button.dataset.context === 'tasks-index'

        const response = await toggleTaskCompletion(button)

        if (onDashboard) {
            onDashboardTaskCompleted(button)
        }

        if (onTasksIndex) {
            onTasksIndexToggle(button, Boolean(response?.completed))
        }
    })

    addGlobalEventListener('click', '[data-task-delete]', async (e, button) => {
        e.preventDefault()
        await deleteTask(button)
    })
}
