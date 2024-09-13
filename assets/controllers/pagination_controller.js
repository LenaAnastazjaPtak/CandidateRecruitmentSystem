import {Controller} from '@hotwired/stimulus';

function updateUrlParams(key, value) {
    let url = window.location.href;
    let queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if (urlParams.get(key) !== null) {
        url = url.replace((key + '=' + urlParams.get(key)), key + '=' + value);
    } else {
        if (queryString === '') {
            url = url + '?' + key + '=' + value;
        } else {
            url = url + '&' + key + '=' + value;
        }
    }
    window.history.pushState(document.title, document.title, url);
    return url
}

function headers(options) {
    options = options || {}
    options.headers = options.headers || {}
    options.headers['X-Requested-With'] = 'XMLHttpRequest'
    return options
}


export default class extends Controller {
    connect() {
        this.pageContent = document.querySelector("#page-content");
    }

    changePage(event) {
        event.preventDefault()
        const newPage = event.currentTarget.getAttribute('data-page')
        const url = updateUrlParams('page', newPage)
        fetch(url, headers({method: 'GET'}))
            .then((response) => response.text())
            .then((text) => {
                this.pageContent.innerHTML = text
            });
        window.scrollTo({top: 0, behavior: 'smooth'});
    }
}
