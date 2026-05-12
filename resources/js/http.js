import axios from 'axios'

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

const http = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        ...(csrfToken ? {'X-CSRF-TOKEN': csrfToken} : {}),
    },
})

http.interceptors.response.use(
    response => response,
    error => {
        if (!error.response) {
            alert('Unable to connect to the server')

            return Promise.reject(error)
        }

        const status = error.response.status

        switch (status) {
            case 401:
                window.location.href = '/login'
                break

            case 419:
                window.location.reload()
                break

            case 500:
                alert('A server error has occurred')
                break
        }
        return Promise.reject(error)
    })

export default http
