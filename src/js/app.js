
const navToggle = document.querySelector('.nav-toggle')
if (navToggle) {
    navToggle.addEventListener('click', (event) => {
        event.preventDefault()
        const nav = document.querySelector('.navigation')
        if (nav) {
            nav.classList.toggle('active')
        }
    })
}

const searchToggle = document.querySelector('.search-toggle')
if (searchToggle) {
    searchToggle.addEventListener('click', (event) => {
        event.preventDefault()
        const search = document.querySelector('.search-form')
        if (search) {
            search.classList.toggle('active')
        }
    })
}

const activityDots = document.querySelectorAll('.activity .year .dots .dot')
if (activityDots.length) {
    activityDots.forEach(dot => {
        let date = dot.dataset.date
        if (date) {
            dot.addEventListener('click', () => window.location.href = `/${date.replaceAll('-','/')}`)
        }
    });
}