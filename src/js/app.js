
const navToggle = document.querySelector('.nav-toggle')
if (navToggle) {
    navToggle.addEventListener('click', (event) => {
        event.preventDefault()
        const nav = document.querySelector('.nav')
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