import http from './http.js'
import { addGlobalEventListener } from './utils.js'

function sameOrigin(url) {
  try {
    const u = new URL(url, window.location.href)
    return u.origin === window.location.origin
  } catch {
    return false
  }
}

async function loadInto(target, url, { push = true } = {}) {
  if (!target || !url) return

  target.setAttribute('aria-busy', 'true')
  target.classList.add('opacity-60')

  try {
    const response = await http.get(url, {
      headers: { Accept: 'text/html' },
    })

    target.innerHTML = response.data

    if (push) {
      window.history.pushState({ ajax: true }, '', url)
    }
  } finally {
    target.removeAttribute('aria-busy')
    target.classList.remove('opacity-60')
  }
}

function buildUrlFromForm(form) {
  const action = form.getAttribute('action') || window.location.pathname
  const method = (form.getAttribute('method') || 'GET').toUpperCase()
  if (method !== 'GET') return action

  const url = new URL(action, window.location.origin)
  const fd = new FormData(form)

  // Drop empty values to keep URLs clean.
  for (const [key, value] of fd.entries()) {
    const v = typeof value === 'string' ? value.trim() : value
    if (v === '' || v == null) continue
    url.searchParams.set(key, String(v))
  }

  return url.toString()
}

function findTarget(fromEl) {
  return fromEl.closest('[data-ajax-scope]')?.querySelector('[data-ajax-target]') ||
    document.querySelector('[data-ajax-target]')
}

function isPaginationLink(a) {
  if (!a) return false
  if (a.closest('nav[aria-label="Pagination Navigation"]')) return true
  if (a.closest('nav[role="navigation"]')) return true
  if (a.closest('.pagination')) return true
  if (a.getAttribute('rel') === 'next' || a.getAttribute('rel') === 'prev') return true
  return false
}

export function initAjaxListHandlers() {
  // Any explicit AJAX links (e.g. "Clear" filter).
  addGlobalEventListener('click', 'a[data-ajax-link]', async (e, a) => {
    const href = a.getAttribute('href')
    if (!href) return
    if (!sameOrigin(href)) return
    if (a.getAttribute('target') === '_blank') return
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return

    e.preventDefault()

    if (a.hasAttribute('data-ajax-reset-form')) {
      const form = a.closest('form[data-ajax-form]')
      if (form) form.reset()
    }

    const target = findTarget(a)
    await loadInto(target, href, { push: true })
  })

  // Form submit (filters/search).
  addGlobalEventListener('submit', 'form[data-ajax-form]', async (e, form) => {
    e.preventDefault()
    const target = findTarget(form)
    const url = buildUrlFromForm(form)
    await loadInto(target, url, { push: true })
  })

  // Auto-submit on change for filter controls (opt-in).
  addGlobalEventListener(
    'change',
    'form[data-ajax-form][data-ajax-auto-submit] select, form[data-ajax-form][data-ajax-auto-submit] input',
    async (e, el) => {
      const form = el.closest('form')
      if (!form) return
      const target = findTarget(form)
      const url = buildUrlFromForm(form)
      await loadInto(target, url, { push: true })
    }
  )

  // Pagination links inside target only.
  addGlobalEventListener('click', '[data-ajax-target] a', async (e, a) => {
    const href = a.getAttribute('href')
    if (!href) return
    if (!sameOrigin(href)) return
    if (a.getAttribute('target') === '_blank') return
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return
    if (!isPaginationLink(a)) return

    e.preventDefault()
    const target = findTarget(a)
    await loadInto(target, href, { push: true })
  })

  // AJAX delete forms (opt-in).
  addGlobalEventListener('submit', 'form[data-ajax-delete]', async (e, form) => {
    e.preventDefault()

    const action = form.getAttribute('action')
    if (!action) return

    const confirmation = form.dataset.confirm || 'Delete this item?'
    if (!window.confirm(confirmation)) return

    const button = form.querySelector('button[type="submit"], input[type="submit"]')
    if (button) button.disabled = true

    try {
      await http.delete(action)

      const row = form.closest('[data-recurring-task-row]') || form.closest('tr')
      if (row) row.remove()

      // Refresh list to keep pagination/empty state correct.
      const target = findTarget(form)
      await loadInto(target, window.location.href, { push: false })
    } finally {
      if (button) button.disabled = false
    }
  })

  // Back/forward support.
  window.addEventListener('popstate', async () => {
    const target = document.querySelector('[data-ajax-target]')
    if (!target) return
    await loadInto(target, window.location.href, { push: false })
  })
}

